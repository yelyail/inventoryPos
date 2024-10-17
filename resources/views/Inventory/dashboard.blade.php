@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div class="upanddown">
  <div class="downdiv">
    <div class="downdash" style="overflow-x:auto !important;">
        <div class="stat place-items-center">
          <div class="stat-title">Low Stock Products</div>
            <table id="dataTableLowStock" class="table table-xs">
              <thead style="font-size: 20px !important; color: #222831; position: sticky; top: 0; background-color: white; text-align: center;">
                <tr>
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
                <td style="font-size: 15px !important; text-align: center;">
                </td>
                <td style="font-size: 15px !important; text-align: center;">
                </td>
                <td style="font-size: 15px !important; text-align: center;">
                </td>
                <td style="font-size: 15px !important; text-align: center; color: red;">
                  <b></b> 
                </td>
              </tr>
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
                    <img class="imgava" src="" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                  </div>
                </td>
                <td style="font-size: 15px !important; text-align: center;"></td>
                <td style="font-size: 15px !important; text-align: center;">
                </td>
                <td style="font-size: 15px !important; text-align: center;">
                </td>
                <td style="font-size: 15px !important; text-align: center;">
                </td>
              </tr>
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
       
            <tr>
              <td>
                <div class="avatar">
                  <img class="imgava" src="" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
                </div>
              </td>
                <td style="font-size: 15px !important; text-align: center;"></td>
                        <td style="font-size: 15px !important; text-align: center;">
                            
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">
                            
                        </td>
                        <td style="font-size: 15px !important; text-align: center;">
                            
                        </td>
                        <td  style="font-size: 15px !important; text-align: center;"
                            class="">
                            <b></b>
                        </td>
            </tr>
        
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

</div> <!--end of upa nddown--->
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