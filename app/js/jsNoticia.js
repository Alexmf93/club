const noticiaForm = document.getElementById('noticiaForm')

noticiaForm.addEventListener('submit', (e) => {
    e.preventDefault()
    
    let spanErrors = document.querySelectorAll('.error')
    spanErrors.forEach(span => {
        span.innerText = ""
    })

    const titulo = document.getElementById('titulo').value
    const noticia = document.getElementById('contenido').value
    
    if (titulo.trim().length < 3) {
        document.getElementById('tituloError').innerText = "El título debe tener un mínimo de 3 caracteres"
    }

    if (noticia.trim().length < 3) {
        document.getElementById('noticiaError').innerText = "La noticia debe tener un mínimo de 3 caracteres"
    }
})
    