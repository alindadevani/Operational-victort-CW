<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Support\BookingSlot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /** Menampilkan jadwal booking mobil berdasarkan tanggal. */
    public function index(Request $request): View
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $bookingHariIni = Borrowing::with(['team', 'car'])
            ->whereDate('tanggal', $tanggal)
            ->where('status', '!=', 'Ditolak')
            ->get();

        $jadwal = collect(BookingSlot::daftar())->map(function (array $slot) use ($bookingHariIni) {
            $bookingDiSlot = $bookingHariIni->filter(function (Borrowing $borrowing) use ($slot) {
                return substr((string) $borrowing->slot_mulai, 0, 5) === $slot['mulai'];
            });

            return [
                'label' => $slot['label'],
                'mulai' => $slot['mulai'],
                'terisi' => $bookingDiSlot->count(),
                'kuota' => BookingSlot::KUOTA_PER_SLOT,
                'daftar_booking' => $bookingDiSlot->values(),
            ];
        });

        return view('borrowing.schedule', compact('jadwal', 'tanggal'));
    }
}