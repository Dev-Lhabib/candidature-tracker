@props([
    'label' => 'Pièces jointes (PDF, DOC, DOCX — max 5 Mo chacun)',
    'maxFiles' => 10,
    'hint' => 'Ajoutez plusieurs fichiers (bouton « Choisir des fichiers » ou Ctrl+clic), puis enregistrez une seule fois.',
])

<div data-fichiers-picker data-max-files="{{ $maxFiles }}" class="space-y-2">
    <x-input-label for="fichiers" :value="$label" />
    <input
        data-fichiers-input
        id="fichiers"
        name="fichiers[]"
        type="file"
        multiple
        accept=".pdf,.doc,.docx"
        {{ $attributes->merge(['class' => 'block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100']) }}
    />
    <p class="text-xs text-slate-500">{{ $hint }}</p>
    <ul data-fichiers-list class="hidden space-y-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm"></ul>
    <x-input-error :messages="$errors->get('fichiers')" class="mt-2" />
    <x-input-error :messages="$errors->get('fichiers.*')" class="mt-2" />
</div>
