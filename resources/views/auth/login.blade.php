<x-layouts.guest :title="'Login'">

    <h2 class="font-display text-2xl text-ink mb-1">Masuk ke Akun Anda</h2>
    <p class="text-sm text-gray-500 mb-6">Silakan login menggunakan email dan password Anda.</p>

    {{-- Status session (misal setelah reset password) --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-input label="Email" name="email" type="email" required autofocus placeholder="nama@vif.com"
            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" />

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                name="password"
                id="password"
                required
                placeholder="••••••••"
                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition {{ $errors->has('password') ? 'border-red-400' : '' }}"
            >
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                Ingat saya
            </label>

            <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
                Lupa password?
            </a>
        </div>

        <x-button type="submit" class="w-full justify-center bg-ink hover:bg-primary-dark">
            Masuk
        </x-button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Daftar di sini</a>
    </p>

</x-layouts.guest>
