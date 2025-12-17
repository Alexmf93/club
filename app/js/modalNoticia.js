// Modal para ver noticia completa (ya existía en script.js)
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('read-more')) {
        const titulo = e.target.getAttribute('data-titulo');
        const contenido = e.target.getAttribute('data-contenido');
        const imagen = e.target.getAttribute('data-imagen');
        const fecha = e.target.getAttribute('data-fecha');

        // Formatear fecha en español
        const fechaObj = new Date(fecha);
        const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        const mes = meses[fechaObj.getMonth()];
        const fechaFormato = fechaObj.getDate() + ' de ' + mes + ' de ' + fechaObj.getFullYear();

        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <img src="${imagen}" alt="${titulo}" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <h2>${titulo}</h2>
                <p>${contenido}</p>
                <p><small>Publicado: ${fechaFormato}</small></p>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'flex';

        document.querySelector('.close-modal').addEventListener('click', function () {
            modal.remove();
        });

        modal.addEventListener('click', function (ev) {
            if (ev.target === modal) modal.remove();
        });

        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') modal.remove();
        });
    }
});