// Función que redirige al hacer click en un marcador
function onMarkerClick(event, route){
    window.location.replace(route);
}

async function map_admin_pins(url){
    let response = await fetch(url+'/api/map/datasets',{
        method: "GET"
    })

    let data = await response.json();
    console.log(data)

    if (data.length > 0) {
        draw_pins(data);

    }

    drawnMap();
}

function draw_pins(datareads){
    // Creamos un grupo de clúster usando Leaflet.markercluster
    var markersCluster = L.markerClusterGroup({
        spiderfyOnMaxZoom: true,     // Expande el clúster en el máximo zoom para ver marcadores individuales
        showCoverageOnHover: false,    // Desactiva la visualización del área cubierta al pasar el mouse
        zoomToBoundsOnClick: true,     // Hace zoom al hacer clic en el clúster
        maxClusterRadius: 40           // Ajusta el radio (en píxeles) para agrupar marcadores
    });

    // Por cada dato se crea un marcador y se agrega al grupo de clúster
    datareads.forEach(element => {
        let marker = L.marker([element.latitude, element.longitude]);
        marker.bindPopup(element.dataset_name);
        marker.on('mouseover', function (e) {
            this.openPopup();
        });
        marker.on('mouseout', function (e) {
            this.closePopup();
        });
        marker.on('click', (event) => onMarkerClick(event, '/admin/dataset/' + element.dataset_id + '/show'));
        markersCluster.addLayer(marker);
    });

    // Agregamos el grupo de clúster al mapa global
    map.addLayer(markersCluster);
}

function drawnMap() {
    // Inicializamos el mapa con las coordenadas deseadas
    var localMap = L.map('map').setView([38.47652239055892, -1.3260276860442988], 9);

    // Agregamos la capa base del mapa
    L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
    }).addTo(localMap);

    // Obtenemos la URL del elemento (si se utiliza)
    let url = document.getElementById("url").value;

    // Actualizamos la variable global 'map'
    map = localMap;
    map_admin_pins(url);
}


// --- Código Global Existente ---

// Se crea el mapa con coordenadas hardcodeadas
var map = L.map('map').setView([38.47652239055892, -1.3260276860442988], 9);

// Se agrega la capa base
L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
}).addTo(map);

// Se obtiene la URL del elemento (si se utiliza)
let url = document.getElementById("url").value;

// Se llama a map_admin_pins con la URL
map_admin_pins(url);
// document.getElementById('status').addEventListener("change", map_technicians_pins);
