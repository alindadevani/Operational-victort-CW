<x-layouts.app :title="'Tambah Mobil'">

    <div class="max-w-lg bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-5">Form Tambah Mobil</h3>

        <form method="POST" action="{{ route('admin.cars.store') }}" class="space-y-4">
            @csrf

            <x-input label="Nama Mobil" name="nama_mobil" required autofocus placeholder="Contoh: Toyota Avanza" />

            <x-input label="Plat Nomor" name="plat_nomor" required placeholder="Contoh: L 1234 ABC" />

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" required
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
                    <option value="tersedia" selected>Tersedia</option>
                    <option value="dipinjam">Dipinjam</option>
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" placeholder="Opsional"
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">Simpan</x-button>
                <a href="{{ route('admin.cars.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>

</x-layouts.app>
