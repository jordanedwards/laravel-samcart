<?php

namespace Orchardcity\LaravelSamcart\Values;

class OrderItem
{
    public int $id;
    public int $product_id;
    public ?int $subscription_id;
    public ?string $sku;
    public ?string $internal_product_name;
    public string $product_name;
    public int $charge_id;
    public string $pricing_type;
    public string $processor_transaction_id;
    public string $currency;
    public int $quantity;
    public string $status;
    public int $initial_price_subtotal;
    public int $initial_price_taxes;
    public int $initial_price_shipping;
    public int $initial_price_total;

    public function populateFromArray(array $array)
    {
        foreach ($array as $key => $value){
            if (property_exists(self::class, $key)){
                $this->$key = $value;
            }
        }
    }
}
