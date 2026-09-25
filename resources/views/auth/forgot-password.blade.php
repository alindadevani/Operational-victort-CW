<x-layouts.guest :title="'Lupa Password'">

    <h2 class="font-display text-2xl text-ink mb-1">Lupa Password</h2>
    <p class="text-sm text-gray-500 mb-6">
        Masukkan email Anda, kami akan mengirimkan link untuk reset password.
    </p>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-input label="Email" name="email" type="email" required autofocus placeholder="nama@vif.com"
            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" />

        <x-button type="submit" class="w-full justify-center bg-ink hover:bg-primary-dark">
            Kirim Link Reset Password
        </x-button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Kembali ke halaman login</a>
    </p>

</x-layouts.guest>
