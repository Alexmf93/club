document.addEventListener('DOMContentLoaded', function() {
    
    // =============================================
    // Modal para mostrar detalles de noticias
    // =============================================
    
    // Crear el modal HTML dinámicamente
    const modalHTML = `
        <div id="noticiaModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="modalImagen" src="" alt="Noticia" class="modal-imagen">
                <h2 id="modalTitulo"></h2>
                <p id="modalFecha" class="modal-fecha"></p>
                <p id="modalContenido" class="modal-contenido"></p>
            </div>
        </div>
    `;
    
    // Insertar el modal al final del body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = document.getElementById('noticiaModal');
    const closeBtn = document.querySelector('.close');
    
    // Obtener todos los botones "Leer más"
    const readMoreButtons = document.querySelectorAll('.read-more');
    
    // Agregar evento click a cada botón "Leer más"
    readMoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const noticiaCard = this.closest('.news-card');
            if (!noticiaCard) return;
            
            // Obtener datos de la tarjeta
            const imagen = noticiaCard.querySelector('img').src;
            const titulo = noticiaCard.querySelector('h3').textContent;
            const contenido = noticiaCard.querySelector('p').textContent;
            const noticiaId = noticiaCard.querySelector('button').id;
            
            // Convertir fecha a formato español sin hora
            const fechaObj = new Date(noticiaId);
            const fechaFormato = fechaObj.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Llenar el modal con los datos
            document.getElementById('modalImagen').src = imagen;
            document.getElementById('modalTitulo').textContent = titulo;
            document.getElementById('modalContenido').textContent = contenido;
            document.getElementById('modalFecha').textContent = `Publicado: ${fechaFormato}`;
            
            // Mostrar el modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Evitar scroll al fondo
        });
    });
    
    // Cerrar modal al hacer clic en la X
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    // Cerrar modal al hacer clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'flex') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
});