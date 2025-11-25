const testimonioForm = document.getElementById('testimonioForm')

testimonioForm.addEventListener('submit', (e) => {
    e.preventDefault()
    
    let spanErrors = document.querySelectorAll('.error')
    spanErrors.forEach(span => {
        span.innerText = ""
    })

    const autor = document.getElementById('autor').value
    const texto = document.getElementById('testimonio').value
    
    if(autor.trim() === ""){
        document.getElementById('autorError').innerText = "Rellene los campos seleccionados"
    }

    if(texto.trim() === ""){
        document.getElementById('testimonioError').innerText = "Rellene los campos seleccionados"
    }
})