<?php
/**
 * Created by PhpStorm.
 
 
 */

namespace nanatty32\HubtelMerchantAccount\MobileMoney\Receive;


class Request
{
    public $CustomerName = '';

    public $CustomerEmail = '';

    public $CustomerMsisdn = '';

    public $Channel = '';

    public $Amount = 0;

    public $PrimaryCallbackURL = '';

    public $SecondaryCallbackURL = '';

    public $ClientReference = '';

    public $Description = '';

    public $Token = '';

    public $FeesOnCustomer = false;

}