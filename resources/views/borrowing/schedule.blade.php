@php
    $tanggalTerpilih = \Carbon\Carbon::parse($tanggal);
    $totalTerisi = $jadwal->sum('terisi');
    $totalKuota = $jadwal->sum('kuota');
    $persentaseTerisi = $totalKuota > 0 ? round(($totalTerisi / $totalKuota) * 100) : 0;
@endphp

<x-layouts.app :title="'Jadwal Mobil'">

    <section class="relative overflow-hidden bg-ink rounded-2xl p-6 sm:p-8 mb-6">
        <div class="absolute -right-16 -top-24 w-72 h-72 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute right-10 bottom-0 w-32 h-32 rounded-full bg-white/5 blur-2xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 text-primary-light text-xs font-medium uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                    Operasional kendaraan
                </div>
                <h2 class="font-display text-3xl sm:text-4xl text-white leading-tight">Jadwal Mobil</h2>
                <p class="text-white/55 text-sm mt-2 max-w-md">
                    Pantau ketersediaan slot sebelum mengajukan booking kendaraan.
                </p>
            </div>

            <form method="GET" action="{{ route('schedule.index') }}" class="flex items-end gap-3">
                <div>
                    <label for="tanggal" class="block text-xs font-medium text-white/55 mb-1.5">Pilih tanggal</label>
                    <input id="tanggal" type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="w-full sm:w-auto rounded-lg border-0 bg-white px-3 py-2.5 text-sm text-ink focus:ring-2 focus:ring-primary">
                </div>
            </form>
        </div>
    </section>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">Jadwal terpilih</p>
            <h3 class="font-display text-xl text-ink mt-1">{{ $tanggalTerpilih->translatedFormat('l, d F Y') }}</h3>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
            Tersedia
            <span class="w-2.5 h-2.5 rounded-full bg-red-400 ml-3"></span>
            Penuh
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <div class="bg-white border border-gray-100 rounded-xl px-4 py-3">
            <p class="text-xs text-gray-400">Slot terisi</p>
            <p class="font-display text-2xl text-ink mt-1">{{ $totalTerisi }} <span class="font-sans text-sm text-gray-400">/ {{ $totalKuota }}</span></p>
        </div>
        <div class="bg-white border border-gray-100 rounded-xl px-4 py-3">
            <p class="text-xs text-gray-400">Slot tersedia</p>
            <p class="font-display text-2xl text-primary-dark mt-1">{{ $totalKuota - $totalTerisi }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-xl px-4 py-3">
            <p class="text-xs text-gray-400">Kapasitas terpakai</p>
            <p class="font-display text-2xl text-ink mt-1">{{ $persentaseTerisi }}<span class="font-sans text-sm text-gray-400">%</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($jadwal as $slot)
            @php
                $slotPenuh = $slot['terisi'] >= $slot['kuota'];
                $slotHampirPenuh = !$slotPenuh && $slot['terisi'] > 0;
            @endphp
            <div class="relative bg-white rounded-xl border border-gray-100 overflow-hidden {{ $slotPenuh ? 'shadow-sm' : 'hover:shadow-md' }} transition-shadow">
                <div class="h-1 {{ $slotPenuh ? 'bg-red-400' : ($slotHampirPenuh ? 'bg-amber-400' : 'bg-primary') }}"></div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3 mb-5">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Slot waktu</p>
                            <h3 class="font-display text-xl text-ink">{{ $slot['label'] }}</h3>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $slotPenuh ? 'bg-red-50 text-red-600' : ($slotHampirPenuh ? 'bg-amber-50 text-amber-700' : 'bg-primary-light text-primary-dark') }}">
                            {{ $slot['terisi'] }} / {{ $slot['kuota'] }} terisi
                        </span>
                    </div>

                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden mb-5">
                        <div class="h-full rounded-full {{ $slotPenuh ? 'bg-red-400' : ($slotHampirPenuh ? 'bg-amber-400' : 'bg-primary') }}" style="width: {{ min(100, ($slot['terisi'] / $slot['kuota']) * 100) }}%"></div>
                    </div>

                    @forelse ($slot['daftar_booking'] as $booking)
                        <div class="flex items-center gap-3 py-3 border-t border-gray-100 first:border-t-0">
                            <div class="w-9 h-9 rounded-lg {{ $booking->status === 'Ditolak' ? 'bg-red-50 text-red-500' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center shrink-0 text-sm font-semibold">
                                {{ strtoupper(substr($booking->nama, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-ink truncate">{{ $booking->nama }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $booking->team?->nama_team ?? '-' }} · {{ $booking->car?->nama_mobil ?? 'Menunggu persetujuan' }}</p>
                            </div>
                            <span class="ml-auto shrink-0 text-[10px] font-medium {{ $booking->status === 'Selesai' ? 'text-blue-600' : ($booking->status === 'Disetujui' ? 'text-primary-dark' : 'text-amber-600') }}">
                                {{ $booking->status === 'Menunggu Persetujuan' ? 'Menunggu' : $booking->status }}
                            </span>
                        </div>
                    @empty
                        <div class="flex items-center gap-2 py-2 text-sm text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-gray-200"></span>
                            Slot ini masih kosong
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

</x-layouts.app>