<div
    x-data="{ show: false, cible: null }"
    x-on:open-delete-modal.window="show = true; cible = $event.detail"
    x-on:close-delete-modal.window="show = false; cible = null"
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
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Suppression définitive</h3>
        </div>
        <div class="card-body pt-5">
            <p class="text-slate-600 mb-4">Cette action est <strong class="text-red-600">irréversible</strong>.</p>
            <template x-if="cible">
                <div class="rounded-xl bg-red-50 border border-red-200 p-4 mb-6">
                    <p class="font-semibold text-red-900" x-text="cible.entreprise"></p>
                    <p class="text-sm text-red-600" x-text="cible.poste"></p>
                </div>
            </template>
            <div class="flex gap-3 justify-end">
                <button type="button" x-on:click="show = false" class="btn-secondary">Annuler</button>
                <template x-if="cible">
                    <button
                        type="button"
                        x-on:click="fetch(cible.action, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '', 'X-Requested-With': 'XMLHttpRequest' },
                        }).then(() => { show = false; window.location.reload(); })"
                        class="btn-danger">
                        Supprimer
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
