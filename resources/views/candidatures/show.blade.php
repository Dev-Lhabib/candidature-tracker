<x-app-layout>
    <x-slot name="header">
        <div class="min-w-0">
            <h1 class="page-title truncate">{{ $candidature->entreprise }}</h1>
            <p class="page-subtitle truncate">{{ $candidature->poste }}</p>
        </div>
    </x-slot>

    <div x-data="{
        showArchive: false,
        showDelete: false,
        archiveUrl: '',
        deleteUrl: '',
        entretienLabel: '',
        openArchive(url) { this.showArchive = true; this.archiveUrl = url; },
        openDelete(url, label) { this.showDelete = true; this.deleteUrl = url; this.entretienLabel = label; },
        confirmArchive() {
            fetch(this.archiveUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-Requested-With': 'XMLHttpRequest' } }).then(() => window.location.href = @js(route('candidatures.index')));
        },
        confirmDelete() {
            fetch(this.deleteUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-Requested-With': 'XMLHttpRequest' } }).then(() => window.location.reload());
        }
    }" class="max-w-4xl mx-auto space-y-6">

        {{-- Modals --}}
        <div x-show="showArchive" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click="showArchive = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative card max-w-md w-full overflow-hidden" @click.stop>
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4 font-bold text-white">Archiver cette candidature ?</div>
                <div class="card-body">
                    <p class="text-slate-600 mb-4">Elle sera déplacée vers les archives.</p>
                    <div class="rounded-xl bg-slate-50 border p-4 mb-6">
                        <p class="font-semibold">{{ $candidature->entreprise }}</p>
                        <p class="text-sm text-slate-500">{{ $candidature->poste }}</p>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="showArchive = false" class="btn-secondary">Annuler</button>
                        <button type="button" @click="confirmArchive()" class="btn-success">Archiver</button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click="showDelete = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative card max-w-md w-full overflow-hidden" @click.stop>
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 font-bold text-white">Supprimer l'entretien ?</div>
                <div class="card-body">
                    <p class="text-slate-600 mb-4">Cette action est irréversible.</p>
                    <div class="rounded-xl bg-slate-50 border p-4 mb-6">
                        <p class="font-semibold" x-text="entretienLabel"></p>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="showDelete = false" class="btn-secondary">Annuler</button>
                        <button type="button" @click="confirmDelete()" class="btn-danger">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('candidatures.index') }}" class="btn-ghost text-sm inline-flex">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
            <div class="flex gap-2">
                <a href="{{ route('candidatures.edit', $candidature) }}" class="btn-primary text-sm py-2">Modifier</a>
                <button type="button" x-on:click="openArchive(@js(route('candidatures.destroy', $candidature)))" class="btn-danger text-sm py-2">Archiver</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex flex-wrap gap-2 mb-6">
                    <x-status-badge :variant="$candidature->statut" :label="App\Models\Candidature::statuts()[$candidature->statut]" />
                    <x-status-badge :variant="$candidature->priorite" :label="App\Models\Candidature::priorites()[$candidature->priorite]" />
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Date de candidature</dt>
                        <dd class="mt-1 text-slate-900 font-medium">{{ $candidature->date_candidature->format('d/m/Y') }}</dd>
                    </div>
                    @if($candidature->url_offre)
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Offre</dt>
                        <dd class="mt-1">
                            <a href="{{ $candidature->url_offre }}" target="_blank" rel="noopener" class="text-brand-600 hover:text-brand-700 font-medium text-sm break-all">
                                {{ Str::limit($candidature->url_offre, 50) }} ↗
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>

                @if($candidature->fichier_path)
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Pièce jointe</p>
                    <a href="{{ route('candidatures.download', $candidature) }}" class="btn-secondary text-sm inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Télécharger
                    </a>
                </div>
                @endif

                @if($candidature->notes)
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Notes</p>
                    <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $candidature->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="text-lg font-bold text-slate-900 mb-6">Entretiens</h2>

                <div class="space-y-4 mb-8">
                    @forelse($candidature->entretiens as $e)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 flex flex-col sm:flex-row sm:justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ App\Models\Entretien::types()[$e->type] }}</p>
                                <p class="text-sm text-slate-600 mt-0.5">{{ $e->date_heure->format('d/m/Y à H:i') }}</p>
                                <div class="mt-2">
                                    <x-status-badge :variant="$e->resultat" :label="App\Models\Entretien::resultats()[$e->resultat]" />
                                </div>
                                @if($e->notes_preparation)
                                    <p class="text-sm text-slate-600 mt-3">{{ $e->notes_preparation }}</p>
                                @endif
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('entretiens.edit', $e) }}" class="btn-secondary text-xs py-2">Modifier</a>
                                <button type="button" x-on:click="openDelete(@js(route('entretiens.destroy', $e)), @js(App\Models\Entretien::types()[$e->type] . ' - ' . $e->date_heure->format('d/m/Y à H:i')))" class="btn-ghost text-xs py-2 text-red-600">Supprimer</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-8 rounded-xl border border-dashed border-slate-200">Aucun entretien enregistré</p>
                    @endforelse
                </div>

                <div class="pt-6 border-t border-slate-200">
                    <h3 class="text-sm font-bold text-slate-900 mb-4">Ajouter un entretien</h3>
                    <form method="POST" action="{{ route('entretiens.store', $candidature) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="type" value="Type *" />
                                <select id="type" name="type" class="form-select" required>
                                    @foreach(App\Models\Entretien::types() as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_heure" value="Date et heure *" />
                                <x-text-input id="date_heure" name="date_heure" type="datetime-local" required />
                                <x-input-error :messages="$errors->get('date_heure')" class="mt-2" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="resultat" value="Résultat *" />
                            <select id="resultat" name="resultat" class="form-select" required>
                                @foreach(App\Models\Entretien::resultats() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('resultat')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="notes_preparation" value="Notes de préparation" />
                            <textarea id="notes_preparation" name="notes_preparation" rows="3" class="form-textarea"></textarea>
                            <x-input-error :messages="$errors->get('notes_preparation')" class="mt-2" />
                        </div>
                        <x-primary-button>Ajouter l'entretien</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
