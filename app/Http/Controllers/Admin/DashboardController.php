<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Borrowing;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Admin dengan 5 kartu statistik
     * dan tabel aktivitas terbaru (gabungan absensi & peminjaman).
     */
    public function index(): View
    {
        $today = now()->toDateString();

        // Card 1: Total absensi hari ini
        $totalAbsensiHariIni = Attendance::whereDate('tanggal', $today)->count();

        // Card 2: Total karyawan hadir hari ini (sama dengan total absensi karena status selalu "Hadir")
        $totalKaryawanHadir = Attendance::whereDate('tanggal', $today)
            ->where('status', 'Hadir')
            ->count();

        // Card 3: Total mobil tersedia
        $totalMobilTersedia = Car::where('status', 'tersedia')->count();

        // Card 4: Total mobil sedang dipinjam
        $totalMobilDipinjam = Car::where('status', 'dipinjam')->count();

        // Card 5: Total peminjaman bulan ini
        $totalPeminjamanBulanIni = Borrowing::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Tabel aktivitas terbaru: 5 absensi + 5 peminjaman terbaru digabung dan diurutkan
        $absensiTerbaru = Attendance::with('team')->latest()->take(5)->get()->map(function ($item) {
            return [
                'jenis' => 'Absensi',
                'keterangan' => $item->nama.' ('.$item->team->nama_team.') - Hadir',
                'waktu' => $item->created_at,
            ];
        });

        $peminjamanTerbaru = Borrowing::with(['team', 'car'])->latest()->take(5)->get()->map(function ($item) {
            return [
                'jenis' => 'Peminjaman Mobil',
                'keterangan' => $item->nama.' booking '.($item->car?->nama_mobil ?? 'mobil belum ditentukan').' - '.$item->status,
                'waktu' => $item->created_at,
            ];
        });

        $aktivitasTerbaru = $absensiTerbaru->concat($peminjamanTerbaru)
            ->sortByDesc('waktu')
            ->take(10)
            ->values();

        return view('dashboard.admin', compact(
            'totalAbsensiHariIni',
            'totalKaryawanHadir',
            'totalMobilTersedia',
            'totalMobilDipinjam',
            'totalPeminjamanBulanIni',
            'aktivitasTerbaru'
        ));
    }
}
