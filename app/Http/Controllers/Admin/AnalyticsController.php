<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Car;
use App\Models\Team;
use App\Support\BookingSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /** Menampilkan analitik penggunaan mobil yang dapat difilter berdasarkan bulan. */
    public function index(Request $request): View
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $tanggalAwal = Carbon::parse($bulan.'-01')->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $bookingBulanIni = Borrowing::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();

        $totalBooking = $bookingBulanIni->count();
        $totalSelesai = $bookingBulanIni->where('status', 'Selesai')->count();
        $totalDitolak = $bookingBulanIni->where('status', 'Ditolak')->count();
        $rataRataPerHari = round($totalBooking / $tanggalAwal->daysInMonth, 1);

        $teamPalingAktif = Team::withCount(['borrowings' => function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }])->orderByDesc('borrowings_count')->get();

        $maxBookingTeam = $teamPalingAktif->max('borrowings_count') ?: 1;

        $tanggalRange = collect();
        for ($tanggal = $tanggalAwal->copy(); $tanggal->lte($tanggalAkhir); $tanggal->addDay()) {
            $tanggalRange->push($tanggal->format('Y-m-d'));
        }

        $trenHarian = $tanggalRange->map(function (string $tanggal) use ($bookingBulanIni) {
            return $bookingBulanIni->filter(function (Borrowing $borrowing) use ($tanggal) {
                return $borrowing->tanggal?->format('Y-m-d') === $tanggal;
            })->count();
        });
        $labelTren = $tanggalRange->map(fn (string $tanggal) => Carbon::parse($tanggal)->format('d'));

        $slotFavorit = collect(BookingSlot::daftar())->map(function (array $slot) use ($bookingBulanIni) {
            return $bookingBulanIni->filter(function (Borrowing $borrowing) use ($slot) {
                return substr((string) $borrowing->slot_mulai, 0, 5) === $slot['mulai'];
            })->count();
        });
        $labelSlot = collect(BookingSlot::daftar())->pluck('label');

        $mobilTerpakai = Car::withCount(['borrowings' => function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->where('status', '!=', 'Ditolak');
        }])->orderByDesc('borrowings_count')->get();

        $maxBookingMobil = $mobilTerpakai->max('borrowings_count') ?: 1;

        return view('dashboard.analytics', compact(
            'bulan',
            'totalBooking',
            'totalSelesai',
            'totalDitolak',
            'rataRataPerHari',
            'teamPalingAktif',
            'maxBookingTeam',
            'labelTren',
            'trenHarian',
            'labelSlot',
            'slotFavorit',
            'mobilTerpakai',
            'maxBookingMobil'
        ));
    }
}