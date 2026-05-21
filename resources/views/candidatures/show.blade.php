<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $candidature->entreprise }} — {{ $candidature->poste }}</h2>
    </x-slot>

    <div x-data="{
        showArchive: false,
        showDelete: false,
        archiveUrl: '',
        deleteUrl: '',
        entretienLabel: '',
        openArchive(url) {
            this.showArchive = true;
            this.archiveUrl = url;
        },
        openDelete(url, label) {
            this.showDelete = true;
            this.deleteUrl = url;
            this.entretienLabel = label;
        },
        confirmArchive() {
            fetch(this.archiveUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => window.location.reload());
        },
        confirmDelete() {
            fetch(this.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => window.location.reload());
        }
    }" class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <div
            x-show="showArchive || showDelete"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50"
            style="display: none;"
        >
            <div x-on:click="showArchive = false; showDelete = false" class="absolute inset-0 bg-gray-500/75"></div>
            <div class="relative flex items-center justify-center min-h-screen">
                <template x-if="showArchive">
                    <div
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
                                <p class="font-semibold text-gray-900">{{ $candidature->entreprise }}</p>
                                <p class="text-sm text-gray-500">{{ $candidature->poste }}</p>
                            </div>
                            <div class="flex gap-3 justify-end">
                                <button type="button" x-on:click="showArchive = false" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-sm">Annuler</button>
                                <button type="button" x-on:click="confirmArchive()" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm">Archiver</button>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="showDelete">
                    <div
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="bg-white rounded-xl overflow-hidden shadow-2xl sm:w-full sm:max-w-md mx-4"
                    >
                        <div class="bg-red-600 px-6 py-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <h3 class="text-lg font-bold text-white">Supprimer l'entretien</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer cet entretien ?</p>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                                <p class="font-semibold text-gray-900" x-text="entretienLabel"></p>
                            </div>
                            <div class="flex gap-3 justify-end">
                                <button type="button" x-on:click="showDelete = false" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-sm">Annuler</button>
                                <button type="button" x-on:click="confirmDelete()" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 text-sm">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold">{{ $candidature->entreprise }}</h3>
                    <p class="text-xl text-gray-600">{{ $candidature->poste }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('candidatures.edit', $candidature) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Modifier</a>
                    <button type="button" x-on:click="openArchive('{{ route('candidatures.destroy', $candidature) }}')" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">Archiver</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <p class="mt-1 px-2 inline-block text-sm rounded-full bg-blue-100 text-blue-800">{{ App\Models\Candidature::statuts()[$candidature->statut] }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Priorité</p>
                    <p class="mt-1 px-2 inline-block text-sm rounded-full
                        @if($candidature->priorite === 'haute') bg-red-100 text-red-800
                        @elseif($candidature->priorite === 'moyenne') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ App\Models\Candidature::priorites()[$candidature->priorite] }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de candidature</p>
                    <p class="mt-1 text-gray-900">{{ $candidature->date_candidature->format('d/m/Y') }}</p>
                </div>
                @if($candidature->url_offre)
                <div>
                    <p class="text-sm font-medium text-gray-500">URL de l'offre</p>
                    <a href="{{ $candidature->url_offre }}" target="_blank" class="mt-1 text-indigo-600 underline text-sm">{{ Str::limit($candidature->url_offre, 40) }}</a>
                </div>
                @endif
            </div>

            @if($candidature->fichier_path)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Fichier joint</p>
                <a href="{{ route('candidatures.download', $candidature) }}" class="mt-1 inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 text-sm rounded hover:bg-indigo-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Télécharger
                </a>
            </div>
            @endif

            @if($candidature->notes)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Notes</p>
                <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $candidature->notes }}</p>
            </div>
            @endif
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Entretiens</h3>

            @forelse($candidature->entretiens as $e)
                <div class="border-b border-gray-200 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ App\Models\Entretien::types()[$e->type] }}</p>
                            <p class="text-sm text-gray-600">{{ $e->date_heure->format('d/m/Y à H:i') }}</p>
                            <p class="text-sm mt-1">
                                <span class="font-medium text-gray-500">Résultat :</span>
                                <span class="px-2 inline-block text-xs rounded-full
                                    @if($e->resultat === 'positif') bg-green-100 text-green-800
                                    @elseif($e->resultat === 'negatif') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ App\Models\Entretien::resultats()[$e->resultat] }}
                                </span>
                            </p>
                            @if($e->notes_preparation)
                            <p class="text-sm text-gray-600 mt-2">{{ $e->notes_preparation }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('entretiens.edit', $e) }}" class="text-sm text-gray-600 hover:underline">Modifier</a>
                            <button type="button" x-on:click="openDelete('{{ route('entretiens.destroy', $e) }}', '{{ App\Models\Entretien::types()[$e->type] . ' - ' . $e->date_heure->format('d/m/Y à H:i') }}')" class="text-red-600 hover:underline text-sm">Supprimer</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Aucun entretien enregistré.</p>
            @endforelse

            <h4 class="text-md font-semibold mt-6 mb-3">Ajouter un entretien</h4>
            <form method="POST" action="{{ route('entretiens.store', $candidature) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="type" value="Type *" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @foreach(App\Models\Entretien::types() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_heure" value="Date et heure *" />
                        <x-text-input id="date_heure" name="date_heure" type="datetime-local" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('date_heure')" class="mt-2" />
                    </div>
                </div>
                <div>
                    <x-input-label for="resultat" value="Résultat *" />
                    <select id="resultat" name="resultat" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @foreach(App\Models\Entretien::resultats() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('resultat')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="notes_preparation" value="Notes de préparation" />
                    <textarea id="notes_preparation" name="notes_preparation" rows="3" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                    <x-input-error :messages="$errors->get('notes_preparation')" class="mt-2" />
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Ajouter l'entretien</button>
            </form>
        </div>
    </div>
</x-app-layout>