<?php
error_reporting(E_ALL);
include_once 'funkcije.php';

$xmldoc = new DOMDocument();
$xmldoc->load('podaci.xml');

$xpathvar = new Domxpath($xmldoc);
$customQuery = customQuery();
$queryResult = $xpathvar->query($customQuery);
$all = $xpathvar->query("/data/nationalPark");
// if(!empty($_REQUEST['sort'])){
//     $parks = iterator_to_array($queryResult);
//     if ((int)$_REQUEST['sort'] == "asc") {
//         usort($parks, 'ascsort_by_numeric_id_attr');
//     } else {
//         usort($parks, 'descsort_by_numeric_id_attr');
//     }
    
//     $queryResult=$parks;
// }
// # Nažalost nije proradilo :(
// function ascsort_by_numeric_id_attr($a, $b)
// {
//     return (int) $a->getAttribute('id') - (int) $b->getAttribute('id');
// }

// function descsort_by_numeric_id_attr($a, $b)
// {
//     return (int) $b->getAttribute('id') - (int) $a->getAttribute('id');
// }

?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Nacionalni parkovi</title>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600' rel='stylesheet' type='text/css' />
        <link href="dizajn.css" rel="stylesheet" type="text/css" media="screen" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script> 
        <script src="detalji.js"></script>
    </head>
        <body>
            <div id="wrapper">
                <div id="header-wrapper">
                    <div class="header">
                        <a href="index.html">
                            <img id="header-image" src="./images/header3.jpg" alt="Planine"/>
                        </a>
                        <div class="center">
                            <a id="headerTitle" href="index.html">NACIONALNI PARKOVI</a>
                        </div>
                    </div>
                </div>
                <!-- end #header -->
                <div id="page">
                    <div id="page-bgtop">
                        <div id="page-bgbtm">
                            <div id="content">
                                <div id="post">
                                    <div id="entry">
                                        <h2>Pronađeni rezultati</h2>
                                        <h3><?php echo "Pronađeno je ", sizeof($queryResult), " rezultata od mogućih ", sizeof($all); ?></h3>
                                        <table class="table">
                                            <tr>
                                                <th>Naziv parka</th>
                                                <th>Fotografija</th>
                                                <!-- <th>Sažetak</th> -->
                                                <th>Država</th>
                                                <!-- <th>Predio</th> -->
                                                <!-- <th>wiki lokacija</th>
                                                <th>Nominatim lokacija</th> -->
                                                <!-- <th>Površina (km<sup>2</sup>) -->
                                                </th>
                                                <!-- <th>UNESCO</th> -->
                                                <!-- <th>Posjetitelji godišnje</th> -->
                                                <th>Akcija</th>
                                            </tr>
                                            <?php
                                                
                                                foreach ($queryResult as $result){
                                                    $id = $result->getAttribute('id');
                                                    $wikiId = $result->getAttribute('wikiId');
                                                    $wikiMedia_pre = microtime(true);
                                                    $wikiJson = getWikimedia($wikiId);
                                                    $wikiMedia_post = microtime(true);
                                                    $wikiMedia_time = $wikiMedia_post - $wikiMedia_pre;
                                                    $nominatim_pre = microtime(true);
                                                    $mediaXml = getNearestTownGeo($wikiId);
                                                    $nominatim_post = microtime(true);
                                                    $nominatim_time = $nominatim_post - $nominatim_pre;
                                                    $visitors = getVisitors($wikiId);
                                                    $wikiFlag=0;
                                                    $nominatimFlag=0;
                                            ?>
                                                <tr onmouseover="promijeniBoju(this)">
                                                    <td>
                                                        <?php echo $result->getElementsByTagName('name')->item(0)->nodeValue; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if(!isset($wikiJson["thumbnail"])){
                                                                echo "Potrebno vrijeme dohvata:" . $wikiMedia_time . " sekundi" . "<br>";
                                                                echo "Nema rezultata";
                                                            } else {
                                                                $thumbnail = $wikiJson["thumbnail"]["source"];
                                                                echo "Potrebno vrijeme dohvata:" . $wikiMedia_time . " sekundi" . "<br>";
                                                            }
                                                        ?>
                                                        <img src=<?php echo $thumbnail ?> width="100" height="100" />
                                                    </td>
                                                    <!-- <td>
                                                        <?php 
                                                            if(!isset($wikiJson["extract"])){
                                                                echo "Nema rezultata";
                                                            } else {
                                                                echo substr($wikiJson["extract"], 0, 299) . "...";
                                                            }
                                                        ?>
                                                    </td> -->
                                                    <td>
                                                        <?php echo $result->getElementsByTagName('country')->item(0)->nodeValue; ?>
                                                    </td>
                                                    <!-- <td>
                                                        <?php
                                                            $link = $result->getElementsByTagName('county');
                                                            for ($i = 0; $i < $link->length; $i++) {
                                                                echo $result->getElementsByTagName('county')->item($i)->nodeValue;
                                                                echo '<br/>';
                                                            }
                                                        ?>
                                                    </td> -->
                                                    <!-- <td>
                                                        <?php 
                                                            if(!isset($wikiJson["coordinates"])){
                                                                echo "Potrebno vrijeme dohvata:" . $wikiMedia_time . " sekundi" . "<br>";
                                                                echo "Nema rezultata";
                                                            } else {
                                                                $coordinates = $wikiJson["coordinates"];
                                                                echo "Potrebno vrijeme dohvata:" . $wikiMedia_time . " sekundi" . "<br>";
                                                                echo "Geografska širina: " . $coordinates["lat"] . "<br>";
                                                                echo "Geografska duljina: " . $coordinates["lon"] . "<br>";
                                                                $wikiFlag=1;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if(!isset($mediaXml)){
                                                                echo "Potrebno vrijeme dohvata:" . $nominatim_time . " sekundi" . "<br>";
                                                                echo "Nema rezultata";
                                                            } else {
                                                                echo "Potrebno vrijeme dohvata:" . $nominatim_time . " sekundi" . "<br>";
                                                                echo "Geografska širina: " . $mediaXml->place[0]['lat'] . "<br>";
                                                                echo "Geografska duljina: " . $mediaXml->place[0]['lon'] . "<br>";
                                                                $nominatimFlag=1;
                                                            }
                                                        ?>
                                                    </td> -->
                                                    <!-- <td>
                                                        <?php echo $result->getElementsByTagName('area')->item(0)->nodeValue; ?>
                                                    </td> -->

                                                    <!-- <td>
                                                        <?php 
                                                            echo $result->getElementsByTagName('gov')->item(0)->getAttribute('unesco');
                                                        ?>
                                                    </td> -->
                                                    <!-- <td>
                                                        <?php 
                                                            echo $visitors;
                                                        ?>
                                                    </td> -->
                                                    <td>
                                                            <button type="button" onclick="detalji('<?php echo $id; ?>'); leafletTile('<?php if($wikiFlag === 0){echo 'null';} else {echo $coordinates['lat'];} ?>', '<?php if($wikiFlag === 0){echo 'null';} else {echo $coordinates['lon'];} ?>', '<?php echo $result->getElementsByTagName('name')->item(0)->nodeValue; ?>', '<?php if($nominatimFlag === 0){echo 'null';} else {echo $mediaXml->place[0]['lat'];} ?>', '<?php if($nominatimFlag === 0){echo 'null';} else {echo $mediaXml->place[0]['lon'];} ?>')" >Detalji</button>
                                                    </td>
                                                </tr>
                                                <?php }
                                                   
                                                ?>
                                            </xsl:for-each>
                                        </table>
                                        
                                    </div>
                                    <div class="nbsp">&#160;
                                    </div>
                                    <div id="map"></div>
                                </div>
                            </div>
                            <!-- end #content -->
                            <div id="sidebar">
                                <ul>
                                    <li>
                                        <h2>Kazalo</h2>
                                        <ul>
                                            <li>
                                                <a href="index.html">Početna</a>
                                            </li>
                                            <li>
                                                <a href="obrazac.html">Pretraživanje</a>
                                            </li>
                                            <li>
                                                <a href="https://www.fer.unizg.hr/predmet/or">Stranica predmeta</a>
                                            </li>
                                            <li>
                                                <a href="https://www.fer.unizg.hr/" target="_blank">FER</a>
                                            </li>
                                            <li>
                                                <a href="mailto:jakov.buratovic@fer.hr">E-pošta</a>
                                            </li>
                                            <li>
                                                <a href="podaci.xml">Tablica</a>
                                            </li>
                                            <li>
                                                <a href="podaci.nova.xml">Tablica Nova</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <!-- end #sidebar -->
                            <div id="details"></div>
                            <div class="nbsp">&#160;</div>
                            
                        </div>
                    </div>
                    <!-- end #page -->
                </div>
                <div id="footer">
                    <p>&#169;Autor: Jakov Buratović</p>
                </div>
                <!-- end #footer -->
            </div>
            
        </body>
</html>
