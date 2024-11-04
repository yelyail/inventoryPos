@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-60 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        <div class="w-full p-3">
            <div class="bg-gray">
                <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                    <h1 class="prod_title text-2xl font-bold">Sales Reports</h1>
                    <button type="button" class="btn custom-btn flex items-center p-2 text-white rounded bg-blue-500 hover:bg-blue-600" id="sales-button">
                        <i class="fas fa-print mr-2"></i>
                        Generate Sales Report
                    </button>
                </div>
            </div>
        </div>
        <div class="container mt-7">
            <div class="flex mb-4 items-center justify-between">
                <div class="flex-grow">
                    <div class="relative w-full">
                        <div class="flex items-center rounded-md">
                            <span class="input-group-text p-2" id="basic-addon1">
                                <i class="fas fa-sharp fa-magnifying-glass pr-0 md:pr-3"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control p-2 rounded-l-md" placeholder="Search..." aria-label="Search">
                            <button class="btn custom-btn p-2 rounded-r-md" type="button" onclick="filterTable()">Search</button>
                        </div>
                    </div>
                </div>
                <div class="flex items-center ml-4">
                    <div class="mr-4">
                        <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="date" id="from_date" class="form-control p-2 rounded-md" aria-label="From Date">
                    </div>
                    <div>
                        <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="date" id="to_date" class="form-control p-2 rounded-md" aria-label="To Date">
                    </div>
                    <button type="button" class="btn btn-custom mt-5" id="filter-button">Filter</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-850 cstm-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Customer Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Product Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Qty</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Unit Price</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total Amount</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Discount</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">VAT Tax</th> 
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount Paid</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Payment Type</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Reference Number</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Order Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Warranty Expired</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Sales Recipient</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Repair Date</th>    
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Return for Repair</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orderDetails as $orderDetail)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ ucwords(strtolower($orderDetail->customer_name)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ ucwords(strtolower($orderDetail->product_name)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ $orderDetail->qtyOrder }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">₱{{ number_format($orderDetail->unitPrice, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">₱{{ number_format($orderDetail->totalPrice, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">₱{{ number_format($orderDetail->discount, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">₱{{ number_format($orderDetail->VAT, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">₱{{ number_format($orderDetail->amount_paid, 2) }}</td>
                                <td class="text-sm text-gray-500">{{ ucwords(strtolower($orderDetail->paymentType)) }}</td>
                                <td class="text-sm text-gray-500">{{ $orderDetail->reference_num ?? 'N/A' }}</td>
                                <td class="text-sm text-gray-500">{{ $orderDetail->order_date }}</td>
                                <td class="text-gray-500">{{ $orderDetail->warranty_expired }} until {{ $orderDetail->warranty_expiration_date }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ Session::get('fullname', 'User') }}</td>
                                <td class="px-4 py-2 text-gray-500">
                                    {{ $orderDetail->repair_date ? \Carbon\Carbon::parse($orderDetail->repair_date)->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $repairRequested = !empty($orderDetail->repair_date);
                                    @endphp
                                    <button class="bg-blue-200 hover:bg-blue-300 text-black px-2 py-1 rounded flex items-center 
                                                {{ $repairRequested ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                            @if (!$repairRequested)
                                                onclick="showRepairAlert('{{$orderDetail->order_id}}', this)" 
                                            @endif
                                            {{ $repairRequested ? 'disabled' : '' }}>
                                            Request Repair
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
