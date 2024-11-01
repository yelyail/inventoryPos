<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderReceipts;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class printController extends Controller
{
    public function inventoryReportPrint(Request $request){
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ], [
            'from_date.required' => 'Please provide a starting date.',
            'to_date.required' => 'Please provide an ending date.',
            'to_date.after_or_equal' => 'The ending date must be the same or later than the starting date.'
        ]);
    
        $fromDate = \Carbon\Carbon::parse($request->input('from_date'))->startOfDay();
        $toDate = \Carbon\Carbon::parse($request->input('to_date'))->endOfDay();
    
        $reportCounter = $request->session()->get('report_counter', 1);
        $request->session()->put('report_counter', $reportCounter + 1);
    }
    public function salesReportPrint(Request $request){
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ], [
            'from_date.required' => 'Please provide a starting date.',
            'to_date.required' => 'Please provide an ending date.',
            'to_date.after_or_equal' => 'The ending date must be the same or later than the starting date.'
        ]);
    
        $fromDate = \Carbon\Carbon::parse($request->input('from_date'))->startOfDay();
        $toDate = \Carbon\Carbon::parse($request->input('to_date'))->endOfDay();
    
        $reportCounter = $request->session()->get('report_counter', 1);
        $request->session()->put('report_counter', $reportCounter + 1);
    }
    public function orderReceiptPrint($orderreceipts_id, $applyVat = true) {
        $orderReceipt = orderReceipts::with(['orders.product', 'customer'])->findOrFail($orderreceipts_id);
        $orderItems = $orderReceipt->orders; 
    
        $totalPrice = 0;
        $totalQuantity = 0;
    
        $items = $orderItems->map(function($order) use (&$totalPrice, &$totalQuantity) {
            $product = $order->product;
            if (!$product) {
                Log::debug('Missing product for order ID: ' . $order->id);
                return [
                    'product_name' => 'Unknown Product',
                    'quantity' => $order->qtyOrder,
                    'serial_num' => 'N/A',
                    'total_price' => $order->total_amount,
                ];
            }
    
            // If the product exists, continue as normal
            $serialNum = $product->serial->serial_num ?? 'N/A'; 
    
            $totalPrice += $order->total_amount;
            $totalQuantity += $order->qtyOrder; 
    
            return [
                'product_name' => $product->product_name,
                'serial_num' => $serialNum,
                'quantity' => $order->qtyOrder,
                'serial_num' => $serialNum,
                'total_price' => $order->total_amount,
            ];
        });
    
        $vatRate = 0.12; // 12% VAT
        $vatAmount = $applyVat ? $totalPrice * $vatRate : 0;
        $finalTotal = $totalPrice + $vatAmount;
    
        $representative = Session::get('fullname'); 
        $payment = paymentMethod::findOrFail($orderReceipt->payment_id);
        $reference = uniqid();
        $customer = $orderReceipt->customer;
        $discount = $payment->discount;
    
        // Prepare data for the view
        $data = [
            'title' => 'Temporary Receipt',
            'reference' => $reference,
            'date' => now()->format('m/d/Y H:i:s'),
            'customer_name' => $customer->customer_name,
            'address' => $customer->customer_address,
            'representative' => $representative,
            'orderItems' => $items, 
            'discount' => $discount,
            'subtotal' => number_format($totalPrice, 2),
            'vat_amount' => number_format($vatAmount, 2),
            'total_price' => number_format($finalTotal, 2),
            'payment_type' => $payment->paymentType,
            'payment' => number_format($payment->amount_paid, 2),
            'amount_deducted' => number_format($finalTotal - $payment->amount_paid, 2),
        ];
    
        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('orderReceipt', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return $dompdf->stream('orderReceipt.pdf');
    }
    
    
}
