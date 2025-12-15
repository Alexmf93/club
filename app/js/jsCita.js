const formulario = document.getElementById('citaForm');

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

        document.querySelectorAll('.error').forEach(span => span.innerText = "");

        const id_socio = (document.getElementById('id_socio').value || '').trim();
        const id_servicio = (document.getElementById('id_servicio').value || '').trim();
        const fecha_cita = (document.getElementById('fecha_cita').value || '').trim();
        const hora_cita = (document.getElementById('hora_cita').value || '').trim();

        let valid = true;

        if (id_socio === '') {
            document.getElementById('id_socioError').innerText = "Debe seleccionar un socio";
            valid = false;
        }

        if (id_servicio === '') {
            document.getElementById('id_servicioError').innerText = "Debe seleccionar un servicio";
            valid = false;
        }

        if (fecha_cita === '') {
            document.getElementById('fecha_citaError').innerText = "Debe seleccionar una fecha";
            valid = false;
        } else {
            const fecha_obj = new Date(fecha_cita);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            if (fecha_obj <= hoy) {
                document.getElementById('fecha_citaError').innerText = "La fecha debe ser posterior a hoy";
                valid = false;
            }
        }

        if (hora_cita === '') {
            document.getElementById('hora_citaError').innerText = "Debe seleccionar una hora";
            valid = false;
        }

        if (!valid) return;

        const submitBtn = formulario.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(formulario);

        fetch('procesar_cita.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json().catch(() => ({ success: false, error: `Respuesta inesperada del servidor (HTTP ${res.status})` })))
        .then(data => {
            if (data && data.success) {
                const anchor = '#admin-citas';
                const msgPart = data.message ? `?msg=${encodeURIComponent(data.message)}` : '';

                if (data.message) {
                    showFlash(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = `cita.php${msgPart}${anchor}`;
                    }, 1200);
                } else {
                    window.location.href = `cita.php${msgPart}${anchor}`;
                }
                return;
            }

            const msg = (data && data.error) ? data.error : 'Error del servidor al crear la cita.';
            showFlash(msg, 'error');
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        })
        .catch(err => {
            showFlash('Error de red: ' + (err && err.message ? err.message : err), 'error');
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        });
    });
}