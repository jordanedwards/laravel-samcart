<?php

namespace Orchardcity\LaravelSamcart\Values;

use Illuminate\Support\Collection;

class Order extends ListObject
{
    public int $id;
    public int $customer_id;
    public bool $test_mode;
    public string $order_date;
    public Collection $cart_items;
    public int $subtotal;
    public int $discount;
    public int $taxes;
    public int $shipping;
    public int $total;
    public ?int $card_used;
    public string $processor_name;

    /**
     * @param array $array
     * @return void|null
     */
    public function populateFromArray(array $array)
    {
        if (!isset($array['id'])){
            return null;
        }

        foreach ($array as $key => $value){
            if ($key == 'cart_items'){
                continue;
            }
            if (property_exists(self::class, $key)){
                $this->$key = $value;
            }
        }

        $this->cart_items = new Collection();
        foreach ($array['cart_items'] as $key => $value){
            $record = new OrderItem();

            if (property_exists(OrderItem::class, $key)){
                $record->$key = $value;
            }
            $this->cart_items->add($record);
        }
    }
}
