<?php

	#
	# query.php
	# Direct query engine for the Bungie API
	#

# Include global configuration and core functions
require_once 'config.php';

function pRawQuery($pQueryPath, &$pQueryReturn, $RequiresAuth=FALSE, $token = NULL) {
	$pQueryHandle = curl_init();
	$pQueryURL = PROTOCOL.API_SERVER.$pQueryPath;

    curl_setopt($pQueryHandle, CURLOPT_URL,$pQueryURL);
    curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
	if ($RequiresAuth) {
    	curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
    		'X-API-Key: '.API_KEY,
			'Origin: '.ORIGIN_HEADER,
			'User-Agent: '.USER_AGENT,
			'Authorization: Bearer '.$token
			]);
        } else {
    	curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
    		'X-API-Key: '.API_KEY,
			'Origin: '.ORIGIN_HEADER,
			'User-Agent: '.USER_AGENT
			]);
        }

        $pQueryReturn = curl_exec($pQueryHandle);
        curl_close($pQueryHandle);

		print_r($pQueryReturn);

    return  json_decode($pQueryReturn);


}

print_r(pRawQuery("/Platform/User/GetMembershipsById/4611686018430373576/2/", $pQueryResults, FALSE, NULL));
?>