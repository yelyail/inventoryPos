@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-60 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        <div class="w-full p-3">
            <div class="bg-gray">
                <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                    <h1 class="prod_title text-2xl font-bold">Inventory Reports</h1>
                    <button type="button" class="btn custom-btn flex items-center p-2 text-white rounded bg-blue-500 hover:bg-blue-600" id="inventory-button" onclick="generateInventoryReport()">
                        <i class="fas fa-print mr-2"></i>
                        Generate Inventory Report
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
                    <button type="button" class="btn btn-custom mt-5" id="filter-button" onclick="filterTable()">Filter</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-850 cstm-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Category</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Serial #</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Product Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Supplier Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Price</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date Added</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Warranty Expired</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Replace Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Return for Replace</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-4 py-2">{{ $product->category_name }}</td>  
                                <td class="px-4 py-2">{{ $product->serial_number }}</td> 
                                <td class="px-4 py-2">{{ $product->model_name }}</td> 
                                <td class="px-4 py-2">{{ $product->supplier_name }}</td> 
                                <td class="px-4 py-2">{{ $product->unitPrice }}</td> 
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($product->date_added)->format('Y-m-d') }}</td> 
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($product->warranty_expired)->format('Y-m-d') }}</td>  
                                <td class="px-4 py-2">
                                    {{ $product->replace_date ? \Carbon\Carbon::parse($product->replace_date)->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $warrantyExpired = \Carbon\Carbon::parse($product->warranty_expired)->isPast();
                                    @endphp
                                    <button class="bg-blue-200 hover:bg-blue-300 text-black px-2 py-1 rounded flex items-center 
                                                {{ $warrantyExpired ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                            @if (!$warrantyExpired)
                                                onclick="showTransferAlert('{{ $product->serial_Id }}', this)" 
                                            @endif
                                            {{ $warrantyExpired ? 'disabled' : '' }}>
                                        Request Replace
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('filter-button').addEventListener('click', filterTable); // Add event listener to filter button

        function filterTable() {
            let table = document.querySelector('.cstm-table');
            let searchInput = document.getElementById('searchInput').value.toLowerCase();
            let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
            let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;

            let tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let showRow = true;

                // Search filtering
                if ((td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1) && 
                    (td[2] && td[2].textContent.toLowerCase().indexOf(searchInput) === -1)) {
                    showRow = false;
                }

                // Date filtering
                let tdDate = td[5];  
                if (tdDate) {
                    let rowDate = new Date(tdDate.textContent.trim());
                    if ((fromDate && rowDate < fromDate) || (toDate && rowDate > toDate)) {
                        showRow = false;
                    }
                }

                tr[i].style.display = showRow ? '' : 'none';
            }
        }

        function generateInventoryReport() {
            let fromDate = document.getElementById('from_date').value;
            let toDate = document.getElementById('to_date').value;

            if (fromDate && toDate) {
                let params = new URLSearchParams({
                    from_date: fromDate,
                    to_date: toDate
                });
                window.location.href = "{{ route('inventoryReportPrint') }}?" + params.toString();
            } else {
                alert("Please select a valid date range before generating the report.");
            }
        }
        document.getElementById('inventory-button').addEventListener('click', generateInventoryReport);
    });
</script>
@endsection
