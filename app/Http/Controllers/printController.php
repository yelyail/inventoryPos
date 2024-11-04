<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderReceipt;
use App\Models\orderReceipts;
use App\Models\orders;
use App\Models\serial;
use App\Models\PaymentMethod; 
use App\Models\product; 
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class printController extends Controller
{
    public function inventoryReportPrint(Request $request) {
        $representative = Session::get('fullname'); 

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

        $products = DB::table('product')
            ->select(
                'product.product_id',
                'inventory.inventory_id',
                'product.product_image',
                'category.category_name',
                'serial.serial_number',
                'category.brand_name',
                'product.product_name as model_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count'),
                DB::raw('(SELECT MAX(replace.replace_date) FROM replace WHERE serial.serial_Id = replace.serial_id) as replace_date')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->where('inventory.status', '=', 'approve')
            ->whereBetween('product.added_date', [$fromDate, $toDate]) 
            ->groupBy(
                'product.product_id',
                'inventory.inventory_id',
                'product.product_image',
                'serial.serial_number',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit'
            )
            ->orderBy('product.added_date', 'desc')
            ->get();

        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->orderBy('serial_number')
                ->pluck('serial_number')
                ->toArray();
        }

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for the selected date range.'], 404);
        }

        $data = [
            'title' => 'Inventory Report',
            'date' => now()->format('m/d/Y H:i:s'),
            'representative' => $representative,
            'products' => $products,
            'fromDate' => $fromDate->format('Y-m-d'),
            'toDate' => $toDate->format('Y-m-d'),
        ];

        // PDF generation
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('inventoryReports', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('inventoryReport.pdf', ['Attachment' => true]);
    }

    public function salesReportPrint(Request $request) {
        // Validate the request input for date range
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ], [
            'from_date.required' => 'Please provide a starting date.',
            'to_date.required' => 'Please provide an ending date.',
            'to_date.after_or_equal' => 'The ending date must be the same or later than the starting date.'
        ]);
    
        // Parse and set the date range
        $fromDate = \Carbon\Carbon::parse($request->input('from_date'))->startOfDay();
        $toDate = \Carbon\Carbon::parse($request->input('to_date'))->endOfDay();
    
        $VAT_RATE = 0.12; 
        $orderDetails = DB::table('orderreceipts')
            ->select(
                'c.customer_name',
                'p.product_name',
                'c.customer_address',
                'p.unitPrice',
                'pm.paymentType',
                'pm.reference_num',
                'pm.amount_paid',
                'pm.discount',
                'o.qtyOrder',
                'o.total_amount',
                'orderreceipts.order_date',
                'orderreceipts.status'
            )
            ->leftJoin('orders as o', 'o.order_id', '=', 'orderreceipts.orderreceipts_id')
            ->leftJoin('product as p', 'p.product_id', '=', 'o.product_id')
            ->leftJoin('customer as c', 'c.customer_id', '=', 'orderreceipts.customer_id')
            ->leftJoin('paymentmethod as pm', 'pm.payment_id', '=', 'orderreceipts.payment_id')
            ->whereBetween('orderreceipts.order_date', [$fromDate, $toDate]) // Filter by date range
            ->get();
    
        $orderDetails = $orderDetails->map(function ($order) use ($VAT_RATE) {
            $orderDate = Carbon::parse($order->order_date);
            $warrantyExpired = $orderDate->copy()->addYear(); 
    
            $order->warranty_expired = $warrantyExpired->isPast() ? 'Expired' : 'Valid';
            $order->warranty_expiration_date = $warrantyExpired->format('Y-m-d');
    
            if (!empty($order->discount) && $order->discount > 0) {
                $order->VAT = 0; 
            } else {
            }
            
            $order->totalPrice = $order->qtyOrder * $order->unitPrice;
    
            return $order;
        });
    
        // Prepare data for PDF generation
        $data = [
            'title' => 'Sales Report',
            'reference' => uniqid(),
            'date' => now()->format('m/d/Y H:i:s'),
            'fromDate' => $fromDate->format('m/d/Y'),
            'toDate' => $toDate->format('m/d/Y'),
            'orderDetails' => $orderDetails 
        ];
    
        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('salesReport', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return $dompdf->stream('salesReport.pdf');
    }
    
    public function orderReceiptPrint($orderreceipts_id) {
        $orderReceipt = orderReceipts::with('orders.product.serial')->findOrFail($orderreceipts_id);
        $customer = customer::findOrFail($orderReceipt->customer_id);
        
        $representative = Session::get('fullname') ?? 'Unknown Representative';
        $payment = paymentMethod::findOrFail($orderReceipt->payment_id);
        
        $orders = orders::where('order_id', $orderReceipt->order_id)
            ->whereDate('created_at', $orderReceipt->created_at->toDateString()) 
            ->get();
        $orderItems = collect();
        $totalPrice = 0;
    
        foreach ($orders as $item) {
            $product = $item->product;
    
            if ($product) {
                $serials = $product->serial()
                    ->where('status', 'sold')
                    ->where('updated_at', $item->updated_at) 
                    ->get();
    
                if ($serials->isNotEmpty()) {
                    foreach ($serials as $serial) {
                        $orderItems->push([
                            'product_name' => $product->product_name ?? 'Unknown Product',
                            'serial_number' => $serial->serial_number,
                            'quantity' => 1,
                            'total_price' => $item->total_amount / $item->qtyOrder, 
                        ]);
                    }
                } else {
                    $orderItems->push([
                        'product_name' => $product->product_name ?? 'Unknown Product',
                        'serial_number' => 'N/A',
                        'quantity' => $item->qtyOrder,
                        'total_price' => $item->total_amount,
                    ]);
                }
                $totalPrice += $item->total_amount; 
            } else {
                $orderItems->push([
                    'product_name' => 'Unknown Product',
                    'serial_number' => 'N/A',
                    'quantity' => $item->qtyOrder,
                    'total_price' => $item->total_amount,
                ]);
                $totalPrice += $item->total_amount;
            }
        }
        $totalPayments = paymentMethod::where('payment_id', $orderReceipt->payment_id)
                ->sum('amount_paid'); 
    
        $discount = $payment->discount ?? 0;
        $vatRate = ($discount > 0) ? 0 : 0.12;
        $vatAmount = $totalPrice * $vatRate;
        $finalTotal = $totalPrice + $vatAmount - $discount; 
        $amountDeducted = $totalPayments - $finalTotal; 
    
        $data = [
            'title' => 'Temporary Receipt',
            'reference' => uniqid(),
            'order_id' => $orderReceipt->order_id,
            'date' => now()->format('m/d/Y H:i:s'),
            'customer_name' => $customer->customer_name,
            'address' => $customer->customer_address,
            'representative' => $representative, 
            'orderItems' => $orderItems,
            'discount' => number_format($discount, 2),
            'subtotal' => number_format($totalPrice, 2),
            'vat_amount' => number_format($vatAmount, 2),
            'total_price' => number_format($finalTotal, 2),
            'payment_type' => $payment->paymentType,
            'payment' => number_format($payment->amount_paid, 2),
            'amount_deducted' => number_format($amountDeducted, 2),
        ];
    
        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('orderReceipt', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return $dompdf->stream('orderReceipt.pdf');
    }
    
    
}
