/* Panel Hot Wings — vista previa y validación de subida */
(function () {
    'use strict';

    const ALLOWED = ['image/jpeg', 'image/png', 'image/webp'];
    const MAX_BYTES = 6 * 1024 * 1024;

    document.querySelectorAll('.upload-form').forEach((form) => {
        const input = form.querySelector('input[type="file"]');
        const dz = form.querySelector('.dropzone');
        const previews = form.querySelector('.previews');
        const submit = form.querySelector('button[type="submit"]');
        const max = parseInt(form.dataset.max, 10) || 1;

        let errBox = form.querySelector('.file-error');
        if (!errBox) {
            errBox = document.createElement('p');
            errBox.className = 'file-error';
            errBox.style.display = 'none';
            previews.after(errBox);
        }

        const setError = (msg) => {
            errBox.textContent = msg || '';
            errBox.style.display = msg ? 'block' : 'none';
            submit.disabled = !!msg;
        };

        function render() {
            const files = Array.from(input.files || []);
            previews.innerHTML = '';
            setError('');

            if (files.length === 0) return;
            if (files.length > max) {
                setError('Máximo ' + max + ' imagen(es) para esta sección.');
            }
            for (const f of files) {
                if (!ALLOWED.includes(f.type)) {
                    setError('Solo se permiten imágenes JPG, PNG o WEBP.');
                    continue;
                }
                if (f.size > MAX_BYTES) {
                    setError('"' + f.name + '" supera el tamaño máximo de 6 MB.');
                    continue;
                }
                const item = document.createElement('div');
                item.className = 'preview-item';
                const img = document.createElement('img');
                img.src = URL.createObjectURL(f);
                img.onload = () => URL.revokeObjectURL(img.src);
                const name = document.createElement('span');
                name.className = 'pname';
                name.textContent = f.name;
                item.append(img, name);
                previews.appendChild(item);
            }
        }

        input.addEventListener('change', render);

        // Arrastrar y soltar
        ['dragenter', 'dragover'].forEach((ev) =>
            dz.addEventListener(ev, (e) => { e.preventDefault(); dz.classList.add('dragover'); }));
        ['dragleave', 'drop'].forEach((ev) =>
            dz.addEventListener(ev, (e) => { e.preventDefault(); dz.classList.remove('dragover'); }));
        dz.addEventListener('drop', (e) => {
            if (e.dataTransfer && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                render();
            }
        });
    });
})();
