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
  <link rel="stylesheet" href="{{ asset('css/Invside.css') }}">
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
    <h1 class="labeltitle">Inventory</h1>
  </div>
  <div class="join">
    <div>
      <input id="searchInput" class="input input-bordered join-item" placeholder="Search" onkeyup="filterTable()" />
    </div>
    <select id="filterSelect" class="select select-bordered join-item" onchange="filterTable()">
      <option disabled selected>Filter</option>
      <option value="All">All</option>
      <option value="For Approval">For Approval</option>
      <option value="For Inspection">For Inspection </option>
    </select>
  </div>
  <div class="flex-none gap-2">
    <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
          <img alt="Tailwind CSS Navbar component" src="/Images/account.png" style="filter: invert(1) !important;" />
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
        </li>
    </ul>
        </div>
      </div>
    </div>

<div class="wholediv">

  <div class="upanddown">
    <div class="minitab">
      <div role="tablist" class="tabs tabs-boxed">
        <!-- <a href="{{ route('Invside') }}" role="tab" class="tab" id="tab">Stocks</a>
        <a role="tab" class="tab tab-active" id="tab">Pending</a> -->
        <a href="{{ route('Received') }}" role="tab" class="tab"  id="tab"style="visibility: hidden;">Received</a>
        <a class="tab" style="width: 70px; height:30px !important; margin-left:850px">
        <div class="tooltip-C">
        <div class="tooltipB"></div>
        </div></a>
        <a role="tab" class="tab">
          <button class="btn" onclick="my_modal_3.showModal()">➕ Add Pending</button>
          
          <dialog id="my_modal_3" class="modal">
            <div class="modal-box" style="width:1000px; height: 800px; ">
               <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
             <select name="category" id="category" class="select select-bordered w-full max-w-xs" style="position: absolute; right: 650px; z-index: 10;" onchange="filterByCategory()">
                  <option value="" disabled selected>Select Category:</option>
                  <option value="all">All Category</option>
                  @foreach($CategorySL as $catSL)
                      <option value="{{ $catSL->Category_Name }}">{{ $catSL->Category_Name }}</option>
                  @endforeach
              </select>
              </form>
              
          <form method="post" action="{{ route('pending.add') }}" class="formbra" enctype="multipart/form-data">
                @csrf
                <label for="" class="block">Select a product</label>
                <!-- Product Table -->
          <div class="insude" style="height:260px; overflow:auto;">
            <table id="productTable" class="table table-sm">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th>Category</th>
                  <th>Brand</th>
                  <th>Model</th>
                </tr>
              </thead>
              <tbody>
                @if($ProductSL->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No active products found.</td>
                    </tr>
                @else
                  @foreach($ProductSL as $PD)
                  <tr data-category="{{ strtolower($PD->category ? $PD->category->Category_Name : 'n/a') }}">
                  <td><input type="radio" name="product_id" value="{{ $PD->Product_ID }}" style="width: 30px; height:30px;" required></td>
                  <td>
                  <div class="avatar" onclick="openModalpic('{{ asset('product_images/' . $PD->Image) }}', {{ json_encode($PD->description) }})">
                    <img src="{{ asset('product_images/' . $PD->Image) }}" alt="" class="imgava" style="width: 50px; height: 50px; border-radius: 50%;">
                  </td>
                  <td>{{ $PD->category ? $PD->category->Category_Name : 'N/A' }}</td>
                  <td>{{ $PD->brand ? $PD->brand->Brand_Name : 'N/A' }}</td>
                  <td>{{ $PD->product_name }}</td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
    
    <!-- Form fields to be populated -->
    <div class="input-group">
      <div class="input-item">
            <label for="batch_number" class="block">Supplier:</label>
            <select name="Supplier_ID" id="" class="select select-bordered w-full max-w-xs" required>
              <option value="" disabled selected>Select Supplier</option>
              @foreach($supSL as $sp)
                      <option value="{{ $sp->Supplier_ID }}">{{ $sp->Company_Name }}</option>
              @endforeach
            </select>
        </div>
        <div class="input-item">
            <label for="serial_number" class="block">Serial Number:</label>
            <input type="text" name="serial_number" class="input input-bordered" placeholder="Enter Serial Number" required>
        </div>
        <div class="input-item">
          <label for="batch_number" class="block">Received Report:</label>
          <input type="file" id="image"  name="reportnameimage" class="file-input file-input-bordered w-full max-w-xs" required/>
        </div>
    </div>

    <div class="input-group">
      <div class="input-item">
          <label for="batch_number" class="block">Date Arrived:</label>
          <input type="datetime-local" name="datearrived" id="" class="input input-bordered" required>
        </div >
        <div class="input-item">
          <label for="batch_number" class="block">Date Inspected:</label>
          <input type="datetime-local" name="dateinspected" id="" class="input input-bordered">
        </div>
    </div>
          <button class="btn btn-primary w-full mt-4">➕ Add to Pending</button>
      </form>
          </div>
      </dialog>
        </a>
      </div>
    </div>

    <!-- Modal for full image display -->
<dialog id="imageModal" class="modal">
    <div class="modal-box" style="overflow:auto;">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
        <img id="fullImage" src="" alt="Full Size Image" style="width: 100%; height: auto;">
        <p><b>Description:</b></p>
        <p id="imageDescription" class="mt-2"></p>
    </div>
</dialog>

<dialog id="reportModal" class="modal">
    <div class="modal-box" style="overflow:auto;">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
        <img id="reportimg" src="" alt="Full Size Image" style="width: 100%; height: auto;">
    </div>
</dialog>



<div class="downdiv">
  <div class="overflow-x-auto" style="width: 1550px;">
    <table id="dataTable" class="table table-lg">
      <thead style="font-size: 20px !important; color: #222831; position: sticky; top: 0; background-color: white; text-align: center;">
        <tr>
          <th style="width: 10%;"></th>               
          <th style="width: 10%;">Serial #</th>
          <th style="width: 10%;">Category</th>
          <th style="width: 10%;">Brand</th>
          <th style="width: 10%;">Model</th>
          <th style="width: 10%;">Date Arrived</th>
          <th style="width: 10%;">Date Checked</th>
          <th style="width: 10%;">Delivery Report Photo</th>
          <th style="width: 10%;">Action</th>
        </tr>
      </thead>
      <tbody>
        @if($pendingInventories->isEmpty())
          <tr>
            <td colspan="8" class="text-center">No pending inventory found.</td>
          </tr>
        @else
          @foreach($pendingInventories as $item)
            <tr>
              <td>
                @if($item->product)
                <div class="avatar" onclick="openModalpic('{{ asset('product_images/' . $item->product->Image) }}', {{ json_encode($item->product->description) }})">
                    <img class="imgava" src="{{ asset('product_images/' . $item->product->Image) }}" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                  </div>
                @else
                  <span>N/A</span>
                @endif
              </td>
              <td>{{ $item->serial_number }}</td>
              <td>{{ $item->product && $item->product->category ? $item->product->category->Category_Name : 'N/A' }}</td>
              <td>{{ $item->product && $item->product->brand ? $item->product->brand->Brand_Name : 'N/A' }}</td>
              <td>{{ $item->product ? $item->product->product_name : 'N/A' }}</td>
              <td>{{ \Carbon\Carbon::parse($item->date_arrived)->format('m/d/Y, h:i A') }}</td>
              <td>{{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('m/d/Y, h:i A') : 'N/A' }}</td>
              <td>
                @if($item->deliveryR_photo)
                  <div class="avatar" onclick="openModalreport('{{ asset('product_reportdev_images/' . $item->deliveryR_photo) }}')">
                    <img class="imgava" src="{{ asset('product_reportdev_images/' . $item->deliveryR_photo) }}" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                  </div>
                @else
                  <span>N/A</span>
                @endif
              </td>
              <td>
                  @if($item->date_checked)
                <form style="display:inline;" method="POST" action="{{ route('product.approved', $item->Inventory_ID) }}">
                  @csrf
                  @method('PATCH')
                  <div class="tooltip-C">
                    <div class="tooltipB">Approve</div>
                    <button type="submit" class="edit-bt">
                      <img src="{{ asset('images/approve.png') }}" alt="Approve" width="45">
                    </button>
                  </div>
                </form>
                @endif
        @csrf
        <div class="tooltip-C">
          <div class="tooltipB">Edit</div>
          <!-- Attach the modal opening to the edit button -->
          <button type="button" class="edit-bt" onclick="showpendingmodal({{ $item->Inventory_ID }}, '{{ $item->Supplier_ID }}', '{{ $item->serial_number }}', '{{ $item->date_arrived }}', '{{ $item->date_checked }}')">
            <img src="{{ asset('images/update.png') }}" alt="Edit" width="45">
          </button>
        </div>
    </td>
  </tr>
@endforeach
        @endif
      </tbody>
    </table>
  </div>
</div>

</div>
</div>
    @foreach($pendingInventories as $item)
<dialog id="modaleditpending{{ $item->Inventory_ID }}" class="modal">
    <div class="modal-box" style="height: 560px;">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('modaleditpending{{ $item->Inventory_ID }}').close()">✕</button>
        <form id="editForm{{ $item->Inventory_ID }}" method="POST" action="{{ route('pending.update', $item->Inventory_ID) }}" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col justify-center items-center space-y-4">
                <!-- Supplier Selection -->
                <div class="input-item w-full max-w-xs">
                    <label for="Supplier_ID">Supplier:</label>
                    <select name="snumberid" id="suid{{ $item->Inventory_ID }}" class="select select-bordered w-full" required>
                        <option value="" disabled>Select Supplier</option>
                        @foreach($supSL as $sp)
                            <option value="{{ $sp->Supplier_ID }}">{{ $sp->Company_Name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Serial Number -->
                <div class="input-item w-full max-w-xs">
                    <label for="serial_number">Serial Number:</label>
                    <input type="text" name="snumber" id="senum{{ $item->Inventory_ID }}" class="input input-bordered w-full" placeholder="Enter Serial Number" required>
                </div>

                <!-- Received Report File Input -->
                <div class="input-item w-full max-w-xs">
                    <label for="reportnameimage">Received Report:</label>
                    <input type="file" name="rnameimage" id="rimage{{ $item->Inventory_ID }}" class="file-input file-input-bordered w-full"/>
                </div>

                <!-- Date Arrived -->
                <div class="input-item w-full max-w-xs">
                    <label for="datearrived">Date Arrived:</label>
                    <input type="datetime-local" name="darrived" id="drive{{ $item->Inventory_ID }}" class="input input-bordered w-full" required>
                </div>

                <!-- Date Inspected -->
                <div class="input-item w-full max-w-xs">
                    <label for="dateinspected">Date Inspected:</label>
                    <input type="datetime-local" name="dinspect" id="dinsp{{ $item->Inventory_ID }}" class="input input-bordered w-full">
                </div>

                <!-- Update Button -->
                <button type="submit" class="btn btn-primary w-full">Update</button>
            </div>
        </form>
    </div>
</dialog>
@endforeach

<!-- Success Alert -->
@if(session('success'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform -translate-y-full opacity-0 transition-transform duration-500 ease-in-out">
        <div class="flex items-center">
        <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif
@if(session('error'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg">
        <div class="flex items-center">
            <img src="/image-Icon/danger.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/Product.js') }}"></script>
<script src="{{ asset('js/pending.js') }}"></script>
<script>
function openModal() {
    // You can dynamically populate the category options here using JS if $CategorySL is available
    let categories = @json($CategorySL);
    let select = document.getElementById('category');
    
    // Clear existing options in the select
    select.innerHTML = `<option value="" disabled selected>Select Category:</option>
                        <option value="all">All Categories</option>`;
    
    categories.forEach(category => {
        let option = document.createElement('option');
        option.value = category.Category_Name;
        option.text = category.Category_Name;
        select.appendChild(option);
    });

    // Show the modal
    document.getElementById('my_modal_3').showModal();
}

function filterByCategory() {
    const selectedCategory = document.getElementById('category').value.toLowerCase();
    const rows = document.querySelectorAll('#productTable tbody tr');

    rows.forEach(row => {
        const rowCategory = row.getAttribute('data-category');
        if (selectedCategory === 'all' || rowCategory.includes(selectedCategory)) {
            row.style.display = ''; // Show row
        } else {
            row.style.display = 'none'; // Hide row
        }
    });
}
</script>
</body>
</html>
