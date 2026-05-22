<div
    x-data="{
        show: false,
        deleteUrl: '',
        fileName: '',
        open(detail) {
            this.show = true;
            this.deleteUrl = detail.url;
            this.fileName = detail.name;
        },
        close() {
            this.show = false;
            this.deleteUrl = '';
            this.fileName = '';
        },
        confirm() {
            fetch(this.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then(() => window.location.reload());
        },
    }"
    x-on:open-delete-fichier-modal.window="open($event.detail)"
    x-on:keydown.escape.window="if (show) close()"
>
    <div
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div x-on:click="close()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative card w-full max-w-md overflow-hidden" @click.stop>
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Supprimer ce fichier ?
            </div>
            <div class="card-body">
                <p class="text-slate-600 mb-4">Cette pièce jointe sera supprimée définitivement.</p>
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 mb-6">
                    <p class="font-semibold text-slate-900 break-all" x-text="fileName"></p>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" x-on:click="close()" class="btn-secondary">Annuler</button>
                    <button type="button" x-on:click="confirm()" class="btn-danger">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>
