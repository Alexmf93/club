const formulario = document.getElementById('citaForm');

if (formulario) {
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

        if (valid) formulario.submit();
    });
}