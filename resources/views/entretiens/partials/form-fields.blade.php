@props(['entretien' => null])

@php
    $typeOptions = App\Models\Entretien::types(auth()->user());
    $selectedType = old('type', $entretien?->type);
    $isNewType = old('type') === '__new__' || ($selectedType && ! array_key_exists($selectedType, $typeOptions));
    $minDateHeure = now()->format('Y-m-d\TH:i');
    $dateHeureValue = old('date_heure');
    if ($dateHeureValue === null) {
        $stored = $entretien?->date_heure;
        $dateHeureValue = ($stored && $stored->gte(now()))
            ? $stored->format('Y-m-d\TH:i')
            : $minDateHeure;
    }
@endphp

<div class="space-y-5">
    <div
        x-data="{
            typeMode: @js($isNewType ? '__new__' : $selectedType),
            showCustom: @js($isNewType)
        }"
        x-init="$watch('typeMode', value => showCustom = value === '__new__')"
    >
        <x-input-label for="type" value="Type *" />
        <select
            id="type"
            name="type"
            class="form-select"
            x-model="typeMode"
        >
            @foreach($typeOptions as $key => $label)
                <option value="{{ $key }}" @selected(!$isNewType && $selectedType === $key)>{{ $label }}</option>
            @endforeach
            <option value="__new__" @selected($isNewType)>+ Ajouter un type…</option>
        </select>
        <div x-show="showCustom" x-cloak class="mt-3">
            <x-input-label for="type_custom" value="Nouveau type *" />
            <x-text-input
                id="type_custom"
                name="type_custom"
                type="text"
                class="mt-1 block w-full"
                :value="old('type_custom')"
                placeholder="Ex. Assessment center"
            />
            <x-input-error :messages="$errors->get('type_custom')" class="mt-2" />
        </div>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="date_heure" value="Date et heure *" />
        <x-text-input
            id="date_heure"
            name="date_heure"
            type="datetime-local"
            :value="$dateHeureValue"
            :min="$minDateHeure"
        />
        <p class="mt-1.5 text-xs text-slate-500">À partir de maintenant uniquement.</p>
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
