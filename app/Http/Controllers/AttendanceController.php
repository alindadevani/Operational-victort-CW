<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Menampilkan form absensi publik (dibuka dari hasil scan QR Code).
     * Tidak memerlukan login karena diakses langsung oleh karyawan via HP.
     */
    public function create(): View
    {
        $teams = Team::orderBy('nama_team')->get();

        return view('attendance.form', compact('teams'));
    }

    /**
     * Menyimpan data absensi ke database.
     * Tanggal, jam, dan status diisi otomatis oleh sistem (bukan input user).
     */
    public function store(AttendanceRequest $request): RedirectResponse
    {
        Attendance::create([
            'nama' => $request->nama,
            'team_id' => $request->team_id,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => now()->toTimeString(),
            'status' => 'Hadir',
            'foto' => $request->foto,
        ]);

        return redirect()
            ->route('attendance.create')
            ->with('success', 'Absensi berhasil. Terima kasih.');
    }
}
