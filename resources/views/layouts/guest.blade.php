<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} - VIF Operational System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#0F2417',
                        primary: { DEFAULT: '#16A34A', dark: '#0F7A38', light: '#DCFCE7' },
                        sand: '#F3F6F2',
                    },
                    fontFamily: {
                        display: ['"Fraunces"', 'serif'],
                        sans: ['"Inter"', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Fraunces', serif; }
        .grain { background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.08) 1px, transparent 0); background-size: 18px 18px; }
    </style>
</head>
<body class="bg-sand text-ink">

<div class="min-h-screen flex">

    {{-- ================= SISI KIRI: ILUSTRASI + BRAND (disembunyikan di layar kecil) ================= --}}
    <div class="hidden lg:flex lg:w-[46%] relative bg-ink overflow-hidden flex-col justify-between p-12">

        <div class="absolute inset-0 grain"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-primary-dark/30 to-transparent"></div>

        <div class="relative z-10 flex items-center gap-3">
            <div class="bg-white rounded-lg px-2 py-1.5">
                <img src="{{ asset('images/logo-victory.png') }}" alt="PT Victory International Futures" class="h-6 w-auto object-contain">
            </div>
            <span class="text-white/90 text-sm tracking-wide">Victory International Futures</span>
        </div>

        <div class="relative z-10">
            <h1 class="font-display text-white text-4xl xl:text-[2.75rem] leading-[1.15] mb-4">
                Satu tempat untuk<br>seluruh operasional<br>cabang Ciputra World.
            </h1>
            <p class="text-white/60 text-sm max-w-xs mb-10">
                Absensi, peminjaman kendaraan, dan laporan tim — tercatat rapi, bisa dipantau kapan saja.
            </p>

            <div class="relative h-64">
                <div class="absolute left-0 top-4 w-56 bg-white/95 rounded-2xl p-4 shadow-2xl -rotate-3">
                    <p class="text-[11px] text-gray-400 mb-2">Kehadiran hari ini</p>
                    <div class="flex items-end gap-1.5 h-16">
                        <div class="w-4 bg-primary/25 rounded-t" style="height:40%"></div>
                        <div class="w-4 bg-primary/40 rounded-t" style="height:65%"></div>
                        <div class="w-4 bg-primary rounded-t" style="height:95%"></div>
                        <div class="w-4 bg-primary/40 rounded-t" style="height:55%"></div>
                        <div class="w-4 bg-primary/25 rounded-t" style="height:30%"></div>
                    </div>
                    <p class="mt-2 text-2xl font-display font-semibold text-ink">32<span class="text-sm font-sans text-gray-400 font-normal"> / 35 hadir</span></p>
                </div>

                <div class="absolute right-0 top-0 w-52 bg-white rounded-2xl p-4 shadow-2xl rotate-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-full bg-primary-light flex items-center justify-center text-primary-dark font-semibold text-xs">B</div>
                        <div>
                            <p class="text-xs font-medium text-ink">Budi Santoso</p>
                            <p class="text-[10px] text-gray-400">Team Harry</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-primary-light px-3 py-2">
                        <span class="text-[11px] text-primary-dark font-medium">Hadir · 08:12</span>
                        <svg class="w-4 h-4 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                <div class="absolute left-16 bottom-0 w-44 bg-white rounded-2xl p-3.5 shadow-2xl rotate-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-ink/5 flex items-center justify-center">
                            <svg class="w-4 h-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 17V8a1 1 0 011-1h9v10"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-medium text-ink">Toyota Avanza</p>
                            <p class="text-[10px] text-gray-400">Sedang dipinjam</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="relative z-10 text-white/40 text-xs">© {{ date('Y') }} PT Victory International Futures</p>
    </div>

    {{-- ================= SISI KANAN: SLOT FORM (login/register/dll masuk di sini) ================= --}}
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-sm">

            {{-- Brand mark versi mobile, cuma muncul kalau layar sempit --}}
            <div class="lg:hidden flex items-center gap-3 mb-10">
                <img src="{{ asset('images/logo-victory.png') }}" alt="PT Victory International Futures" class="h-9 w-auto object-contain">
                <span class="text-ink/70 text-sm">Victory International Futures</span>
            </div>

            {{ $slot }}
        </div>
    </div>

</div>

</body>
</html>
