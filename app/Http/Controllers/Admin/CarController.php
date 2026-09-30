<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        $data = $request->validated();
        unset($data['foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('cars', 'public');
        }

        Car::create($data);

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
        $data = $request->validated();
        unset($data['foto']);

        if ($request->hasFile('foto')) {
            $oldPhoto = $car->foto;
            $data['foto'] = $request->file('foto')->store('cars', 'public');
            $car->update($data);

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        } else {
            $car->update($data);
        }

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
        if ($car->foto) {
            Storage::disk('public')->delete($car->foto);
        }

        return redirect()
            ->route('admin.cars.index')
            ->with('success', 'Mobil berhasil dihapus.');
    }
}
