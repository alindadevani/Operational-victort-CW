<x-layouts.guest :title="'Reset Password'">

    <h2 class="font-display text-2xl text-ink mb-1">Reset Password</h2>
    <p class="text-sm text-gray-500 mb-6">Masukkan password baru Anda.</p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-input label="Email" name="email" type="email" :value="old('email', $request->email)" required autofocus
            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" />

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password Baru <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password" id="password" required
                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition {{ $errors->has('password') ? 'border-red-400' : '' }}">
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Konfirmasi Password Baru <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition">
        </div>

        <x-button type="submit" class="w-full justify-center bg-ink hover:bg-primary-dark">
            Reset Password
        </x-button>
    </form>

</x-layouts.guest>
