<?php

    function getNearestTownGeo($wikiId) {
        if (strstr($wikiId, 'National') !== false) {
            $url = "https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles="  . $wikiId . "&format=json";
            $flag=0;
            
        } else {
            $url = "https://hr.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles="  . $wikiId . "&format=json";
            $flag=1;
            
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);
        $encod = utf8_encode($data);
        $json = json_decode($encod, true);
        foreach ($json["query"]["pages"] as $page) {
            $unparsedInfobox = $page["revisions"][0]["*"];
            if($flag === 0){
                preg_match_all ("|nearest_city\s*=\s*\[\[[a-zA-Z\s]*\]\]|U", $unparsedInfobox, $array);
                if(isset($array[0][0])){
                    $cityRaw = explode("=", $array[0][0]);
                    $cityTrim = ltrim($cityRaw[1]);
                    $city = substr($cityTrim, 2, -2);
                    return nominatim($city); 
                }
                
            } else {
                preg_match_all ("|najbliži grad\s*=\s*\[\[[\p{L}\s]*\]\]|u", $unparsedInfobox, $array);
                if(isset($array[0][0])){
                    $cityRaw = explode("=", $array[0][0]);
                    $cityTrim = ltrim($cityRaw[1]);
                    $city = substr($cityTrim, 2, -2);
                    return nominatim($city);  
                }
            }
            
        }
        
    }

    function nominatim($address){
        $url = "https://nominatim.openstreetmap.org/search?q="  . urlencode($address) . "&format=xml";
        $context = stream_context_create(
            array(
              'http' => array(
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
              ),
          ));
        $data = file_get_contents($url, false, $context);
        return simplexml_load_string($data);
    }

    function getWikimedia($wikiId) {
        if (strstr($wikiId, 'National') !== false) {
            $url = "https://en.wikipedia.org/api/rest_v1/page/summary/" . $wikiId;
            
        } else {
            $url = "https://hr.wikipedia.org/api/rest_v1/page/summary/" . $wikiId;
            
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($data, true);
        return $json;
    }


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
            foreach ($_REQUEST['surface'] as $key) {
                if ($key == 1) {
                    $min = 0;
                    $max = 100;
                } elseif ($key == 2) {
                    $min = 101;
                    $max = 1000;
                } elseif ($key == 3) {
                    $min = 1001;
                    $max = 10000;
                } elseif ($key == 4) {
                    $min = 10001;
                    $max = 35000;
                }
                for ($i=$min; $i < $max; $i++) { 
                    $surfaceQuery[] = "location/area='" . $i . "'";
                }
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
