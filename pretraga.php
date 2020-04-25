<?php
	error_reporting (E_ALL);
	include_once ('funkcije.php');
	
	$dom = new DOMDocument();
  	$dom->load('podaci.xml');

  	$xp = new DOMXPath($dom);

  	$upit = upit();
	print_r($upit);
  	$queryResult = $xp->query($upit);
?>