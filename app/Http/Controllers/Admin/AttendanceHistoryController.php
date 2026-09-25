<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttendanceHistoryController extends Controller
{
    /**
     * Menampilkan history absensi seluruh karyawan.
     * Bisa diakses oleh Admin maupun Kepala Cabang (route berbeda, view sama).
     */
    public function index(Request $request): View
    {
        $query = Attendance::with('team');

        // Filter search nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter team
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        $attendances = $query->latest('tanggal')
            ->latest('jam_masuk')
            ->paginate(15)
            ->withQueryString();

        $teams = Team::orderBy('nama_team')->get();

        return view('attendance.history', compact('attendances', 'teams'));
    }

    /**
     * Export history absensi ke file Excel, dengan filter yang sama seperti index().
     */
    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(
            new AttendanceExport($request->only(['search', 'tanggal', 'team_id'])),
            'history-absensi-'.now()->format('Y-m-d_His').'.xlsx'
        );
    }
}
