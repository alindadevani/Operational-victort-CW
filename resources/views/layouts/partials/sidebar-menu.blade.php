{{--
    Menu sidebar berbeda tergantung role user.
    Menggunakan helper $user->isAdmin(), isKepalaCabang(), isMarketing()
    supaya kode Blade lebih mudah dibaca.
--}}
@php($user = auth()->user())

@if ($user->isAdmin())

    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
        <x-icon.home />
        Dashboard
    </x-nav-link>

    <x-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.index')">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Jadwal Mobil
    </x-nav-link>

    <x-nav-link :href="route('admin.teams.index')" :active="request()->routeIs('admin.teams.*')">
        <x-icon.users />
        Data Team
    </x-nav-link>

    <x-nav-link :href="route('admin.cars.index')" :active="request()->routeIs('admin.cars.*')">
        <x-icon.truck />
        Data Mobil
    </x-nav-link>

    <x-nav-link :href="route('admin.qrcode')" :active="request()->routeIs('admin.qrcode')">
        <x-icon.qrcode />
        QR Code Absensi
    </x-nav-link>

    <x-nav-link :href="route('admin.attendance-history')" :active="request()->routeIs('admin.attendance-history')">
        <x-icon.clipboard />
        History Absensi
    </x-nav-link>

    <x-nav-link :href="route('admin.borrowings.index')" :active="request()->routeIs('admin.borrowings.*')">
        <x-icon.clock />
        Peminjaman Mobil
    </x-nav-link>

    <x-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Analitik
    </x-nav-link>

@elseif ($user->isKepalaCabang())

    <x-nav-link :href="route('kepala-cabang.dashboard')" :active="request()->routeIs('kepala-cabang.dashboard')">
        <x-icon.home />
        Dashboard Monitoring
    </x-nav-link>

    <x-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.index')">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Jadwal Mobil
    </x-nav-link>

    <x-nav-link :href="route('kepala-cabang.attendance-history')" :active="request()->routeIs('kepala-cabang.attendance-history')">
        <x-icon.clipboard />
        History Absensi
    </x-nav-link>

    <x-nav-link :href="route('kepala-cabang.borrowings.index')" :active="request()->routeIs('kepala-cabang.borrowings.*')">
        <x-icon.clock />
        History Peminjaman
    </x-nav-link>

@elseif ($user->isMarketing())

    <x-nav-link :href="route('marketing.dashboard')" :active="request()->routeIs('marketing.dashboard')">
        <x-icon.home />
        Dashboard
    </x-nav-link>

    <x-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.index')">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/></svg>
        Jadwal Mobil
    </x-nav-link>

    <x-nav-link :href="route('marketing.borrowings.create')" :active="request()->routeIs('marketing.borrowings.create')">
        <x-icon.plus />
        Form Peminjaman Mobil
    </x-nav-link>

    <x-nav-link :href="route('marketing.borrowings.history')" :active="request()->routeIs('marketing.borrowings.history')">
        <x-icon.clock />
        Riwayat Peminjaman Saya
    </x-nav-link>

@endif
