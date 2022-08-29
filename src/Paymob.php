<?php

namespace MG\PayMob;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class Paymob
{
    const URL = 'https://accept.paymob.com/api';

    /**
     * Paymob Authentication
     *
     * @return array
     */
    public function auth(): array
    {
        // Request body
        $json = [
            'api_key' => config('paymob.auth.api_key'),
        ];

        // Send curl
        $response =  Http::post(
            self::URL.'/auth/tokens',
            $json
        );

        return $response->json();
    }

    /**
     * Send order to paymob servers
     *
     * @param string $token
     * @param bool $deliveryNeeded
     * @param int $amountCents
     * @param array $items
     * @return array
     */
    public function makeOrder(string $token, bool $deliveryNeeded, int $amountCents, array $items): array
    {
        $json = [
            'auth_token' => $token,
            'delivery_needed' => $deliveryNeeded,
            'amount_cents' => $amountCents,
            'items' => $items,
        ];

        $response = Http::post(
            self::URL.'/ecommerce/orders',
            $json
        );

        return $response->json();
    }

    /**
     * Get Payment key to load iframe on paymob servers
     *
     * @param string $token
     * @param int $amountCents
     * @param int $expiration
     * @param int $orderId
     * @param array $billingData
     * @param string $currency
     * @return array
     */
    public function getPaymentKey(string $token, int $amountCents, int $expiration, int $orderId, array $billingData, string $currency): array
    {
        $integrationId = config('paymob.auth.integration_id');
        
        $json = [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => $expiration,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => $currency,
            'integration_id' => $integrationId
        ];

        $response = Http::post(
            self::URL.'/acceptance/payment_keys',
            $json
        );

         return $response->json();
    }

    /**
     * Make payment for API (moblie clients).
     * Return iframe_url
     * 
     * @param string $paymentToken
     * @return string
     */
    public function makePayment(array $data): string
    {
        // step 1 -> Authentication
        $authResponse = $this->auth();
        $authToken = $authResponse['token'];

        // step 2 -> Order Registration
        $deliveryNeeded = (isset($data['delivery_needed']) && $data['delivery_needed'])  ? $data['delivery_needed'] : false;
        $amountCents = (isset($data['amount_cents']) && $data['amount_cents'])  ? $data['amount_cents'] : 0;
        $items = (isset($data['items']) && $data['items'])  ? $data['items'] : [];
        
        $orderResponse = $this->makeOrder($authToken, $deliveryNeeded, $amountCents, $items);

        // step 3 => Get Payment Key
        $expiration = (isset($data['expiration']) && $data['expiration'])  ? $data['expiration'] : 3600;
        $merchantOrderId = (isset($data['merchant_order_id']) && $data['merchant_order_id'])  ? $data['merchant_order_id'] : null;
        $billingData = (isset($data['billing_data']) && $data['billing_data'])  ? $data['billing_data'] : [];
        $currency = (isset($data['currency']) && $data['currency'])  ? $data['currency'] : 'EGP';
        $orderId = $orderResponse['id'];

        $paymentKeyResponse = $this->getPaymentKey($authToken, $amountCents, $expiration, $orderId, $billingData, $currency);

        // create iframe url
        $iframeId = config('paymob.auth.iframe_id');
        $paymentToken = $paymentKeyResponse['token'];
        $iframeUrl = 'https://accept.paymobsolutions.com/api/acceptance/iframes/'. $iframeId .'?payment_token='.$paymentToken;

        return $iframeUrl;
    }

    /**
     * Capture authed order.
     *
     * @param string $token
     * @param int $transactionId
     * @param int  amount
     * @return array
     */
    public function capture($token, $transactionId, $amount): array
    {
        return [];
    }

    /**
     * Get paymob all orders.
     *
     * @param string $authToken
     * @param string $page
     * @return Response
     */
    public function getOrders($authToken, $page = 1): Response
    {
    }

    /**
     * Get paymob order.
     *
     * @param  string  $authToken
     * @param  int  $orderId
     * @return Response
     */
    public function getOrder($authToken, $orderId): Response
    {

    }

    /**
     * Get Paymob all transactions.
     *
     * @param  string  $authToken
     * @param  string  $page
     * @return Response
     */
    public function getTransactions($authToken, $page = 1): Response
    {

    }

    /**
     * Get Paymob transaction.
     *
     * @param  string  $authToken
     * @param  int  $transactionId
     * @return Response
     */
    public function getTransaction($authToken, $transactionId): Response
    {

    }
}
