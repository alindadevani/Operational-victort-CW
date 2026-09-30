<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Karyawan - VIF Operational System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: { DEFAULT: '#16A34A', dark: '#15803D', light: '#DCFCE7' } },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-victory.png') }}" alt="PT Victory International Futures" class="h-14 w-auto object-contain mx-auto mb-3">
            <h1 class="text-xl font-bold text-gray-800">Absensi Karyawan</h1>
            <p class="text-sm text-gray-500">PT Victory International Futures - Cabang Ciputra World</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">

            @if (session('success'))
                <div class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-4 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('attendance.store') }}" class="space-y-4" id="formAbsensi">
                @csrf

                <x-input label="Nama Lengkap" name="nama" required autofocus placeholder="Masukkan nama lengkap Anda" />

                <div>
                    <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Team <span class="text-red-500">*</span>
                    </label>
                    <select name="team_id" id="team_id" required
                        class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary text-sm {{ $errors->has('team_id') ? 'border-red-400' : '' }}">
                        <option value="">-- Pilih Team --</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->nama_team }}
                            </option>
                        @endforeach
                    </select>
                    @error('team_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="text" value="{{ now()->translatedFormat('d M Y') }}" disabled
                            class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                        <input type="text" value="{{ now()->format('H:i') }}" disabled
                            class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 text-sm">
                    </div>
                </div>

                {{-- ============ BAGIAN KAMERA ============ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Foto Bukti Kehadiran <span class="text-red-500">*</span>
                    </label>

                    <div id="areaKamera" class="rounded-lg overflow-hidden bg-black relative" style="aspect-ratio: 4/3;">
                        <video id="videoKamera" autoplay playsinline class="w-full h-full object-cover"></video>

                        <button type="button" id="btnSwitchKamera" title="Ganti kamera"
                            class="absolute top-2 right-2 bg-white/90 hover:bg-white text-gray-700 rounded-full p-2 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>

                    <div id="areaPreview" class="rounded-lg overflow-hidden bg-black relative hidden" style="aspect-ratio: 4/3;">
                        <img id="previewFoto" class="w-full h-full object-cover" alt="Preview foto absensi">
                    </div>

                    <div id="errorKamera" class="hidden mt-2 text-xs text-red-500 bg-red-50 border border-red-200 rounded-lg p-3"></div>

                    <div id="controlsKamera" class="flex gap-2 mt-3">
                        <button type="button" id="btnAmbilFoto"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Ambil Foto
                        </button>
                        <button type="button" id="btnUlangFoto"
                            class="hidden flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition">
                            Ambil Ulang
                        </button>
                    </div>

                    <div id="fallbackFoto" class="hidden mt-3">
                        <label for="fotoPerangkat" class="block text-sm font-medium text-gray-700 mb-1">Ambil foto dari kamera HP atau pilih dari galeri</label>
                        <input type="file" id="fotoPerangkat" accept="image/*" capture="environment"
                            class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium">
                    </div>

                    <input type="hidden" name="foto" id="foto_input" value="{{ old('foto') }}">
                    <canvas id="canvasTersembunyi" class="hidden"></canvas>

                    @error('foto')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="btnSubmit" disabled
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-300 text-white text-sm font-medium rounded-lg cursor-not-allowed transition">
                    Absen Sekarang
                </button>
                <p id="pesanFotoWajib" class="text-xs text-center text-gray-400">Ambil foto terlebih dahulu untuk mengaktifkan tombol absen.</p>
            </form>
        </div>
    </div>

    <script>
        const video = document.getElementById('videoKamera');
        const areaKamera = document.getElementById('areaKamera');
        const areaPreview = document.getElementById('areaPreview');
        const previewFoto = document.getElementById('previewFoto');
        const errorKamera = document.getElementById('errorKamera');
        const btnAmbilFoto = document.getElementById('btnAmbilFoto');
        const btnUlangFoto = document.getElementById('btnUlangFoto');
        const btnSwitchKamera = document.getElementById('btnSwitchKamera');
        const controlsKamera = document.getElementById('controlsKamera');
        const fallbackFoto = document.getElementById('fallbackFoto');
        const fotoPerangkat = document.getElementById('fotoPerangkat');
        const fotoInput = document.getElementById('foto_input');
        const canvas = document.getElementById('canvasTersembunyi');
        const btnSubmit = document.getElementById('btnSubmit');
        const pesanFotoWajib = document.getElementById('pesanFotoWajib');

        let modeKameraSaatIni = 'environment';
        let streamAktif = null;
        let fallbackKameraAktif = false;

        function tampilkanErrorKamera(pesan) {
            errorKamera.textContent = pesan;
            errorKamera.classList.remove('hidden');
            btnAmbilFoto.disabled = true;
            btnAmbilFoto.classList.add('opacity-50', 'cursor-not-allowed');
        }

        function gunakanFallbackFoto(pesan) {
            fallbackKameraAktif = true;
            tampilkanErrorKamera(pesan);
            areaKamera.classList.add('hidden');
            controlsKamera.classList.add('hidden');
            fallbackFoto.classList.remove('hidden');
        }

        function simpanFoto(dataUrl) {
            fotoInput.value = dataUrl;
            previewFoto.src = dataUrl;
            areaKamera.classList.add('hidden');
            areaPreview.classList.remove('hidden');
            fallbackFoto.classList.add('hidden');
            controlsKamera.classList.remove('hidden');
            btnAmbilFoto.classList.add('hidden');
            btnUlangFoto.classList.remove('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('bg-gray-300', 'cursor-not-allowed');
            btnSubmit.classList.add('bg-primary', 'hover:bg-primary-dark');
            pesanFotoWajib.classList.add('hidden');

            if (streamAktif) {
                streamAktif.getTracks().forEach(track => track.stop());
                streamAktif = null;
            }
        }

        function prosesFotoPerangkat(file) {
            if (!file.type.startsWith('image/')) {
                gunakanFallbackFoto('File yang dipilih bukan gambar. Pilih foto dari kamera atau galeri.');
                return;
            }

            const image = new Image();
            const imageUrl = URL.createObjectURL(file);

            image.onload = function () {
                const batasPanjang = 1280;
                const skala = Math.min(1, batasPanjang / Math.max(image.width, image.height));
                canvas.width = Math.round(image.width * skala);
                canvas.height = Math.round(image.height * skala);
                canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
                URL.revokeObjectURL(imageUrl);
                simpanFoto(canvas.toDataURL('image/jpeg', 0.8));
            };

            image.onerror = function () {
                URL.revokeObjectURL(imageUrl);
                gunakanFallbackFoto('Foto tidak dapat dibuka. Coba pilih gambar JPG atau PNG.');
            };

            image.src = imageUrl;
        }

        async function nyalakanKamera(mode) {
            if (!window.isSecureContext) {
                gunakanFallbackFoto('Kamera langsung memerlukan HTTPS. Ambil foto melalui kamera HP atau pilih dari galeri di bawah.');
                return;
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                gunakanFallbackFoto('Kamera langsung tidak tersedia di browser ini. Ambil foto melalui kamera HP atau pilih dari galeri.');
                return;
            }

            if (streamAktif) {
                streamAktif.getTracks().forEach(track => track.stop());
            }

            try {
                errorKamera.classList.add('hidden');
                btnAmbilFoto.disabled = false;
                btnAmbilFoto.classList.remove('opacity-50', 'cursor-not-allowed');
                streamAktif = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: mode } },
                    audio: false
                });
                video.srcObject = streamAktif;
                modeKameraSaatIni = mode;
            } catch (error) {
                let pesan = 'Tidak bisa mengakses kamera. Pastikan izin kamera sudah diberikan.';

                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    pesan = 'Akses kamera ditolak. Izinkan kamera melalui pengaturan browser, lalu muat ulang halaman ini.';
                } else if (error.name === 'NotFoundError') {
                    pesan = 'Kamera tidak ditemukan pada perangkat ini.';
                }

                gunakanFallbackFoto(pesan + ' Anda juga bisa memilih foto dari kamera atau galeri.');
            }
        }

        // Kamera belakang menjadi kamera pertama saat halaman dibuka.
        nyalakanKamera('environment');

        btnSwitchKamera.addEventListener('click', function () {
            nyalakanKamera(modeKameraSaatIni === 'environment' ? 'user' : 'environment');
        });

        btnAmbilFoto.addEventListener('click', function () {
            if (!video.videoWidth || !video.videoHeight) {
                tampilkanErrorKamera('Kamera belum siap. Tunggu sebentar lalu coba ambil foto lagi.');
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            simpanFoto(canvas.toDataURL('image/jpeg', 0.85));
        });

        fotoPerangkat.addEventListener('change', function () {
            const file = fotoPerangkat.files[0];
            if (file) prosesFotoPerangkat(file);
        });

        btnUlangFoto.addEventListener('click', function () {
            fotoInput.value = '';
            areaPreview.classList.add('hidden');
            fotoPerangkat.value = '';
            btnUlangFoto.classList.add('hidden');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('bg-gray-300', 'cursor-not-allowed');
            btnSubmit.classList.remove('bg-primary', 'hover:bg-primary-dark');
            pesanFotoWajib.classList.remove('hidden');

            if (fallbackKameraAktif) {
                fallbackFoto.classList.remove('hidden');
                controlsKamera.classList.add('hidden');
                return;
            }

            areaKamera.classList.remove('hidden');
            controlsKamera.classList.remove('hidden');
            btnAmbilFoto.classList.remove('hidden');
            nyalakanKamera(modeKameraSaatIni);
        });

        window.addEventListener('beforeunload', function () {
            if (streamAktif) {
                streamAktif.getTracks().forEach(track => track.stop());
            }
        });
    </script>
</body>
</html>