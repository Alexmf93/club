const noticiaForm = document.getElementById('noticiaForm')

noticiaForm.addEventListener('submit', (e) => {
    e.preventDefault()
    
    let spanErrors = document.querySelectorAll('.error')
    spanErrors.forEach(span => {
        span.innerText = ""
    })

    const titulo = document.getElementById('titulo').value
    const noticia = document.getElementById('contenido').value
    const fecha = document.getElementById('fecha').value
    const archivo = document.getElementById('fotoNoticia').files[0]
    
    // Regex y validaciones
    const soloJPEG = /\.(jpe?g)$/i
    const maxSize = 5 * 1024 * 1024 // 5MB en bytes
    
    // Validación de título
    if (titulo.trim().length < 3) {
        document.getElementById('tituloError').innerText = "El título debe tener un mínimo de 3 caracteres"
    }

    // Validación de noticia
    if (noticia.trim().length < 3) {
        document.getElementById('noticiaError').innerText = "La noticia debe tener un mínimo de 3 caracteres"
    }

    // Validación de fecha: debe ser posterior a hoy
    if (fecha) {
        const hoy = new Date()
        hoy.setHours(0, 0, 0, 0)
        const fechaIngresada = new Date(fecha)
        
        if (fechaIngresada <= hoy) {
            document.getElementById('fechaError').innerText = "La fecha de publicación debe ser posterior a hoy"
        }
    }

    // Validación de imagen: JPEG obligatorio y máximo 5MB
    if (archivo) {
        // Validar formato JPEG
        if (!soloJPEG.test(archivo.name)) {
            document.getElementById('fotoError').innerText = "El archivo debe estar en formato JPEG (.jpg o .jpeg)"
        }
        
        // Validar tamaño máximo
        if (archivo.size > maxSize) {
            document.getElementById('fotoError').innerText = "La imagen no debe superar los 5MB de tamaño"
        }
        
        // Ambas validaciones fallan
        if (!soloJPEG.test(archivo.name) && archivo.size > maxSize) {
            document.getElementById('fotoError').innerText = "El archivo debe ser JPEG y no superar los 5MB"
        }
    } else {
        document.getElementById('fotoError').innerText = "La imagen es obligatoria"
    }
})
    