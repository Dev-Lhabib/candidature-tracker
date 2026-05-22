<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue d'ensemble de votre recherche d'emploi</p>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-8">
        {{-- KPI cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Candidatures actives</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalActives }}</p>
                    <a href="{{ route('candidatures.index') }}" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:text-brand-700">Voir la liste →</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Entretiens</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalEntretiens }}</p>
                    <a href="{{ route('entretiens.create') }}" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:text-brand-700">Planifier →</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Taux entretien / offre</p>
                    <p class="mt-2 text-3xl font-bold text-violet-600">{{ $tauxReponse }}<span class="text-lg text-slate-500">%</span></p>
                    <p class="mt-3 text-xs text-slate-500">Sur les candidatures actives</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Archives</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalArchives }}</p>
                    <a href="{{ route('candidatures.archives') }}" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:text-brand-700">Consulter →</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Par statut --}}
            <div class="card">
                <div class="card-body">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Répartition par statut</h2>
                    @if($totalActives === 0)
                        <p class="text-sm text-slate-500 text-center py-6">Aucune candidature active pour l'instant.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($statuts as $key => $label)
                                @php
                                    $count = $parStatut->get($key, 0);
                                    $pct = $totalActives > 0 ? round(($count / $totalActives) * 100) : 0;
                                @endphp
                                <li>
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <x-status-badge :variant="$key" :label="$label" />
                                        <span class="text-sm font-semibold text-slate-700">{{ $count }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-brand-500 transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Par priorité --}}
            <div class="card">
                <div class="card-body">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Répartition par priorité</h2>
                    @if($totalActives === 0)
                        <p class="text-sm text-slate-500 text-center py-6">Aucune candidature active pour l'instant.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($priorites as $key => $label)
                                @php
                                    $count = $parPriorite->get($key, 0);
                                    $pct = $totalActives > 0 ? round(($count / $totalActives) * 100) : 0;
                                @endphp
                                <li>
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <x-status-badge :variant="$key" :label="$label" />
                                        <span class="text-sm font-semibold text-slate-700">{{ $count }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Prochains entretiens --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Prochains entretiens</h2>
                        <a href="{{ route('entretiens.create') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">+ Ajouter</a>
                    </div>
                    @forelse($entretiensAVenir as $e)
                        <a href="{{ route('candidatures.show', $e->candidature) }}" class="block rounded-xl border border-slate-200 bg-slate-50/50 p-4 mb-3 last:mb-0 hover:border-brand-200 hover:bg-brand-50/30 transition">
                            <p class="font-semibold text-slate-900">{{ $e->candidature->entreprise }}</p>
                            <p class="text-sm text-slate-600">{{ App\Models\Entretien::types()[$e->type] }} — {{ $e->date_heure->format('d/m/Y à H:i') }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-6">Aucun entretien à venir.</p>
                    @endforelse
                </div>
            </div>

            {{-- Dernières candidatures --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Dernières candidatures</h2>
                        <a href="{{ route('candidatures.create') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">+ Ajouter</a>
                    </div>
                    @forelse($recentCandidatures as $c)
                        <a href="{{ route('candidatures.show', $c) }}" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 mb-3 last:mb-0 hover:border-brand-200 hover:bg-brand-50/30 transition">
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 truncate">{{ $c->entreprise }}</p>
                                <p class="text-sm text-slate-600 truncate">{{ $c->poste }}</p>
                            </div>
                            <x-status-badge :variant="$c->statut" :label="$statuts[$c->statut]" />
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-6">Aucune candidature.</p>
                        <div class="text-center">
                            <a href="{{ route('candidatures.create') }}" class="btn-primary text-sm">Créer une candidature</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
