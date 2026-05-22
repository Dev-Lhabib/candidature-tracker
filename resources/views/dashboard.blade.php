<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="page-title">Tableau de bord</h1>
                <p class="page-subtitle">Suivez vos candidatures et ne manquez aucun entretien</p>
            </div>
            <a href="{{ route('entretiens.create') }}" class="btn-primary text-sm shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Planifier un entretien
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-8">
        {{-- Alertes --}}
        @if($entretiensAujourdhui->isNotEmpty() || $entretiensSansPreparationCount > 0 || $entretiensResultatEnAttenteCount > 0)
            <div class="space-y-3">
                @if($entretiensAujourdhui->isNotEmpty())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 sm:px-5 sm:flex sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-200/80 text-amber-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </span>
                            <p class="text-sm font-semibold text-amber-950">
                                {{ $entretiensAujourdhui->count() }} entretien{{ $entretiensAujourdhui->count() > 1 ? 's' : '' }} aujourd'hui — pensez à vous préparer
                            </p>
                        </div>
                    </div>
                @endif
                @if($entretiensSansPreparationCount > 0)
                    <div class="rounded-2xl border border-violet-200 bg-violet-50/80 px-4 py-3 sm:px-5 sm:flex sm:items-center sm:justify-between gap-3">
                        <p class="text-sm font-semibold text-violet-950">
                            {{ $entretiensSansPreparationCount }} entretien{{ $entretiensSansPreparationCount > 1 ? 's' : '' }} à venir sans notes de préparation
                        </p>
                        <a href="#a-preparer" class="text-sm font-bold text-violet-700 hover:text-violet-900 shrink-0">Voir →</a>
                    </div>
                @endif
                @if($entretiensResultatEnAttenteCount > 0)
                    <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 sm:px-5 sm:flex sm:items-center sm:justify-between gap-3">
                        <p class="text-sm font-semibold text-sky-950">
                            {{ $entretiensResultatEnAttenteCount }} entretien{{ $entretiensResultatEnAttenteCount > 1 ? 's' : '' }} passé{{ $entretiensResultatEnAttenteCount > 1 ? 's' : '' }} — résultat à renseigner
                        </p>
                        <a href="#resultats" class="text-sm font-bold text-sky-700 hover:text-sky-900 shrink-0">Mettre à jour →</a>
                    </div>
                @endif
            </div>
        @endif

        {{-- Prochain entretien (hero) --}}
        @if($prochainEntretien)
            <div class="card overflow-hidden border-brand-200/60 shadow-lg shadow-brand-900/5">
                <div class="bg-gradient-to-br from-brand-600 via-brand-700 to-violet-800 px-6 py-8 sm:px-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-widest text-brand-200">Prochain entretien</p>
                            <h2 class="mt-2 text-2xl sm:text-3xl font-bold truncate">{{ $prochainEntretien->candidature->entreprise }}</h2>
                            <p class="mt-1 text-brand-100">{{ $prochainEntretien->candidature->poste }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-lg bg-white/15 px-3 py-1 text-sm font-semibold backdrop-blur">
                                    {{ $prochainEntretien->type_label }}
                                </span>
                                <span class="inline-flex items-center rounded-lg bg-white/15 px-3 py-1 text-sm font-semibold backdrop-blur">
                                    {{ $prochainEntretien->scheduleLabel() }}
                                </span>
                            </div>
                        </div>
                        <div class="shrink-0 text-center lg:text-right">
                            <p class="text-4xl sm:text-5xl font-bold tracking-tight">{{ $prochainEntretien->countdownLabel() }}</p>
                            <p class="mt-2 text-sm text-brand-200">Temps restant</p>
                            @if($prochainEntretien->lacksPreparation())
                                <p class="mt-3 inline-flex items-center gap-1 rounded-lg bg-amber-400/20 px-3 py-1 text-xs font-bold text-amber-100">
                                    Préparation à faire
                                </p>
                            @else
                                <p class="mt-3 inline-flex items-center gap-1 rounded-lg bg-emerald-400/20 px-3 py-1 text-xs font-bold text-emerald-100">
                                    Préparation OK
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('candidatures.show', $prochainEntretien->candidature) }}" class="btn bg-white text-brand-800 hover:bg-brand-50 focus:ring-white">
                            Voir la candidature
                        </a>
                        <a href="{{ route('entretiens.edit', $prochainEntretien) }}" class="btn border border-white/30 bg-white/10 text-white hover:bg-white/20 focus:ring-white/50">
                            {{ $prochainEntretien->lacksPreparation() ? 'Préparer l\'entretien' : 'Modifier l\'entretien' }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-dashed">
                <div class="card-body text-center py-10">
                    <p class="text-slate-600 font-medium">Aucun entretien planifié pour le moment.</p>
                    <p class="text-sm text-slate-500 mt-1">Ajoutez une date pour ne rien oublier.</p>
                    <a href="{{ route('entretiens.create') }}" class="btn-primary mt-6 text-sm">Planifier un entretien</a>
                </div>
            </div>
        @endif

        {{-- KPI --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
            <div class="card">
                <div class="card-body !p-4 sm:!p-5">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500">Actives</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">{{ $totalActives }}</p>
                    <a href="{{ route('candidatures.index') }}" class="mt-2 inline-block text-xs font-semibold text-brand-600 hover:text-brand-700">Liste →</a>
                </div>
            </div>
            <div class="card ring-1 ring-violet-100">
                <div class="card-body !p-4 sm:!p-5">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-violet-600">À venir</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-violet-700">{{ $entretiensAVenirCount }}</p>
                    <p class="mt-2 text-xs text-slate-500">entretien{{ $entretiensAVenirCount > 1 ? 's' : '' }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body !p-4 sm:!p-5">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500">Cette semaine</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">{{ $entretiensCetteSemaine }}</p>
                    <p class="mt-2 text-xs text-slate-500">d'ici dimanche</p>
                </div>
            </div>
            <div class="card {{ $entretiensSansPreparationCount > 0 ? 'ring-1 ring-amber-200' : '' }}">
                <div class="card-body !p-4 sm:!p-5">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider {{ $entretiensSansPreparationCount > 0 ? 'text-amber-700' : 'text-slate-500' }}">À préparer</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold {{ $entretiensSansPreparationCount > 0 ? 'text-amber-700' : 'text-slate-900' }}">{{ $entretiensSansPreparationCount }}</p>
                    <a href="#a-preparer" class="mt-2 inline-block text-xs font-semibold text-brand-600 hover:text-brand-700">Détail →</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body !p-4 sm:!p-5">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500">Taux réponse</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-violet-600">{{ $tauxReponse }}<span class="text-base text-slate-400">%</span></p>
                    <p class="mt-2 text-xs text-slate-500">entretien / offre</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body !p-4 sm:!p-5">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500">À relancer</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">{{ $candidaturesARelancer }}</p>
                    <a href="{{ route('candidatures.index', ['statut' => 'en_attente']) }}" class="mt-2 inline-block text-xs font-semibold text-brand-600 hover:text-brand-700">Filtrer →</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Agenda entretiens --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between gap-3 mb-6">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Agenda des entretiens</h2>
                                <p class="text-sm text-slate-500 mt-0.5">Les prochains rendez-vous, du plus urgent au plus lointain</p>
                            </div>
                            <a href="{{ route('entretiens.create') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 shrink-0">+ Ajouter</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($entretiensAVenir as $e)
                                <x-dashboard-entretien-item :entretien="$e" />
                            @empty
                                <p class="text-sm text-slate-500 text-center py-8">Aucun entretien à venir. Planifiez le prochain pour rester organisé.</p>
                            @endforelse
                        </div>
                        @if($entretiensAVenirCount > $entretiensAVenir->count())
                            <p class="mt-4 text-center text-xs text-slate-500">
                                + {{ $entretiensAVenirCount - $entretiensAVenir->count() }} autre{{ $entretiensAVenirCount - $entretiensAVenir->count() > 1 ? 's' : '' }} entretien{{ $entretiensAVenirCount - $entretiensAVenir->count() > 1 ? 's' : '' }}
                            </p>
                        @endif
                    </div>
                </div>

                @if($entretiensSansPreparation->isNotEmpty())
                    <div id="a-preparer" class="card ring-1 ring-amber-100">
                        <div class="card-body">
                            <h2 class="text-lg font-bold text-slate-900">À préparer en priorité</h2>
                            <p class="text-sm text-slate-500 mt-1 mb-5">Entretiens à venir sans notes — complétez-les pour arriver prêt</p>
                            <div class="space-y-3">
                                @foreach($entretiensSansPreparation as $e)
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 rounded-xl border border-amber-200 bg-amber-50/50 p-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-slate-900">{{ $e->candidature->entreprise }} — {{ $e->type_label }}</p>
                                            <p class="text-sm text-slate-600">{{ $e->scheduleLabel() }} · {{ $e->countdownLabel() }}</p>
                                        </div>
                                        <a href="{{ route('entretiens.edit', $e) }}" class="btn-primary text-sm shrink-0">Ajouter des notes</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($entretiensResultatEnAttente->isNotEmpty())
                    <div id="resultats" class="card">
                        <div class="card-body">
                            <h2 class="text-lg font-bold text-slate-900">Résultats à renseigner</h2>
                            <p class="text-sm text-slate-500 mt-1 mb-5">Entretiens passés — mettez à jour le résultat pour suivre votre pipeline</p>
                            <div class="space-y-3">
                                @foreach($entretiensResultatEnAttente as $e)
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-slate-900">{{ $e->candidature->entreprise }}</p>
                                            <p class="text-sm text-slate-600">{{ $e->type_label }} · {{ $e->scheduleLabel() }}</p>
                                        </div>
                                        <a href="{{ route('entretiens.edit', $e) }}" class="btn-secondary text-sm shrink-0">Renseigner le résultat</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Colonne droite --}}
            <div class="space-y-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">Répartition par statut</h2>
                        @if($totalActives === 0)
                            <p class="text-sm text-slate-500 text-center py-4">Aucune candidature active.</p>
                        @else
                            <ul class="space-y-3">
                                @foreach($statuts as $key => $label)
                                    @php
                                        $count = $parStatut->get($key, 0);
                                        $pct = $totalActives > 0 ? round(($count / $totalActives) * 100) : 0;
                                    @endphp
                                    <li>
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <x-status-badge :variant="$key" :label="$label" />
                                            <span class="text-xs font-semibold text-slate-600">{{ $count }}</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-brand-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">Répartition par priorité</h2>
                        @if($totalActives === 0)
                            <p class="text-sm text-slate-500 text-center py-4">Aucune candidature active.</p>
                        @else
                            <ul class="space-y-3">
                                @foreach($priorites as $key => $label)
                                    @php
                                        $count = $parPriorite->get($key, 0);
                                        $pct = $totalActives > 0 ? round(($count / $totalActives) * 100) : 0;
                                    @endphp
                                    <li>
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <x-status-badge :variant="$key" :label="$label" />
                                            <span class="text-xs font-semibold text-slate-600">{{ $count }}</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Dernières candidatures</h2>
                            <a href="{{ route('candidatures.create') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">+</a>
                        </div>
                        @forelse($recentCandidatures as $c)
                            <a href="{{ route('candidatures.show', $c) }}" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-3 mb-2 last:mb-0 hover:border-brand-200 transition">
                                <div class="min-w-0">
                                    <p class="font-semibold text-sm text-slate-900 truncate">{{ $c->entreprise }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $c->poste }}</p>
                                </div>
                                <x-status-badge :variant="$c->statut" :label="$statuts[$c->statut]" />
                            </a>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4">Aucune candidature.</p>
                        @endforelse
                        <a href="{{ route('candidatures.index') }}" class="mt-4 block text-center text-sm font-semibold text-brand-600 hover:text-brand-700">Toutes les candidatures →</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Archives</p>
                            <p class="text-2xl font-bold text-slate-700 mt-0.5">{{ $totalArchives }}</p>
                        </div>
                        <a href="{{ route('candidatures.archives') }}" class="btn-secondary text-sm">Consulter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
