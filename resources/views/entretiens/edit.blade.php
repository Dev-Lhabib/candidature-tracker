<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Modifier l'entretien</h1>
            <p class="page-subtitle">{{ $entretien->candidature->entreprise }} — {{ $entretien->candidature->poste }}</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('candidatures.show', $entretien->candidature) }}" class="btn-ghost text-sm mb-6 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour au détail
        </a>

        <div class="card">
            <form method="POST" action="{{ route('entretiens.update', $entretien) }}" novalidate class="card-body space-y-6">
                @csrf @method('PUT')

                <x-validation-summary />

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Candidature</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $entretien->candidature->entreprise }} — {{ $entretien->candidature->poste }}</p>
                </div>

                @include('entretiens.partials.form-fields', ['entretien' => $entretien])

                <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                    <x-primary-button>Enregistrer</x-primary-button>
                    <a href="{{ route('candidatures.show', $entretien->candidature) }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
