<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BorrowingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveBorrowingRequest;
use App\Http\Requests\RejectBorrowingRequest;
use App\Models\Borrowing;
use App\Models\Car;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BorrowingHistoryController extends Controller
{
    /**
     * Menampilkan history peminjaman mobil seluruh karyawan.
     * Bisa diakses Admin & Kepala Cabang (view sama, hak edit dibedakan di Blade).
     */
    public function index(Request $request): View
    {
        $query = Borrowing::with(['team', 'car']);

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest('tanggal')
            ->paginate(15)
            ->withQueryString();

        $teams = Team::orderBy('nama_team')->get();
        $cars = Car::orderBy('nama_mobil')->get();

        return view('borrowing.history', compact('borrowings', 'teams', 'cars'));
    }

    /**
     * Menampilkan detail satu peminjaman (dipakai admin untuk proses pengembalian).
     */
    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['team', 'car']);

        return view('borrowing.detail', compact('borrowing'));
    }

    /** Menyetujui booking dan menetapkan mobil yang tersedia. */
    public function approve(ApproveBorrowingRequest $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'Menunggu Persetujuan') {
            return redirect()
                ->route('admin.borrowings.show', $borrowing)
                ->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        $car = Car::whereKey($request->car_id)
            ->where('status', 'tersedia')
            ->first();

        if (!$car) {
            return back()->withInput()->with('error', 'Mobil yang dipilih sudah tidak tersedia.');
        }

        $borrowing->update([
            'car_id' => $car->id,
            'jam_keluar' => $borrowing->slot_mulai,
            'jam_kembali' => $borrowing->slot_selesai,
            'status' => 'Disetujui',
        ]);
        $car->update(['status' => 'dipinjam']);

        return redirect()
            ->route('admin.borrowings.show', $borrowing)
            ->with('success', 'Booking berhasil disetujui.');
    }

    /** Menolak booking dengan alasan dari admin. */
    public function reject(RejectBorrowingRequest $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'Menunggu Persetujuan') {
            return redirect()
                ->route('admin.borrowings.show', $borrowing)
                ->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        $borrowing->update([
            'alasan_penolakan' => $request->alasan_penolakan,
            'status' => 'Ditolak',
        ]);

        return redirect()
            ->route('admin.borrowings.show', $borrowing)
            ->with('success', 'Booking berhasil ditolak.');
    }

    /** Menandai booking selesai dan mengembalikan status mobil. */
    public function complete(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'Disetujui' || !$borrowing->car) {
            return redirect()
                ->route('admin.borrowings.show', $borrowing)
                ->with('error', 'Booking ini belum dapat diselesaikan.');
        }

        $borrowing->update(['status' => 'Selesai']);
        $borrowing->car->update(['status' => 'tersedia']);

        return redirect()
            ->route('admin.borrowings.show', $borrowing)
            ->with('success', 'Booking ditandai selesai, mobil kembali tersedia.');
    }

    /**
     * Export history peminjaman ke Excel dengan filter yang sama seperti index().
     */
    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(
            new BorrowingExport($request->only(['team_id', 'car_id', 'tanggal', 'status'])),
            'history-peminjaman-'.now()->format('Y-m-d_His').'.xlsx'
        );
    }
}
