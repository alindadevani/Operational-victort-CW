<x-layouts.app :title="'Dashboard Marketing'">

    <section class="relative overflow-hidden bg-ink rounded-2xl p-6 sm:p-8 mb-6">
        <div class="absolute -right-16 -top-24 w-72 h-72 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/5 blur-2xl"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-end justify-between gap-6">
            <div>
                <p class="text-primary-light text-xs font-medium uppercase tracking-widest mb-3">Ruang kerja marketing</p>
                <h2 class="font-display text-3xl sm:text-4xl text-white leading-tight">Halo, {{ auth()->user()->name }}</h2>
                <p class="text-white/55 text-sm mt-2 max-w-md">Kelola booking kendaraan dan pantau status perjalananmu dari satu tempat.</p>
            </div>
            <a href="{{ route('marketing.borrowings.create') }}" class="shrink-0 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                <x-icon.plus />
                Ajukan Booking
            </a>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <a href="{{ route('marketing.borrowings.history') }}" class="group bg-white rounded-xl border border-gray-100 p-5 hover:border-primary/40 hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Total booking saya</p>
                    <p class="font-display text-4xl text-ink mt-2">{{ $totalPeminjamanSaya }}</p>
                    <p class="text-xs text-gray-400 mt-1">Seluruh riwayat peminjaman</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-primary-light text-primary-dark flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 17V8a1 1 0 011-1h9v10"/></svg>
                </div>
            </div>
            <p class="text-xs text-primary-dark opacity-0 group-hover:opacity-100 transition mt-4">Lihat riwayat <span class="ml-1">&rsaquo;</span></p>
        </a>

        <a href="{{ route('schedule.index') }}" class="group bg-white rounded-xl border border-gray-100 p-5 hover:border-amber-300 hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Booking aktif</p>
                    <p class="font-display text-4xl text-ink mt-2">{{ $peminjamanAktifSaya }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menunggu persetujuan atau sedang dipakai</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-amber-700 opacity-0 group-hover:opacity-100 transition mt-4">Cek jadwal mobil <span class="ml-1">&rsaquo;</span></p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Aktivitas</p>
                    <h3 class="font-display text-lg text-ink">Booking terbaru</h3>
                </div>
                <a href="{{ route('marketing.borrowings.history') }}" class="text-sm text-primary-dark font-medium hover:underline">Lihat semua</a>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse ($riwayatTerbaru as $item)
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 17V8a1 1 0 011-1h9v10"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-ink truncate">{{ $item->car?->nama_mobil ?? 'Mobil menunggu persetujuan' }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $item->lokasi }} · {{ $item->tanggal->format('d M Y') }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $item->status_badge }}">
                            {{ $item->status === 'Menunggu Persetujuan' ? 'Menunggu' : $item->status }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-gray-400">Belum ada booking.</p>
                        <a href="{{ route('marketing.borrowings.create') }}" class="inline-block mt-2 text-sm text-primary-dark font-medium hover:underline">Buat booking pertama</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-6 h-fit">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Akses cepat</p>
            <h3 class="font-display text-lg text-ink mb-5">Yang ingin kamu lakukan?</h3>
            <div class="space-y-2">
                <a href="{{ route('marketing.borrowings.create') }}" class="flex items-center gap-3 rounded-lg border border-gray-100 px-3 py-3 hover:border-primary/40 hover:bg-primary-light/40 transition">
                    <span class="w-8 h-8 rounded-lg bg-primary-light text-primary-dark flex items-center justify-center"><x-icon.plus /></span>
                    <span class="text-sm font-medium text-ink">Buat booking baru</span>
                    <span class="ml-auto text-gray-300">&rsaquo;</span>
                </a>
                <a href="{{ route('schedule.index') }}" class="flex items-center gap-3 rounded-lg border border-gray-100 px-3 py-3 hover:border-primary/40 hover:bg-primary-light/40 transition">
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/></svg>
                    </span>
                    <span class="text-sm font-medium text-ink">Lihat jadwal mobil</span>
                    <span class="ml-auto text-gray-300">&rsaquo;</span>
                </a>
                <a href="{{ route('marketing.borrowings.history') }}" class="flex items-center gap-3 rounded-lg border border-gray-100 px-3 py-3 hover:border-primary/40 hover:bg-primary-light/40 transition">
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <span class="text-sm font-medium text-ink">Riwayat peminjaman</span>
                    <span class="ml-auto text-gray-300">&rsaquo;</span>
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>