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
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style="width:300px; height: 190px; color: black; background-color: white !important;     border: 3px solid rgb(0, 0, 0);">
        <li>
    <a href="{{ route('staffdashboard') }}">
      <img class="iconmenu"
      src="/Images/dashboard.png"/>
      Dashboard
    </a>
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
          <a href="{{ route('staffPending') }}">
            Pending
          </a>
        </li>
        <li>
          <a href="{{ route('staffInvside') }}">
            Inventory
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
        </li>
    </ul>
    </div>
  </div>
</div>

<div class="wholediv">

<div class="upanddown">

<div class="minitab">
  <div role="tablist" class="tabs tabs-boxed">
    <!-- <a role="tab" class="tab tab-active">Stocks</a>
    <a href="{{ route('staffPending') }}" role="tab" class="tab" id="tab">Pending</a>
    <a href="{{ route('Received') }}" role="tab" class="tab"  id="tab"style="visibility: hidden;">Received</a> -->
        <a role="tab" class="tab" style="width: 85px; height:30px; margin-left:780px">
        <div class="tooltip-C">
        <div class="tooltipB">Returned Products</div>
        <button class="trash" onclick="modalreturn.showModal()"><img class="" style="width:95px; height:55px;" src="/Images/return.png"/></button>
        </div></a>
        <a role="tab" class="tab" style="width: 85px; height:30px;">
        <div class="tooltip-C">
        <div class="tooltipB">Repairing Products</div>
        <button class="trash" onclick="modalrepair.showModal()"><img class="" style="width:95px; height:55px;" src="/Images/repair.png"/></button>
        </div></a>
        <a class="tab" style="width: 85px; height:30px !important; ">
        <div class="tooltip-C">
        <div class="tooltipB">Sold Products</div>
        <button class="trash" onclick="modalsold.showModal()"><img class="" style="width:95px; height:55px;" src="/Images/sold.png"/></button>
        </div></a>
  </div>
</div>

<dialog id="modalsold" class="modal">
    <div class="modal-box" style="width: 90%; max-width: 1400px; height: 800px;">
        <!-- Close button -->
        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('modalsold').close()">✕</button>
        
        <!-- Title -->
        <h2 class="text-2xl font-bold mb-4 text-center">Sold Products</h2>

        <!-- Sold Items List -->
        @foreach($soldItems as $item)
        <div class="bg-base-100 shadow-lg rounded-lg p-4 mb-4">
            <div class="grid grid-cols-3 items-center mt-2">
                <div class="col-span-1">
                    <p class="font-semibold">Invoice: <span class="font-normal">{{ $item->Invoice }}</span></p>              
                    <p class="font-semibold">Status: <span class="font-normal text">{{ $item->status }}</span></p>
                    <p class="font-semibold">Serial Number: <span class="font-normal">{{ $item->inventory->serial_number }}</span></p>
                </div>
                <div class="col-span-1">
                    <p class="font-semibold">Model: <span class="font-normal">{{ $item->inventory->product->product_name }}</span></p>
                    <p class="font-semibold">Date Sold: <span class="font-normal">{{ $item->date_sold }}</span></p>
                    <p class="font-semibold" style="visibility: hidden;">.<span class="font-normal"></span></p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</dialog>


 <dialog id="modalreturn" class="modal">
    <div class="modal-box" style="width: 90%; max-width: 1400px; height: 800px;">
        <!-- Close button -->
        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('modalreturn').close()">✕</button>
        
        <!-- Title -->
        <h2 class="text-2xl font-bold mb-4 text-center">Returned Products</h2>
        
        @foreach ($returneditems as $rt)
        <!-- Returned Item List -->
       <div class="bg-base-100 shadow-lga rounded-lg p-4 mb-4">
            <div class="grid grid-cols-3 items-center mt-2">
                <div class="col-span-1">
                    <p class="font-semibold">Category: <span class="font-normal">{{ $rt->product->category->Category_Name }}</span></p>              
                    <p class="font-semibold">Brand: <span class="font-normal">{{ $rt->product->brand->Brand_Name }}</span></p>
                    <p class="font-semibold">Model: <span class="font-normal">{{ $rt->product->product_name }}</span></p>
                </div>
                <div class="col-span-1">
                    <p class="font-semibold">Serial Number: <span class="font-normal">{{ $rt->serial_number }}</span></p>
                    <p class="font-semibold">Date Returned: <span class="font-normal">{{ $rt->date_returned }}</span></p>
                    <p class="font-semibold" style="">Status: <span class="font-normal">{{ $rt->status }}</span></p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</dialog>

<dialog id="modalrepair" class="modal">
    <div class="modal-box" style="width: 90%; max-width: 1400px; height: 800px;">
        <!-- Close button -->
        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('modalrepair').close()">✕</button>
        
        <!-- Title -->
        <h2 class="text-2xl font-bold mb-4 text-center">Repairing Products</h2>

       @foreach ($repairitems as $rp)
    <!-- Repair Item List -->
    <div class="bg-base-100 shadow-lg rounded-lg p-4 mb-4">
        <div class="grid grid-cols-3 items-center mt-2">
            <div class="col-span-1">
                <p class="font-semibold">Category: 
                    <span class="font-normal">
                        {{ $rp->inventory && $rp->inventory->product && $rp->inventory->product->category ? $rp->inventory->product->category->Category_Name : 'N/A' }}
                    </span>
                </p>
                <p class="font-semibold">Brand: 
                    <span class="font-normal">
                        {{ $rp->inventory && $rp->inventory->product && $rp->inventory->product->brand ? $rp->inventory->product->brand->Brand_Name : 'N/A' }}
                    </span>
                </p>
                <p class="font-semibold">Model: 
                    <span class="font-normal">
                        {{ $rp->inventory && $rp->inventory->product ? $rp->inventory->product->product_name : 'N/A' }}
                    </span>
                </p>
                <p class="font-semibold">Technician: 
                    <span class="font-normal">{{ $rp->technician ? $rp->technician->Name : 'N/A' }}</span>
                </p>
            </div>
            <div class="col-span-1">
                <p class="font-semibold">Serial Number: 
                    <span class="font-normal">{{ $rp->inventory->serial_number ?? 'N/A' }}</span>
                </p>
                <p class="font-semibold">Date Repaired: 
                    <span class="font-normal">{{ $rp->date_repaired ?? 'N/A' }}</span>
                </p>
                <p class="font-semibold">Status: 
                    <span class="font-normal">{{ $rp->status ?? 'N/A' }}</span>
                </p>
                <p class="font-semibold" style="visibility: hidden;">.<span class="font-normal"></span></p>
            </div>
        </div>
    </div>
@endforeach


    </div>
</dialog>



  <div class="div">
            <div class="boxofcontent">
                @foreach ($catinv as $cti)
                    <div class="boxes" data-category="{{ $cti->Category_Name }}" onclick="showProductsInModal('{{ $cti->Category_Name }}')">
                        <div class="product-category" style="font-size: 19px;">{{ $cti->Category_Name }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <dialog id="modalinside" class="modal">
        <div class="modal-box" style="width: 1400px; height: 800px;">
            <div class="join" style="width: 10px;">
                <div>
                  <input id="searchInput" class="input input-bordered join-item" placeholder="Search" onkeyup="filterProducts()"/>
                </div>
            </div>
            <form action="">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('modalinside').close()">✕</button>
            </form>
            <div class="boxofcontent" id="modalContent">
                <!-- Products will be dynamically loaded here -->
            </div>
        </div>
    </dialog>

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
<script>
function showProductsInModal(category) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = ''; // Clear previous content

    const products = @json($productinv); // Pass the products from your backend
    let hasApprovedProducts = false; // Flag to check if any approved products (New, Defective, Repaired) are found
    let hasPendingProducts = false; // Flag to check if any pending products are found

    products.forEach(product => {
        console.log('Checking product:', product); // Log each product being checked

        // Accessing properties based on expected structure
        const productName = product.product?.product_name;
        const description = product.product?.description;
        const image = product.product?.Image;

        if (product.product && product.product.category && product.product.category.Category_Name === category) {

            // Check if product status is Pending
            if (product.status === 'Pending') {
                hasPendingProducts = true; // Mark that we have pending products
            }

            // Only show approved products (New, Defective, Repaired)
            if (product.status === 'New' || product.status === 'Defective' || product.status === 'Repaired' || product.status === 'Repair Failed' || product.status === 'Returned Defective') {
                hasApprovedProducts = true; // Set flag to true if approved products are found

                const warrantyExpirationDate = new Date(product.warranty_supplier);
                const currentDate = new Date();

                // Check conditions for buttons
                const isDamaged = product.status === 'Defective';
                const isWarrantyExpired = currentDate > warrantyExpirationDate;

                modalContent.innerHTML += `
                <div class="card product-card bg-base-100 w-96 shadow-xl">
                    <figure>
                        <img src="{{ asset('product_images/') }}/${image}" alt="" />
                    </figure>
                    <div class="card-body items-center text-center">
                        <h2 class="card-title">${productName}</h2>
                        <p style="font-size:15px;">${description}</p>
                        <div class="card-actions justify-end">
                            <div class="badge badge-outline" style="font-size:9px;">Warranty: ${product.warranty_supplier}</div>
                            <div class="badge badge-outline" style="font-size:9px;">Status: ${product.status}</div>
                        </div>
                        <div class="badge badge-outline product-serial" style="font-size:9px;">Serial: ${product.serial_number}</div>
                        <div class="badge badge-outline product-model" style="font-size:9px;">Model: ${product.model}</div>
                        <div class="badge badge-outline" style="font-size:9px;">Arrived: ${product.date_arrived}</div>
                        <div class="card-actions justify-end">
                        </div>
                    </div>
                </div>`;

            }
        }
    });

    // Show pending message if no approved products are found and there are pending products
    if (!hasApprovedProducts && hasPendingProducts) {
        modalContent.innerHTML += `<h1><b>There are products for this category, but their status is still pending.</b></h1>`;
    }

    // If no products were found for the category, display a message
    if (!hasApprovedProducts && !hasPendingProducts) {
        modalContent.innerHTML = `<h1><b>No products available for this category.</b></h1>`;
    }

    document.getElementById('modalinside').showModal(); // Show the modal
}
function filterProducts() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const serialNumber = card.querySelector('.product-serial').textContent.toLowerCase();
        const model = card.querySelector('.product-model').textContent.toLowerCase();

        if (serialNumber.includes(searchInput) || model.includes(searchInput)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>


<script src="{{ asset('js/alert.js') }}"></script>
</body>
</html>