<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Modifier la candidature</h1>
            <p class="page-subtitle">{{ $candidature->entreprise }} — {{ $candidature->poste }}</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <x-confirm-delete-fichier-modal />

        <div class="card">
            <form method="POST" action="{{ route('candidatures.update', $candidature) }}" enctype="multipart/form-data" novalidate class="card-body space-y-6">
                @csrf @method('PUT')

                <x-validation-summary />

                <div class="space-y-5">
                    <div>
                        <x-input-label for="entreprise" value="Entreprise *" />
                        <x-text-input id="entreprise" name="entreprise" type="text" value="{{ old('entreprise', $candidature->entreprise) }}" />
                        <x-input-error :messages="$errors->get('entreprise')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="poste" value="Poste *" />
                        <x-text-input id="poste" name="poste" type="text" value="{{ old('poste', $candidature->poste) }}" />
                        <x-input-error :messages="$errors->get('poste')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="url_offre" value="URL de l'offre" />
                        <x-text-input id="url_offre" name="url_offre" type="url" value="{{ old('url_offre', $candidature->url_offre) }}" />
                        <x-input-error :messages="$errors->get('url_offre')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="statut" value="Statut *" />
                            <select id="statut" name="statut" class="form-select">
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ old('statut', $candidature->statut) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="priorite" value="Priorité *" />
                            <select id="priorite" name="priorite" class="form-select">
                                @foreach($priorites as $key => $label)
                                    <option value="{{ $key }}" {{ old('priorite', $candidature->priorite) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priorite')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="date_candidature" value="Date de candidature *" />
                        <x-text-input id="date_candidature" name="date_candidature" type="date" value="{{ old('date_candidature', $candidature->date_candidature->format('Y-m-d')) }}" />
                        <x-input-error :messages="$errors->get('date_candidature')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="form-textarea">{{ old('notes', $candidature->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    @if($candidature->fichiers->isNotEmpty())
                    <div>
                        <p class="form-label">Fichiers existants</p>
                        <ul class="space-y-2 mb-4">
                            @foreach($candidature->fichiers as $f)
                                <li class="flex items-center justify-between gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                                    <a href="{{ route('candidatures.fichiers.download', [$candidature, $f]) }}" class="text-brand-600 hover:text-brand-700 font-medium truncate">{{ $f->nom_original }}</a>
                                    <button
                                        type="button"
                                        class="text-red-600 hover:text-red-700 text-xs font-semibold"
                                        x-on:click="$dispatch('open-delete-fichier-modal', { url: @js(route('candidatures.fichiers.destroy', [$candidature, $f])), name: @js($f->nom_original) })"
                                    >Supprimer</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <x-fichiers-input
                        label="Ajouter des fichiers (PDF, DOC, DOCX — max 5 Mo chacun)"
                        hint="Les nouveaux fichiers s'ajoutent aux fichiers déjà enregistrés. Choisissez plusieurs fichiers, puis cliquez une seule fois sur Enregistrer."
                    />
                </div>

                <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                    <x-primary-button>Enregistrer</x-primary-button>
                    <a href="{{ route('candidatures.show', $candidature) }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
