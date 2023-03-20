<?php

namespace Orchardcity\LaravelSamcart;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Orchardcity\LaravelSamcart\Values\Product;

class Samcart extends BaseService
{
    /**
     * @throws GuzzleException
     */
    public function getProducts(
        $limit = 250
    ): false|Collection
    {
        $results = $this->makePaginatedRequest(Endpoints::getProductsURI(), "GET", $limit);

        if (!$results){
            return false;
        }

        $productsCollection = new Collection();

        foreach ($results as $productArray)
        {
            $product = new Product();
            $product->populateFromArray($productArray);
            $productsCollection->add($product);
        }

        return $productsCollection;
    }

    /**
     * @throws GuzzleException
     */
    public function getOrders(): false|Collection
    {
        $results = $this->makeRequest(Endpoints::getOrdersURI());

        if (!$results){
            return false;
        }

        $productsCollection = new Collection();

        foreach ($results as $productArray)
        {
            $product = new Product();
            $product->populateFromArray($productArray);
            $productsCollection->add($product);
        }

        return $productsCollection;
    }
}
