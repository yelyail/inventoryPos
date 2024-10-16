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
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
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
    <h1 class="labeltitle">Products</h1>
  </div>
<div class="join">
    <div>
      <input id="searchInput" class="input input-bordered join-item" placeholder="Search" onkeyup="filterTable()" />
    </div>
  <select id="filterSelect" class="select select-bordered join-item" onchange="filterTable()">
    <option disabled selected>Filter</option >
    <option value="category_name">Category</option>
    <option value="brand_name">Brand</option>
    <option value="product_name">Product Name</option>
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

<div class="upanddown">

<div class="minitab">
  <div role="tablist" class="tabs tabs-boxed">
    <a class="tab" style="width: 70px; height:30px !important; margin-left:1120px;">
    <div class="tooltip-C">
    <div class="tooltipB">Phase-out Products</div>
    <button class="trash" onclick="modaldisposedd.showModal()"><img class="" style="width:45px; height:45;" src="/Images/center.png"/></button>
    </div></a>

<dialog id="modaldisposedd" class="modal" style="z-index: 100 !important;">
    <div class="modal-box" style="width:1400px; height: 800px;">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h2 class="labeltitle" style="padding-left: 100px;">Phase-out Products</h2>

        <!-- Table for disabled products -->
        <table class="table table-lg" style="background-color: transparent;">
            <thead>
                <tr>
                    <th></th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Product Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="disabled-products-table">
                @if($inactiveProducts->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No phase-out products found.</td>
                    </tr>
                @else
                    @foreach($inactiveProducts as $PD) 
                        <tr>
                            <td>
                                <div class="avatar" onclick="openModalpic('{{ asset('product_images/' . $PD->Image) }}', {{ json_encode($PD->description) }})">
                                    <img class="imgava" src="{{ asset('product_images/' . $PD->Image) }}" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                                </div>
                            </td>
                            <td>{{ $PD->category ? $PD->category->Category_Name : 'N/A' }}</td>
                            <td>{{ $PD->brand ? $PD->brand->Brand_Name : 'N/A' }}</td>
                            <td>{{ $PD->product_name }}</td>
                            <td>
                              <form id="reactivateForm{{ $PD->Product_ID }}" action="{{ route('reactivate.product', $PD->Product_ID) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="tooltip-C">
                                    <div class="tooltipB">Reactivate</div>
                                    <button type="button" class="reactivate-bt" onclick="showACTModal('{{ $PD->Product_ID }}')">
                                        <img src="{{ asset('images/able.png') }}" alt="Reactivate" width="35">
                                    </button>
                              </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</dialog>

    
    <a role="tab" class="tab">
    <button class="btn" onclick="my_modal_3.showModal()">➕ Create Product</button>
    </a>

  <dialog id="my_modal_3" class="modal">
  <div class="modal-box">
    <!-- Close Button -->
    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('my_modal_3').close()">✕</button>

    <!-- Modal Content Form -->
    <form action="{{ route('prod.add') }}" method="post" class="formbra" enctype="multipart/form-data">
      @csrf
      <div class="">
        <label for="image" class="block">Image:</label>
        <input type="file" id="image"  name="imgname" class="file-input file-input-bordered w-full max-w-xs" required/>
      </div>

      <div class="">
        <label for="category" class="block">Category</label>
        <select  name="catname" id="category"  class="select select-bordered w-full max-w-xs" required>
          <option value="" disabled selected>Select Category</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->Category_ID }}">{{ $cat->Category_Name }}</option>
          @endforeach
        </select>
      </div>
      
      <div class="">
        <label for="brandName" class="block">Brand Name</label>
        <select id="brandName" name="braname" class="select select-bordered w-full max-w-xs" required>
          <option value="" disabled selected>Select Brand</option>
          @foreach($brands as $BN)
          <option value="{{ $BN->Brand_ID }}">{{ $BN->Brand_Name }}</option>
          @endforeach
        </select>
      </div>

      <div class="">
        <label for="productName" class="block">Product Name</label>
        <input type="text" id="productName" name="prodname" placeholder="Product Name" class="input input-bordered w-full max-w-xs" required />
      </div>

        <div class="">
        <label for="Description" class="block">Description</label>
        <textarea class="textarea textarea-bordered" placeholder="Description" name="descp" style="width: 400px;"></textarea>
        </div>

      <div>
        <button class="btn btn-primary w-full">➕ Create</button>
      </div>
    </form>
  </div>
</dialog>


</a>
  </div>
</div>

<div class="downdiv">
<div class="overflow-x-auto">
  <table id="dataTable" class="table table-lg" style="width: 1450px !important;">
    <thead>
      <tr>
        <th style="width: 20%;"></th>
        <th style="width: 20%;">Category</th>
        <th style="width: 20%;">Brand</th>
        <th style="width: 20%;">Model</th>
        <th style="width: 20%;">Action</th>
      </tr>
    </thead>
    <tbody>
      @if($products->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No products found.</td>
                    </tr>
                @else
      @foreach($products as $PD)
      <tr>
        <td>
         <div class="avatar" onclick="openModalpic('{{ asset('product_images/' . $PD->Image) }}', {{ json_encode($PD->description) }})">
          <img class="imgava" src="{{ asset('product_images/' . $PD->Image) }}" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
         </div>

        </td>
        <td>{{ $PD->category ? $PD->category->Category_Name : 'N/A' }}</td>
        <td>{{ $PD->brand ? $PD->brand->Brand_Name : 'N/A' }}</td>
        <td>{{ $PD->product_name }}</td>
        <td>

      <div class="tooltip-C">
    <div class="tooltipB">Update</div>
   <button type="button" class="edit-bt" 
    onclick="showUpdateModal({{ $PD->Product_ID }}, '{{ $PD->category->Category_ID ?? '' }}', '{{ $PD->brand->Brand_ID ?? '' }}', '{{ $PD->product_name }}', '{{ $PD->description }}')">
    <img src="{{ asset('images/update.png') }}" alt="Update" width="35">
</button>
</div>

<dialog id="Updatemodal{{ $PD->Product_ID }}" class="modal">
    <div class="modal-box">
        <!-- Close Button -->
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('Updatemodal{{ $PD->Product_ID }}').close()">✕</button>

        <form action="{{ route('prod.update', $PD->Product_ID) }}" method="post" class="formbra" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="imageup{{ $PD->Product_ID }}" class="block">Image:</label>
                <input type="file" id="imageup{{ $PD->Product_ID }}" name="imgname" class="file-input file-input-bordered w-full max-w-xs"/>
            </div>

            <div>
                <label for="categoryup{{ $PD->Product_ID }}" class="block">Category</label>
                <select name="catname" id="categoryup{{ $PD->Product_ID }}" class="select select-bordered w-full max-w-xs" required>
                    <option value="" disabled>Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->Category_ID }}" {{ $cat->Category_ID == $PD->category->Category_ID ? 'selected' : '' }}>
                            {{ $cat->Category_Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="brandNameup{{ $PD->Product_ID }}" class="block">Brand Name</label>
                <select id="brandNameup{{ $PD->Product_ID }}" name="braname" class="select select-bordered w-full max-w-xs" required>
                    <option value="" disabled>Select Brand</option>
                    @foreach($brands as $BN)
                        <option value="{{ $BN->Brand_ID }}" {{ $BN->Brand_ID == $PD->brand->Brand_ID ? 'selected' : '' }}>
                            {{ $BN->Brand_Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="productNameup{{ $PD->Product_ID }}" class="block">Product Name</label>
                <input type="text" id="productNameup{{ $PD->Product_ID }}" name="prodname" placeholder="Product Name" class="input input-bordered w-full max-w-xs" value="{{ $PD->product_name }}" required />
            </div>

            <div>
                <label for="disup{{ $PD->Product_ID }}" class="block">Description</label>
                <textarea id="disup{{ $PD->Product_ID }}" class="textarea textarea-bordered" placeholder="Description" name="descp" style="width: 400px;">{{ $PD->description }}</textarea>
            </div>

            <div>
                <button class="btn btn-primary w-full">Update</button>
            </div>
          </form>
      </div>
  </dialog>
            <form style="display:inline;" id="deleteForm{{ $PD->Product_ID }}" action="{{ route('prod.delete', $PD->Product_ID) }}" method="POST">
              @csrf
              @method('DELETE')
              <div class="tooltip-C">
                  <div class="tooltipB">phase-out</div>
                  <button type="button" class="disable-bt" onclick="confirmDelete('{{ $PD->Product_ID }}')">
                      <img src="{{ asset('images/disable.png') }}" alt="Phase out" width="35">
                  </button>
              </div>
          </form>
        </td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div> <!--end of upanddown--->
</div> <!--end of wholediv--->

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


<!-- Success Alert -->
@if(session('success'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform -translate-y-full opacity-0 transition-transform duration-500 ease-in-out">
        <div class="flex items-center">
        <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('success-deleted'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform -translate-y-full opacity-0 transition-transform duration-500 ease-in-out">
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
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-black">
        <h2 class="text-xl font-semibold mb-4">Confirmation</h2>
        <p>Are you sure you want to phase-out this product?</p>
        <div class="mt-6 flex justify-end space-x-4">
            <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Confirm</button>
        </div>
    </div>
</div>

<!-- Reactivation Confirmation Modal -->
<div id="activeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-black">
        <h2 class="text-xl font-semibold mb-4">Reactivate Product</h2>
        <p>Are you sure you want to reactivate this product?</p>
        <div class="mt-6 flex justify-end space-x-4">
            <button id="cancelact" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            <button id="confirmReactivate" class="bg-green-500 text-white px-4 py-2 rounded">Confirm</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/Product.js') }}"></script>
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/Confirmdelete.js') }}"></script>
</body>
</html>