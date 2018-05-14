<?php

return [

	'client_id' 	=> env('BUFFER_API_KEY', false),
	'client_secret' => env('BUFFER_API_SECRET', false),
	'access_token' 	=> env('BUFFER_ACCESS_TOKEN', false),
	'redirect_uri' 	=> env('BUFFER_REDIRECT_URI', false),
	'timeout'		=> env('BUFFER_TIMEOUT', 6.0),
	'retries'		=> env('BUFFER_RETRIES', 10)

];
