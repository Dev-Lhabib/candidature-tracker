<div
    x-data="{ show: false, cible: null }"
    x-on:open-archive-modal.window="show = true; cible = $event.detail"
    x-on:close-archive-modal.window="show = false; cible = null"
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
        <div class="bg-amber-500 px-6 py-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Archiver la candidature</h3>
        </div>

        <div class="p-6">
            <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir archiver cette candidature ?</p>

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
                        x-on:click="fetch(cible.action, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '', 'X-Requested-With': 'XMLHttpRequest' },
                        }).then(() => { show = false; window.location.reload(); })"
                        class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-sm">
                        Archiver
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>