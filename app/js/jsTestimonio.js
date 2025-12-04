const formulario = document.getElementById('testimonioForm');

if (formulario) {
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
            body: formData
        })
        .then(function (res) {
            return res.json().catch(function () {
                return { __rawOk: res.ok, __status: res.status };
            });
        })
        .then(function (data) {
            if (data && data.success) {
                alert('¡Testimonio insertado correctamente!');
                window.location.href = 'testimonio.php';
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