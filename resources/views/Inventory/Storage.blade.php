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
  </div>
<div class="join">
  <div class="flex items-center">
    <input id="searchInput" class="input input-bordered join-item" placeholder="Search" onkeyup="filterTable()" />
  </div>
  <select id="filterSelect" class="select select-bordered join-item" onchange="filterTable()">
    <option value="all">All</option>
    <option value="brand_name">Brand Name</option>
    <option value="category_name">Category Name</option>
  </select>
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

<div class="minitab">
  <div role="tablist" class="tabs tabs-boxed">
<a class="tab" style="margin-left: 900px !important;">
<button class="btn" onclick="my_modal_3.showModal()">➕ Add Brand</button>
<dialog id="my_modal_3" class="modal">
  <div class="modal-box">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <form method="post" action="{{ route('brand.add') }}" class="formbra">
      @csrf
      <label for="" class="block">Brand Name:</label>
      <input id="brandname" name="brandname" type="text" placeholder="Brand Name" class="input input-bordered" required/>
      <button class="btn btn-primary w-full">➕ Add</button>
    </form>
  </div>
</dialog></a>

<a class="tab" style="margin-left: 20px;!important;">
<button class="btn" onclick="my_modal_4.showModal()">➕ Add Category</button>
<dialog id="my_modal_4" class="modal">
  <div class="modal-box">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <form method="post" action="{{ route('cat.add') }}" class="formbra">
      @csrf
      <label for="" class="block">Category Name:</label>
      <input type="text" name="catname" id="catname" placeholder="Category Name" class="input input-bordered" required/>
      <button class="btn btn-primary w-full">➕ Add</button>
    </form>
  </div>
  </dialog>
</a>
  </div>


<div class="flex justify-center mb-2" style="gap:590px;">
  <h2 class="text-xl"><b>Brands</b></h2>
  <h2 class="text-xl"><b>Categories</b></h2>
</div>

<div class="downdiv flex justify-between" style="gap:10px;">
                <div class="overflow-x-auto w-1/2">
                    <table id="dataTable" class="table-lg w-full">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Brand Name</th>
                                <th style="width: 50%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $BN)
                            <tr>
                                <td>{{ $BN['Brand_Name'] }}</td>
                                <td>
                                    <div class="tooltip-C">
                                        <div class="tooltipB">Update</div>
                                        <button type="button" class="edit-bt" onclick="updaterB('{{ $BN->Brand_ID }}', '{{ $BN->Brand_Name }}')">
                                            <img src="{{ asset('images/update.png') }}" alt="Update" width="35">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <dialog id="Updatebrand" class="modal">
                  <div class="modal-box">
                      <form method="dialog">
                          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                      </form>
                      <form method="post" action="{{ route('brand.update') }}" class="formbra">
                          @csrf
                          <input type="hidden" name="brand_id" id="brand_id_up">
                          <label for="" class="block">Brand Name:</label>
                          <input type="text" name="brandname" id="brandnameup" placeholder="Brand Name" class="input input-bordered" required />
                          <button class="btn btn-primary w-full">Update</button>
                      </form>
                  </div>
              </dialog>

                <div class="overflow-x-auto w-1/2">
                    <table id="categoryTable" class="table-lg w-full">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Category Name</th>
                                <th style="width: 50%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $CT)
                            <tr>
                                <td>{{ $CT['Category_Name'] }}</td>
                                <td>
                                    <div class="tooltip-C">
                                        <div class="tooltipB">Update</div>
                                        <button type="button" class="edit-bt" onclick="updateCategory('{{ $CT->Category_ID }}', '{{ $CT->Category_Name }}')">
                                            <img src="{{ asset('images/update.png') }}" alt="Update" width="35px">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
  <dialog id="UpdateCategory" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <form method="post" action="{{ route('category.update') }}" class="formbra">
            @csrf
            <input type="hidden" name="category_id" id="category_id_up"> <!-- Hidden input for category ID -->
            <label for="" class="block">Category Name:</label>
            <input type="text" name="categoryname" id="categorynameup" placeholder="Category Name" class="input input-bordered" required />
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

    <!-- Delete Confirmation Modal
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-black">
            <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
            <p>Are you sure you want to delete this item?</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </div>
    </div> -->
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/Storage.js') }}"></script>
<!-- <script src="{{ asset('js/Confirmdelete.js') }}"></script> -->
</body>
</html>