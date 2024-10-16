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
  </div>
  <div class="join">
    <div class="flex items-center">
      <input id="searchInput" class="input input-bordered join-item" placeholder="Search" onkeyup="filterTable()" />
    </div>
    <select id="filterSelect" class="select select-bordered join-item" onchange="filterTable()">
      <option value="all">All</option>
      <option value="supplier_name">Supplier Name</option>
      <option value="technician_name">Technician Name</option>
    </select>
  </div>

  <div class="flex-none gap-2">
    <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
          <img alt="Tailwind CSS Navbar component" src="/Images/account.png" style="filter: invert(1) !important;" />
        </div>
      </div>
      <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style="border: 2px solid black; background-color:whitesmoke; color:black; z-index: 1000;">
        <h3><b>Account: {{ Session::get('name') }} ({{ Session::get('position') }})</h3></b>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="/Images/logout.png" style="width: 20px; height: 20px;" /><b>Log out</b></a></li>
      </ul>
    </div>
  </div>
</div>

<div class="wholediv">
  <div class="minitab">
    <div role="tablist" class="tabs tabs-boxed">
      <a class="tab" style="margin-left: 900px !important;">
      <button class="btn" onclick="my_modal_3.showModal()">➕ Add Supplier</button>
      <dialog id="my_modal_3" class="modal">
        <div class="modal-box">
          <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
          </form>
          <form method="post" action="{{ route('sup.add') }}" class="formbra">
            @csrf
            <label for="" class="block">Supplier Name:</label>
            <input id="suppliername" name="suppliername" type="text" placeholder="Supplier Name" class="input input-bordered" required />
            <label for="" class="block">Contact:</label>
            <input id="suppliername" name="suppliercon" type="text" placeholder="" class="input input-bordered" required />
            <button class="btn btn-primary w-full">➕ Add</button>
          </form>
        </div>
      </dialog></a>

      <a class="tab" style="margin-left: 20px;!important;">
      <button class="btn" onclick="my_modal_4.showModal()">➕ Add Technician</button>
      <dialog id="my_modal_4" class="modal">
        <div class="modal-box">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <form method="post" action="{{ route('tech.add') }}" class="formbra">
      @csrf
      <label for="" class="block">Technician Name:</label>
      <input type="text" name="techname" id="techname" placeholder="Technician Name" class="input input-bordered" required/>
      <label for="" class="block">Position Level:</label>
      <select id="filterSelect" name="techpos" class="select select-bordered join-item">
        <option value="" disabled selected>Select Position</option>
        <option value="Senior Technician">Senior Technician</option>
        <option value="Junior Technician">Junior Technician</option>
      </select>
      <button class="btn btn-primary w-full">➕ Add</button>
    </form>
  </div>
  </dialog>
</a>
    </div>

    <div class="flex justify-center mb-2" style="gap:650px;">
      <h2 class="text-xl"><b>Suppliers</b></h2>
      <h2 class="text-xl"><b>Technicians</b></h2>
    </div>

    <div class="downdiv flex justify-between" style="gap:10px;">
      <div class="overflow-x-auto w-1/2">
        <table id="supplierTable" class="table-lg w-full">
          <thead>
            <tr>
              <th>Supplier Name</th>
              <th>Contact</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($suppliers as $supplier)
            <tr>
              <td>{{ $supplier['Company_Name'] }}</td>
              <td>{{ $supplier['Contact'] }}</td>
              <td>
                <div class="tooltip-C">
                  <div class="tooltipB">Update</div>
                  <button type="button" class="edit-bt" onclick="updateSupplier('{{ $supplier->Supplier_ID }}', '{{ $supplier->Company_Name }}','{{ $supplier->Contact }}')">
                    <img src="{{ asset('images/update.png') }}" alt="Update" width="35">
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <dialog id="UpdateSupplier" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <form method="post" action="{{ route('sup.update') }}" class="formbra"> <!-- Adjust route -->
            @csrf
            <input type="hidden" name="supplier_idup" id="supplier_id_up">
            <label for="" class="block">Supplier Name:</label>
            <input type="text" name="suppliernameup" id="suppliernameup" placeholder="Supplier Name" class="input input-bordered" required />
            <label for="" class="block">Contact:</label>
            <input type="text" name="supplierconup" id="supplierconup" placeholder="Contact" class="input input-bordered" required /> <!-- Added contact input -->
            <button class="btn btn-primary w-full">Update</button>
        </form>
    </div>
</dialog>

      <div class="overflow-x-auto w-1/2">
        <table id="technicianTable" class="table-lg w-full">
          <thead>
            <tr>
              <th >Technician Name</th>
              <th>Position Level</th>
              <th >Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($technicians as $technician)
            <tr>
              <td>{{ $technician['Name'] }}</td>
              <td>{{ $technician['Position_Level'] }}</td>
              <td>
                <div class="tooltip-C">
                  <div class="tooltipB">Update</div>
                  <button type="button" class="edit-bt" onclick="updateTechnician('{{ $technician->Technician_ID }}', '{{ $technician->Name }}','{{ $technician->Position_Level }}')">
                    <img src="{{ asset('images/update.png') }}" alt="Update" width="35px">
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <dialog id="UpdateTechnician" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <form method="post" action="{{ route('tech.update') }}" class="formbra"> <!-- Adjust route -->
            @csrf
            <input type="hidden" name="technician_idup" id="technician_id_up">
            <label for="" class="block">Technician Name:</label>
            <input type="text" name="techniciannameup" id="techniciannameup" placeholder="Technician Name" class="input input-bordered" required />
            <label for="" class="block">Position Level:</label>
            <select name="position_levelup" id="position_level_up" class="select select-bordered join-item" required>
                <option value="Senior Technician">Senior Technician</option>
                <option value="Junior Technician">Junior Technician</option>
            </select>
            <button class="btn btn-primary w-full">Update</button>
        </form>
    </div>
</dialog>

    </div>
  </div>
</div>

<!-- Success Alerts -->
@if(session('success'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg">
        <div class="flex items-center">
            <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('success-deleted'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg">
        <div class="flex items-center">
            <img src="/image-Icon/trash.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success-deleted') }}</span>
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
<script src="{{ asset('js/suptech.js') }}"></script>
</body>
</html>
