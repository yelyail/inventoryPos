@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div class="upanddown">

<div class="minitab">
  <div role="tablist" class="tabs tabs-boxed">
    <a href="{{ route('inventory') }}" role="tab" class="tab tab-active" id="tab">Stocks</a>
    <a href="{{ route('pending') }}" role="tab" class="tab">Pending Inspection</a>
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