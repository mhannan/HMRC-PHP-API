<?php
require_once('./hmrc_config.php');

function HMRC_helloWorld(){
	$url = "/hello/world";
    return HMRC_Http_Get($url);
}


function HMRC_createUser(){
	// https://developer.service.hmrc.gov.uk/api-documentation/docs/api/service/api-platform-test-user/1.0#_create-a-test-user-which-is-an-individual_post_accordion
	$url = "/create-test-user/individuals";
	$fields = array("serviceNames" => 
					array("national-insurance","self-assessment","mtd-income-tax","customs-services"));
    return HMRC_Http_Post($url, $fields);
}

function HMRC_Http_Get($url, $req_params = null){
	$curl = curl_init();
	$params = array(				
		CURLOPT_URL =>  BASE_ENDPOINT.$url,
		CURLOPT_SSL_VERIFYPEER	=> true,
                      // ref: https://stackoverflow.com/questions/24611640/curl-60-ssl-certificate-unable-to-get-local-issuer-certificate
		CURLOPT_CAINFO	=> "C:\wamp64\www\hmrc-api\cacert.pem",	// change this to your system path where 'cacert.pem' is located. 
		CURLOPT_RETURNTRANSFER => true,		
		CURLOPT_FAILONERROR	=> true,	
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,	
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
		
		CURLOPT_NOBODY => false, 
		CURLOPT_HTTPHEADER => array(	
			"cache-control: no-cache",
			"accept-encoding: gzip, deflate",
			"content-type: application/json",		// HMRC specific header fields... => application/vnd.hmrc.[version]+[content-type]
			"accept: application/vnd.hmrc.1.0+json",
			"server_token: ".SERVER_TOKEN			// from config.php
		),

	);
				// either set Curl_setopt() individually or set as Array. Params List Ref: http://php.net/manual/en/function.curl-setopt.php
	curl_setopt_array($curl, $params); 

  echo '<pre>'; print_r($params); echo '</pre>';
  
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #01: " . $err;
	} else {
		$response = json_decode($response, true);    
		if(array_key_exists("access_token", $response)) return $response;
		if(array_key_exists("error", $response)) echo $response["error_description"];
		echo "cURL Error #02: Something went wrong! Please contact admin.";
	}
}

function HMRC_Http_Post($url, $req_post_fields){
	$curl = curl_init();
	$params = array(				
		CURLOPT_URL =>  BASE_ENDPOINT.$url,
	
		CURLOPT_SSL_VERIFYPEER	=> true,
		        // ref: https://stackoverflow.com/questions/24611640/curl-60-ssl-certificate-unable-to-get-local-issuer-certificate
		CURLOPT_CAINFO	=> "C:\wamp64\www\hmrc-api\cacert.pem",	// change this to your system path where 'cacert.pem' is located. 
		CURLOPT_RETURNTRANSFER => true,	
		CURLOPT_FAILONERROR	=> true,	
		CURLOPT_MAXREDIRS => 10,	
		CURLOPT_TIMEOUT => 30,		
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
		CURLOPT_CUSTOMREQUEST => "POST",	
		CURLOPT_NOBODY => false, 
		CURLOPT_HTTPHEADER => array(	
			"cache-control: no-cache",
			"accept-encoding: gzip, deflate",
			"content-type: application/json",		// HMRC specific header fields... => application/vnd.hmrc.[version]+[content-type]
			"accept: application/vnd.hmrc.1.0+json",
			"server_token: ".SERVER_TOKEN			// from config.php
		),
		CURLOPT_POSTFIELDS => json_encode($req_post_fields)
	);
				// either set Curl_setopt() individually or set as Array. Params List Ref: http://php.net/manual/en/function.curl-setopt.php
	curl_setopt_array($curl, $params); 
  
	echo '<pre>'; print_r($params); echo '</pre>';
	
  $response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #01: " . $err;
	} else {
		$response = json_decode($response, true);    
		if(array_key_exists("access_token", $response)) return $response;
		if(array_key_exists("error", $response)) echo $response["error_description"];
		echo "cURL Error #02: Something went wrong! Please contact admin.";
	}
}
