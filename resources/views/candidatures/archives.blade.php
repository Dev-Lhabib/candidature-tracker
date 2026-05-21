<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Archives</h1>
            <p class="page-subtitle">Candidatures archivées — restauration ou suppression définitive</p>
        </div>
    </x-slot>

    <div x-data class="max-w-4xl mx-auto space-y-6">
        <x-confirm-restore-modal />
        <x-confirm-delete-modal />

        <a href="{{ route('candidatures.index') }}" class="btn-secondary inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour aux candidatures
        </a>

        <div class="space-y-4">
            @forelse($archives as $a)
                <article class="card">
                    <div class="card-body flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ $a->entreprise }}</h3>
                            <p class="text-slate-600">{{ $a->poste }}</p>
                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                Archivée le {{ $a->deleted_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2 shrink-0">
                            <button
                                type="button"
                                x-on:click='$dispatch("open-restore-modal", {
                                    entreprise: @json($a->entreprise),
                                    poste: @json($a->poste),
                                    action: @json(route("candidatures.restore", $a->id))
                                })'
                                class="btn-success text-sm">
                                Restaurer
                            </button>
                            <button
                                type="button"
                                x-on:click='$dispatch("open-delete-modal", {
                                    entreprise: @json($a->entreprise),
                                    poste: @json($a->poste),
                                    action: @json(route("candidatures.forceDelete", $a->id))
                                })'
                                class="btn-danger text-sm">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="card">
                    <div class="card-body text-center py-16">
                        <p class="text-slate-600 font-medium">Aucune candidature archivée</p>
                        <p class="text-sm text-slate-500 mt-1">Les candidatures archivées apparaîtront ici</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
