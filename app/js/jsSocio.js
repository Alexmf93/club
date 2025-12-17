document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formularioSocio');

    if (formulario) {
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

            document.querySelectorAll('.error').forEach(span => span.innerText = "");

            const nombre = (document.getElementById('nombre').value || '').trim();
            const telefono = (document.getElementById('telefono').value || '').trim();
            const password = (document.getElementById('password').value || '');
            const idInput = formulario.querySelector('input[name="id"]');
            const isUpdate = idInput && idInput.value;

            let valid = true;

            if (nombre.length < 3) {
                document.getElementById('nombreError').innerText = "El nombre debe tener al menos 3 caracteres.";
                valid = false;
            }

            if (telefono && !/^\d{9}$/.test(telefono)) {
                document.getElementById('telefonoError').innerText = "El teléfono debe tener 9 dígitos.";
                valid = false;
            }

            if (!isUpdate && password.length < 6) {
                document.getElementById('contraseñaError').innerText = "La contraseña es obligatoria y debe tener al menos 6 caracteres.";
                valid = false;
            } else if (isUpdate && password && password.length < 6) {
                document.getElementById('contraseñaError').innerText = "Si cambia la contraseña, debe tener al menos 6 caracteres.";
                valid = false;
            }

            if (!valid) return;

            const submitBtn = formulario.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(formulario);

            fetch('procesar_socio.php', {
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
                    showFlash(data.message, 'success');
                    
                    // Aquí está la lógica clave:
                    if (data.operation === 'insert') {
                        // Si es una inserción, limpiamos el formulario y recargamos la página para ver el nuevo socio en la lista.
                        formulario.reset();
                        setTimeout(() => {
                            window.location.href = 'socio.php#admin-socios';
                        }, 1200);
                    } else {
                        // Si es una actualización, recargamos la página sin parámetros GET para ver los cambios en la lista.
                        setTimeout(() => {
                            window.location.href = 'socio.php';
                        }, 1200);
                    }
                    return; // Detenemos la ejecución para no continuar
                }

                const msg = (data && data.error) ? data.error : 'Error del servidor al guardar el socio.';
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
});