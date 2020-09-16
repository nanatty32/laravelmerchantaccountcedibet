# Hubtel Merchant Account integration (Pure Laravel CediBet)
![ico-version]



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
Nanatty32\HubtelMerchantAccount\ServiceProvider::class,
```
And add a new line to the `aliases` array:
```php
'aliases' => [
      ...
      'HubtelMerchantAccount' => Nanatty32\HubtelMerchantAccount\HubtelMerchantAccountFacade::class,
      ...
 ]
```

## Using Online Checkout feature

Let's say you are using this feature from a controller method, you can do it like so:

```php
namespace App\Http\Controllers;

use Nanatty32\HubtelMerchantAccount\OnlineCheckout\Item;
use HubtelMerchantAccount;
use App\Stake;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
  
  ...
  
  public function payOnline(Request $request)
    {
        $stake = Stake::where('session_id', $request->session()->getId())->latest()->first();

        if (!$stake) {
            abort(404, 'Invalid stake!');
        }

        // Initiate online checkout
        $ocRequest = new \Nanatty32\HubtelMerchantAccount\OnlineCheckout\Request();
        $ocRequest->invoice->description = "Invoice description";
        $ocRequest->invoice->total_amount = $stake->total;
        $ocRequest->business->name = "CediBet";
        $ocRequest->business->logo_url = asset('/img/logo.png');// Can be changed by developers 
        $ocRequest->business->phone = "0243XXXXXX";
        $ocRequest->business->postal_address = "P. O. Box ******";
        $ocRequest->business->tagline = "Best online Betting Company In Ghana";
        $ocRequest->business->website_url = env('APP_URL');
        $ocRequest->actions->cancel_url = url('/checkout/done');
        $ocRequest->actions->return_url = url('/checkout/done');

        foreach ($stake->items as $item) {

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
use Nanatty32\HubtelMerchantAccount\MobileMoney\Receive\Request as ReceiveMobileMoneyRequest;

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
        $request->Description = "Bet Amount";
        $request->PrimaryCallbackURL = "https://my-application.com/handle" . $this->transaction->id;
        $request->SecondaryCallbackURL = "https://my-application.com/handle/" . $this->transaction->id;
        $response = HubtelMerchantAccount::receiveMobileMoney($request);
    }
```

## Configuration

The defaults are set in `config/hubtelmerchantaccount.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:
```sh
$ php artisan vendor:publish --provider="Nanatty32\HubtelMerchantAccount\ServiceProvider"
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
     * business details
     */
    "business" => [
        "name" => env('APP_NAME')
    ]
];
```
    
## License

Released under the MIT License, see [LICENSE](LICENSE).

[ico-version]: https://img.shields.io/github/release/nanatty32/laravelhubtelmerchantaccountcedibet.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nanatty32/laravelhubtelmerchantaccountcedibet.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nanatty32/laravelhubtelmerchantaccountcedibet.svg?style=flat-square



