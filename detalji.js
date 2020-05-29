function promijeniBoju(moj_red_tablice) {
    moj_red_tablice.style.backgroundColor = "#336699";
}

var req;

function detalji(id, title) {
    console.log(title);
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (req) {
        req.open("GET", "detalji.php?id=" + id + "&show=simple", true);
        req.send(null);
        document.getElementById("details").innerHTML = '<img src="images/spinning.gif" alt="Učitavanje..." />';
        req.onreadystatechange = function() {
            if (req.readyState == 4) { // primitak odgovora
                if (req.status == 200) { // kôd statusa odgovora = 200 OK
                    document.getElementById("details").innerHTML = req.responseText;
                } else { // kôd statusa nije OK
                    alert("Nije primljen 200 OK, nego:\n" + req.statusText);
                }
            }
        };
    }
    if (window.XMLHttpRequest) {
        req1 = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (req1) {
        if(title.includes("Nacionalni")){
            console.log("hrvatska");
            req1.open("GET", "https://hr.wikipedia.org/api/rest_v1/page/html/" + title, true);
        }
        else {
            console.log("engleska");
            req1.open("GET", "https://en.wikipedia.org/api/rest_v1/page/html/" + title, true);
        }
        
        req1.send(null);
        document.getElementById("details").innerHTML = '<img src="images/spinning.gif" alt="Učitavanje..." />';
        req1.onreadystatechange = function() {
            if (req1.readyState == 4) { // primitak odgovora
                if (req1.status == 200) { // kôd statusa odgovora = 200 OK
                    document.getElementById("wikihtml").srcdoc = req1.responseText;
                    document.getElementById("wikihtml").style.width = "650px";
                    document.getElementById("wikihtml").style.height = '1000px';
                } else { // kôd statusa nije OK
                    alert("Nije primljen 200 OK, nego:\n" + req1.statusText);
                }
            }
        };
    }
}

var map;
var init = 0;
var marker1;
var marker2;

function leafletTile(lat, lon, title, latNominatim, lonNominatim) {
    var coordinates = [];
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
        var polyline = L.polyline(coordinates, { color: 'red' }).addTo(map);
        map.fitBounds(polyline.getBounds());
    }

}
