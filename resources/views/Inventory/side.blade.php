@if(!Session::has('user_id'))
    <script type="text/javascript">
        window.location = "{{ route('login.post') }}"; // Redirect to login if session is not set
    </script>
@endif
<!doctype html>
<html data-theme="">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Invside.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reportprint.css') }}">
        <!-- <link rel="stylesheet" href="{{ asset('css/reponsive.css') }}"> -->
    <link rel="icon" type="image/png" href="{{ asset('Images/davlogo2.png') }}">
    <title>DAVCOM Consumer Goods Trading</title>
</head>
<body>
<div class="navbar bg-base-100">
  <div class="navbar-start">
    <a class="btn btn-ghost text-xl"><img src="/Images/davcomlogo.png" style="width: 180px; height: 30px;" /></a>
  </div>
  <div class="navbar-center">
    <a class="text-xl"><b>{{ $pageTitle ?? 'Dashboard' }}</b></a>
  </div>
  <div class="navbar-end">
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
      </ul>
    </div>
  </div>
</div>

<div class="wholediv">
<!--Menu Buttons-->
  <div class="menu-lg">
    <ul class="menu bg-base-200 rounded-box w-56">
    <li>
      <a href="{{ route('dashboard') }}"><img src="/Images/dashboard.png" class="iconmenu"/>Dashboard</a>
    </li>
    <li>
      <a href="{{ route('pos') }}"><img src="/Images/cart.png" class="iconmenu"/>Point of sale</a>
    </li>
    <li>
      <a href="{{ route('inventory') }}"><img src="/Images/box.png" class="iconmenu"/>Inventory</a>
    </li>
    <li>
      <a href="{{ route('report') }}"><img src="/Images/report.png" class="iconmenu"/>Reports</a>
    </li>
  </ul>
  </div>
        <main class="p-6 flex-grow">
            @yield('content')
        </main>
    <!-- External Scripts -->
    @vite('resources/js/app.js')
</body>
</html>
