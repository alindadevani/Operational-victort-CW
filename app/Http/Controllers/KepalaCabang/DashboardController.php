<?php

namespace App\Http\Controllers\KepalaCabang;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Borrowing;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard monitoring untuk Kepala Cabang.
     * Menampilkan statistik ringkas + 3 grafik Chart.js.
     */
    public function index(): View
    {
        $today = now()->toDateString();

        // ---------- STATISTIK CARD ----------
        $totalAbsensiHariIni = Attendance::whereDate('tanggal', $today)->count();

        $totalMobilDipakaiHariIni = Borrowing::whereDate('tanggal', $today)
            ->where('status', 'Dipinjam')
            ->count();

        $jumlahPeminjamanSelesai = Borrowing::where('status', 'Selesai')->count();

        $jumlahPeminjamanAktif = Borrowing::where('status', 'Dipinjam')->count();

        // ---------- GRAFIK 1: Absensi per hari (7 hari terakhir) ----------
        $absensiPerHari = Attendance::select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal', '>=', now()->subDays(6)->toDateString())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Susun label 7 hari terakhir supaya hari tanpa data tetap muncul (nilai 0)
        $labelAbsensi = [];
        $dataAbsensi = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->toDateString();
            $labelAbsensi[] = now()->subDays($i)->translatedFormat('d M');
            $found = $absensiPerHari->firstWhere('tanggal', $tanggal);
            $dataAbsensi[] = $found ? $found->total : 0;
        }

        // ---------- GRAFIK 2: Peminjaman mobil per minggu (4 minggu terakhir) ----------
        $labelPeminjaman = [];
        $dataPeminjaman = [];
        for ($i = 3; $i >= 0; $i--) {
            $mulaiMinggu = now()->subWeeks($i)->startOfWeek();
            $akhirMinggu = now()->subWeeks($i)->endOfWeek();

            $labelPeminjaman[] = 'Minggu '.$mulaiMinggu->translatedFormat('d M');
            $dataPeminjaman[] = Borrowing::whereBetween('tanggal', [
                $mulaiMinggu->toDateString(),
                $akhirMinggu->toDateString(),
            ])->count();
        }

        // ---------- GRAFIK 3: Penggunaan mobil berdasarkan team ----------
        $teams = Team::withCount('borrowings')->orderBy('nama_team')->get();
        $labelTeam = $teams->pluck('nama_team');
        $dataTeam = $teams->pluck('borrowings_count');

        return view('dashboard.kepala-cabang', compact(
            'totalAbsensiHariIni',
            'totalMobilDipakaiHariIni',
            'jumlahPeminjamanSelesai',
            'jumlahPeminjamanAktif',
            'labelAbsensi',
            'dataAbsensi',
            'labelPeminjaman',
            'dataPeminjaman',
            'labelTeam',
            'dataTeam'
        ));
    }
}
