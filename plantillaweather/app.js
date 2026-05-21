
//http://api.weatherapi.com/v1/current.json?key=027e499e71bf4031b73155522211901&q=Granada


function crearFila(ciudad, humedad, temperatura, icono) {
  const fila = document.createElement("tr");

  const td_ciudad = document.createElement("td");
  td_ciudad.classList.add("text-center");
  td_ciudad.innerText = ciudad;

  const td_humedad = document.createElement("td");
  td_humedad.classList.add("text-center");
  td_humedad.innerText = humedad+"%";

  const td_temperatura = document.createElement("td");
  td_temperatura.classList.add("text-center");
  td_temperatura.innerText = temperatura+"ºC";


  const imagen = document.createElement("img");
  imagen.src = icono;

  const td_icono = document.createElement("td");
  td_icono.classList.add("text-center");
  td_icono.appendChild(imagen);



  fila.appendChild(td_ciudad);
  fila.appendChild(td_humedad);
  fila.appendChild(td_temperatura);
  fila.appendChild(td_icono);

  return fila;
}

const btnBuscar = document.getElementById('buscar');
const inputCiudad = document.getElementById('ciudad');
const contenedor = document.getElementById('tiempo_api');
const cargando = document.getElementById('cargando');
const API_KEY = '027e499e71bf4031b73155522211901';

const buscarCiudad = async () => {
    const ciudad = inputCiudad.value.trim();

    if (!ciudad) {
        alert('Por favor introduce el nombre de una ciudad.');
        return;
    }

    cargando.style.display = 'block';

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

        contenedor.appendChild(crearFila(ciudad, humidity, temp_c, icono));
    } catch (error) {
        alert(error.message);
    } finally {
        cargando.style.display = 'none';
    }
};

btnBuscar.addEventListener('click', buscarCiudad);

inputCiudad.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') buscarCiudad();
});
