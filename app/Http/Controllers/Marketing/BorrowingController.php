<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\BorrowingRequest;
use App\Http\Requests\SignatureRequest;
use App\Models\Borrowing;
use App\Models\Team;
use App\Support\BookingSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Menampilkan form peminjaman mobil untuk Marketing.
    * Menampilkan slot waktu booking yang tersedia.
     */
    public function create(): View
    {
        $teams = Team::orderBy('nama_team')->get();
        $slots = BookingSlot::daftar();

        return view('borrowing.create', compact('teams', 'slots'));
    }

    /**
    * Menyimpan pengajuan booking dan menunggu persetujuan Admin.
     */
    public function store(BorrowingRequest $request): RedirectResponse
    {
        $slot = collect(BookingSlot::daftar())->firstWhere('mulai', $request->slot_mulai);

        Borrowing::create([
            'nama' => $request->nama,
            'team_id' => $request->team_id,
            'car_id' => null,
            'lokasi' => $request->lokasi,
            'keperluan' => $request->keperluan,
            'tanggal' => $request->tanggal,
            'slot_mulai' => $slot['mulai'],
            'slot_selesai' => $slot['selesai'],
            'jam_keluar' => $slot['mulai'],
            'status' => 'Menunggu Persetujuan',
        ]);

        return redirect()
            ->route('marketing.borrowings.history')
            ->with('success', 'Booking mobil berhasil diajukan. Menunggu persetujuan Admin.');
    }

    /** Menampilkan form tanda tangan untuk booking milik marketing yang disetujui. */
    public function showSignatureForm(Borrowing $borrowing): View
    {
        abort_if($borrowing->nama !== auth()->user()->name, 403);
        abort_if($borrowing->status !== 'Disetujui', 403, 'Booking ini belum disetujui atau sudah selesai.');

        return view('borrowing.signature', compact('borrowing'));
    }

    /** Menyimpan tanda tangan lalu menyelesaikan booking dan mengembalikan mobil. */
    public function storeSignature(SignatureRequest $request, Borrowing $borrowing): RedirectResponse
    {
        abort_if($borrowing->nama !== auth()->user()->name, 403);
        abort_if($borrowing->status !== 'Disetujui', 403);

        $borrowing->update([
            'nama_sopir' => $request->nama_sopir,
            'tanda_tangan_sopir' => $request->tanda_tangan_sopir,
            'tanda_tangan_peminjam' => $request->tanda_tangan_peminjam,
            'status' => 'Selesai',
        ]);

        if ($borrowing->car) {
            $borrowing->car->update(['status' => 'tersedia']);
        }

        return redirect()
            ->route('marketing.borrowings.history')
            ->with('success', 'Tanda tangan berhasil disimpan. Peminjaman selesai, mobil sudah bisa digunakan kembali.');
    }

    /**
     * Menampilkan riwayat peminjaman milik user marketing yang sedang login.
     * Difilter berdasarkan nama user yang login.
     */
    public function history(Request $request): View
    {
        $borrowings = Borrowing::with(['team', 'car'])
            ->where('nama', $request->user()->name)
            ->latest('tanggal')
            ->paginate(10);

        return view('borrowing.my-history', compact('borrowings'));
    }
}
