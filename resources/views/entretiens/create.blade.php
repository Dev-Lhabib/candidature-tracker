<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Nouvel entretien</h1>
            <p class="page-subtitle">Planifier un entretien pour une candidature</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('candidatures.index') }}" class="btn-ghost text-sm mb-6 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour
        </a>

        @if($candidatures->isEmpty())
            <div class="card">
                <div class="card-body text-center py-10">
                    <p class="text-slate-600 mb-6">Vous devez d'abord créer une candidature avant d'ajouter un entretien.</p>
                    <a href="{{ route('candidatures.create') }}" class="btn-primary">Créer une candidature</a>
                </div>
            </div>
        @else
            <div class="card">
                <form method="POST" action="{{ route('entretiens.store') }}" novalidate class="card-body space-y-6">
                    @csrf

                    <x-validation-summary />

                    <div>
                        <x-input-label for="candidature_id" value="Candidature *" />
                        <select id="candidature_id" name="candidature_id" class="form-select">
                            <option value="" disabled {{ $selectedCandidatureId ? '' : 'selected' }}>— Choisir une candidature —</option>
                            @foreach($candidatures as $c)
                                <option value="{{ $c->id }}" {{ (int) old('candidature_id', $selectedCandidatureId) === $c->id ? 'selected' : '' }}>
                                    {{ $c->entreprise }} — {{ $c->poste }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('candidature_id')" class="mt-2" />
                    </div>

                    @include('entretiens.partials.form-fields')

                    <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                        <x-primary-button>Créer l'entretien</x-primary-button>
                        <a href="{{ route('candidatures.index') }}" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
