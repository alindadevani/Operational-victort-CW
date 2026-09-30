<x-layouts.app :title="'Edit Mobil'">

    <div class="max-w-lg bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-5">Form Edit Mobil</h3>

        <form method="POST" action="{{ route('admin.cars.update', $car) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <x-input label="Nama Mobil" name="nama_mobil" :value="$car->nama_mobil" required autofocus />

            <x-input label="Plat Nomor" name="plat_nomor" :value="$car->plat_nomor" required />

            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto Mobil</label>
                @if ($car->foto)
                    <img id="previewFotoMobil" src="{{ asset('storage/'.$car->foto) }}" alt="Foto {{ $car->nama_mobil }}"
                        class="mb-3 h-40 w-full rounded-lg border border-gray-200 object-cover">
                @else
                    <img id="previewFotoMobil" class="hidden mb-3 h-40 w-full rounded-lg border border-gray-200 object-cover" alt="Preview foto mobil">
                @endif
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp"
                    class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium">
                <p class="mt-1 text-xs text-gray-500">Pilih foto baru untuk mengganti foto saat ini. JPG, PNG, atau WEBP, maksimal 5 MB.</p>
                @error('foto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

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

    <script>
        document.getElementById('foto').addEventListener('change', function (event) {
            const preview = document.getElementById('previewFotoMobil');
            const file = event.target.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
    </script>

</x-layouts.app>
