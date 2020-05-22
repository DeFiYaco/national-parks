<?php
error_reporting(E_ALL);

$xmldoc = new DOMDocument();
$xmldoc->load('podaci.xml');

$xpathvar = new Domxpath($xmldoc);

$all = $xpathvar->query("/data/nationalPark");

if (!empty($_REQUEST['id'])){
    foreach($all as $elem){
        if(($_REQUEST['id']) == ($elem->getAttribute('id'))) {
                echo "<b>Dodatni detalji:</b><br/>";
                echo "Broj posjetitelja u godini: ";
                if ($elem->getElementsByTagName('visitors')->length == 0){
                    echo "Nema podataka";
                } else {
                    echo $elem->getElementsByTagName('visitors')->item(0)->nodeValue;
                }
                echo "<br/>Upraviteljsko tijelo: <br/>";
                if ($elem->getElementsByTagName('gov')->length == 0){
                    echo "Nema podataka";
                } else {
                    echo $elem->getElementsByTagName('gov')->item(0)->nodeValue;
                }
                echo "<br/>Kontakt email adresa: <br/>";
                if ($elem->getElementsByTagName('contact')->length == 0){
                    echo "Nema podataka";
                } elseif ($elem->getElementsByTagName('contact')->item(0)->getElementsByTagName('email')->length != 0) {
                    echo $elem->getElementsByTagName('contact')->item(0)->getElementsByTagName('email')->item(0)->nodeValue;
                } else {
                    echo "Nema podataka";
                }
                echo "<br/>Kontakt telefon: <br/>";
                if ($elem->getElementsByTagName('contact')->length == 0){
                    echo "Nema podataka";
                } elseif ($elem->getElementsByTagName('contact')->item(0)->getElementsByTagName('tel')->length != 0) {
                    echo $elem->getElementsByTagName('contact')->item(0)->getElementsByTagName('tel')->item(0)->nodeValue;
                } else {
                    echo "Nema podataka";
                }
                echo "<br/>";
                
                
                
                
                
        }
    }
}


sleep(1);


?>