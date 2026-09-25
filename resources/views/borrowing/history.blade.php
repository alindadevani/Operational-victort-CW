@php
    $isAdmin = auth()->user()->isAdmin();
    $routePrefix = $isAdmin ? 'admin' : 'kepala-cabang';
@endphp

<x-layouts.app :title="'History Peminjaman Mobil'">

    {{-- ============ FILTER ============ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route($routePrefix.'.borrowings.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Team</label>
                <select name="team_id" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
                    <option value="">Semua Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->nama_team }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mobil</label>
                <select name="car_id" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
                    <option value="">Semua Mobil</option>
                    @foreach ($cars as $car)
                        <option value="{{ $car->id }}" {{ request('car_id') == $car->id ? 'selected' : '' }}>
                            {{ $car->nama_mobil }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Persetujuan" {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="sm:col-span-4 flex items-center gap-3">
                <x-button type="submit">Terapkan Filter</x-button>
                <a href="{{ route($routePrefix.'.borrowings.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>

                @if ($isAdmin)
                    <a href="{{ route('admin.borrowings.export', request()->query()) }}"
                       class="ml-auto inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition">
                        Export Excel
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ============ TABEL HISTORY ============ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Team</th>
                        <th class="px-5 py-3 font-medium">Mobil</th>
                        <th class="px-5 py-3 font-medium">Plat Nomor</th>
                        <th class="px-5 py-3 font-medium">Lokasi</th>
                        <th class="px-5 py-3 font-medium">Jam Keluar</th>
                        <th class="px-5 py-3 font-medium">Jam Kembali</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($borrowings as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->nama }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->team->nama_team ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->car?->nama_mobil ?? 'Belum ditentukan' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->car?->plat_nomor ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->lokasi }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->jam_keluar }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->jam_kembali ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $item->status_badge }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route($routePrefix.'.borrowings.show', $item) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-gray-400">Tidak ada data peminjaman ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($borrowings->hasPages())
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>
