<div
    x-data="{ show: false, cible: null }"
    x-on:open-restore-modal.window="show = true; cible = $event.detail"
    x-on:close-restore-modal.window="show = false; cible = null"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    <div x-on:click="show = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div
        x-show="show"
        x-transition
        class="relative card w-full max-w-md overflow-hidden"
        @click.stop
    >
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Restaurer la candidature</h3>
        </div>
        <div class="card-body pt-5">
            <p class="text-slate-600 mb-4">Elle réapparaîtra dans votre liste active.</p>
            <template x-if="cible">
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 mb-6">
                    <p class="font-semibold text-slate-900" x-text="cible.entreprise"></p>
                    <p class="text-sm text-slate-500" x-text="cible.poste"></p>
                </div>
            </template>
            <div class="flex gap-3 justify-end">
                <button type="button" x-on:click="show = false" class="btn-secondary">Annuler</button>
                <template x-if="cible">
                    <button
                        type="button"
                        x-on:click="fetch(cible.action, {
                            method: 'PUT',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '', 'X-Requested-With': 'XMLHttpRequest' },
                        }).then(() => { show = false; window.location.reload(); })"
                        class="btn-success">
                        Restaurer
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
