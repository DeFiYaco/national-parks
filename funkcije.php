<?php
    function parser($input) {
        return	"translate(" . $input . ",  'abcdefghijklljmnnjopqrstuvwxyzšđčćž', 'ABCDEFGHIJKLLJMNNJOPQRSTUVWXYZŠĐČĆŽ')";
    }

    function customQuery() {
        $query = array();
        if (!empty($_REQUEST['name']))
            $query[] = 'contains(' . parser('name') . ', "' . mb_strtoupper($_REQUEST['name'], "UTF-8") . '")';
        if (!empty($_REQUEST['country']))
            $query[] = 'contains(' . parser('location/country') . ', "' . mb_strtoupper($_REQUEST['country'], "UTF-8") . '")';
        if (!empty($_REQUEST['county']))
            $query[] = 'contains(' . parser('location/counties/county') . ', "' . mb_strtoupper($_REQUEST['county'], "UTF-8") . '")';
        if (!empty($_REQUEST['type'])){
            if($_REQUEST['type'] == "fake"){
                $type="Uređen";
            } else
                $type="Prirodan";
            $query[] = 'contains(' . parser('location/@type') . ', "' . mb_strtoupper($type, "UTF-8") . '")';
        }

		if (!empty($_REQUEST['continent'])) {
			$drzavaQuery = array();
			foreach ($_REQUEST['continent'] as $continent) {
                if($continent == "Africa"){
                     $parsedContinent="Afrika";
                } elseif ($continent == "Antarctica") {
                    $parsedContinent="Antarktika";
                } elseif ($continent == "Australia") {
                    $parsedContinent="Australija";
                } elseif ($continent == "SouthAmerica") {
                    $parsedContinent="JužnaAmerika";
                } elseif ($continent == "NorthAmerica") {
                    $parsedContinent="SjevernaAmerika";
                } elseif ($continent == "Europe") {
                    $parsedContinent="Europa";
                } else {
                    $parsedContinent="Azija";
                }
				$drzavaQuery[] = "location/@continent='" . $parsedContinent . "'";
			}
		}
		if (!empty($drzavaQuery))
			$query[] =  "(" . implode( " or ", $drzavaQuery) . ")";

        if (!empty($_REQUEST['alt'])) {
            $altQuery = array();
            if ((int)$_REQUEST['alt'] <= 50) {
                $start = 0;
            } else {
                $start = (int)$_REQUEST['alt'] - 50;
            }
            for ($i=$start; $i < (int)$_REQUEST['alt'] + 51; $i++) { 
                $altQuery[] = "location/altitude='" . $i . "'";
            }
        }
        if (!empty($altQuery))
            $query[] =  "(" . implode( " or ", $altQuery) . ")";

        if (!empty($_REQUEST['latitude'])) {
            $latitude = $_REQUEST['latitude'];
            $latitude = explode(" ",$latitude);
            $query[] = 'contains(' . parser('location/geo/latitude/deg') . ', "' . $latitude[0] . '")';
            if (!empty($latitude[1])) {
                $query[] = 'contains(' . parser('location/geo/latitude/min') . ', "' . $latitude[1] . '")';
            }
            if (!empty($latitude[2])) {
                $query[] = 'contains(' . parser('location/geo/latitude/sec') . ', "' . $latitude[2] . '")';
            }
            if (!empty($latitude[3])) {
                $query[] = 'contains(' . parser('location/geo/latitude/@dir') . ', "' . $latitude[3] . '")';
            }
        }

        if (!empty($_REQUEST['longitude'])) {
            $longitude = $_REQUEST['longitude'];
            $longitude = explode(" ",$longitude);
            $query[] = 'contains(' . parser('location/geo/longitude/deg') . ', "' . $longitude[0] . '")';
            if (!empty($longitude[1])) {
                $query[] = 'contains(' . parser('location/geo/latitude/min') . ', "' . $latitude[1] . '")';
            }
            if (!empty($longitude[2])) {
                $query[] = 'contains(' . parser('location/geo/longitude/sec') . ', "' . $longitude[2] . '")';
            }
            if (!empty($longitude[3])) {
                $query[] = 'contains(' . parser('location/geo/longitude/@dir') . ', "' . $longitude[3] . '")';
            }
        }

        if (!empty($_REQUEST['surface'])) {
            $surfaceQuery = array();
            // TODO: multiple selections
            foreach ($_REQUEST['surface'] as $key) {
                echo $key;
            }
            if ((int)$_REQUEST['surface'] == 1) {
                $min = 0;
                $max = 100;
            } elseif ((int)$_REQUEST['surface'] == 2) {
                $min = 101;
                $max = 1000;
            } elseif ((int)$_REQUEST['surface'] == 3) {
                $min = 1001;
                $max = 10000;
            } elseif ((int)$_REQUEST['surface'] == 4) {
                $min = 10001;
                $max = 50000;
            }
            for ($i=$min; $i < $max; $i++) { 
                $surfaceQuery[] = "location/area='" . $i . "'";
            }
        }

        if (!empty($surfaceQuery))
            $query[] =  "(" . implode( " or ", $surfaceQuery) . ")";

        $xpathQuery = implode(" and ", $query);
		

		if(!empty($xpathQuery))
			return "/data/nationalPark[" . $xpathQuery . "]";

		else
			return "/data/nationalPark";
    }
