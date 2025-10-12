<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Event;
use App\Models\Booking;

class PaymentController extends Controller
{
    // Show events with dynamic eSewa signature for each booking
    public function showEvents()
    {
        $events = Event::all();

        // We'll pass these values dynamically per event in the Blade
        return view('events', compact('events'));
    }

    // AJAX endpoint to generate eSewa signature dynamically
public function generateSignatureAjax(Request $request)
{
    $request->validate([
        'total_amount' => 'required|numeric|min:0',
    ]);

    $totalAmount = $request->total_amount;
    $transactionUuid = (string) Str::uuid(); // unique per transaction
    $productCode = env('ESEWA_MERCHANT_CODE', 'EPAYTEST'); 
    $secretKey = env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q'); 

    $signedFieldString = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
    $signature = base64_encode(hash_hmac('sha256', $signedFieldString, $secretKey, true));

    return response()->json([
        'total_amount' => $totalAmount,
        'transaction_uuid' => $transactionUuid,
        'product_code' => $productCode,
        'signature' => $signature,
    ]);
}


    // Success callback
    public function success(Request $request)
    {
        $pid = $request->input('pid'); // e.g., transaction UUID
        $tAmt = $request->input('tAmt');
        $amt = $request->input('amt');
        $txAmt = $request->input('txAmt');
        $psc = $request->input('psc');
        $pdc = $request->input('pdc');

        // Verify payment with eSewa
        $res = \Illuminate\Support\Facades\Http::asForm()->post('https://uat.esewa.com.np/epay/transrec', [
            'amt' => $amt,
            'pdc' => $pdc,
            'psc' => $psc,
            'txAmt' => $txAmt,
            'tAmt' => $tAmt,
            'pid' => $pid,
            'scd' => env('ESEWA_MERCHANT_CODE'),
        ]);

        if (strpos($res->body(), 'Success') !== false) {
            return redirect()->route('events')->with('success', 'Payment Successful!');
        } else {
            return redirect()->route('events')->with('error', 'Payment verification failed.');
        }
    }

    // Failure callback
    public function failure(Request $request)
    {
        return redirect()->route('events')->with('error', 'Payment Failed or Cancelled.');
    }
}
