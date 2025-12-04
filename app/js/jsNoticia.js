const formulario = document.getElementById('noticiaForm');

if (formulario) {
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        document.querySelectorAll('.error').forEach(span => span.innerText = "");

        const titulo = (document.getElementById('titulo').value || '').trim();
        const contenido = (document.getElementById('contenido').value || '').trim();
        const fecha_publicacion = (document.getElementById('fecha_publicacion').value || '').trim();
        const fileInput = document.getElementById('fotoNoticia');
        const archivo = (fileInput && fileInput.files && fileInput.files.length) ? fileInput.files[0] : null;

        let valid = true;

        if (titulo.length < 5 || titulo.length > 200) {
            document.getElementById('tituloError').innerText = "El título debe tener entre 5 y 200 caracteres";
            valid = false;
        }

        if (contenido === '') {
            document.getElementById('noticiaError').innerText = "El contenido es obligatorio";
            valid = false;
        } else if (contenido.length < 20) {
            document.getElementById('noticiaError').innerText = "El contenido debe tener al menos 20 caracteres";
            valid = false;
        }

        if (fecha_publicacion === '') {
            document.getElementById('fechaError').innerText = "Debe seleccionar una fecha";
            valid = false;
        } else {
            const fecha_obj = new Date(fecha_publicacion);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
        }

        if (!archivo) {
            document.getElementById('fotoError').innerText = "La imagen es obligatoria";
            valid = false;
        } else {
            const soloJPEG = /\.(jpe?g)$/i;
            const maxSize = 5 * 1024 * 1024;

            if (!soloJPEG.test(archivo.name)) {
                document.getElementById('fotoError').innerText = "El archivo debe estar en formato JPEG (.jpg o .jpeg)";
                valid = false;
            } else if (archivo.size > maxSize) {
                document.getElementById('fotoError').innerText = "La imagen no debe superar los 5MB de tamaño";
                valid = false;
            }
        }

        if (!valid) return;

        const submitBtn = formulario.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(formulario);

        fetch('procesar_noticia.php', {
            method: 'POST',
            body: formData
        })
        .then(function (res) {
            return res.json().catch(function () {
                return { __rawOk: res.ok, __status: res.status };
            });
        })
        .then(function (data) {
            if (data && data.success) {
                alert('¡Noticia insertada correctamente!');
                window.location.href = 'noticia.php';
                return;
            }

            if (data && data.__rawOk) {
                window.location.reload();
                return;
            }

            const msg = (data && data.error) ? data.error : 'Error del servidor';
            alert('No se pudo guardar: ' + msg);
            if (submitBtn) submitBtn.disabled = false;
        })
        .catch(function (err) {
            alert('Error de red: ' + (err && err.message ? err.message : err));
            if (submitBtn) submitBtn.disabled = false;
        });
    });
}

// Modal para ver noticia completa (ya existía en script.js)
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('read-more')) {
        const titulo = e.target.getAttribute('data-titulo');
        const contenido = e.target.getAttribute('data-contenido');
        const imagen = e.target.getAttribute('data-imagen');
        const fecha = e.target.getAttribute('data-fecha');

        // Formatear fecha en español
        const fechaObj = new Date(fecha);
        const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        const mes = meses[fechaObj.getMonth()];
        const fechaFormato = fechaObj.getDate() + ' de ' + mes + ' de ' + fechaObj.getFullYear();

        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <img src="${imagen}" alt="${titulo}" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <h2>${titulo}</h2>
                <p>${contenido}</p>
                <p><small>Publicado: ${fechaFormato}</small></p>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'flex';

        document.querySelector('.close-modal').addEventListener('click', function () {
            modal.remove();
        });

        modal.addEventListener('click', function (ev) {
            if (ev.target === modal) modal.remove();
        });

        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') modal.remove();
        });
    }
});