<?php
/**
 * Created by PhpStorm.
 
 */

namespace nanatty32\HubtelMerchantAccount;


use nanatty32\HubtelMerchantAccount\Helpers\SendsRequests;
use nanatty32\HubtelMerchantAccount\MobileMoney\Receive\Request as ReceiveMobileMoneyRequest;
use nanatty32\HubtelMerchantAccount\MobileMoney\Receive\Response as ReceiveMobileMoneyResponse;
use nanatty32\HubtelMerchantAccount\MobileMoney\Refund\Request as RefundMobileMoneyRequest;
use nanatty32\HubtelMerchantAccount\MobileMoney\Refund\Response as RefundMobileMoneyResponse;
use nanatty32\HubtelMerchantAccount\OnlineCheckout\Request as OnlineCheckoutRequest;
use nanatty32\HubtelMerchantAccount\OnlineCheckout\InvoiceStatusResponse as OnlineCheckoutInvoiceStatusResponse;

class MerchantAccount
{
    /** @var SendsRequests */
    protected $http;

    /**
     * @param SendsRequests $http
     * @internal param array $config
     */
    public function __construct(SendsRequests $http)
    {
        $this->http = $http;
    }

    /**
     * Receive mobile money
     *
     * @param ReceiveMobileMoneyRequest $request
     * @return ReceiveMobileMoneyResponse
     */
    public function receiveMobileMoney(ReceiveMobileMoneyRequest $request)
    {
        $response = $this->http->sendReceiveMobileMoneyRequest($request);
        return $response;
        // return new ReceiveMobileMoneyResponse(...$response);
    }

    /**
     * Refund mobile money
     * @param RefundMobileMoneyRequest $request
     * @return RefundMobileMoneyResponse
     */
    public function refundMobileMoney(RefundMobileMoneyRequest $request)
    {
        $response = $this->http->sendRefundMobileMoneyRequest($request);
        return new RefundMobileMoneyResponse(...$response);
    }

    /**
     * Online checkout
     *
     * @param OnlineCheckoutRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function onlineCheckout(OnlineCheckoutRequest $request)
    {
        $checkout_url = $this->http->sendOnlineCheckoutRequest($request);
        return header('Location: ' . $checkout_url);
    }

    /**
     * Check online checkout invoice status
     *
     * @param string $token
     * @return OnlineCheckoutInvoiceStatusResponse
     * @throws \Exception
     */
    public function checkInvoiceStatus($token)
    {
        $response = $this->http->sendCheckInvoiceStatusRequest($token);
        return $response;
    }

}