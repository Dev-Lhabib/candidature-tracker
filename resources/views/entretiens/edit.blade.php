<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Modifier l'entretien</h1>
            <p class="page-subtitle">{{ $entretien->candidature->entreprise }}</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('candidatures.show', $entretien->candidature) }}" class="btn-ghost text-sm mb-6 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour au détail
        </a>

        <div class="card">
            <form method="POST" action="{{ route('entretiens.update', $entretien) }}" class="card-body space-y-6">
                @csrf @method('PUT')

                <div class="space-y-5">
                    <div>
                        <x-input-label for="type" value="Type *" />
                        <select id="type" name="type" class="form-select" required>
                            @foreach(App\Models\Entretien::types() as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $entretien->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_heure" value="Date et heure *" />
                        <x-text-input id="date_heure" name="date_heure" type="datetime-local" value="{{ old('date_heure', $entretien->date_heure->format('Y-m-d\TH:i')) }}" required />
                        <x-input-error :messages="$errors->get('date_heure')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="resultat" value="Résultat *" />
                        <select id="resultat" name="resultat" class="form-select" required>
                            @foreach(App\Models\Entretien::resultats() as $key => $label)
                                <option value="{{ $key }}" {{ old('resultat', $entretien->resultat) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('resultat')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="notes_preparation" value="Notes de préparation" />
                        <textarea id="notes_preparation" name="notes_preparation" rows="4" class="form-textarea">{{ old('notes_preparation', $entretien->notes_preparation) }}</textarea>
                        <x-input-error :messages="$errors->get('notes_preparation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                    <x-primary-button>Enregistrer</x-primary-button>
                    <a href="{{ route('candidatures.show', $entretien->candidature) }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
