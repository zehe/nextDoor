<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/16/15
 * Time: 1:40 PM
 */
function getaddress($address){
    // url encode the address
    $address = urlencode($address);

    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDxgb9HBTqkXFW1AQOJd8dbrtw41bJhOQk";

    // get the json response
    $resp_json = file_get_contents($url);

    // decode the json
    $resp = json_decode($resp_json, true);

    // response status will be 'OK', if able to geocode given address
    if($resp['status']=='OK'){

//        // get the important data
//        $lati = $resp['results'][0]['geometry']['location']['lat'];
//        $longi = $resp['results'][0]['geometry']['location']['lng'];
//        $formatted_address = $resp['results'][0]['formatted_address'];
//
//        // verify if data is complete
//        if($lati && $longi && $formatted_address){
//
//            // put the data in the array
//            $data_arr = array();
//
//            array_push(
//                $data_arr,
//                $lati,
//                $longi,
//                $formatted_address
//            );
//
//            return $data_arr;
        $data_arr = array();
        foreach ($resp["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                if (in_array("street_number", $address["types"])) {
                    array_push($data_arr,$address["long_name"]);
                }
            }
        }
        foreach ($resp["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                if (in_array("route", $address["types"])) {
                    array_push($data_arr,$address["long_name"]);
                }
            }
        }
        foreach ($resp["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                if (in_array("neighborhood", $address["types"])) {
                    array_push($data_arr,$address["long_name"]);
                }
            }
        }
        return $data_arr;
    }else{
            return false;
        }
}

?>