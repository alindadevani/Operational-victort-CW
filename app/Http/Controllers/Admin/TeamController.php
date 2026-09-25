<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Menampilkan daftar semua team.
     */
    public function index(): View
    {
        $teams = Team::withCount(['attendances', 'borrowings'])
            ->latest()
            ->paginate(10);

        return view('team.index', compact('teams'));
    }

    /**
     * Menampilkan form tambah team.
     */
    public function create(): View
    {
        return view('team.create');
    }

    /**
     * Menyimpan data team baru ke database.
     */
    public function store(TeamRequest $request): RedirectResponse
    {
        Team::create($request->validated());

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Team baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit team.
     */
    public function edit(Team $team): View
    {
        return view('team.edit', compact('team'));
    }

    /**
     * Memperbarui data team.
     */
    public function update(TeamRequest $request, Team $team): RedirectResponse
    {
        $team->update($request->validated());

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Data team berhasil diperbarui.');
    }

    /**
     * Menghapus data team.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Team berhasil dihapus.');
    }
}
