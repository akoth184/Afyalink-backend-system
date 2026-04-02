<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;
use Carbon\Carbon;

class MpesaController extends Controller
{
    private $consumerKey;
    private $consumerSecret;
    private $shortcode;
    private $passkey;
    private $callbackUrl;
    private $baseUrl;

    public function __construct()
    {
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->shortcode = env('MPESA_SHORTCODE', '174379');
        $this->passkey = env('MPESA_PASSKEY');
        $this->callbackUrl = env('MPESA_CALLBACK_URL', 'https://afyalink.ke/mpesa/callback');
        $this->baseUrl = env('MPESA_ENV', 'sandbox') === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    private function getAccessToken()
    {
        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);
        $response = Http::withoutVerifying()->timeout(30)->withHeaders([
            'Authorization' => 'Basic ' . $credentials
        ])->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');
        return $response->json()['access_token'] ?? null;
    }

    public function showPaymentPage()
    {
        $user = Auth::user();
        $payments = Payment::where('patient_id', $user->id)->latest()->get();
        return view('patient.payments', compact('payments'));
    }

    public function initiatePayment(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|string|in:consultation,pharmacy,lab,inpatient',
        ]);

        $user = Auth::user();
        $phone = $this->formatPhone($request->phone);
        $amount = (int) $request->amount;
        $timestamp = Carbon::now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $payment = Payment::create([
            'patient_id' => $user->id,
            'payment_type' => $request->payment_type,
            'amount' => $amount,
            'phone_number' => $phone,
            'status' => 'pending',
            'description' => ucfirst($request->payment_type) . ' payment - AfyaLink',
        ]);

        $token = $this->getAccessToken();
        if (!$token) {
            return back()->with('error', 'Could not connect to M-PESA. Please try again.');
        }

        $response = Http::withoutVerifying()->timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => 'AfyaLink-' . $user->patient_id,
            'TransactionDesc' => ucfirst($request->payment_type) . ' Fee - AfyaLink',
        ]);

        $data = $response->json();

        if (isset($data['CheckoutRequestID'])) {
            $payment->update([
                'checkout_request_id' => $data['CheckoutRequestID'],
                'merchant_request_id' => $data['MerchantRequestID'],
            ]);
            return back()->with('success', 'STK Push sent to ' . $request->phone . '! Check your phone and enter your M-PESA PIN to complete payment.');
        }

        $payment->update(['status' => 'failed']);
        return back()->with('error', 'Payment initiation failed. ' . ($data['errorMessage'] ?? 'Please try again.'));
    }

    public function callback(Request $request)
    {
        $data = $request->all();
        \Illuminate\Support\Facades\Log::info('M-Pesa Callback Received', $data);
        
        $body = $data['Body']['stkCallback'] ?? null;
        if (!$body) return response()->json(['status' => 'ok']);

        $checkoutRequestId = $body['CheckoutRequestID'];
        $resultCode = $body['ResultCode'];

        \Illuminate\Support\Facades\Log::info('Looking for payment with checkout_request_id: ' . $checkoutRequestId);
        
        $payment = Payment::where('checkout_request_id', $checkoutRequestId)->first();
        if (!$payment) {
            \Illuminate\Support\Facades\Log::warning('Payment not found for checkout_request_id: ' . $checkoutRequestId);
            return response()->json(['status' => 'ok']);
        }

        if ($resultCode == 0) {
            $items = collect($body['CallbackMetadata']['Item'] ?? []);
            $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
            $payment->update([
                'status' => 'completed',
                'mpesa_receipt' => $receipt,
                'paid_at' => now(),
            ]);
            \Illuminate\Support\Facades\Log::info('Payment completed for checkout_request_id: ' . $checkoutRequestId);
        } else {
            $payment->update(['status' => 'failed']);
            \Illuminate\Support\Facades\Log::info('Payment failed for checkout_request_id: ' . $checkoutRequestId);
        }

        return response()->json(['status' => 'ok']);
    }

    public function checkStatus(Request $request)
    {
        $payment = Payment::where('checkout_request_id', $request->checkout_id)
            ->where('patient_id', Auth::id())
            ->first();
        if (!$payment) return response()->json(['status' => 'not_found']);
        return response()->json(['status' => $payment->status, 'receipt' => $payment->mpesa_receipt]);
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (substr($phone, 0, 1) === '0') $phone = '254' . substr($phone, 1);
        if (substr($phone, 0, 2) === '07') $phone = '254' . substr($phone, 1);
        if (substr($phone, 0, 1) !== '2') $phone = '254' . $phone;
        return $phone;
    }
}
