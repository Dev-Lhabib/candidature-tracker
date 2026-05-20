<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $candidature->entreprise }} — {{ $candidature->poste }}</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold">{{ $candidature->entreprise }}</h3>
                    <p class="text-xl text-gray-600">{{ $candidature->poste }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('candidatures.edit', $candidature) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Modifier</a>
                    <form method="POST" action="{{ route('candidatures.destroy', $candidature) }}">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Archiver cette candidature ?')" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">Archiver</button>
                    </form>
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
                            <form method="POST" action="{{ route('entretiens.destroy', $e) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Supprimer cet entretien ?')" class="text-red-600 hover:underline text-sm">Supprimer</button>
                            </form>
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