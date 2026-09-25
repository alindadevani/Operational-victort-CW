<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard sederhana untuk role Marketing.
     * Menampilkan ringkasan aktivitas peminjaman milik user yang login.
     */
    public function index(): View
    {
        $user = Auth::user();

        $totalPeminjamanSaya = Borrowing::where('nama', $user->name)->count();

        $peminjamanAktifSaya = Borrowing::where('nama', $user->name)
            ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
            ->count();

        $riwayatTerbaru = Borrowing::with(['team', 'car'])
            ->where('nama', $user->name)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.marketing', compact(
            'totalPeminjamanSaya',
            'peminjamanAktifSaya',
            'riwayatTerbaru'
        ));
    }
}
