function promijeniBoju(moj_red_tablice) {
    moj_red_tablice.style.backgroundColor = "#336699"
}

let req;
function detalji(id) {

    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (req) {

        req.open("GET", "detalji.php?id=" + id + "&show=simple", true);
        req.send(null);
        document.getElementById("details").innerHTML = '<img src="images/spinning.gif" alt="Učitavanje..." />';
        req.onreadystatechange = () => {
            if (req.readyState == 4) { // primitak odgovora
                if (req.status == 200) { // kôd statusa odgovora = 200 OK
                    document.getElementById("details").innerHTML = req.responseText;
                } else { // kôd statusa nije OK
                    alert("Nije primljen 200 OK, nego:\n" + req.statusText);
                }
            }
        };
    }
}

var map;
let init = 0;
let marker1;
let marker2;

function leafletTile(lat, lon, title, latNominatim, lonNominatim) {
    let coordinates = [];
    document.getElementById("map").style.visibility = 'visible';
    if (!init) {
        map = L.map('map').setView([lat, lon], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        init = 1;
    }

    try {
        map.removeLayer(marker1);
        map.removeLayer(marker2);
    } catch (err) { }
    if (lat.includes("null")) {
        console.log("wiki Location not available")
    } else {
        coordinates.push([lat, lon]);
        marker1 = L.marker([lat, lon]).addTo(map)
            .bindPopup(title)
            .openPopup();
    }
    if (latNominatim.includes("null")) {
        console.log("nominatim location not available")
    } else {
        coordinates.push([latNominatim, lonNominatim]);
        marker2 = L.marker([latNominatim, lonNominatim]).addTo(map)
            .bindPopup(title + " nominatim")
            .openPopup();
    }

    if (coordinates.length === 2) {
        let polyline = L.polyline(coordinates, { color: 'red' }).addTo(map);
        map.fitBounds(polyline.getBounds());
    }

}
