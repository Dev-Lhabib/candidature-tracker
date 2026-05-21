<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Nouvelle candidature</h1>
            <p class="page-subtitle">Ajoutez une offre à votre suivi</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <form method="POST" action="{{ route('candidatures.store') }}" enctype="multipart/form-data" class="card-body space-y-6">
                @csrf

                <div class="space-y-5">
                    <div>
                        <x-input-label for="entreprise" value="Entreprise *" />
                        <x-text-input id="entreprise" name="entreprise" type="text" value="{{ old('entreprise') }}" required />
                        <x-input-error :messages="$errors->get('entreprise')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="poste" value="Poste *" />
                        <x-text-input id="poste" name="poste" type="text" value="{{ old('poste') }}" required />
                        <x-input-error :messages="$errors->get('poste')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="url_offre" value="URL de l'offre" />
                        <x-text-input id="url_offre" name="url_offre" type="url" value="{{ old('url_offre') }}" placeholder="https://" />
                        <x-input-error :messages="$errors->get('url_offre')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="statut" value="Statut *" />
                            <select id="statut" name="statut" class="form-select mt-0" required>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ old('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="priorite" value="Priorité *" />
                            <select id="priorite" name="priorite" class="form-select mt-0" required>
                                @foreach($priorites as $key => $label)
                                    <option value="{{ $key }}" {{ old('priorite') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priorite')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="date_candidature" value="Date de candidature *" />
                        <x-text-input id="date_candidature" name="date_candidature" type="date" value="{{ old('date_candidature') }}" required />
                        <x-input-error :messages="$errors->get('date_candidature')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="form-textarea">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="fichier" value="Pièce jointe (PDF, DOC, DOCX — max 5 Mo)" />
                        <input id="fichier" name="fichier" type="file" accept=".pdf,.doc,.docx"
                            class="mt-1 block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                        <x-input-error :messages="$errors->get('fichier')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                    <x-primary-button>Créer la candidature</x-primary-button>
                    <a href="{{ route('candidatures.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
