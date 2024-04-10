// Si se quiere cambiar estilo del mapa mirar: http://leaflet-extras.github.io/leaflet-providers/preview/index.html
function onMarkerClick(event, route){
    window.location.replace(route);
}

async function map_admin_pins(url){

    let response = await fetch(url+'/api/map/datarsets',{
        method: "GET"
    })

    let data = await response.json();
    console.log(data)

    if (data.length > 0) {
        draw_pins(data);
    }

    drawnMap()
}

function draw_pins(datareads){
    // for each property we draw a pin in the map with different features like mouseOver and onClick
    datareads.forEach(element => {
        let marker = L.marker([element.latitude, element.longitude])

        marker.bindPopup(element.dataset_name)
        marker.on('mouseover', function (e) {
            this.openPopup();
        });
        marker.on('mouseout', function (e) {
            this.closePopup();
        });
        marker.on('click',(event) => this.onMarkerClick(event, '/admin/dataset/'+element.dataset_id+'/show'))
        marker.addTo(map)
    });
}

function drawnMap() {
    // Seleccionamos las coordenadas donde comienza el zoom del mapa
    var map = L.map('map').setView([38.47652239055892, -1.3260276860442988], 9);

    // Agregamos la capa (el mapa que vemos)
    L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
    }).addTo(map);

    // Obtenemos la URL
    let url = document.getElementById("url").value

    map_admin_pins(url);
}


// We select the coordinates where the map starts zooming
var map = L.map('map').setView([38.47652239055892, -1.3260276860442988], 9);

// We add the layer(the map that we see)
L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
	maxZoom: 19,
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
}).addTo(map);

// We get the URL
let url = document.getElementById("url").value

map_admin_pins(url)
// document.getElementById('status').addEventListener("change", map_technicians_pins);

