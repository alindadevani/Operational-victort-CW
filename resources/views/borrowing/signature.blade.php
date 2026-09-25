<x-layouts.app :title="'Isi Tanda Tangan'">

    <div class="max-w-xl bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-1">Konfirmasi Selesai Peminjaman</h3>
        <p class="text-sm text-gray-500 mb-5">
            Mobil <strong>{{ $borrowing->car?->nama_mobil ?? 'Belum ditentukan' }}</strong> telah selesai digunakan.
            Lengkapi tanda tangan di bawah ini untuk menyelesaikan peminjaman.
        </p>

        <div class="bg-primary-light rounded-lg p-4 mb-5 text-sm text-primary-dark">
            <p><strong>Lokasi:</strong> {{ $borrowing->lokasi }}</p>
            <p><strong>Jadwal:</strong> {{ $borrowing->tanggal->format('d-m-Y') }}, {{ $borrowing->slot_mulai }} - {{ $borrowing->slot_selesai }}</p>
        </div>

        <form method="POST" action="{{ route('marketing.borrowings.signature.store', $borrowing) }}" class="space-y-5">
            @csrf

            <x-input label="Nama Sopir" name="nama_sopir" required autofocus placeholder="Nama sopir yang mengantar" />

            <x-signature-pad name="tanda_tangan_sopir" label="Tanda Tangan Sopir" />

            <x-signature-pad name="tanda_tangan_peminjam" label="Tanda Tangan Peminjam" />

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">Selesaikan Peminjaman</x-button>
                <a href="{{ route('marketing.borrowings.history') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>

</x-layouts.app>