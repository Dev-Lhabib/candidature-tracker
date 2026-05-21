/**
 * Accumulates file selections across multiple "Choose files" clicks.
 * Vanilla JS so uploads work even when Alpine loads late or fails.
 */
function syncFileInput(input, files) {
    const dt = new DataTransfer();
    files.forEach((f) => dt.items.add(f));
    input.files = dt.files;
}

function initFichiersPicker(wrapper) {
    const input = wrapper.querySelector('[data-fichiers-input]');
    const list = wrapper.querySelector('[data-fichiers-list]');
    const maxFiles = parseInt(wrapper.dataset.maxFiles || '10', 10);
    const files = [];

    const render = () => {
        if (!list) {
            return;
        }

        list.innerHTML = '';

        files.forEach((file, index) => {
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between gap-2';

            const name = document.createElement('span');
            name.className = 'truncate text-slate-700';
            name.textContent = file.name;

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'shrink-0 text-xs font-semibold text-red-600 hover:text-red-700';
            remove.textContent = 'Retirer';
            remove.addEventListener('click', () => {
                files.splice(index, 1);
                syncFileInput(input, files);
                render();
            });

            li.append(name, remove);
            list.appendChild(li);
        });

        list.classList.toggle('hidden', files.length === 0);
    };

    input.addEventListener('change', () => {
        for (const file of input.files) {
            const duplicate = files.some(
                (f) =>
                    f.name === file.name &&
                    f.size === file.size &&
                    f.lastModified === file.lastModified
            );

            if (!duplicate && files.length < maxFiles) {
                files.push(file);
            }
        }

        syncFileInput(input, files);
        input.value = '';
        render();
    });

    wrapper.closest('form')?.addEventListener('submit', () => {
        syncFileInput(input, files);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-fichiers-picker]').forEach(initFichiersPicker);
});
