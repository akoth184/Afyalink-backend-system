<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortcode;
    protected $passkey;
    protected $environment;
    protected $callbackUrl;

    public function __construct()
    {
        $this->consumerKey = config('mpesa.consumer_key', env('MPESA_CONSUMER_KEY'));
        $this->consumerSecret = config('mpesa.consumer_secret', env('MPESA_CONSUMER_SECRET'));
        $this->shortcode = config('mpesa.shortcode', env('MPESA_SHORTCODE'));
        $this->passkey = config('mpesa.passkey', env('MPESA_PASSKEY'));
        $this->environment = config('mpesa.environment', env('MPESA_ENVIRONMENT', 'sandbox'));
        $this->callbackUrl = config('mpesa.callback_url', env('MPESA_CALLBACK_URL'));
    }

    /**
     * Generate OAuth access token
     */
    public function getAccessToken()
    {
        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($url);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error('M-Pesa Access Token Error: ' . $response->body());
        return null;
    }

    /**
     * Generate STK Push password
     */
    protected function generatePassword()
    {
        $timestamp = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        return $password;
    }

    /**
     * Initiate STK Push
     */
    public function stkPush($phoneNumber, $amount, $accountReference, $transactionDesc = null)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token'
            ];
        }

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $timestamp = now()->format('YmdHis');
        $password = $this->generatePassword();

        // Format phone number (remove leading 0, add 254)
        $phone = $this->formatPhoneNumber($phoneNumber);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $amount,
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc ?? 'AfyaLink Payment',
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        if ($response->successful()) {
            $result = $response->json();
            Log::info('M-Pesa STK Push Initiated', [
                'phone' => $phone,
                'amount' => $amount,
                'checkout_request_id' => $result['CheckoutRequestID'] ?? null
            ]);

            return [
                'success' => true,
                'checkout_request_id' => $result['CheckoutRequestID'] ?? null,
                'response_code' => $result['ResponseCode'] ?? null,
                'response_description' => $result['ResponseDescription'] ?? null,
            ];
        }

        Log::error('M-Pesa STK Push Error: ' . $response->body());
        return [
            'success' => false,
            'message' => $response->json()['errorMessage'] ?? 'Failed to initiate payment'
        ];
    }

    /**
     * Query STK Push status
     */
    public function queryStatus($checkoutRequestId)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return ['success' => false, 'message' => 'Failed to get access token'];
        }

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query'
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';

        $timestamp = now()->format('YmdHis');
        $password = $this->generatePassword();

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'result' => $response->json()
            ];
        }

        return ['success' => false, 'message' => 'Failed to query payment status'];
    }

    /**
     * Format phone number to 254 format
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 254
        if (substr($phone, 0, 1) === '0') {
            return '254' . substr($phone, 1);
        }

        // If starts with 7 or 1, add 254
        if (substr($phone, 0, 1) === '7' || substr($phone, 0, 1) === '1') {
            return '254' . $phone;
        }

        return $phone;
    }

    /**
     * Handle callback from M-Pesa
     */
    public function handleCallback($data)
    {
        Log::info('M-Pesa Callback Received', $data);

        // Extract relevant data from callback
        $result = $data['Body']['stkCallback'] ?? [];

        $checkoutRequestId = $result['CheckoutRequestID'] ?? null;
        $resultCode = $result['ResultCode'] ?? null;
        $resultDesc = $result['ResultDesc'] ?? null;

        // Get metadata items
        $metadata = [];
        if (isset($result['CallbackMetadata']['Item'])) {
            foreach ($result['CallbackMetadata']['Item'] as $item) {
                $metadata[$item['Name']] = $item['Value'] ?? null;
            }
        }

        return [
            'checkout_request_id' => $checkoutRequestId,
            'result_code' => $resultCode,
            'result_desc' => $resultDesc,
            'amount' => $metadata['Amount'] ?? null,
            'mpesa_receipt_number' => $metadata['MpesaReceiptNumber'] ?? null,
            'phone_number' => $metadata['PhoneNumber'] ?? null,
            'transaction_date' => $metadata['TransactionDate'] ?? null,
        ];
    }
}
