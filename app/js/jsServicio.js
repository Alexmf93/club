const formulario = document.getElementById('formularioServicio');

if (formulario) {
    function showFlash(message, type = 'success') {
        // intenta insertar dentro de .container2 si existe
        const container = document.querySelector('.container2') || document.body;
        const div = document.createElement('div');
        div.className = type === 'success' ? 'flash-success' : 'flash-error';
        div.textContent = message;
        // insertar al inicio del contenedor
        container.insertBefore(div, container.firstChild);
        // auto ocultar después de 3s
        setTimeout(() => {
            div.style.transition = 'opacity 0.4s, transform 0.4s';
            div.style.opacity = '0';
            div.style.transform = 'translateY(-6px)';
            setTimeout(() => div.remove(), 450);
        }, 3000);
    }
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        // limpiar errores
        document.querySelectorAll('.error').forEach(span => span.innerText = "");

        const nombre2 = (document.getElementById('nombre2').value || '').trim();
        const duracion = Number(document.getElementById('duracion').value);
        const precio = Number(document.getElementById('precio').value);

        let valid = true;

        if (nombre2.length < 3 || nombre2.length > 50) {
            document.getElementById('nombre2Error').innerText = "El nombre debe contener entre 3 y 50 caracteres";
            valid = false;
        }
        if (!Number.isFinite(duracion) || duracion < 15) {
            document.getElementById('duracionError').innerText = "La duración no puede ser inferior a 15 minutos";
            valid = false;
        }
        if (!Number.isFinite(precio) || precio < 0) {
            document.getElementById('precioError').innerText = "El precio no puede ser inferior a 0";
            valid = false;
        }

        if (!valid) return;

        const submitBtn = formulario.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(formulario);

        fetch('procesar_servicio.php', { // Aseguramos que el servidor responda JSON
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            // Intentamos parsear JSON; si falla devolvemos un objeto que indica el estado crudo
            return res.json().catch(function () {
                // Si no es JSON, asumimos que es un error o una redirección directa
                return { success: false, error: `Respuesta inesperada del servidor (HTTP ${res.status})` };
            });
        })
        .then(function (data) {
            if (data && data.success) {
                const idPart = data.id ? `id=${encodeURIComponent(data.id)}` : '';
                const msgPart = data.message ? `msg=${encodeURIComponent(data.message)}` : '';
                let query = '';
                if (idPart && msgPart) query = `?${idPart}&${msgPart}`;
                else if (idPart) query = `?${idPart}`;
                else if (msgPart) query = `?${msgPart}`;

                const anchor = '#admin-servicios';

                if (data.message) {
                    showFlash(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = `servicio.php${query}${anchor}`;
                    }, 1200); // Redirigir después de 1.2 segundos para que el usuario vea el mensaje
                } else {
                    // Si no hay mensaje, redirigir directamente
                    window.location.href = `servicio.php${query}${anchor}`;
                }
                return; // ¡Importante! Detiene la ejecución para no mostrar errores falsos.

            } else if (data && data.error) {
                // Error específico del servidor
                showFlash(data.error, 'error');
            } else {
                // Error genérico si la respuesta no fue exitosa
                showFlash('Error del servidor al guardar el servicio.', 'error');
            }

            // Reactivar el botón solo en caso de error
            if (submitBtn) submitBtn.disabled = false;
        })
        .catch(function (err) {
            showFlash('Error de red: ' + (err && err.message ? err.message : err), 'error');
            if (submitBtn) submitBtn.disabled = false;
        });
    });
}