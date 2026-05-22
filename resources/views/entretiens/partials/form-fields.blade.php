@props(['entretien' => null])

<div class="space-y-5">
    <div>
        <x-input-label for="type" value="Type *" />
        <select id="type" name="type" class="form-select">
            @foreach(App\Models\Entretien::types() as $key => $label)
                <option value="{{ $key }}" {{ old('type', $entretien?->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="date_heure" value="Date et heure *" />
        <x-text-input
            id="date_heure"
            name="date_heure"
            type="datetime-local"
            :value="old('date_heure', $entretien?->date_heure?->format('Y-m-d\TH:i'))"
        />
        <x-input-error :messages="$errors->get('date_heure')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="resultat" value="Résultat *" />
        <select id="resultat" name="resultat" class="form-select">
            @foreach(App\Models\Entretien::resultats() as $key => $label)
                <option value="{{ $key }}" {{ old('resultat', $entretien?->resultat ?? 'en_attente') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('resultat')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="notes_preparation" value="Notes de préparation" />
        <textarea id="notes_preparation" name="notes_preparation" rows="4" class="form-textarea">{{ old('notes_preparation', $entretien?->notes_preparation) }}</textarea>
        <x-input-error :messages="$errors->get('notes_preparation')" class="mt-2" />
    </div>
</div>
