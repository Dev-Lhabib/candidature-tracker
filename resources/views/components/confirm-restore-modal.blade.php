<div
    x-data="{ show: false, cible: null }"
    x-on:open-restore-modal.window="console.log('[RESTORE-MODAL] event received', $event.detail); show = true; cible = $event.detail"
    x-on:close-restore-modal.window="show = false; cible = null"
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
        <div class="bg-green-600 px-6 py-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Restaurer la candidature</h3>
        </div>

        <div class="p-6">
            <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir restaurer cette candidature ?</p>

            <template x-if="cible">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <p class="font-semibold text-gray-900" x-text="cible.entreprise"></p>
                    <p class="text-sm text-gray-500" x-text="cible.poste"></p>
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
                        x-on:click="console.log('[RESTORE-MODAL] fetch start', cible.action); fetch(cible.action, {
                            method: 'PUT',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        }).then(r => { console.log('[RESTORE-MODAL] fetch response', r); show = false; window.location.reload(); })"
                        class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-sm">
                        Restaurer
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>