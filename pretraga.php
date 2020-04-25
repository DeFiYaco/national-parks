<?php
	error_reporting (E_ALL);
	include_once ('funkcije.php');
	
    $xmldoc = new DOMDocument();
    $xmldoc->load('podaci.xml');

    $xpathvar = new Domxpath($xmldoc);

    $queryResult = $xpathvar->query('//nationalPark/location[type="Prirodan"]');
    foreach($queryResult as $result) {
        echo $result->textContent;
    }
?>

<html>
            <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <title>Nacionalni parkovi</title>
                <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600' rel='stylesheet' type='text/css' />
                <link href="dizajn.css" rel="stylesheet" type="text/css" media="screen" />
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
                                            <table class="table">
                                                <tr>
                                                    <th>Naziv parka</th>
                                                    <th>Država</th>
                                                    <th>Predio</th>
                                                    <th>Površina (km<sup>2</sup>)
                                                    </th>
                                                    <th>UNESCO</th>
                                                </tr>
                                                <xsl:for-each select="data/nationalPark">
                                                    <tr>
                                                        <td>
                                                            <xsl:value-of select="name"/>
                                                        </td>
                                                        <td>
                                                            <xsl:value-of select="location/country"/>
                                                        </td>
                                                        <td>
                                                            <xsl:for-each select="location/counties/county">
                                                                <xsl:value-of select="."/>
                                                                <xsl:if test="position() != last()">
                                                                    <xsl:text>, </xsl:text>
                                                                </xsl:if>
                                                            </xsl:for-each>
                                                        </td>
                                                        <td>
                                                            <xsl:value-of select="location/area"/>
                                                        </td>

                                                        <td>
                                                            <xsl:value-of select="gov/@unesco"/>
                                                        </td>
                                                    </tr>
                                                </xsl:for-each>
                                            </table>
                                        </div>
                                        <div class="nbsp">&#160;
                                        </div>
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