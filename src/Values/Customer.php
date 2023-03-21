<?php

namespace Orchardcity\LaravelSamcart\Values;

class Customer extends ListObject
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public ?string $phone;
    public ?int $lifetime_value;
    public array $addresses;
    public string $created_at;
    public string $updated_at;

    public function populateFromArray(array $array)
    {
        foreach ($array as $key => $value){
            if (property_exists(self::class, $key)){
                $this->$key = $value;
            }
        }
    }
}
