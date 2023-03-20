<?php

namespace Orchardcity\LaravelSamcart;

class Endpoints
{
    public const BASE = 'https://api.samcart.com/';

    public static function getProductsURI(): string
    {
        return self::BASE . 'products';
    }

    public static function getByProductIdURI($id): string
    {
        return self::BASE . 'products/' . $id;
    }

    public static function getCustomersURI(): string
    {
        return self::BASE . 'customers';
    }

    public static function getByCustomerIdURI($id): string
    {
        return self::BASE . 'customers/' . $id;
    }

    public static function getAddressesByCustomerIdURI($id): string
    {
        return self::BASE . 'customers/' . $id . '/addresses';
    }

    public static function getChargesByCustomerIdURI($id): string
    {
        return self::BASE . 'customers/' . $id . '/charges';
    }

    public static function getOrdersByCustomerIdURI($id): string
    {
        return self::BASE . 'customers/' . $id . '/orders';
    }

    public static function getOrdersURI(): string
    {
        return self::BASE . 'orders';
    }

    public static function getByOrderIdURI($id): string
    {
        return self::BASE . 'orders/' . $id;
    }

    public static function getChargesByOrderIdURI($id): string
    {
        return self::BASE . 'orders/' . $id . '/charges';
    }

    public static function getCustomerByOrderIdURI($id): string
    {
        return self::BASE . 'orders/' . $id . '/customer';
    }
    public static function getAllRefundsURI(): string
    {
        return self::BASE . 'refunds';
    }

    public static function issueChargeRefundURI($id): string
    {
        return self::BASE . 'refunds/charges/' . $id ;
    }
}
