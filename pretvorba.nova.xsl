<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
    <xsl:template match="/">
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
                                            <h2>Nacionalni parkovi</h2>
                                            <table>
                                                <tr>
                                                    <th>Naziv parka</th>
                                                    <th>Država</th>
                                                    <th>Predio</th>
                                                    <th>Površina (km<sup>2</sup>)
                                                    </th>
                                                    <th>UNESCO</th>
                                                </tr>
                                                <xsl:for-each select="data/nationalPark">
                                                    <xsl:sort select="name"/>
                                                    <xsl:choose>
                                                        <xsl:when test="position() = last() - 2">
                                                            <tr bgcolor="#D3D3D3">
                                                                <td>
                                                                    <xsl:value-of select="name"/>
                                                                </td>
                                                                <td>
                                                                    <xsl:value-of select="location/country"/>
                                                                </td>
                                                                <td>
                                                                    <xsl:for-each select="location/counties/county">
                                                                        <xsl:sort select="."/>
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
                                                        </xsl:when>
                                                        <xsl:otherwise>
                                                            <tr>
                                                                <td>
                                                                    <xsl:value-of select="name"/>
                                                                </td>
                                                                <td>
                                                                    <xsl:value-of select="location/country"/>
                                                                </td>
                                                                <td>
                                                                    <xsl:for-each select="location/counties/county">
                                                                        <xsl:sort select="."/>
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
                                                        </xsl:otherwise>

                                                    </xsl:choose>

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
    </xsl:template>

</xsl:stylesheet>