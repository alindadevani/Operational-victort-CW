<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - VIF Operational System</title>

    {{-- Tailwind CSS via CDN, supaya mahasiswa mudah menjalankan tanpa build step --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#0F2417',
                        primary: {
                            DEFAULT: '#16A34A', // Hijau utama sesuai tema PT VIF
                            dark: '#0F7A38',
                            light: '#DCFCE7',
                        },
                    },
                    fontFamily: {
                        display: ['"Fraunces"', 'serif'],
                    },
                },
            },
        }
    </script>

    {{-- Heroicons via CDN (pakai icon inline SVG di setiap view) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Fraunces', serif; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- ============ SIDEBAR KIRI ============ --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
        >
            <div class="flex items-center justify-center h-16 border-b border-gray-200 px-4">
                <img src="{{ asset('images/logo-victory.png') }}" alt="PT Victory International Futures" class="h-9 w-auto object-contain">
            </div>

            <nav class="mt-4 px-2 space-y-1">
                @include('layouts.partials.sidebar-menu')
            </nav>
        </aside>

        {{-- Overlay untuk mobile saat sidebar terbuka --}}
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            style="display: none;"
        ></div>

        {{-- ============ KONTEN UTAMA ============ --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- ============ NAVBAR ATAS ============ --}}
            <header class="flex items-center justify-between h-16 bg-white border-b border-gray-200 px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-700">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
                    </div>

                    <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            {{-- ============ AREA KONTEN ============ --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">

                {{-- Notifikasi sukses / error --}}
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
