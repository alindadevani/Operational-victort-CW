<x-layouts.guest :title="'Daftar Akun'">

    <h2 class="font-display text-2xl text-ink mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-gray-500 mb-6">Lengkapi data di bawah ini untuk mendaftar.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-input label="Nama Lengkap" name="name" required autofocus placeholder="Masukkan nama lengkap"
            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" />

        <x-input label="Email" name="email" type="email" required placeholder="nama@vif.com"
            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" />

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <select
                name="role"
                id="role"
                required
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm {{ $errors->has('role') ? 'border-red-400' : '' }}"
            >
                <option value="">-- Pilih Role --</option>
                <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>Marketing / MT</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Operasional</option>
                <option value="kepala_cabang" {{ old('role') == 'kepala_cabang' ? 'selected' : '' }}>Kepala Cabang</option>
            </select>
            @error('role')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                name="password"
                id="password"
                required
                placeholder="Minimal 8 karakter"
                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition {{ $errors->has('password') ? 'border-red-400' : '' }}"
            >
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Konfirmasi Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                required
                placeholder="Ulangi password"
                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition"
            >
        </div>

        <x-button type="submit" class="w-full justify-center bg-ink hover:bg-primary-dark">
            Daftar
        </x-button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Masuk di sini</a>
    </p>

</x-layouts.guest>
