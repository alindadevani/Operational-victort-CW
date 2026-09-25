<x-layouts.app :title="'Analitik Peminjaman'">

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">Ringkasan performa penggunaan mobil operasional.</p>
        <form method="GET" action="{{ route('admin.analytics.index') }}">
            <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:border-primary focus:ring-primary">
        </form>
    </div>

    {{-- Ringkasan angka bulan terpilih. --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 mb-2">Total booking bulan ini</p>
            <p class="font-display text-3xl text-ink">{{ $totalBooking }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 mb-2">Selesai</p>
            <p class="font-display text-3xl text-ink">{{ $totalSelesai }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 mb-2">Ditolak</p>
            <p class="font-display text-3xl text-ink">{{ $totalDitolak }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 mb-2">Rata-rata per hari</p>
            <p class="font-display text-3xl text-ink">{{ $rataRataPerHari }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-display text-base text-ink mb-1">Team paling aktif</h3>
            <p class="text-xs text-gray-400 mb-5">Jumlah booking per team bulan ini</p>

            <div class="space-y-4">
                @foreach ($teamPalingAktif as $team)
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="{{ $team->borrowings_count > 0 ? 'text-ink font-medium' : 'text-gray-400' }}">{{ $team->nama_team }}</span>
                            <span class="{{ $team->borrowings_count > 0 ? 'text-gray-400' : 'text-gray-300' }}">{{ $team->borrowings_count }} booking</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $team->borrowings_count > 0 ? 'bg-primary' : 'bg-gray-200' }} rounded-full" style="width:{{ ($team->borrowings_count / $maxBookingTeam) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($teamPalingAktif->first() && $teamPalingAktif->first()->borrowings_count > 0)
                <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-400">
                    <strong class="text-primary-dark">{{ $teamPalingAktif->first()->nama_team }}</strong> paling banyak menggunakan mobil operasional bulan ini.
                </div>
            @endif
        </div>

        <div class="lg:col-span-3 bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-display text-base text-ink mb-1">Tren booking harian</h3>
            <p class="text-xs text-gray-400 mb-5">Sepanjang bulan yang dipilih</p>
            <canvas id="trenChart" height="140"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-display text-base text-ink mb-1">Slot waktu favorit</h3>
            <p class="text-xs text-gray-400 mb-5">Slot mana yang paling sering dibooking</p>
            <canvas id="slotChart" height="160"></canvas>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-display text-base text-ink mb-1">Mobil paling sering dipakai</h3>
            <p class="text-xs text-gray-400 mb-5">Distribusi penggunaan per unit mobil</p>
            <div class="space-y-3 mt-2">
                @foreach ($mobilTerpakai as $mobil)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg {{ $mobil->borrowings_count > 0 ? 'bg-primary-light' : 'bg-gray-50' }} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 {{ $mobil->borrowings_count > 0 ? 'text-primary-dark' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 17V8a1 1 0 011-1h9v10"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-ink">{{ $mobil->nama_mobil }}</p>
                            <div class="h-1.5 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                <div class="h-full {{ $mobil->borrowings_count > 0 ? 'bg-primary' : 'bg-gray-200' }} rounded-full" style="width:{{ ($mobil->borrowings_count / $maxBookingMobil) * 100 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-400">{{ $mobil->borrowings_count }}x</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('trenChart'), {
                type: 'line',
                data: {
                    labels: @json($labelTren),
                    datasets: [{
                        data: @json($trenHarian),
                        borderColor: '#16A34A',
                        backgroundColor: 'rgba(22,163,74,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } },
                },
            });

            new Chart(document.getElementById('slotChart'), {
                type: 'bar',
                data: {
                    labels: @json($labelSlot),
                    datasets: [{
                        data: @json($slotFavorit),
                        backgroundColor: '#16A34A',
                        borderRadius: 6,
                        maxBarThickness: 40,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } },
                },
            });
        </script>
    @endpush

</x-layouts.app>