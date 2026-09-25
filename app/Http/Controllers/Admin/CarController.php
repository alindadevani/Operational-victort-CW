<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Menampilkan daftar semua mobil operasional.
     */
    public function index(): View
    {
        $cars = Car::latest()->paginate(10);

        return view('mobil.index', compact('cars'));
    }

    public function create(): View
    {
        return view('mobil.create');
    }

    public function store(CarRequest $request): RedirectResponse
    {
        Car::create($request->validated());

        return redirect()
            ->route('admin.cars.index')
            ->with('success', 'Mobil baru berhasil ditambahkan.');
    }

    public function edit(Car $car): View
    {
        return view('mobil.edit', compact('car'));
    }

    public function update(CarRequest $request, Car $car): RedirectResponse
    {
        $car->update($request->validated());

        return redirect()
            ->route('admin.cars.index')
            ->with('success', 'Data mobil berhasil diperbarui.');
    }

    public function destroy(Car $car): RedirectResponse
    {
        // Cegah hapus mobil yang sedang dipinjam supaya data peminjaman tidak rusak
        if ($car->status === 'dipinjam') {
            return redirect()
                ->route('admin.cars.index')
                ->with('error', 'Mobil sedang dipinjam, tidak bisa dihapus.');
        }

        $car->delete();

        return redirect()
            ->route('admin.cars.index')
            ->with('success', 'Mobil berhasil dihapus.');
    }
}
