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
