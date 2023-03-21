## PHP/Laravel Samcart Api Library

This is a php library for interacting with the Samcart API (Specifically written for Laravel)

Get your key and read the docs here:
https://developer.samcart.com/#section/Introduction

Use the postman collection I've created here: https://www.postman.com/helloenthusiast/workspace/orchardcity/collection/17590512-f73e7c43-5f87-4aea-a58f-26845d4ddfdf
for development. 

For updates, improvements, or fixes, please feel free to open a PR!

### To install:
`composer require orchardcity/laravel-samcart`

### To use:
```
$samcart = new Samcart($api_key);

$samcart->testConnection();
$samcart->getCustomers(50);
$samcart->getOrders();
$samcart->getProucts();

$samcart->getProductById('343357');
$samcart->getCustomerById('343357');
$samcart->getOrderById('343357');

$samcart->issueChargeRefund(134443)
```
