<?php

namespace Orchardcity\LaravelSamcart;

use Endpoints;
use GuzzleHttp\Exception\GuzzleException;

class Samcart extends BaseService
{

    /**
     * @throws GuzzleException
     */
    public function getProducts(): false|array
    {
        $results = $this->makeRequest(Endpoints::getProductsURI());
        return $results;
    }
}
