<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes Candidatures</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex gap-4 mb-6">
            <a href="{{ route('candidatures.create') }}"
               class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Nouvelle candidature
            </a>
            <a href="{{ route('candidatures.archives') }}"
               class="px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                Archives
            </a>
        </div>

        <form method="GET" action="{{ route('candidatures.index') }}" class="bg-white p-4 rounded shadow mb-6 flex gap-4 items-end">
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" id="statut" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    @foreach($statuts as $key => $label)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                <select name="priorite" id="priorite" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Toutes</option>
                    @foreach($priorites as $key => $label)
                        <option value="{{ $key }}" {{ request('priorite') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Filtrer</button>
                <a href="{{ route('candidatures.index') }}" class="ml-2 text-sm text-gray-600 underline">Réinitialiser</a>
            </div>
        </form>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @forelse($candidatures as $c)
                    <div class="border-b border-gray-200 py-4 flex justify-between items-start gap-4">
                        <div>
                            <h3 class="font-bold text-lg">{{ $c->entreprise }}</h3>
                            <p class="text-gray-600">{{ $c->poste }}</p>
                            <div class="flex gap-2 mt-1">
                                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">{{ $statuts[$c->statut] }}</span>
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    @if($c->priorite === 'haute') bg-red-100 text-red-800
                                    @elseif($c->priorite === 'moyenne') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $priorites[$c->priorite] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Candidaturé le {{ $c->date_candidature->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex gap-2 items-start">
                            <a href="{{ route('candidatures.show', $c) }}" class="text-indigo-600 hover:underline text-sm">Voir</a>
                            <a href="{{ route('candidatures.edit', $c) }}" class="text-gray-600 hover:underline text-sm">Modifier</a>
                            <form method="POST" action="{{ route('candidatures.destroy', $c) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Archiver cette candidature ?')" class="text-red-600 hover:underline text-sm">Archiver</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Aucune candidature pour le moment. <a href="{{ route('candidatures.create') }}" class="text-indigo-600 underline">Créer la première</a></p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>