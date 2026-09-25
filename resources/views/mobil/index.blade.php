<x-layouts.app :title="'Data Mobil Operasional'">

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">Kelola data mobil operasional kantor.</p>
        <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition">
            <x-icon.plus /> Tambah Mobil
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Nama Mobil</th>
                        <th class="px-5 py-3 font-medium">Plat Nomor</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Keterangan</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($cars as $car)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-500">{{ $loop->iteration + ($cars->currentPage() - 1) * $cars->perPage() }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $car->nama_mobil }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $car->plat_nomor }}</td>
                            <td class="px-5 py-3">
                                <x-badge :color="$car->status === 'tersedia' ? 'green' : 'red'">
                                    {{ ucfirst($car->status) }}
                                </x-badge>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $car->keterangan ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" onsubmit="return confirm('Yakin ingin menghapus mobil ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada data mobil.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($cars->hasPages())
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $cars->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>
