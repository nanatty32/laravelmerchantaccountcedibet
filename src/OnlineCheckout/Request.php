<?php

namespace nanatty32\HubtelMerchantAccount\OnlineCheckout;


class Request
{
    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * @var Business
     */
    public $business;

    /**
     * @var Actions
     */
    public $actions;

    /**
     * @var mixed
     */
    public $custom_data;

    public function __construct()
    {
        $this->invoice = new Invoice();
        $this->business = new Business();
        $this->actions = new Actions();
    }
}