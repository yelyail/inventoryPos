@if(!Session::has('user_id'))
    <script type="text/javascript">
        window.location = "{{ route('Login') }}"; // Redirect to login if session is not set
    </script>
@endif
<!doctype html>
<html data-theme="">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
        <!-- <link rel="stylesheet" href="{{ asset('css/reponsive.css') }}"> -->
    <link rel="icon" type="image/png" href="{{ asset('Images/davlogo2.png') }}">
    <title>DAVCOM Consumer Goods Trading</title>
</head>
<!--NavBar Heading-->
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
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style="width:300px; height: 450px; color: black; background-color: white !important;     border: 3px solid rgb(0, 0, 0);">
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
    <h1 class="labeltitle">Dashboard</h1>
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
        <h3><b>Account:  {{ Session::get('name') }} ({{ Session::get('position') }})</h3></b>
        <!-- <li><a><img src="/Images/megaphone.png" style="width: 20px; height: 20px;" /> <b>Activity Log</b></a></li> -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="/Images/logout.png" style="width: 20px; height: 20px;" /><b>Log out</b></a></li>
        <!-- <li><select>
        <option value="light">Light Mode</option>
        <option value="dark">Dark Mode</option>
      </select></li> -->
      </ul>
    </div>
  </div>
</div>

<div class="wholediv">
<!--Menu Buttons-->

<div class="upanddown">

<div class="downdiv">
    <div class="downdash" style="overflow:auto !important;">
        <div class="stat place-items-center">
            <div class="stat-title">Low Stock Products</div>
            <table id="dataTableLowStock" class="table table-xs">
                <thead style="font-size: 20px !important; color: #222831; position: sticky; top: 0; background-color: white; text-align: center;">
                    <tr>
                        <th></th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($inventoryall as $product)
                  <tr>
                      <td>
                          <div class="avatar"  onclick="openModalpic('{{ asset('product_images/' . $product->image) }}', {{ json_encode($product->description) }})">
                              <img class="imgava" src="{{ asset('product_images/' . ($product->image ?? 'default.jpg')) }}" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                          </div>
                      </td>
                      <td style="font-size: 15px !important; text-align: center;">
                          {{ $product->category_name ?? 'N/A' }} <!-- Use category_name directly -->
                      </td>
                      <td style="font-size: 15px !important; text-align: center;">
                          {{ $product->brand_name ?? 'N/A' }} <!-- Updated to use brand_name -->
                      </td>
                      <td style="font-size: 15px !important; text-align: center;">
                          {{ $product->product_name ?? 'N/A' }} <!-- This should still work -->
                      </td>
                      <td style="font-size: 15px !important; text-align: center; color: red;">
                          <b>{{ $product->total_count ?? 0 }}</b> <!-- Ensure to show 0 if total_count is null -->
                      </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
        </div>
    </div>

    <div class="downdash" style="overflow:auto !important;">
        <div class="stat place-items-center">
            <div class="stat-title">Defective Products</div>
            <table id="dataTableDamaged" class="table table-xs">
                <thead style="font-size: 20px !important; color: #222831; position: sticky; top: 0; background-color: white; text-align: center;">
                    <tr>
                        <th></th>
                        <th>Serial #</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($damagedall as $damaged)
                    <tr>
                        <td>
                            <div class="avatar" onclick="openModalpic('{{ asset('product_images/' . $damaged->product->Image) }}', {{ json_encode($damaged->product->description) }})">
                                <img class="imgava" src="{{ asset('product_images/' . optional($damaged->product)->Image ?? 'default.jpg') }}" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                            </div>
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">{{ $damaged->serial_number ?? 'N/A' }}</td>
                        <td style="font-size: 15px !important; text-align: center;">
                            {{ optional(optional($damaged->product)->category)->Category_Name ?? 'N/A' }}
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">
                            {{ optional(optional($damaged->product)->brand)->Brand_Name ?? 'N/A' }}
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">
                            {{ optional($damaged->product)->product_name ?? 'N/A' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="updiv">

<div class="maindash" style="overflow:auto !important;">
    <div class="stat place-items-center">
            <div class="stat-title">Products Pending Inspection</div>
            <table class="table table-xs">
    <thead style="font-size: 20px !important; color: #222831; position: sticky; top: 0; background-color: white; text-align: center;">
        <tr>
            <th></th>
            <th>Serial #</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Days Since Arrived</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendingItems as $item)
            <tr>
              <td>
                <div class="avatar" onclick="openModalpic('{{ asset('product_images/' . $item->product->Image) }}', {{ json_encode($item->product->description) }})">
                  <img class="imgava" src="{{ asset('product_images/' . optional($item->product)->Image ?? 'default.jpg') }}" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                </div>
              </td>
                <td style="font-size: 15px !important; text-align: center;">{{ $item->serial_number ?? 'N/A' }}</td>
                        <td style="font-size: 15px !important; text-align: center;">
                            {{ optional(optional($item->product)->category)->Category_Name ?? 'N/A' }}
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">
                            {{ optional(optional($item->product)->brand)->Brand_Name ?? 'N/A' }}
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">
                            {{ optional($item->product)->product_name ?? 'N/A' }}
                        </td>
                        <td  style="font-size: 15px !important; text-align: center;"
                            class="{{ ($item->days_since_arrived >= 0 && $item->days_since_arrived <= 2) ? 'text-green-500' : (($item->days_since_arrived >= 3 && $item->days_since_arrived <= 5) ? 'text-yellow-500' : 'text-red-500') }}">
                            <b>{{ $item->days_since_arrived }}</b>
                        </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
  </div>
</div> <!--end of updiv--->

<dialog id="imageModal" class="modal">
  <div class="modal-box">
    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalpic()">✕</button>
    <div class="flex justify-center">
      <img id="fullImage" src="" alt="Image" style="max-width: 80%; max-height: 80vh;"/>
    </div>
    <p><b>Description:</b></p>
    <p id="imageDescription" class="mt-2"></p>
  </div>
</dialog>

</div> <!--end of upanddown--->
</div> <!--end of wholediv--->
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
</body>
</html>