<?php

namespace Orchardcity\LaravelSamcart;

use AuthException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class BaseService
{
    protected bool $active = false;
    protected string $api_key;
    /**
     * Guzzle Http Client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * @param string|null $api_key
     * @throws AuthException
     */
    public function __construct(
        string $api_key = null)
    {
        if ($api_key) {
            $this->api_key = $api_key;
        } else {
            throw new \AuthException('Api key not given');
        }

        $this->client = new Client();
    }

    public function testConnection(): bool
    {
        return false;
    }

    /**
     * @param string $url
     * @param string $method POST, GET, PUT, PATCH, DELETE
     * @param array $args array, additional arguments for request
     *
     * @return false|array
     * @throws GuzzleException
     */
    public function makeRequest(
        string $url,
        string $method = 'GET',
        array $args = []): false|array
    {
        $request_body = json_encode($args);

        if( $method === "GET" ){
            if($args) {
                $url .= '?' . http_build_query($args);
            }
            $request = new Request($method, $url);
        } else {
            // I.E. "POST"
            $request = new Request($method, $url, array(
                'Content-Type'   => 'application/json',
                'Content-Length' => strlen($request_body)
            ), $request_body);
        }

        $response = $this->client->send($request, [
            'exceptions' => false
        ]);

        $responseContents = $response->getBody()->getContents();
        $status_code = $response->getStatusCode();

        if ($status_code == 429)
        {
            // Rate limiting encountered. Wait 30 seconds, then try again
            sleep(10);
            $this->makeRequest($url, $method, $args);
            exit();
        }

        // If not between 200 and 300
        if (!preg_match("/^[2-3][0-9]{2}/", $status_code)) {
            throw new Exception('Call to samcart api returned status code: '. $status_code);
        }

        $response_body = json_decode($responseContents, false);

        if($response_body) {
            return $response_body;
        }

        return false;
    }
}
