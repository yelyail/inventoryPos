@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-48 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        
<div class="upanddown">

<div class="downdiv">
    <div class="downdash" style="overflow:auto !important; max-height: 400px;">
        <div class="stat place-items-center">
            <div class="stat-title">Low Stock Products</div>
            <table id="dataTableLowStock" class="table table-s">
                <thead style="font-size: 20px !important; color: #222831;  top: 0; background-color: bg-gray-100; text-align: center;">
                    <tr>
                        <th>Product Image</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Unit</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($lowStockProducts) > 0)
                    @foreach ($approvedProducts as $product)
                        @if (in_array($product->product_id, array_column($lowStockProducts, 'product_id')))
                            <tr>                  
                                <td class="text-2xl flex items-center justify-center">
                                    <img src="{{ asset("storage/{$product->product_image}") }}" 
                                        alt="{{ $product->model_name }}" 
                                        style="width: 70px; height: 50px;">
                                </td>
                                <td class="cat">{{ ucwords(strtolower($product->category_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ ucwords(strtolower($product->brand_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ ucwords(strtolower($product->model_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ $product->typeOfUnit ?? 'N/A' }}</td>
                                <td class="cat text-center">{{ $product->serial_count }}</td>
                            </tr>
                        @endif
                    @endforeach 
                @else
                    <tr>
                        <td colspan="6">No low stock items.</td>
                    </tr>
                @endif

                </tbody>
            </table>
        </div>
    </div>

    <div class="downdash" style="overflow:auto !important; max-height: 400px;">
        <div class="stat place-items-center">
            <div class="stat-title">Defective Products</div>
                <table id="dataTableDamaged" class="table table-s">
                    <thead style="font-size: 20px !important; color: #222831; top: 0; background-color: bg-gray-100; text-align: center;">
                        <tr>
                            <th>Product Image</th>
                            <th>Serial #</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($defectiveProducts as $product)
                            <tr>                  
                                <td class="text-2xl flex items-center justify-center">
                                    <img src="{{ asset("storage/{$product->product_image}") }}" 
                                        alt="{{ $product->model_name }}" 
                                        style="width: 70px; height: 50px;">
                                </td>
                                <td class="cat">{{ $product->serial_number ?? 'N/A' }}</td> <!-- Adjust to access serial_number directly -->
                                <td class="cat">{{ ucwords(strtolower($product->category_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ ucwords(strtolower($product->brand_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ ucwords(strtolower($product->model_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ $product->typeOfUnit ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>
    </div>
</div>

    <div class="updiv">
        <div class="maindash" style="overflow:auto !important; max-height: 400px;">
            <div class="stat place-items-center">
                <div class="stat-title">Products Pending Inspection</div>
                <table class="table table-xs">
                    <thead style="font-size: 20px !important; color: #222831; top: 0; background-color: bg-gray-100; text-align: center;">
                        <tr>
                            <th>Product Image</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Model Name</th>
                            <th>Date Added</th>
                            <th>Days Since Arrived</th>
                        </tr>
                    </thead>
                    <tbody class="text-center font-medium">
                        @foreach($pendingProducts as $product)
                            <tr class="text-center">                  
                                <td class="text-2xl flex items-center justify-center" style="height: 100px;">
                                    <img src="{{ asset("storage/{$product->product_image}") }}" 
                                    alt="{{ $product->model_name }}" 
                                    style="width: 100px; height: 100px;">
                                </td>
                                <td class="cat">{{ ucwords(strtolower($product->category_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ ucwords(strtolower($product->brand_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ ucwords(strtolower($product->model_name ?? 'N/A')) }}</td>
                                <td class="cat">{{ \Carbon\Carbon::parse($product->date_added)->format('Y-m-d') }}</td>
                                <td class="cat">
                                    {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->date_added)) }} days
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!--end of updiv--->
</div>
</div>
</main>

<script src="{{ asset('js/Product.js') }}"></script>
@if(session('success'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform -translate-y-full opacity-0 transition-transform duration-500 ease-in-out">
        <div class="flex items-center">
        <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif
<script src="{{ asset('js/alert.js') }}"></script>
@endsection
