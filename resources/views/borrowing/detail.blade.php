@php
    $isAdmin = auth()->user()->isAdmin();
    $routePrefix = $isAdmin ? 'admin' : 'kepala-cabang';
@endphp

<x-layouts.app :title="'Detail Peminjaman'">

    <div class="max-w-2xl bg-white rounded-xl border border-gray-200 shadow-sm p-6">

        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-gray-800">Detail Peminjaman Mobil</h3>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $borrowing->status_badge }}">
                {{ $borrowing->status }}
            </span>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-500">Nama Peminjam</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->nama }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Team</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->team->nama_team ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Mobil</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->car?->nama_mobil ?? 'Belum ditentukan' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Plat Nomor</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->car?->plat_nomor ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Lokasi Tujuan</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->lokasi }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Tanggal</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->tanggal->format('d-m-Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jam Keluar</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->jam_keluar }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jam Kembali</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->jam_kembali ?? '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Keperluan</dt>
                <dd class="font-medium text-gray-800">{{ $borrowing->keperluan }}</dd>
            </div>
            @if ($borrowing->sudah_tanda_tangan)
                <div>
                    <dt class="text-gray-500">Nama Sopir</dt>
                    <dd class="font-medium text-gray-800">{{ $borrowing->nama_sopir }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-gray-500 mb-2">Tanda Tangan Sopir</dt>
                    <dd>
                        <img src="{{ $borrowing->tanda_tangan_sopir }}" alt="Tanda Tangan Sopir" class="border border-gray-200 rounded-lg bg-white max-w-xs">
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-gray-500 mb-2">Tanda Tangan Peminjam</dt>
                    <dd>
                        <img src="{{ $borrowing->tanda_tangan_peminjam }}" alt="Tanda Tangan Peminjam" class="border border-gray-200 rounded-lg bg-white max-w-xs">
                    </dd>
                </div>
            @endif
            @if ($borrowing->catatan)
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Catatan Pengembalian</dt>
                    <dd class="font-medium text-gray-800">{{ $borrowing->catatan }}</dd>
                </div>
            @endif
        </dl>

        {{-- Admin dapat memproses booking sesuai statusnya. --}}
        @if ($isAdmin && $borrowing->status === 'Menunggu Persetujuan')
            <div class="mt-6 pt-6 border-t border-gray-200 space-y-4">
                <h4 class="font-medium text-gray-800">Proses Persetujuan Booking</h4>
                <p class="text-sm text-gray-500">Slot diajukan: {{ $borrowing->slot_mulai }} - {{ $borrowing->slot_selesai }}</p>

                <form method="POST" action="{{ route('admin.borrowings.approve', $borrowing) }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Mobil yang Digunakan</label>
                        <select name="car_id" required class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm">
                            <option value="">-- Pilih Mobil Tersedia --</option>
                            @foreach (\App\Models\Car::where('status', 'tersedia')->orderBy('nama_mobil')->get() as $car)
                                <option value="{{ $car->id }}">{{ $car->nama_mobil }} - {{ $car->plat_nomor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-button type="submit">Setujui Booking</x-button>
                </form>

                <form method="POST" action="{{ route('admin.borrowings.reject', $borrowing) }}" class="space-y-3 pt-3 border-t border-gray-100">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" rows="2" required placeholder="Contoh: Slot sudah penuh / mobil dalam perbaikan"
                            class="w-full rounded-lg border-gray-300 focus:border-red-400 focus:ring-red-400 text-sm"></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                        Tolak Booking
                    </button>
                </form>
            </div>
        @endif

        @if ($isAdmin && $borrowing->status === 'Disetujui')
            <div class="mt-6 pt-6 border-t border-gray-200">
                <form method="POST" action="{{ route('admin.borrowings.complete', $borrowing) }}">
                    @csrf
                    @method('PUT')
                    <x-button type="submit">Tandai Selesai (Mobil Sudah Kembali)</x-button>
                </form>
            </div>
        @endif

        @if ($borrowing->status === 'Ditolak' && $borrowing->alasan_penolakan)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-red-800 mb-1">Alasan Penolakan:</p>
                    <p class="text-sm text-red-700">{{ $borrowing->alasan_penolakan }}</p>
                </div>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route($routePrefix.'.borrowings.index') }}" class="text-sm text-gray-500 hover:underline">
                &larr; Kembali ke History Peminjaman
            </a>
        </div>
    </div>

</x-layouts.app>
