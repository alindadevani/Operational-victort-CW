<x-layouts.app :title="'Edit Mobil'">

    <div class="max-w-lg bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-5">Form Edit Mobil</h3>

        <form method="POST" action="{{ route('admin.cars.update', $car) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-input label="Nama Mobil" name="nama_mobil" :value="$car->nama_mobil" required autofocus />

            <x-input label="Plat Nomor" name="plat_nomor" :value="$car->plat_nomor" required />

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" required
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
                    <option value="tersedia" {{ $car->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ $car->status === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">{{ old('keterangan', $car->keterangan) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">Simpan Perubahan</x-button>
                <a href="{{ route('admin.cars.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>

</x-layouts.app>
