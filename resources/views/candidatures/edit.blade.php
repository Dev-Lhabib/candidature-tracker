<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier Candidature</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('candidatures.update', $candidature) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="entreprise" value="Entreprise *" />
                        <x-text-input id="entreprise" name="entreprise" type="text" value="{{ old('entreprise', $candidature->entreprise) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('entreprise')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="poste" value="Poste *" />
                        <x-text-input id="poste" name="poste" type="text" value="{{ old('poste', $candidature->poste) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('poste')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="url_offre" value="URL de l'offre" />
                        <x-text-input id="url_offre" name="url_offre" type="url" value="{{ old('url_offre', $candidature->url_offre) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('url_offre')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="statut" value="Statut *" />
                            <select id="statut" name="statut" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ old('statut', $candidature->statut) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="priorite" value="Priorité *" />
                            <select id="priorite" name="priorite" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                @foreach($priorites as $key => $label)
                                    <option value="{{ $key }}" {{ old('priorite', $candidature->priorite) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priorite')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="date_candidature" value="Date de candidature *" />
                        <x-text-input id="date_candidature" name="date_candidature" type="date" value="{{ old('date_candidature', $candidature->date_candidature->format('Y-m-d')) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('date_candidature')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('notes', $candidature->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="fichier" value="Fichier (PDF, DOC, DOCX — max 5 Mo)" />
                        @if($candidature->fichier_path)
                            <p class="text-sm text-gray-500 mb-1">Fichier actuel : <a href="{{ route('candidatures.download', $candidature) }}" class="text-indigo-600 underline">Télécharger</a></p>
                        @endif
                        <input id="fichier" name="fichier" type="file" accept=".pdf,.doc,.docx" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <x-input-error :messages="$errors->get('fichier')" class="mt-2" />
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Enregistrer</button>
                    <a href="{{ route('candidatures.show', $candidature) }}" class="px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>