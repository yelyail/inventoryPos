@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')

<div class="upanddown" style="width: 1310px; text-align: center;">
    <div class="minitab"></div>

    <div class="date-input-container">
        <div class="date-labels">
            <label for="from">From:</label>
            <input type="date" id="from" class="input input" style="background-color: transparent !important;">
            <label for="to">To:</label>
            <input type="date" id="to" class="input input" style="background-color: transparent !important;">
        </div>
    </div>
    <h3 class="labeltitle">Inventory Summary</h3>
    <table class="table table-xs" style="text-align: center;">
        <thead>
            <tr class="bg-gray-200">
                <th class="">Type</th>
                <th class="">Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="">Incoming</td>
                <td class="" id="incomingCount">0</td>
            </tr>
            <tr>
                <td class="">Outgoing</td>
                <td class="" id="outgoingCount">0</td>
            </tr>
            <tr>
                <td class="">Repair</td>
                <td class="" id="repairCount">0</td>
            </tr>
        </tbody>
    </table>

    <div class="overflow-x-auto" style="height: 490px;">
        <h3 class="labeltitle">Inventory Details</h3>
        <table class="table table-xs" id="inventoryTable">
            <thead>
                <tr class="bg-gray-200">
                    <th class="">Model</th>
                    <th class="">Category</th>
                    <th class="">Brand</th>
                    <th class="">Serial #</th>
                    <th class="">Status</th>
                    <th class="">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr data-arrived="" data-status="">
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                </tr>
            </tbody>
        </table>
    </div>
</div> <!--end of upanddown-->
</div> <!--end of wholediv-->

<script src="{{ asset('js/excel_print.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fromInput = document.getElementById('from');
    const toInput = document.getElementById('to');
    const tableRows = document.querySelectorAll('#inventoryTable tbody tr');

    let incomingCount = 0;
    let outgoingCount = 0;
    let repairCount = 0;

    function filterTable() {
        incomingCount = 0;
        outgoingCount = 0;
        repairCount = 0;

        const fromDate = new Date(fromInput.value);
        const toDate = new Date(toInput.value);

        tableRows.forEach(row => {
            const dateArrived = new Date(row.getAttribute('data-arrived'));
            const status = row.getAttribute('data-status');

            if (fromInput.value && toInput.value) {
                if (dateArrived >= fromDate && dateArrived <= toDate) {
                    row.style.display = '';
                    countStatus(status);
                } else {
                    row.style.display = 'none';
                }
            } else {
                row.style.display = '';
                countStatus(status);
            }
        });

        updateSummaryCounts(incomingCount, outgoingCount, repairCount);
    }

    function countStatus(status) {
        const incomingStatuses = [
            'Returned Customer',
            'New',
            'Returned w/ charged',
            'Repair Failed',
            'Returned Defective'
        ];

        if (incomingStatuses.includes(status)) incomingCount++;
        else if (status === 'Sold') outgoingCount++;
        else if (status === 'Repaired') repairCount++;
    }

    function updateSummaryCounts(incomingCount, outgoingCount, repairCount) {
        document.getElementById('incomingCount').innerText = incomingCount;
        document.getElementById('outgoingCount').innerText = outgoingCount;
        document.getElementById('repairCount').innerText = repairCount;
    }

    fromInput.addEventListener('change', filterTable);
    toInput.addEventListener('change', filterTable);
});
</script>
@endsection
