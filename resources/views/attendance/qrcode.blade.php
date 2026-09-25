<x-layouts.app :title="'QR Code Absensi'">

    <div class="max-w-md mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
        <h3 class="font-semibold text-gray-800 mb-2">QR Code Absensi Kantor</h3>
        <p class="text-sm text-gray-500 mb-6">
            Cetak dan tempelkan QR Code ini di area absensi kantor.
            Karyawan tinggal scan menggunakan kamera HP untuk mengisi absensi.
        </p>

        <div class="inline-block p-4 bg-white border-2 border-primary-light rounded-xl">
            {!! $qrCode !!}
        </div>

        <p class="mt-4 text-xs text-gray-400 break-all">{{ $url }}</p>

        <button onclick="window.print()" class="mt-6 inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition">
            Cetak QR Code
        </button>
    </div>

</x-layouts.app>
