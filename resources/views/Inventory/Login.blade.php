<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('Images/davlogo2.png') }}">
    <title>DAVCOM Consumer Goods Trading</title>
</head>
<body>
 <div class="hero bg-base-200 min-h-screen">
  <div class="hero-content flex-col lg:flex-row-reverse">
    <div class="text-center lg:text-left">
      <img src="/Images/davcomlogo.png" style="width: 390px; height: 70px;filter: brightness(0) saturate(100%) invert(50%) sepia(11%) saturate(1476%) hue-rotate(174deg) brightness(89%) contrast(83%);" />
      <h1 class="text-5xl font-bold">Consumer Goods Trading!</h1>
      <p class="py-6">
        DAVCOM is a developing company based in Davao City, established in 2021. Initially focused on selling radio communication products, we have expanded to include rescue, medical equipment, and vehicles.
      </p>
    </div>
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
      <form class="card-body" method="post" action="{{route('Login')}}">
        @csrf
        <div class="form-control">
          <label class="label">
            <span class="label-text">Username:</span>
          </label>
          <input type="text" name="username" placeholder="username" class="input input-bordered" required />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Password:</span>
          </label>
          <input type="password" name="password" placeholder="password" class="input input-bordered" required />
        </div>
        <div class="form-control mt-6">
          <button class="btn btn-primary" style="color: white">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>
@if(session('error'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg">
        <div class="flex items-center">
            <img src="/image-Icon/danger.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif
@if(session('success'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform -translate-y-full opacity-0 transition-transform duration-500 ease-in-out">
        <div class="flex items-center">
        <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif
<script src="{{ asset('js/alert.js') }}"></script>
</body>
</html>
