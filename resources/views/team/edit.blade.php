<x-layouts.app :title="'Edit Team'">

    <div class="max-w-lg bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-5">Form Edit Team</h3>

        <form method="POST" action="{{ route('admin.teams.update', $team) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-input label="Nama Team" name="nama_team" :value="$team->nama_team" required autofocus />

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">Simpan Perubahan</x-button>
                <a href="{{ route('admin.teams.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>

</x-layouts.app>
