<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Mes candidatures</h1>
            <p class="page-subtitle">Suivez vos offres, filtres et priorités</p>
        </div>
    </x-slot>

    <div x-data="{
        showArchive: false,
        archiveEntrep: '',
        archivePoste: '',
        archiveUrl: '',
        openArchive(entrep, poste, url) {
            this.showArchive = true;
            this.archiveEntrep = entrep;
            this.archivePoste = poste;
            this.archiveUrl = url;
        },
        confirmArchive() {
            fetch(this.archiveUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => window.location.reload());
        }
    }" class="max-w-6xl mx-auto space-y-6">

        {{-- Archive modal --}}
        <div x-show="showArchive" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-on:click="showArchive = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative card w-full max-w-md overflow-hidden" @click.stop>
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        Archiver la candidature
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <p class="text-slate-600 mb-4">Cette candidature sera déplacée vers les archives.</p>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 mb-6">
                        <p class="font-semibold text-slate-900" x-text="archiveEntrep"></p>
                        <p class="text-sm text-slate-500" x-text="archivePoste"></p>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" x-on:click="showArchive = false" class="btn-secondary">Annuler</button>
                        <button type="button" x-on:click="confirmArchive()" class="btn-success">Archiver</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('candidatures.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle candidature
            </a>
        </div>

        <form method="GET" action="{{ route('candidatures.index') }}" class="card card-body">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach($statuts as $key => $label)
                            <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priorite" class="form-label">Priorité</label>
                    <select name="priorite" id="priorite" class="form-select">
                        <option value="">Toutes les priorités</option>
                        @foreach($priorites as $key => $label)
                            <option value="{{ $key }}" {{ request('priorite') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">Filtrer</button>
                    <a href="{{ route('candidatures.index') }}" class="btn-ghost">Réinitialiser</a>
                </div>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-2">
            @forelse($candidatures as $c)
                <article class="card group hover:shadow-card-hover transition-shadow duration-300">
                    <div class="card-body">
                        <div class="flex justify-between items-start gap-3">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-slate-900 truncate group-hover:text-brand-700 transition-colors">
                                    <a href="{{ route('candidatures.show', $c) }}">{{ $c->entreprise }}</a>
                                </h3>
                                <p class="text-slate-600 font-medium">{{ $c->poste }}</p>
                            </div>
                            @if($c->entretiens->count() > 0)
                                <span class="shrink-0 badge bg-brand-50 text-brand-700 ring-brand-200/60">
                                    {{ $c->entretiens->count() }} entretien{{ $c->entretiens->count() > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2 mt-4">
                            <x-status-badge :variant="$c->statut" :label="$statuts[$c->statut]" />
                            <x-status-badge :variant="$c->priorite" :label="$priorites[$c->priorite]" />
                        </div>

                        <p class="mt-3 text-xs text-slate-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Candidature du {{ $c->date_candidature->format('d/m/Y') }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-slate-100">
                            <a href="{{ route('candidatures.show', $c) }}" class="btn-primary text-xs py-2">Voir le détail</a>
                            <a href="{{ route('candidatures.edit', $c) }}" class="btn-secondary text-xs py-2">Modifier</a>
                            <button type="button" x-on:click="openArchive(@js($c->entreprise), @js($c->poste), @js(route('candidatures.destroy', $c)))" class="btn-ghost text-xs py-2 text-red-600 hover:text-red-700 hover:bg-red-50">Archiver</button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="card col-span-full">
                    <div class="card-body text-center py-16">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-slate-600 font-medium">Aucune candidature pour le moment</p>
                        <p class="text-sm text-slate-500 mt-1 mb-6">Commencez par ajouter votre première opportunité</p>
                        <a href="{{ route('candidatures.create') }}" class="btn-primary">Créer une candidature</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
