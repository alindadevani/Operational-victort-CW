<x-layouts.app :title="'Tambah Team'">

    <div class="max-w-lg bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-5">Form Tambah Team</h3>

        <form method="POST" action="{{ route('admin.teams.store') }}" class="space-y-4">
            @csrf

            <x-input label="Nama Team" name="nama_team" required autofocus placeholder="Contoh: Team Budi" />

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">Simpan</x-button>
                <a href="{{ route('admin.teams.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>

</x-layouts.app>
