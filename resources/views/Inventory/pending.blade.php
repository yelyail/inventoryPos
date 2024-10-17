@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')


<div class="upanddown">

<div class="minitab">
  <div role="tablist" class="tabs tabs-boxed" style="background-color: transparent;">
    <a href="{{ route('inventory') }}" role="tab" class="tab">Stocks</a>
    <a href="{{ route('pending') }}" role="tab" class="tab tab-active" id="tab">Pending Inspection</a>
    <a class="tab" style="margin-left:650px; background-color:transparent;"><button class="btn" onclick="my_modal_3.showModal()" style="background-color:#222831; color: white;">➕ Add Pending</button></a>
  </div>
</div>

<dialog id="my_modal_3" class="modal">
        <div class="modal-box" style="width:2400px !important; height: 800px;">
          <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            <select name="category" id="category" class="select select-bordered w-full max-w-xs" style="position: absolute; right: 650px; z-index: 10;" onchange="filterByCategory()">
              <option value="" disabled selected>Select Category:</option>
              <option value="all">All Category</option>
              <!-- Category options here -->
            </select>
          </form>
          <form method="post" action="#" class="formbra" enctype="multipart/form-data">
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
                  <!-- Product rows here -->
                </tbody>
              </table>
            </div>
    
            <!-- Form fields to be populated -->
            <div class="input-group">
              <div class="input-item">
                <label for="batch_number" class="block">Supplier:</label>
                <select name="Supplier_ID" id="" class="select select-bordered w-full max-w-xs" required>
                  <option value="" disabled selected>Select Supplier</option>
                  <!-- Supplier options here -->
                </select>
              </div>
              <div class="input-item">
                <label for="serial_number" class="block">Serial Number:</label>
                <input type="text" name="serial_number" class="input input-bordered" placeholder="Enter Serial Number" required>
              </div>
              <div class="input-item">
                <label for="batch_number" class="block">Received Report:</label>
                <input type="file" id="image" name="reportnameimage" class="file-input file-input-bordered w-full max-w-xs" required/>
              </div>
            </div>
    
            <div class="input-group">
              <div class="input-item">
                <label for="batch_number" class="block">Date Arrived:</label>
                <input type="datetime-local" name="datearrived" id="" class="input input-bordered" required>
              </div>
              <div class="input-item">
                <label for="batch_number" class="block">Date Inspected:</label>
                <input type="datetime-local" name="dateinspected" id="" class="input input-bordered">
              </div>
            </div>
            <button class="btn btn-primary w-full mt-4">➕ Add to Pending</button>
          </form>
        </div>
      </dialog>

      <div class="div-table" style="width: 1310px; margin-top:20px;">
      <table id="pendingTable" class="table table-bordered">
        <thead>
          <tr>
            <th>Category</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Status</th>
            <th>Batch Number</th>
            <th>Date Arrived</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Table rows for pending items here -->
        </tbody>
      </table>
    </div>
  </div>

    </div>

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
@endsection