@if(!Session::has('user_id'))
    <script type="text/javascript">
        window.location = "{{ route('Login') }}"; // Redirect to login if session is not set
    </script>
@endif

<!doctype html>
<html data-theme="forest">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Bra-Cat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('Images/davlogo2.png') }}">
    <title>DAVCOM Consumer Goods Trading</title>
</head>

<body>
   <div class="navbar bg-base-100">
  
<div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-10 w-10"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h7" />
        </svg>
      </div>
      <ul
        tabindex="0"
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style="width:300px; height: 550px; color: black; background-color: white !important;     border: 3px solid rgb(0, 0, 0);">
        <li>
    <a href="{{ route('dashboard') }}">
      <img class="iconmenu"
      src="/Images/dashboard.png"/>
      Dashboard
    </a>
  </li>
  <li>
    <details open>
      <summary>
        <img class="iconmenu"
      src="/Images/box.png"/>
        Product Management
      </summary>
      <ul>
        <li>
          <a href="{{ route('Storage') }}">
            Brand / Category
          </a>
        </li>
        <li>
          <a href="{{ route('Product') }}">
            Product
          </a>
        </li>
      </ul>
    </details>
  </li>
  <li>
    <details open>
      <summary>
        <img class="iconmenu"
      src="/Images/cart.png"/>
        Sales Management
      </summary>
      <ul>
        <li>
          <a href="">
            Saleble products
          </a>
        </li>
      </ul>
    </details>
  </li>
  <li>
    <details open>
      <summary>
        <img class="iconmenu"
      src="/Images/warehouse.png"/>
        Inventory Management
      </summary>
      <ul>
        <li>
          <a href="{{ route('Pending') }}">
            Pending
          </a>
        </li>
        <li>
          <a href="{{ route('Invside') }}">
            Inventory
          </a>
        </li>
        <li>
          <a href="{{ route('Suptech') }}">
            Supplier / Technician
          </a>
        </li>
      </ul>
    </details>
  </li>
  <li>
    <details open>
      <summary>
        <img class="iconmenu"
      src="/Images/report.png"/>
        Reports
      </summary>
      <ul>
        <li>
          <a href="{{ route('Report') }}">
            Inventory Report
          </a>
        </li>
      </ul>
    </details>
  </li>
      </ul>
    </div>
        <div class="flex-1">
            <a class="btn btn-ghost text-xl"><img src="/Images/davcomlogo.png" style="width: 180px; height: 30px;" /></a>
            <h1 class="labeltitle">Reports</h1>
        </div>
        <div class="tooltip-C" style="margin-right: 20px;">
            <div class="tooltipB">Print</div>
            <a role="tab" class=""><button onclick="window.print()" style="width: 30px; filter: invert(1);">
                <img src="/Images/print.png" alt="">
            </button></a>
        </div>
        <div class="flex-none gap-2">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img alt="Tailwind CSS Navbar component" src="/Images/account.png" style="filter: invert(1) !important;" />
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style=" border: 2px solid black; background-color:whitesmoke; color:black; z-index: 1000;">
                    <h3><b>Account:  {{ Session::get('name') }} ({{ Session::get('position') }})</h3></b>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="/Images/logout.png" style="width: 20px; height: 20px;" /><b>Log out</b></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="wholediv">
      
        <div class="upanddown" style="margin-left: 15px;">        
            <div class="minitab"></div>


                        

            <div class="date-input-container" style="margin-left:590px;">
                <div class="date-labels">
                    <label for="from">From:</label>
                    <input type="date" id="from" class="input input" style="background-color: transparent !important;">
                    <label for="to">To:</label>
                    <input type="date" id="to" class="input input" style="background-color: transparent !important;">
                </div>
            </div>
            <h3 class="labeltitle" style="margin-left: 120px;" >Inventory Summary</h3>
            <table class="table table-xs">
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
                        <h3 class="labeltitle" style="margin-left: 130px;">Inventory Details</h3>
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
                                @foreach ($inventoryDetails as $item)
                          <tr data-arrived="{{ $item->date_arrived }}" data-status="{{ $item->status }}"> 
                              <td class="">{{ $item->Product_Name }}</td>
                              <td class="">{{ $item->Category_Name }}</td>
                              <td class="">{{ $item->Brand_Name }}</td>
                              <td class="">{{ $item->serial_number }}</td>
                              <td class="">{{ $item->status }}</td>
                              <td class="">
                                  {{ \Carbon\Carbon::parse($item->date_arrived)->format('m/d/Y, h:i A') }}
                              </td>

                          </tr>
                          @endforeach

                            </tbody>
                        </table>
                    </div>
        </div> <!--end of upanddown--->
    </div> <!--end of wholediv--->

    <script src="{{ asset('js/excel_print.js') }}"></script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
    const fromInput = document.getElementById('from');
    const toInput = document.getElementById('to');
    const tableRows = document.querySelectorAll('#inventoryTable tbody tr');

    // Declare count variables outside the function to maintain their state
    let incomingCount = 0;
    let outgoingCount = 0;
    let repairCount = 0;

    function filterTable() {
        // Reset counts for every filtering
        incomingCount = 0;
        outgoingCount = 0;
        repairCount = 0;

        const fromDate = new Date(fromInput.value);
        const toDate = new Date(toInput.value);

        tableRows.forEach(row => {
            const dateArrived = new Date(row.getAttribute('data-arrived'));
            const status = row.getAttribute('data-status');

            // Check if the row's status is among the incoming statuses and is within the selected date range
            if (fromInput.value && toInput.value) {
                if (dateArrived >= fromDate && dateArrived <= toDate) {
                    row.style.display = ''; // Show row
                    countStatus(status); // Count status
                } else {
                    row.style.display = 'none'; // Hide row
                }
            } else {
                row.style.display = ''; // Show all if no date is selected
                countStatus(status); // Count status
            }
        });

        // Update the summary counts after filtering
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

    // Add event listeners to the date inputs
    fromInput.addEventListener('change', filterTable);
    toInput.addEventListener('change', filterTable);
});
</script>


</body>
</html>
