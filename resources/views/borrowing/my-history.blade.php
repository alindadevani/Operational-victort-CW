<x-layouts.app :title="'Riwayat Peminjaman Saya'">

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Mobil</th>
                        <th class="px-5 py-3 font-medium">Lokasi</th>
                        <th class="px-5 py-3 font-medium">Keperluan</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Jam Keluar</th>
                        <th class="px-5 py-3 font-medium">Jam Kembali</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($borrowings as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-500">{{ $loop->iteration + ($borrowings->currentPage() - 1) * $borrowings->perPage() }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->car?->nama_mobil ?? 'Belum ditentukan' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->lokasi }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ Str::limit($item->keperluan, 40) }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->tanggal->format('d-m-Y') }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->jam_keluar }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->jam_kembali ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $item->status_badge }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                @if ($item->status === 'Disetujui' && !$item->sudah_tanda_tangan)
                                    <a href="{{ route('marketing.borrowings.signature.create', $item) }}"
                                       class="inline-flex items-center gap-1 text-primary-dark font-medium hover:underline text-sm">
                                        Isi Tanda Tangan
                                    </a>
                                @elseif ($item->status === 'Selesai')
                                    <span class="text-gray-400 text-sm">Sudah selesai</span>
                                @else
                                    <span class="text-gray-300 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-gray-400">Anda belum pernah meminjam mobil.</td>
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
