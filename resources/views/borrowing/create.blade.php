<x-layouts.app :title="'Form Peminjaman Mobil'">

    <div class="max-w-xl bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-1">Form Peminjaman Mobil Operasional</h3>
        <p class="text-sm text-gray-500 mb-5">Lengkapi form di bawah ini untuk mengajukan peminjaman mobil.</p>

        <form method="POST" action="{{ route('marketing.borrowings.store') }}" class="space-y-4">
            @csrf

            <x-input label="Nama" name="nama" :value="old('nama', auth()->user()->name)" required autofocus />

            <div>
                <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Team <span class="text-red-500">*</span>
                </label>
                <select name="team_id" id="team_id" required
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm {{ $errors->has('team_id') ? 'border-red-400' : '' }}">
                    <option value="">-- Pilih Team --</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->nama_team }}
                        </option>
                    @endforeach
                </select>
                @error('team_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <x-input label="Lokasi Tujuan" name="lokasi" required placeholder="Contoh: Kantor Pusat Surabaya" />

            <div>
                <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-1">
                    Keperluan <span class="text-red-500">*</span>
                </label>
                <textarea name="keperluan" id="keperluan" rows="3" required placeholder="Jelaskan keperluan peminjaman"
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm {{ $errors->has('keperluan') ? 'border-red-400' : '' }}">{{ old('keperluan') }}</textarea>
                @error('keperluan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <x-input label="Tanggal" name="tanggal" type="date" :value="old('tanggal', now()->toDateString())" required min="{{ now()->toDateString() }}" />

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pilih Slot Waktu <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach ($slots as $slot)
                        <label class="cursor-pointer">
                            <input type="radio" name="slot_mulai" value="{{ $slot['mulai'] }}"
                                   {{ old('slot_mulai') == $slot['mulai'] ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="text-center text-sm border border-gray-300 rounded-lg py-2.5 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition">
                                {{ $slot['label'] }}
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('slot_mulai')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-400">
                    Cek dulu <a href="{{ route('schedule.index') }}" class="text-primary hover:underline" target="_blank">Jadwal Mobil</a> supaya tidak booking slot yang sudah penuh.
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">Ajukan Peminjaman</x-button>
                <a href="{{ route('marketing.dashboard') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>

</x-layouts.app>
