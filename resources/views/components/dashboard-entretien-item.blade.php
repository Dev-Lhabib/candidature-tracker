@props(['entretien', 'showPreparation' => true])

@php
    $urgency = $entretien->urgency();
    $urgencyRing = match ($urgency) {
        'critical' => 'border-amber-300 bg-amber-50/80 ring-amber-200/60',
        'soon'     => 'border-violet-200 bg-violet-50/50 ring-violet-100',
        default    => 'border-slate-200 bg-slate-50/50 ring-slate-100',
    };
    $urgencyBadge = match ($urgency) {
        'critical' => 'bg-amber-100 text-amber-900 ring-amber-200/60',
        'soon'     => 'bg-violet-100 text-violet-800 ring-violet-200/60',
        default    => 'bg-slate-100 text-slate-700 ring-slate-200/60',
    };
@endphp

<a
    href="{{ route('candidatures.show', $entretien->candidature) }}"
    {{ $attributes->merge(['class' => "group block rounded-xl border p-4 ring-1 transition hover:shadow-md {$urgencyRing}"]) }}
>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <p class="font-semibold text-slate-900 group-hover:text-brand-700 truncate">
                    {{ $entretien->candidature->entreprise }}
                </p>
                <span class="badge ring-1 ring-inset {{ $urgencyBadge }} shrink-0">
                    {{ $entretien->countdownLabel() }}
                </span>
            </div>
            <p class="text-sm text-slate-600 truncate">{{ $entretien->candidature->poste }}</p>
            <p class="mt-1.5 text-sm font-medium text-slate-800">
                {{ $entretien->type_label }} · {{ $entretien->scheduleLabel() }}
            </p>
            @if($showPreparation && $entretien->lacksPreparation())
                <p class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-amber-700">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Notes de préparation à compléter
                </p>
            @elseif($showPreparation && filled($entretien->notes_preparation))
                <p class="mt-2 text-xs text-emerald-700 font-medium">Préparation notée</p>
            @endif
        </div>
        <x-status-badge :variant="$entretien->resultat" :label="App\Models\Entretien::resultats()[$entretien->resultat]" class="shrink-0" />
    </div>
</a>
