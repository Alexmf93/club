const formulario = document.getElementById('testimonioForm');

if (formulario) {
    // Función para mostrar mensajes flash (consistente con otros scripts)
    function showFlash(message, type = 'success') {
        const container = document.querySelector('.container2') || document.body;
        const div = document.createElement('div');
        div.className = type === 'success' ? 'flash-success' : 'flash-error';
        div.textContent = message;
        container.insertBefore(div, container.firstChild);
        setTimeout(() => {
            div.style.transition = 'opacity 0.4s, transform 0.4s';
            div.style.opacity = '0';
            div.style.transform = 'translateY(-6px)';
            setTimeout(() => div.remove(), 450);
        }, 3000);
    }

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        // Limpiar errores
        document.querySelectorAll('.error').forEach(span => span.innerText = "");

        const id_autor = (document.getElementById('id_autor').value || '').trim();
        const contenido = (document.getElementById('contenido').value || '').trim();

        let valid = true;

        if (id_autor === '') {
            document.getElementById('id_autorError').innerText = "Debe seleccionar un autor";
            valid = false;
        }

        if (contenido === '') {
            document.getElementById('contenidoError').innerText = "El testimonio no puede estar vacío";
            valid = false;
        }

        if (contenido.length < 10) {
            document.getElementById('contenidoError').innerText = "El testimonio debe tener al menos 10 caracteres";
            valid = false;
        }

        if (!valid) return;

        const submitBtn = formulario.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(formulario);

        fetch('procesar_testimonio.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            // Si la respuesta no es JSON, la promesa se rechazará.
            return res.json().catch(function () {
                // Creamos un objeto de error estándar para manejarlo consistentemente.
                return { success: false, error: `Respuesta inesperada del servidor (HTTP ${res.status})` };
            });
        })
        .then(function (data) {
            if (data && data.success) {
                if (data.message) {
                    showFlash(data.message, 'success');
                    // Esperamos un poco para que el usuario vea el mensaje antes de recargar
                    setTimeout(() => {
                        window.location.href = 'testimonio.php#listado-testimonios';
                    }, 1200);
                } else {
                    window.location.href = 'testimonio.php#listado-testimonios';
                }
                return;
            }

            // Si llegamos aquí, hubo un error validado por el backend.
            const msg = (data && data.error) ? data.error : 'No se pudo guardar el testimonio.';
            showFlash(msg, 'error');
            if (submitBtn) submitBtn.disabled = false;
        })
        .catch(function (err) {
            // Error de red o un problema que impidió el fetch.
            const errorMsg = 'Error de red: ' + (err && err.message ? err.message : 'No se pudo conectar con el servidor.');
            showFlash(errorMsg, 'error');
            if (submitBtn) submitBtn.disabled = false;
        });
    });
}