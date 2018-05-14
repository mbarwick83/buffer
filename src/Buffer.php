<?php

namespace Mbarwick83\Buffer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class Buffer
{
    const API_HOST = 'https://api.bufferapp.com/';
    const LOGIN_HOST = 'https://bufferapp.com/';

    protected $client;
    protected $client_key;
    protected $client_secret;
    protected $redirect_uri;
    protected $timeout;
    protected $retries;

    public function __construct()
    {
        $this->client_key = config('buffer.client_id');
        $this->client_secret = config('buffer.client_secret');
        $this->redirect_uri = config('buffer.redirect_uri');
        $this->timeout = config('buffer.timeout');
        $this->retries = config('buffer.retries');

        $handlerstack = HandlerStack::create();
        $handlerstack->push(Middleware::retry(function($retry, $request, $value, $reason)
        {
            if ($value !== null) { return false; }
            return $retry < $this->retries;
        }));

    	$this->client = new Client([
            'handler' => $handlerstack,
    	    'base_uri' => self::API_HOST,
    	    'timeout'  => $this->timeout,
    	]);
    }

    /**
    * Get authorization url for oauth
    *
    * @return String
    */
    public function getLoginUrl()
    {
	   return $this->url('oauth2/authorize', self::LOGIN_HOST);
    }

    /**
    * Get user's access token and basic info
    *
    * @param string $code
    */
    public function getAccessToken($code)
    {
	   return $this->post('1/oauth2/token.json', true, ['code' => $code]);
    }

    /**
     * Get user details from access token
     */
    public function getUserDetails($access_token)
    {
        $account = $this->get('1/user.json', ['access_token' => $access_token]);
        return array_merge($account, ['access_token' => $access_token]);
    }

    /**
    * Make URLs for user browser navigation.
    *
    * @param string $path
    * @param string $host [base url]
    * @param array  $parameters
    *
    * @return string
    */
    protected function url($path, $host, array $parameters = null)
    {
    	$query = [
            'client_id' => $this->client_key,
    	    'redirect_uri' => $this->redirect_uri,
    	    'response_type' => 'code'
    	];

        if ($parameters)
            $query = array_merge($query, $parameters);

        $query = http_build_query($query);

        return sprintf('%s%s?%s', $host, $path, $query);
    }

    /**
    * Make POST calls to the API
    *
    * @param  string  $path
    * @param  boolean $authorization [Use access token query params]
    * @param  array   $parameters    [Optional query parameters]
    * @return Array
    */
    public function post($path, $authorization = false, array $parameters)
    {
    	$query = [];

    	if ($authorization)
    	    $query = [
    	        'client_id' => $this->client_key,
    	    	'client_secret' => $this->client_secret,
    	    	'redirect_uri' => $this->redirect_uri,
    	    	'grant_type' => 'authorization_code',
    	    ];

    	if ($parameters)
            $query = array_merge($query, $parameters);

        try {
    	    $response = $this->client->request('POST', $path, [
    	        'form_params' => $query
    	    ]);

            return $this->toArray($response);
    	}
    	catch (ClientException $e) {
    	    return $this->toArray($e->getResponse());
        }
    }

    /**
    * Make GET calls to the API
    *
    * @param  string $path
    * @param  array  $parameters [Query parameters]
    * @return Array
    */
    public function get($path, array $parameters)
    {
        try {
    	    $response = $this->client->request('GET', $path, [
    	        'query' => $parameters
    	    ]);

            return $this->toArray($response);
    	}
    	catch (ClientException $e) {
    	    return $this->toArray($e->getResponse());
    	}
    }

    /**
    * Convert API response to array
    *
    * @param  Object $response
    * @return Array
    */
    protected function toArray($response)
    {
    	return json_decode($response->getBody()->getContents(), true);
    }
}
