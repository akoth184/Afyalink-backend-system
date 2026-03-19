<?php

namespace App\Http\Controllers;

use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Initiate M-Pesa STK Push payment
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'service_type' => 'required|string',
        ]);

        $user = Auth::user();

        // Generate unique account reference
        $accountReference = 'AFYA' . $user->id . time();

        // Initiate STK Push
        $result = $this->mpesaService->stkPush(
            $request->input('phone'),
            $request->input('amount'),
            $accountReference,
            $request->input('service_type')
        );

        if ($result['success']) {
            // Log the transaction attempt
            Log::info('M-Pesa Payment Initiated', [
                'user_id' => $user->id,
                'phone' => $request->input('phone'),
                'amount' => $request->input('amount'),
                'checkout_request_id' => $result['checkout_request_id'],
                'account_reference' => $accountReference,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment request sent to your phone',
                'checkout_request_id' => $result['checkout_request_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to initiate payment',
        ], 400);
    }

    /**
     * Handle M-Pesa callback
     */
    public function handleCallback(Request $request)
    {
        $data = $request->all();

        Log::info('M-Pesa Callback Received', $data);

        $callbackData = $this->mpesaService->handleCallback($data);

        // Check if payment was successful
        if ($callbackData['result_code'] === 0) {
            // Payment successful - log it
            Log::info('M-Pesa Payment Successful', [
                'checkout_request_id' => $callbackData['checkout_request_id'],
                'amount' => $callbackData['amount'],
                'mpesa_receipt_number' => $callbackData['mpesa_receipt_number'],
                'phone_number' => $callbackData['phone_number'],
            ]);

            // TODO: Update payment status in database if you have a payments table

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
            ]);
        }

        // Payment failed
        Log::warning('M-Pesa Payment Failed', [
            'checkout_request_id' => $callbackData['checkout_request_id'],
            'result_code' => $callbackData['result_code'],
            'result_desc' => $callbackData['result_desc'],
        ]);

        return response()->json([
            'success' => false,
            'message' => $callbackData['result_desc'] ?? 'Payment failed',
        ]);
    }

    /**
     * Query payment status
     */
    public function queryStatus(Request $request)
    {
        $request->validate([
            'checkout_request_id' => 'required|string',
        ]);

        $result = $this->mpesaService->queryStatus($request->input('checkout_request_id'));

        return response()->json($result);
    }
}
