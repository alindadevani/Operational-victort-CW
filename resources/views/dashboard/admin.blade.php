<x-layouts.app :title="'Dashboard Admin'">

    {{-- ============ HERO: KEHADIRAN HARI INI ============ --}}
    <div class="bg-ink rounded-2xl p-7 mb-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="relative flex flex-wrap items-end justify-between gap-6">
            <div>
                <p class="text-white/50 text-sm mb-1">Kehadiran hari ini</p>
                <p class="font-display text-5xl text-white">
                    {{ $totalAbsensiHariIni }}<span class="text-2xl text-white/40 font-sans"> karyawan absen</span>
                </p>
                <p class="text-white/40 text-xs mt-2">
                    @if ($totalAbsensiHariIni == 0)
                        Belum ada yang absen hari ini — cek QR Code sudah tertempel dengan benar.
                    @else
                        {{ $totalKaryawanHadir }} orang tercatat hadir sejauh ini hari ini.
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.qrcode') }}" class="shrink-0 inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                <x-icon.qrcode />
                Lihat QR Code
            </a>
        </div>
    </div>

    {{-- ============ CARD STATISTIK: SEMUA BISA DIKLIK ============ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.attendance-history') }}" class="group bg-white rounded-xl border border-gray-100 p-5 hover:border-primary/40 hover:shadow-md transition">
            <p class="text-xs text-gray-400 mb-3">Karyawan hadir</p>
            <p class="font-display text-3xl text-ink mb-1">{{ $totalKaryawanHadir }}</p>
            <p class="text-xs text-primary-dark opacity-0 group-hover:opacity-100 transition flex items-center gap-1">Lihat history <span class="group-hover:translate-x-0.5 transition-transform">&rsaquo;</span></p>
        </a>

        <a href="{{ route('admin.cars.index') }}" class="group bg-white rounded-xl border border-gray-100 p-5 hover:border-primary/40 hover:shadow-md transition">
            <p class="text-xs text-gray-400 mb-3">Mobil tersedia</p>
            <p class="font-display text-3xl text-ink mb-1">{{ $totalMobilTersedia }}</p>
            <p class="text-xs text-primary-dark opacity-0 group-hover:opacity-100 transition flex items-center gap-1">Lihat data mobil <span class="group-hover:translate-x-0.5 transition-transform">&rsaquo;</span></p>
        </a>

        <a href="{{ route('admin.borrowings.index') }}" class="group bg-white rounded-xl border border-gray-100 p-5 hover:border-primary/40 hover:shadow-md transition">
            <p class="text-xs text-gray-400 mb-3">Mobil dipinjam</p>
            <p class="font-display text-3xl text-ink mb-1">{{ $totalMobilDipinjam }}</p>
            <p class="text-xs text-primary-dark opacity-0 group-hover:opacity-100 transition flex items-center gap-1">Lihat peminjaman <span class="group-hover:translate-x-0.5 transition-transform">&rsaquo;</span></p>
        </a>

        <a href="{{ route('admin.analytics.index') }}" class="group bg-white rounded-xl border border-gray-100 p-5 hover:border-primary/40 hover:shadow-md transition">
            <p class="text-xs text-gray-400 mb-3">Peminjaman bulan ini</p>
            <p class="font-display text-3xl text-ink mb-1">{{ $totalPeminjamanBulanIni }}</p>
            <p class="text-xs text-primary-dark opacity-0 group-hover:opacity-100 transition flex items-center gap-1">Lihat analitik <span class="group-hover:translate-x-0.5 transition-transform">&rsaquo;</span></p>
        </a>
    </div>

    {{-- ============ AKTIVITAS TERBARU ============ --}}
    <div class="bg-white rounded-xl border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-display text-base text-ink">Aktivitas terbaru</h3>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse ($aktivitasTerbaru as $aktivitas)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="w-8 h-8 rounded-full {{ $aktivitas['jenis'] === 'Absensi' ? 'bg-primary-light' : 'bg-blue-50' }} flex items-center justify-center shrink-0">
                        @if ($aktivitas['jenis'] === 'Absensi')
                            <svg class="w-4 h-4 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 17V8a1 1 0 011-1h9v10"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-ink">{{ $aktivitas['keterangan'] }}</p>
                        <p class="text-xs text-gray-400">{{ $aktivitas['waktu']->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </div>

</x-layouts.app>