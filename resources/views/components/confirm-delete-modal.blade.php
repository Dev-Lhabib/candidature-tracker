<div
    x-data="{ show: false, cible: null }"
    x-on:open-delete-modal.window="console.log('[DELETE-MODAL] event received', $event.detail); show = true; cible = $event.detail"
    x-on:close-delete-modal.window="show = false; cible = null"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500/75"></div>
    </div>

    <div
        x-show="show"
        class="mb-6 bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all sm:w-full sm:max-w-md sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div class="bg-red-600 px-6 py-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Supprimer définitivement</h3>
        </div>

        <div class="p-6">
            <p class="text-gray-600 mb-2">Cette action est <strong>irréversible</strong>.</p>
            <p class="text-gray-600 mb-4">Toutes les données de cette candidature seront définitivement supprimées.</p>

            <template x-if="cible">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="font-semibold text-red-900" x-text="cible.entreprise"></p>
                    <p class="text-sm text-red-600" x-text="cible.poste"></p>
                </div>
            </template>

            <div class="flex gap-3 justify-end">
                <button
                    type="button"
                    x-on:click="show = false"
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm">
                    Annuler
                </button>
                <template x-if="cible">
                    <button
                        type="button"
                        x-on:click="console.log('[DELETE-MODAL] fetch start', cible.action); fetch(cible.action, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        }).then(r => { console.log('[DELETE-MODAL] fetch response', r); show = false; window.location.reload(); })"
                        class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition text-sm">
                        Supprimer
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>