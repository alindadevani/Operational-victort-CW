@php
    // Menentukan apakah user adalah admin (untuk menampilkan tombol export)
    // dan menentukan nama route yang sedang aktif (admin.* atau kepala-cabang.*)
    $isAdmin = auth()->user()->isAdmin();
    $routePrefix = $isAdmin ? 'admin' : 'kepala-cabang';
@endphp

<x-layouts.app :title="'History Absensi'">

    {{-- ============ FILTER ============ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route($routePrefix.'.attendance-history') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama karyawan..."
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
            </div>

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

            <div class="sm:col-span-4 flex items-center gap-3">
                <x-button type="submit">Terapkan Filter</x-button>
                <a href="{{ route($routePrefix.'.attendance-history') }}" class="text-sm text-gray-500 hover:underline">Reset</a>

                @if ($isAdmin)
                    <a href="{{ route('admin.attendance-history.export', request()->query()) }}"
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
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Team</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Jam Masuk</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Foto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($attendances as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-500">{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->nama }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->team->nama_team ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->tanggal->format('d-m-Y') }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->jam_masuk }}</td>
                            <td class="px-5 py-3">
                                <x-badge color="green">{{ $item->status }}</x-badge>
                            </td>
                            <td class="px-5 py-3">
                                @if ($item->foto)
                                    <img src="{{ $item->foto }}" alt="Foto absensi {{ $item->nama }}"
                                         class="w-12 h-12 rounded-lg object-cover cursor-pointer hover:opacity-80 transition"
                                         onclick="document.getElementById('modalFoto_{{ $item->id }}').classList.remove('hidden')">

                                    <div id="modalFoto_{{ $item->id }}" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
                                         onclick="this.classList.add('hidden')">
                                        <img src="{{ $item->foto }}" alt="Foto absensi {{ $item->nama }}" class="max-w-full max-h-full rounded-lg" onclick="event.stopPropagation()">
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-400">Tidak ada data absensi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>
