<?php

namespace Orchardcity\LaravelSamcart;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Orchardcity\LaravelSamcart\Values\Order;
use Orchardcity\LaravelSamcart\Values\Product;
use Orchardcity\LaravelSamcart\Values\Customer;
use Orchardcity\LaravelSamcart\Config\V1\Endpoints;

class Samcart extends BaseService
{
    // Retrieve Lists
    /**
     * @throws GuzzleException|AuthException
     */
    public function getProducts($limit = 250): false|Collection
    {
        return $this->getListOf(get_class(new Product()), Endpoints::getProductsURI(), $limit);
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function getOrders($limit = 100): false|Collection
    {
        return $this->getListOf(get_class(new Order()), Endpoints::getOrdersURI(), $limit);
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function getCustomers($limit = 250): false|Collection
    {
        return $this->getListOf(get_class(new Customer()), Endpoints::getCustomersURI(), $limit);
    }

    // Retrieve individual records

    /**
     * @throws GuzzleException|AuthException
     */
    public function getProductById($id)
    {
        return $this->getObjectById(get_class(new Product()), Endpoints::getByProductIdURI($id));
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function getCustomerById($id)
    {
        return $this->getObjectById(get_class(new Customer()), Endpoints::getByCustomerIdURI($id));
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function getOrderById($id)
    {
        return $this->getObjectById(get_class(new Order()), Endpoints::getByOrderIdURI($id));
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function issueChargeRefund($id): bool
    {
        $result = $this->makeRequest(Endpoints::issueChargeRefundURI($id),"POST");
        if (!$result){
            return false;
        }

        return true;
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function getListOf(string $listObjectClassName, string $url, int $limit = 250): bool|Collection
    {
        $results = $this->makePaginatedRequest($url, "GET", $limit);

        if (!$results){
            return false;
        }

        $collection = new Collection();

        foreach ($results as $resultsArray)
        {
            $record = new $listObjectClassName();
            $record->populateFromArray($resultsArray);
            $collection->add($record);
        }

        return $collection;
    }

    /**
     * @throws GuzzleException|AuthException
     */
    public function getObjectById(string $listObjectClassName, string $url)
    {
        $result = $this->makeRequest($url,"GET");
        if (!$result){
            return null;
        }

        $record = new $listObjectClassName();
        $record->populateFromArray($result);
        return $record;
    }
}
