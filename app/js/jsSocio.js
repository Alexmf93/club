const formulario = document.getElementById('formularioSocio') || document.getElementById('formulario');

if (formulario) {
    formulario.addEventListener('submit', (e) => {
        e.preventDefault();

        let valid = true;
        document.querySelectorAll('.error').forEach(span => span.innerText = "");

        const nombre = document.getElementById('nombre')?.value ?? "";
        const password = document.getElementById('password')?.value ?? "";
        const telefono = document.getElementById('telefono')?.value ?? "";
        const archivoInput = document.getElementById('foto');
        const archivo = (archivoInput && archivoInput.files && archivoInput.files.length) ? archivoInput.files[0] : null;

        const soloLetrasNumerosGuiones = /^[A-Za-z][A-Za-z0-9_]*$/;
        const tlfnEspaolNueveDigitos = /^\+34\d{9}$/;
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

        if (telefono && !tlfnEspaolNueveDigitos.test(telefono.trim())) {
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

        if (valid) {
            // Enviar por AJAX usando FormData para que procesar_socio.php actualice la BD
            const submitBtn = formulario.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(formulario);
            const currentId = formData.get('id'); // puede ser null para nuevo socio

            fetch('procesar_socio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en el servidor');
                // Redirigir para ver los cambios (si hay id, mostrar ese socio)
                const target = currentId ? `socio.php?id=${encodeURIComponent(currentId)}#admin-socios` : 'socio.php';
                window.location.href = target;
            })
            .catch(err => {
                alert('Error al guardar: ' + err.message);
                if (submitBtn) submitBtn.disabled = false;
            });
        }
    });
}