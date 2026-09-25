<x-layouts.app :title="'Dashboard Monitoring'">

    {{-- ============ CARD STATISTIK ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <x-stat-card label="Absensi Hari Ini" :value="$totalAbsensiHariIni" color="green">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Mobil Dipakai Hari Ini" :value="$totalMobilDipakaiHariIni" color="red">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 17V8a1 1 0 011-1h9v10" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Peminjaman Selesai" :value="$jumlahPeminjamanSelesai" color="blue">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Peminjaman Aktif" :value="$jumlahPeminjamanAktif" color="yellow">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- ============ GRAFIK ============ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Grafik Absensi 7 Hari Terakhir</h3>
            <canvas id="chartAbsensi" height="220"></canvas>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Grafik Peminjaman Mobil per Minggu</h3>
            <canvas id="chartPeminjaman" height="220"></canvas>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Penggunaan Mobil Berdasarkan Team</h3>
        <canvas id="chartTeam" height="220"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Data dikirim dari Controller (KepalaCabang\DashboardController)
        const labelAbsensi = @json($labelAbsensi);
        const dataAbsensi = @json($dataAbsensi);

        const labelPeminjaman = @json($labelPeminjaman);
        const dataPeminjaman = @json($dataPeminjaman);

        const labelTeam = @json($labelTeam);
        const dataTeam = @json($dataTeam);

        // Grafik 1: Absensi per hari (line chart)
        new Chart(document.getElementById('chartAbsensi'), {
            type: 'line',
            data: {
                labels: labelAbsensi,
                datasets: [{
                    label: 'Jumlah Absensi',
                    data: dataAbsensi,
                    borderColor: '#16A34A',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            },
        });

        // Grafik 2: Peminjaman per minggu (bar chart)
        new Chart(document.getElementById('chartPeminjaman'), {
            type: 'bar',
            data: {
                labels: labelPeminjaman,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: dataPeminjaman,
                    backgroundColor: '#16A34A',
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            },
        });

        // Grafik 3: Penggunaan mobil per team (doughnut chart)
        new Chart(document.getElementById('chartTeam'), {
            type: 'doughnut',
            data: {
                labels: labelTeam,
                datasets: [{
                    data: dataTeam,
                    backgroundColor: ['#16A34A', '#22C55E', '#4ADE80', '#86EFAC', '#BBF7D0'],
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    </script>
    @endpush

</x-layouts.app>
