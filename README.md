# Hubtel Merchant Account integration (Pure Laravel CediBet)
[![Latest Release on GitHub][ico-version]][link-packagist]



Based on https://developers.hubtel.com/documentations/merchant-account-api

## About

The `laravel-hubtel-merchant-account` package allows you to accept and process payments using [Hubtel Merchant Account API](https://developers.hubtel.com/documentations/merchant-account-api) directly in your Laravel application.

## Features

* Receive mobile money
* Send mobile money
* Check status of transaction
* Online checkout

## Installation

Require the `nanatty32/laravelhubtelmerchantaccountcedibet` package in your `composer.json` and update your dependencies:
```sh
$ composer require nanatty32/laravelhubtelmerchantaccountcedibet
```
If you're using Laravel 5.5, this is all there is to do.

Should you still be on older versions of Laravel, the final steps for you are to add the service provider of the package and alias the package. To do this open your `config/app.php` file.

Add the HubtelMerchantAccount\ServiceProvider to your `providers` array:
```php
nanatty32\HubtelMerchantAccount\ServiceProvider::class,
```
And add a new line to the `aliases` array:
```php
'aliases' => [
      ...
      'HubtelMerchantAccount' => nanatty32\HubtelMerchantAccount\HubtelMerchantAccountFacade::class,
      ...
 ]
```

## Using Online Checkout feature

Let's say you are using this feature from a controller method, you can do it like so:

```php
namespace App\Http\Controllers;

use nanatty32\HubtelMerchantAccount\OnlineCheckout\Item;
use HubtelMerchantAccount;
use App\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
  
  ...
  
  public function payOnline(Request $request)
    {
        $order = Order::where('session_id', $request->session()->getId())->latest()->first();

        if (!$order) {
            abort(404, 'Invalid order!');
        }

        // Initiate online checkout
        $ocRequest = new \nanatty32\HubtelMerchantAccount\OnlineCheckout\Request();
        $ocRequest->invoice->description = "Invoice description";
        $ocRequest->invoice->total_amount = $order->total;
        $ocRequest->store->name = "CediBet";
        $ocRequest->store->logo_url = asset('/img/logo.png');// Can be changed by developers 
        $ocRequest->store->phone = "0546XXXXXX";
        $ocRequest->store->postal_address = "P. O. Box ******";
        $ocRequest->store->tagline = "Best online Betting Company In Ghana";
        $ocRequest->store->website_url = env('APP_URL');
        $ocRequest->actions->cancel_url = url('/checkout/done');
        $ocRequest->actions->return_url = url('/checkout/done');

        foreach ($order->items as $item) {

            $invoiceItem = new Item();
            $invoiceItem->name = $item->product_name;
            $invoiceItem->description = "";
            $invoiceItem->quantity = $item->quantity;
            $invoiceItem->unit_price = $item->price;
            $invoiceItem->total_price = $item->price * $item->quantity;

            $ocRequest->invoice->addItem($invoiceItem);
        }
       
        HubtelMerchantAccount::onlineCheckout($ocRequest);
    }
```

## Receive Mobile Money

Here is how you request mobile money payment from the controller method:
```php
namespace App\Http\Controllers;

use HubtelMerchantAccount;
use nanatty32\HubtelMerchantAccount\MobileMoney\Receive\Request as ReceiveMobileMoneyRequest;

class CheckoutController extends Controller
{
  
  ...
  
  public function payOnline(Request $request)
    {
        $request = new ReceiveMobileMoneyRequest();
        $request->Amount = $this->transaction->amount;
        $request->Channel = $this->transaction->channel;
        $request->CustomerMsisdn = $this->transaction->mobile_wallet_number;
        $request->CustomerName = "N/A";
        $request->Description = "N/A ";
        $request->PrimaryCallbackURL = "https://my-application.com/handle" . $this->transaction->id;
        $request->SecondaryCallbackURL = "https://my-application.com/handle/" . $this->transaction->id;
        $response = HubtelMerchantAccount::receiveMobileMoney($request);
    }
```

## Configuration

The defaults are set in `config/hubtelmerchantaccount.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:
```sh
$ php artisan vendor:publish --provider="nanatty32\HubtelMerchantAccount\ServiceProvider"
```

    
```php
return [

    /**
     * Merchant account number
     */
    "account_number" => env('HUBTEL_MERCHANT_ACCOUNT_NUMBER'),

    /**
     * Login credentials for hubtel api
     *
     */
    "api_key" => [
        "client_id" => env('HUBTEL_MERCHANT_ACCOUNT_CLIENT_ID'),
        "client_secret" => env('HUBTEL_MERCHANT_ACCOUNT_CLIENT_SECRET')
    ],

    /**
     * Store details
     */
    "store" => [
        "name" => env('APP_NAME')
    ]
];
```
    
## License

Released under the MIT License, see [LICENSE](LICENSE).

[ico-version]: https://img.shields.io/github/release/nanatty32/laravelhubtelmerchantaccountcedibet.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square



# laravelmerchantaccountcedibet
