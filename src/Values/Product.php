<?php

namespace Orchardcity\LaravelSamcart\Values;

class Product
{
    public int $id;
    public ?string $sku;
//    public string $internal_product_name;
    public string $product_name;
    public string $description;
    public string $currency;
    public string $price;
    public string $product_category;
    public string $pricing_type;
    public string $status;
    public string $taxes;
    public string $upsell_funnels;
    public string $slug;
    public string $custom_domain;
    public array $product_tags;
    public string $created_at;
    public string $updated_at;
    public string $archived_at;

    public function populateFromArray(array $array)
    {
        if (!isset($array['id'])){
            return null;
        }
        foreach ($array as $key => $value){
            $this->$key = $value;
        }
    }
}
