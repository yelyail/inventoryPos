@if(!Session::has('user_id'))
    <script type="text/javascript">
        window.location = "{{ route('login.post') }}"; // Redirect to login if session is not set
    </script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DAVCOM Consumer Goods Trading</title>
    <meta name="author" content="name">
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script>

</head>

<body class="font-sans leading-normal tracking-normal mt-12">
<header>
    <!--Nav-->
    <nav aria-label="menu nav" class="bg-gray-800 pt-4 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">
        <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                <a href="#" aria-label="Home">
                    <span class="text-xl pl-2"><a class="btn btn-ghost text-xl"><img src="/Images/davcomlogo.png" style="width: 180px; height: 30px;" /></a></span>
                </a>
            </div>
            <div class="flex flex-1 md:w-1/3 justify-center mx-auto text-white px-2">
                <h2 class="font-bold uppercase text-white-900 text-2xl"><b>{{ $pageTitle ?? 'Dashboard' }}</b></h2>
            </div>
            <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                    <li class="flex-1 md:flex-none md:mr-3">
                        <div class="relative inline-block">
                            <button onclick="toggleDD('myDropdown')" class="drop-button text-white py-2 px-2"> 
                                <span class="pr-2"></span> Hi, User 
                                <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </button>
                            <div id="myDropdown" class="dropdownlist absolute bg-gray-800 text-white right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                <div class="border border-gray-800"></div>
                                <a href="#" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main>
    <div class="flex flex-col md:flex-row">
        <nav aria-label="alternative nav" class="md:fixed md:left-0 md:top-0">
            <div class="bg-gray-800 shadow-xl h-full md:h-screen z-10 w-full md:w-48">
                <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                    <ul class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                        <li class="mr-3 flex-1">
                            <a href="{{ route('dashboard') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-pink-500">
                                <i class="fas fa-list-check pr-0 md:pr-3"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Dashboard</span>
                            </a>
                        </li>
                        <li class="mr-3 flex-1">
                            <a href="{{ route('pos') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-gray-500">
                                <i class="fa fa-cart-shopping pr-0 md:pr-3"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">POS</span>
                            </a>
                        </li>
                        <li class="mr-3 flex-1">
                            <a href="{{ route('inventory') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                <i class="fas fa-sharp fa-boxes-stacked pr-0 md:pr-3:"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-white md:text-gray-200 block md:inline-block">Inventory</span>
                            </a>
                        </li>
                        <li class="relative mr-3 flex-1">
                            <button onclick="toggleDropdown('reportDropdown')" class="block w-full text-left py-1 md:py-3 pl-0 md:pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                                <i class="fa fa-square-poll-vertical pr-0 md:pr-3"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Reports</span>
                            </button>
                            <ul id="reportDropdown" class="absolute left-0 hidden mt-2 w-full bg-gray-800 shadow-lg">
                                <li>
                                    <a href="{{ route('report') }}" class="block py-1 md:py-3 pl-2 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                                        <i class="fa fa-clipboard pr-0 md:pr-3"></i>
                                        <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Inventory Reports</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('salesReport') }}" class="block py-1 md:py-3 pl-2 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                                        <i class="fa fa-chart-line pr-0 md:pr-3"></i>
                                        <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Sales Reports</span>
                                    </a>
                                </li>
                            </ul>
                        </li>                        
                    </ul>
                </div>
            </div>
        </nav>
        <main >
            @yield('content')
        </main>
    <!-- External Scripts -->
    @vite('resources/js/app.js')
</body>
</html>
