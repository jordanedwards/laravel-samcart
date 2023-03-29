<?php

namespace Orchardcity\LaravelSamcart;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Orchardcity\LaravelSamcart\Values\Charge;
use Orchardcity\LaravelSamcart\Values\Order;
use Orchardcity\LaravelSamcart\Values\Product;
use Orchardcity\LaravelSamcart\Values\Customer;
use Orchardcity\LaravelSamcart\Config\V1\Endpoints;

class Samcart extends BaseService
{
    // Retrieve Lists
    /**
     * @param int $limit
     * @return false|Collection
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getProducts(int $limit = 250): false|Collection
    {
        return $this->getListOf(get_class(new Product()), Endpoints::getProductsURI(), $limit);
    }

    /**
     * @param int $limit
     * @return false|Collection
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getOrders(int $limit = 100): false|Collection
    {
        return $this->getListOf(get_class(new Order()), Endpoints::getOrdersURI(), $limit);
    }

    /**
     * @param int $limit
     * @return false|Collection
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getCustomers(int $limit = 250): false|Collection
    {
        return $this->getListOf(get_class(new Customer()), Endpoints::getCustomersURI(), $limit);
    }

    // Retrieve individual records

    /**
     * @param int $id
     * @return mixed|null
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getProductById(int $id): mixed
    {
        return $this->getObjectById(get_class(new Product()), Endpoints::getByProductIdURI($id));
    }

    /**
     * @param int $id
     * @return mixed|null
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getCustomerById(int $id): mixed
    {
        return $this->getObjectById(get_class(new Customer()), Endpoints::getByCustomerIdURI($id));
    }

    /**
     * @param int $id
     * @return mixed|null
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getOrderById(int $id): mixed
    {
        return $this->getObjectById(get_class(new Order()), Endpoints::getByOrderIdURI($id));
    }

    /**
     * @param int $id
     * @return false|Collection
     * @throws AuthException
     * @throws GuzzleException
     */
    public function getChargesByOrderById(int $id): false|Collection
    {
        return $this->getListOf(get_class(new Charge()), Endpoints::getChargesByOrderIdURI($id));
    }


    /**
     * @param int $order_id
     * @return bool
     * @throws GuzzleException
     * @throws AuthException
     */
    public function issueOrderRefund(int $order_id): bool
    {
        // Look up charges that belong to this order
        $list = $this->getChargesByOrderById($order_id);

        /** @var Charge $charge */
        foreach ($list as $charge){
            // Refund each charge
            if (!$this->issueChargeRefund($charge->id)){
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function issueChargeRefund(int $id): bool
    {
        try {
            $result = $this->makeRequest(Endpoints::issueChargeRefundURI($id),"POST");
            if (!$result){
                return false;
            }

        } catch (GuzzleException $exception){
            if ($exception->getCode() == '409')
            {
                // This charge has already been refunded, it's ok to return as successful
                return true;
            }
            // This needs work; There could be other guzzle exceptions that we should handle
            // within the context of a guzzle exception, rather than generalizing
            throw new Exception($exception->getMessage(), $exception->getCode());
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
