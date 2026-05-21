<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes Candidatures</h2>
    </x-slot>

    <div x-data="{
        showArchive: false,
        archiveEntrep: '',
        archivePoste: '',
        archiveUrl: '',
        openArchive(entrep, poste, url) {
            this.showArchive = true;
            this.archiveEntrep = entrep;
            this.archivePoste = poste;
            this.archiveUrl = url;
        },
        confirmArchive() {
            fetch(this.archiveUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => window.location.reload());
        }
    }" class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div
            x-show="showArchive"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50"
            style="display: none;"
        >
            <div x-on:click="showArchive = false" class="absolute inset-0 bg-gray-500/75"></div>
            <div class="relative flex items-center justify-center min-h-screen">
                <div
                    x-show="showArchive"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="bg-white rounded-xl overflow-hidden shadow-2xl sm:w-full sm:max-w-md mx-4"
                >
                    <div class="bg-amber-500 px-6 py-4 flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <h3 class="text-lg font-bold text-white">Archiver la candidature</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir archiver cette candidature ?</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                            <p class="font-semibold text-gray-900" x-text="archiveEntrep"></p>
                            <p class="text-sm text-gray-500" x-text="archivePoste"></p>
                        </div>
                        <div class="flex gap-3 justify-end">
                            <button type="button" x-on:click="showArchive = false" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-sm">Annuler</button>
                            <button type="button" x-on:click="confirmArchive()" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm">Archiver</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mb-6">
            <a href="{{ route('candidatures.create') }}"
               class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Nouvelle candidature
            </a>
            <a href="{{ route('candidatures.archives') }}"
               class="px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                Archives
            </a>
        </div>

        <form method="GET" action="{{ route('candidatures.index') }}" class="bg-white p-4 rounded shadow mb-6 flex gap-4 items-end">
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" id="statut" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    @foreach($statuts as $key => $label)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                <select name="priorite" id="priorite" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Toutes</option>
                    @foreach($priorites as $key => $label)
                        <option value="{{ $key }}" {{ request('priorite') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Filtrer</button>
                <a href="{{ route('candidatures.index') }}" class="ml-2 text-sm text-gray-600 underline">Réinitialiser</a>
            </div>
        </form>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @forelse($candidatures as $c)
                    <div class="border-b border-gray-200 py-4 flex justify-between items-start gap-4">
                        <div>
                            <h3 class="font-bold text-lg">{{ $c->entreprise }}</h3>
                            <p class="text-gray-600">{{ $c->poste }}</p>
                            <div class="flex gap-2 mt-1">
                                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">{{ $statuts[$c->statut] }}</span>
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    @if($c->priorite === 'haute') bg-red-100 text-red-800
                                    @elseif($c->priorite === 'moyenne') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $priorites[$c->priorite] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Candidaturé le {{ $c->date_candidature->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex gap-2 items-start">
                            <a href="{{ route('candidatures.show', $c) }}" class="text-indigo-600 hover:underline text-sm">Voir</a>
                            <a href="{{ route('candidatures.edit', $c) }}" class="text-gray-600 hover:underline text-sm">Modifier</a>
                            <button type="button" x-on:click="openArchive('{{ addslashes($c->entreprise) }}', '{{ addslashes($c->poste) }}', '{{ route('candidatures.destroy', $c) }}')" class="text-red-600 hover:underline text-sm">Archiver</button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Aucune candidature pour le moment. <a href="{{ route('candidatures.create') }}" class="text-indigo-600 underline">Créer la première</a></p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>