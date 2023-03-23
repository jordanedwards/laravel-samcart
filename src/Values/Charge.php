<?php

namespace Orchardcity\LaravelSamcart\Values;

use Illuminate\Support\Collection;

class Charge extends ListObject
{
    public int $id;
    public int $customer_id;
    public ?int $affiliate_id;
    public int $order_id;
    public ?int $subscription_rebill_id;
    public bool $test_mode = false;
    public string $processor_name;
    public string $processor_transaction_id;
    public string $currency;
    public ?string $charge_refund_status;
    public ?int $card_used;
    public string $order_date;
    public string $created_at;
    public int $total;

    public function populateFromArray(array $array)
    {
        if (!isset($array['id'])){
            return null;
        }

        foreach ($array as $key => $value){
            if (property_exists(self::class, $key)){
                $this->$key = $value;
            }
        }
    }
}
