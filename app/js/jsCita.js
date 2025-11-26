const citaForm = document.getElementById('citaForm')

citaForm.addEventListener('submit', (e) => {
    e.preventDefault()
    
    // Limpiar todos los mensajes de error
    let spanErrors = document.querySelectorAll('.error')
    spanErrors.forEach(span => {
        span.innerText = ""
    })

    const cliente = document.getElementById('cliente').value
    const servicio = document.getElementById('servicio').value
    const fechaCita = document.getElementById('fechaCita').value
    
    let hayErrores = false

    // Validación de cliente: no puede ser la opción por defecto (vacía)
    if (cliente === "") {
        document.getElementById('clienteError').innerText = "Debe seleccionar un cliente"
        hayErrores = true
    }

    // Validación de servicio: no puede ser la opción por defecto (vacía)
    if (servicio === "") {
        document.getElementById('servicioError').innerText = "Debe seleccionar un servicio"
        hayErrores = true
    }

    // Validación de fecha: debe ser posterior a hoy
    if (fechaCita) {
        const hoy = new Date()
        hoy.setHours(0, 0, 0, 0)
        const fechaIngresada = new Date(fechaCita)
        
        if (fechaIngresada <= hoy) {
            document.getElementById('fechaCitaError').innerText = "La fecha de la cita debe ser posterior a hoy"
            hayErrores = true
        }
    } else {
        document.getElementById('fechaCitaError').innerText = "Debe seleccionar una fecha"
        hayErrores = true
    }

    // Si no hay errores, aquí se podría enviar el formulario
    if (!hayErrores) {
        console.log("Formulario válido. Datos listos para enviar.")
        // En una aplicación real, aquí se enviaría el formulario al servidor
        // citaForm.submit()
    }
})
