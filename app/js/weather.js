const API_KEY = '027e499e71bf4031b73155522211901';
const btnBuscar = document.getElementById('btnBuscarCiudad');
const inputCiudad = document.getElementById('ciudadInput');
const contenedor = document.getElementById('weatherResultados');
const cargando = document.getElementById('weatherCargando');

function crearTarjeta(nombre, humedad, temperatura, icono, descripcion) {
    const card = document.createElement('div');
    card.className = 'weather-card';
    card.innerHTML = `
        <img src="${icono}" alt="${descripcion}" class="weather-icon">
        <h3 class="weather-city">${nombre}</h3>
        <p class="weather-temp">${temperatura}°C</p>
        <p class="weather-desc">${descripcion}</p>
        <p class="weather-humidity">Humedad: ${humedad}%</p>
    `;
    return card;
}

async function buscarCiudad() {
    const ciudad = inputCiudad.value.trim();
    if (!ciudad) {
        alert('Por favor introduce el nombre de una ciudad.');
        return;
    }

    cargando.style.display = 'block';
    contenedor.innerHTML = '';

    try {
        const response = await fetch(
            `https://api.weatherapi.com/v1/current.json?key=${API_KEY}&q=${encodeURIComponent(ciudad)}&lang=es`
        );

        if (!response.ok) {
            const err = await response.json();
            if (err.error?.code === 1006) {
                throw new Error(`No se ha encontrado la ciudad "${ciudad}". Comprueba que el nombre es correcto.`);
            }
            throw new Error(err.error?.message || `Error HTTP ${response.status}`);
        }

        const data = await response.json();
        const { humidity, temp_c, condition } = data.current;
        const icono = condition.icon.startsWith('//') ? 'https:' + condition.icon : condition.icon;

        contenedor.appendChild(crearTarjeta(data.location.name, humidity, temp_c, icono, condition.text));
    } catch (error) {
        contenedor.innerHTML = `<p class="weather-error">${error.message}</p>`;
    } finally {
        cargando.style.display = 'none';
    }
}

btnBuscar.addEventListener('click', buscarCiudad);
inputCiudad.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') buscarCiudad();
});
