@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div class="downdiv" style="overflow:auto !important; background-color:#00000013;">
    
<div class="card card-compact bg-base-100 w-96 shadow-xl">
  <figure>
    <img
      src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
      alt="Shoes" />
  </figure>
  <div class="card-body">
    <h2 class="card-title">Shoes!</h2>
    <p>Stock: 10</p>
      <button class="btn btn-primary">Buy</button>
  </div>
</div>

<div class="card card-compact bg-base-100 w-96 shadow-xl">
  <figure>
    <img
      src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
      alt="Shoes" />
  </figure>
  <div class="card-body">
    <h2 class="card-title">Shoes!</h2>
    <p>Stock: 10</p>
      <button class="btn btn-primary">Buy</button>
  </div>
</div>

<div class="card card-compact bg-base-100 w-96 shadow-xl">
  <figure>
    <img
      src="Images/aw.jpg"
      alt="Shoes" />
  </figure>
  <div class="card-body">
    <h2 class="card-title">Shoes!</h2>
    <p>Stock: 10</p>
      <button class="btn btn-primary">Buy</button>
  </div>
</div>

<div class="card card-compact bg-base-100 w-96 shadow-xl">
  <figure>
    <img
      src="Images/canter.jfif"
      alt="Shoes" />
  </figure>
  <div class="card-body">
    <h2 class="card-title">Shoes!</h2>
    <p>Stock: 10</p>
      <button class="btn btn-primary">Buy</button>
  </div>
</div>
    
</div> <!--end of downdiv--->

<div class="downdash" style="height: 650px;">
<div class="card-title">Cart</div>
</div>

</div> <!--end of updiv--->

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