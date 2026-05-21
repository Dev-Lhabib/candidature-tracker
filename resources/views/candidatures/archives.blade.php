<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Archives</h2>
    </x-slot>

    {{-- Wrap everything in ONE Alpine root --}}
    <div x-data>
        <x-confirm-restore-modal />
        <x-confirm-delete-modal />

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('candidatures.index') }}" class="px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">← Retour aux candidatures</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse($archives as $a)
                        <div class="border-b border-gray-200 py-4 flex justify-between items-start gap-4">
                            <div>
                                <h3 class="font-bold text-lg">{{ $a->entreprise }}</h3>
                                <p class="text-gray-600">{{ $a->poste }}</p>
                                <p class="text-sm text-gray-500 mt-1">Archivée le {{ $a->deleted_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex gap-2 items-start">
                                <button
                                    type="button"
                                    x-on:click='$dispatch("open-restore-modal", {
                                        entreprise: @json($a->entreprise),
                                        poste: @json($a->poste),
                                        action: @json(route("candidatures.restore", $a->id))
                                    })'
                                    class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                                    Restaurer
                                </button>

                                <button
                                    type="button"
                                    x-on:click='$dispatch("open-delete-modal", {
                                        entreprise: @json($a->entreprise),
                                        poste: @json($a->poste),
                                        action: @json(route("candidatures.forceDelete", $a->id))
                                    })'
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Aucune candidature archivée.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>