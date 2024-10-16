<!doctype html>
<html data-theme="">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Bra-Cat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Invside.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('Images/davlogo2.png') }}">
    <title>DAVCOM Consumer Goods Trading</title>


</head>
<!--NavBar Heading-->
<body>
 <div class="navbar bg-base-100">
  <div class="flex-1">
    <a class="btn btn-ghost text-xl"><img src="/Images/davcomlogo.png" style="width: 180px; height: 30px;" /></a>
    <h1 class="labeltitle">Received Report</h1>
  </div>
  <div class="flex-none gap-2">
    <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
          <img
            alt="Tailwind CSS Navbar component"
            src="/Images/account.png" style="filter: invert(1) !important;" />
        </div>
      </div>
      <ul
        tabindex="0"
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style=" border: 2px solid black; background-color:whitesmoke; color:black; z-index: 1000;">
        <li><a><img src="/Images/megaphone.png" style="width: 20px; height: 20px;" /> <b>Activity Log</b></a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="/Images/logout.png" style="width: 20px; height: 20px;" /><b>Log out</b></a></li>
      </ul>
    </div>
  </div>
</div>

<div class="wholediv">
<!--Menu Buttons-->
<aside>
  <div class="sidemenu">
  <ul class="menu bg-base-200 rounded-box">
  <li>
    <a href="{{ route('dashboard') }}" class="tooltip tooltip" data-tip="Dashboard">
      <img class="iconmenu"
      src="/Images/dashboard.png"/>
    </a>
  </li>
  <li>
    <a href="{{ route('Storage') }}" class="tooltip tooltip" data-tip="Brand/Category">
      <img class="iconmenu"
      src="/Images/tag.png"/>
    </a>
  </li>
  <li>
    <a href="{{ route('Product') }}" class="tooltip tooltip" data-tip="Products">
      <img class="iconmenu"
      src="/Images/box.png"/>
    </a>  
  </li>
  <li>
    <a href="{{ route('Invside') }}" class="tooltip tooltip" data-tip="Inventory">
      <img class="iconmenu"
      src="/Images/warehouse.png"/>
    </a>
  </li>
   <li>
    <a href="{{ route('Report') }}" class="tooltip tooltip" data-tip="Reports">
      <img class="iconmenu"
      src="/Images/report.png"/>
    </a>
  </li>
</ul>
</aside>

<div class="upanddown">
    <div class="minitab">
      <div role="tablist" class="tabs tabs-boxed">
        <a href="{{ route('Invside') }}" role="tab" class="tab" id="tab">Stocks</a>
        <a href="{{ route('Pending') }}" role="tab" class="tab"  id="tab">Pending</a>
        <a class="tab tab-active" role="tab" class="tab">Received</a>
        <a class="tab" style="width: 70px; height:30px !important; margin-left:850px">
        <div class="tooltip-C">
        <div class="tooltipB">Generate Report</div>
        <button class="trash" onclick="modalgenerate.showModal()"><img class="" style="width:45px; height:45;" src="/Images/reportgenerate.png"/></button>
        </div></a>
        <a role="tab" class="tab">
        <button class="btn" onclick="modaladdlist.showModal()">➕ List Product</button></a>
</div>
</div> <!--end of upanddown--->
<div class="downdiv">
    <div class="overflow-x-auto" style="width: 1400px;">
<table class="table table-sm">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Address</th>
            <th>Date</th>
            <th>Invoice Number</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Item Description</th>
            <th>Shipper's Name:</th>
            <th>Waybill No.</th>
            <th>Amount</th>
            <th>Received by:</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
</div>
</div> <!--end of wholediv--->

<dialog id="modaladdlist" class="modal">
    <div class="modal-box" style="width:1400px; height: 800px; overflow: auto;">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
         <!-- Header Section -->
        <div class="header" style="text-align: center;">    
            <h1>
                <img src="/Images/davcomlogo.png" 
                     style="width: 390px; height: 70px; 
                            filter: brightness(0) saturate(100%) invert(50%) sepia(11%) saturate(1476%) hue-rotate(174deg) brightness(89%) contrast(83%); 
                            margin-left: 480px;" />
                CONSUMER GOODS TRADING
            </h1>
            <p style="text-align: center;">Door 22, E.C. Business Center, C.M. Recto St, Davao City</p>
            <p style="text-align: center;">Tel: 0975-285-1156 / 0917-136-0542</p>
            <h2 style="text-align: center; margin-top: 10px;">RECEIVING REPORT</h2>
        </div>

        <!-- Report Details Section -->
        <div class="report-details" style="margin-top: 20px;">
            <div style="display: inline-block; width: 50%; float: left;">
                <p><strong>Supplier:</strong> ABC Trading</p>
                <p><strong>Address:</strong> 123 Market Street, Davao City</p>
            </div>
            <div style="display: inline-block; width: 50%; float: right; text-align: right;">
                <p><strong>Date:</strong> September 29, 2024</p>
                <p><strong>Invoice Number:</strong> INV-001234</p>
            </div>
        </div>

        <!-- Items Table Section -->
        <table class="table table-sm" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead style="background-color: #f0f0f0;">
                <tr>
                    <th style="border: 1px solid black; padding: 8px;">QTY</th>
                    <th style="border: 1px solid black; padding: 8px;">UNIT</th>
                    <th style="border: 1px solid black; padding: 8px;">ITEM DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">10</td>
                    <td style="border: 1px solid black; padding: 8px;">pcs</td>
                    <td style="border: 1px solid black; padding: 8px;">Radio Transmitters</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">5</td>
                    <td style="border: 1px solid black; padding: 8px;">boxes</td>
                    <td style="border: 1px solid black; padding: 8px;">School Notebooks (A4 Size)</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">20</td>
                    <td style="border: 1px solid black; padding: 8px;">sets</td>
                    <td style="border: 1px solid black; padding: 8px;">2-Way Radio Headsets</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">15</td>
                    <td style="border: 1px solid black; padding: 8px;">pcs</td>
                    <td style="border: 1px solid black; padding: 8px;">Portable Antennas</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">100</td>
                    <td style="border: 1px solid black; padding: 8px;">pcs</td>
                    <td style="border: 1px solid black; padding: 8px;">AAA Batteries (Duracell)</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer" style="margin-top: 20px;">
            <div style="display: inline-block; width: 100%;">
                <p><strong>Shipper's Name:</strong> XYZ Shipping Co.</p>
            </div>

            <div style="display: inline-block; width: 100%;">
                <p><strong>Waybill No.:</strong> WB-56789</p>
            </div>

            <div style="display: inline-block; width: 100%; text-align: right;">
                <p><strong>Amount:</strong> PHP 25,000.00</p>
            </div>

            <div style="display: inline-block; width: 100%; text-align: right;">
                <p><strong>Received by:</strong> John Dela Cruz</p>
            </div>

        </div>

    </div>
    </div>
</dialog>

<dialog id="modalgenerate" class="modal">
    <div class="modal-box" style="width:1400px; height: 800px; overflow: auto;">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <!-- Header Section -->
        <div class="header" style="text-align: center;">    
            <h1>
                <img src="/Images/davcomlogo.png" 
                     style="width: 390px; height: 70px; 
                            filter: brightness(0) saturate(100%) invert(50%) sepia(11%) saturate(1476%) hue-rotate(174deg) brightness(89%) contrast(83%); 
                            margin-left: 480px;" />
                CONSUMER GOODS TRADING
            </h1>
            <p style="text-align: center;">Door 22, E.C. Business Center, C.M. Recto St, Davao City</p>
            <p style="text-align: center;">Tel: 0975-285-1156 / 0917-136-0542</p>
            <h2 style="text-align: center; margin-top: 10px;">RECEIVING REPORT</h2>
        </div>

        <!-- Report Details Section -->
        <div class="report-details" style="margin-top: 20px;">
            <div style="display: inline-block; width: 50%; float: left;">
                <p><strong>Supplier:</strong> ABC Trading</p>
                <p><strong>Address:</strong> 123 Market Street, Davao City</p>
            </div>
            <div style="display: inline-block; width: 50%; float: right; text-align: right;">
                <p><strong>Date:</strong> September 29, 2024</p>
                <p><strong>Invoice Number:</strong> INV-001234</p>
            </div>
        </div>

        <!-- Items Table Section -->
        <table class="table table-sm" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead style="background-color: #f0f0f0;">
                <tr>
                    <th style="border: 1px solid black; padding: 8px;">QTY</th>
                    <th style="border: 1px solid black; padding: 8px;">UNIT</th>
                    <th style="border: 1px solid black; padding: 8px;">ITEM DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">10</td>
                    <td style="border: 1px solid black; padding: 8px;">pcs</td>
                    <td style="border: 1px solid black; padding: 8px;">Radio Transmitters</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">5</td>
                    <td style="border: 1px solid black; padding: 8px;">boxes</td>
                    <td style="border: 1px solid black; padding: 8px;">School Notebooks (A4 Size)</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">20</td>
                    <td style="border: 1px solid black; padding: 8px;">sets</td>
                    <td style="border: 1px solid black; padding: 8px;">2-Way Radio Headsets</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">15</td>
                    <td style="border: 1px solid black; padding: 8px;">pcs</td>
                    <td style="border: 1px solid black; padding: 8px;">Portable Antennas</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">100</td>
                    <td style="border: 1px solid black; padding: 8px;">pcs</td>
                    <td style="border: 1px solid black; padding: 8px;">AAA Batteries (Duracell)</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer" style="margin-top: 20px;">
            <div style="display: inline-block; width: 100%;">
                <p><strong>Shipper's Name:</strong> XYZ Shipping Co.</p>
            </div>

            <div style="display: inline-block; width: 100%;">
                <p><strong>Waybill No.:</strong> WB-56789</p>
            </div>

            <div style="display: inline-block; width: 100%; text-align: right;">
                <p><strong>Amount:</strong> PHP 25,000.00</p>
            </div>

            <div style="display: inline-block; width: 100%; text-align: right;">
                <p><strong>Received by:</strong> John Dela Cruz</p>
            </div>

        </div>

    </div>
</dialog>

            
</body>
</html>