@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-48 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        
<div class="upanddown">

<div class="downdiv">
    <div class="downdash" style="overflow:auto !important; max-height: 400px;">
        <div class="stat place-items-center">
            <div class="stat-title">Low Stock Products</div>
            <table id="dataTableLowStock" class="table table-s">
                <thead style="font-size: 20px !important; color: #222831;  top: 0; background-color: bg-gray-100; text-align: center;">
                    <tr>
                        <th></th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Unit</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                  <tr>
                      <td>
                          <div class="avatar">
                              <img class="imgava" src="" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                          </div>
                      </td>
                      <td style="font-size: 15px !important; text-align: center;"></td>
                      <td style="font-size: 15px !important; text-align: center;"></td>
                      <td style="font-size: 15px !important; text-align: center;"></td>
                      <td style="font-size: 15px !important; text-align: center; color: red;"><b></b></td>
                  </tr>
              </tbody>
            </table>
        </div>
    </div>

    <div class="downdash" style="overflow:auto !important; max-height: 400px;">
        <div class="stat place-items-center">
            <div class="stat-title">Defective Products</div>
            <table id="dataTableDamaged" class="table table-s">
                <thead style="font-size: 20px !important; color: #222831; top: 0; background-color: bg-gray-100; text-align: center;">
                    <tr>
                        <th></th>
                        <th>Serial #</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="avatar">
                                <img class="imgava" src="" alt="" style="width: 50px; height: 50px;  border-radius: 50%;">
                            </div>
                        </td>
                        <td style="font-size: 15px !important; text-align: center;"></td>
                        <td style="font-size: 15px !important; text-align: center;"></td>
                        <td style="font-size: 15px !important; text-align: center;"></td>
                        <td style="font-size: 15px !important; text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="updiv">
    <div class="maindash" style="overflow:auto !important; max-height: 400px;">
    <div class="stat place-items-center">
            <div class="stat-title">Products Pending Inspection</div>
            <table class="table table-xs">
    <thead style="font-size: 20px !important; color: #222831; top: 0; background-color: bg-gray-100; text-align: center;">
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
            <tr>
              <td>
                <div class="avatar">
                  <img class="imgava" src="" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                </div>
              </td>
                <td style="font-size: 15px !important; text-align: center;"></td>
                <td style="font-size: 15px !important; text-align: center;"></td>
                <td style="font-size: 15px !important; text-align: center;"></td>
                <td style="font-size: 15px !important; text-align: center;"></td>
                <td style="font-size: 15px !important; text-align: center;"><b></b></td>
            </tr>
    </tbody>
</table>
    </div>
    </div>
</div> <!--end of updiv--->
</div>
</div>
</main>

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
