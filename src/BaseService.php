<?php

namespace Orchardcity\LaravelSamcart;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Orchardcity\LaravelSamcart\Config\V1\Endpoints;

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
            throw new AuthException('Api key not given');
        }

        $this->client = new Client();
    }

    // Unfortunately, Samcart doesn't have a route to check auth, so we just get one page of orders to
    // validate that auth and connection work
    /**
     * @throws GuzzleException
     * @throws AuthException
     */
    public function testConnection(): bool
    {
        if ($this->makeRequest(Endpoints::getOrdersURI(), "GET", [], 1)){
            return true;
        }
        return false;
    }

    /**
     * Use for retrieving a single record, records that aren't paginated, or only the first page of paginated models
     */
    /**
     * @param string $url
     * @param string $method POST, GET, PUT, PATCH, DELETE
     * @param array $args array, additional arguments for request
     *
     * @return false|array
     * @throws GuzzleException|AuthException
     */
    public function makeRequest(
        string $url,
        string $method = "GET",
        array $args = []): false|array
    {
        $request_body = json_encode($args);
        $headers = [
            'sc-api' => $this->api_key,
            'Accept' => 'application/json'
        ];

        if($method === "GET"){
            if($args) {
                $url .= '?' . http_build_query($args);
            }
            $request = new Request($method, $url, $headers);

        } else {
            // I.E. "POST"
            $request = new Request($method, $url, $headers, $request_body);
        }

        try {
            $response = $this->client->send($request, ['headers' => $headers]);

        } catch (ClientException $exception){

            if ($exception->getCode() == 401){
                throw new AuthException('Invalid Api Key');
            }

            if ($exception->getCode() == 429)
            {
                // Rate limiting encountered. Wait 5 seconds, then try again
                sleep(5);
                return $this->makeRequest($url, $method, $args);
            }
        }

        $responseContents = $response->getBody()->getContents();

        // If not between 200 and 300
        if (!preg_match("/^[2-3][0-9]{2}/", $response->getStatusCode())) {
            throw new \Exception('Call to samcart api returned status code: '. $response->getStatusCode());
        }

        $response_body = json_decode($responseContents, true);

        if($response_body) {
            return $response_body;
        }

        return false;
    }


    /**
     * Use this to return an array of records for the paginated models (https://developer.samcart.com/#section/Pagination)
     * I.e. Charges, Customers, Products, Refunds
     * @param string $url
     * @param string $method POST, GET, PUT, PATCH, DELETE
     * @param int $limit used for pagination, must be multiples of 100
     * @param array $args array, additional arguments for request
     * @return false|array
     * @throws GuzzleException
     * @throws AuthException
     */
    public function makePaginatedRequest(
        string    $url,
        string    $method = "GET",
        int       $limit = 200,
        array     $args = []
    ): false|array
    {
        $data = [];
        $response_body = $this->makeRequest(
            $url,
            $method,
            $args
        );
        if (!$response_body)
        {
            return $data;
        }

        $data = $response_body['data'];

        while (isset($response_body['pagination']['next'])
            AND sizeof($data) < $limit){

            $response_body = $this->makeRequest(
                $url,
                $method,
                $args
            );

            if($response_body) {
                $data = array_merge($data, $response_body['data']);
            }

        }

        return $data;
    }
}
