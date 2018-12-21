<?php
define('HMRC_MODE', 'SANDBOX');	// values 'SANDBOX', 'LIVE'

$_base_endpoint 	= (HMRC_MODE ==='SANDBOX'? 'https://test-api.service.hmrc.gov.uk' : 'https://api.service.hmrc.gov.uk');
define('BASE_ENDPOINT', $_base_endpoint);

$client_Id 		= 'jECySkf8flOGIgpiah3TpH2mBAUa';
$client_secret 	= '496ceb8a-87ca-4e05-ad01-d2aa6f0a3ec7';

$_server_token 	= '28b127add1ff069fecff297a4d943c';
define('SERVER_TOKEN', $_server_token);

