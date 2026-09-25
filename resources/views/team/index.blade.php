<x-layouts.app :title="'Data Team'">

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">Kelola data team karyawan PT Victory International Futures.</p>
        <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition">
            <x-icon.plus /> Tambah Team
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Nama Team</th>
                        <th class="px-5 py-3 font-medium">Total Absensi</th>
                        <th class="px-5 py-3 font-medium">Total Peminjaman</th>
                        <th class="px-5 py-3 font-medium">Dibuat</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($teams as $team)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-500">{{ $loop->iteration + ($teams->currentPage() - 1) * $teams->perPage() }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $team->nama_team }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $team->attendances_count }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $team->borrowings_count }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $team->created_at->format('d-m-Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.teams.edit', $team) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" onsubmit="return confirm('Yakin ingin menghapus team ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada data team.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($teams->hasPages())
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $teams->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>
