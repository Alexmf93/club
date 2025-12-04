const formulario = document.getElementById('formularioServicio');

if (formulario) {
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

        fetch('procesar_servicio.php', {
            method: 'POST',
            body: formData
        })
        .then(function (res) {
            // Intentamos parsear JSON; si falla devolvemos un objeto que indica el estado crudo
            return res.json().catch(function () {
                return { __rawOk: res.ok, __status: res.status };
            });
        })
        .then(function (data) {
            if (data && data.success) {
                const id = data.id ? `?id=${encodeURIComponent(data.id)}#admin-servicios` : '';
                window.location.href = `servicio.php${id}`;
                return;
            }

            if (data && data.__rawOk) {
                // caso en que servidor respondió OK pero sin JSON
                window.location.reload();
                return;
            }

            // error del servidor (puede venir en data.error) o respuesta no OK
            const msg = (data && data.error) ? data.error : 'Error del servidor al guardar el servicio';
            alert('No se pudo guardar: ' + msg);
            if (submitBtn) submitBtn.disabled = false;
        })
        .catch(function (err) {
            alert('Error de red: ' + (err && err.message ? err.message : err));
            if (submitBtn) submitBtn.disabled = false;
        });
    });
}