<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier Entretien</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('entretiens.update', $entretien) }}">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="type" value="Type *" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @foreach(App\Models\Entretien::types() as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $entretien->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_heure" value="Date et heure *" />
                        <x-text-input id="date_heure" name="date_heure" type="datetime-local" value="{{ old('date_heure', $entretien->date_heure->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('date_heure')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="resultat" value="Résultat *" />
                        <select id="resultat" name="resultat" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @foreach(App\Models\Entretien::resultats() as $key => $label)
                                <option value="{{ $key }}" {{ old('resultat', $entretien->resultat) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('resultat')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="notes_preparation" value="Notes de préparation" />
                        <textarea id="notes_preparation" name="notes_preparation" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('notes_preparation', $entretien->notes_preparation) }}</textarea>
                        <x-input-error :messages="$errors->get('notes_preparation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Enregistrer</button>
                    <a href="{{ route('candidatures.show', $entretien->candidature) }}" class="px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>