const servicio = document.getElementById('servicio')

servicio.addEventListener('submit', (e) => {
    e.preventDefault()
    
    let spanErrors = document.querySelectorAll('.error')
    spanErrors.forEach(span => {
        span.innerText = ""
    })
    
    const nombre2 = document.getElementById('nombre2').value
    const duracion = document.getElementById('duracion').value
    const precio = document.getElementById('precio').value


    if(nombre2.trim().length < 3 || nombre2.trim().length > 50){
        document.getElementById('nombre2Error').innerText = "El nombre debe contener entre 3 y 50 caracteres"
    }

    if(duracion < 15){
        document.getElementById('duracionError').innerText = "La duración no puede ser inferior a 15 minutos"
    }

    if(precio < 0){
        document.getElementById('precioError').innerText = "El precio no puede ser inferior a 0"
    }
})