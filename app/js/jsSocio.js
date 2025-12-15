(function () {
    const formulario = document.getElementById('formularioSocio') || document.getElementById('formulario');

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

    if (!formulario) return;

    formulario.addEventListener('submit', async (e) => {
        e.preventDefault();

        let valid = true;
        document.querySelectorAll('.error').forEach(span => span.innerText = "");

        const nombre = document.getElementById('nombre')?.value ?? "";
        const password = document.getElementById('password')?.value ?? "";
        const telefono = document.getElementById('telefono')?.value ?? "";
        const archivoInput = document.getElementById('foto');
        const archivo = (archivoInput && archivoInput.files && archivoInput.files.length) ? archivoInput.files[0] : null;

        const soloLetrasNumerosGuiones = /^[A-Za-z][A-Za-z0-9_]*$/;
        const tlfnEspañolNueveDigitos = /^\+34\d{9}$/;
        const soloJPEG = /\.(jpe?g)$/i;
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (nombre.trim().length < 3 || nombre.trim().length > 50) {
            document.getElementById('nombreError').innerText = "El nombre debe contener entre 3 y 50 caracteres";
            valid = false;
        }

        if (password) {
            if (password.trim().length < 8 || password.trim().length > 16 || !soloLetrasNumerosGuiones.test(password.trim())) {
                document.getElementById('contraseñaError').innerText = "La contraseña debe tener 8-16 caracteres, empezar por letra y solo contener letras, números o _";
                valid = false;
            }
        }

        if (telefono && !tlfnEspañolNueveDigitos.test(telefono.trim())) {
            document.getElementById('telefonoError').innerText = "Debe ser un número español con prefijo +34 y 9 dígitos";
            valid = false;
        }

        if (archivo) {
            if (!soloJPEG.test(archivo.name)) {
                document.getElementById('fotoError').innerText = "El formato de la imagen debe ser JPEG (.jpg/.jpeg)";
                valid = false;
            } else if (archivo.size > maxSize) {
                document.getElementById('fotoError').innerText = "La imagen no debe superar los 5MB";
                valid = false;
            }
        }

        if (!valid) return;

        const submitBtn = formulario.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        try {
            const formData = new FormData(formulario);
            const res = await fetch('procesar_socio.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data = null;
            try { data = await res.json(); } catch (err) { data = null; }

            if (res.ok && data && data.success) {
    const idPart = data.id ? `id=${encodeURIComponent(data.id)}` : '';
    const msgPart = data.message ? `msg=${encodeURIComponent(data.message)}` : '';
    // construir query correctamente (si hay id y msg => ?id=...&msg=...; si sólo msg => ?msg=...)
    let query = '';
    if (idPart && msgPart) query = `?${idPart}&${msgPart}`;
    else if (idPart) query = `?${idPart}`;
    else if (msgPart) query = `?${msgPart}`;

    const anchor = '#admin-socios';
    if (data.message) {
        // mostrar banner flash y redirigir después de breve espera
        showFlash(data.message, 'success');
        setTimeout(() => {
            window.location.href = `socio.php${query}${anchor}`;
        }, 1200);
    } else {
        window.location.href = `socio.php${query}${anchor}`;
    }
} else if (res.ok) {
                window.location.reload();
            } else {
                const msg = (data && data.error) ? data.error : `Error del servidor (${res.status})`;
                showFlash(msg, 'error');
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            showFlash('Error de red: ' + err.message, 'error');
            if (submitBtn) submitBtn.disabled = false;
        }
    });
})();