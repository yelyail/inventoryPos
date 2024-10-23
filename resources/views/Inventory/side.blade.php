@if(!Session::has('user_id'))
    <script type="text/javascript">
        window.location = "{{ route('login.post') }}"; // Redirect to login if session is not set
    </script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAVCOM Consumer Goods Trading</title>
    <meta name="author" content="name">
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
            </div>
            <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                    <li class="flex-1 md:flex-none md:mr-1">
                        <div class="relative inline-block">
                            <button onclick="toggleDD('myDropdown')" class="drop-button text-white py-2 px-2">
                                <span class="pr-2"></span> Hi, {{ Session::get('fullname', 'User') }}
                                <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </button>

                            <div id="myDropdown" class="dropdownlist absolute bg-gray-800 text-white right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <div class="border border-gray-800"></div>
                                <a href="#" class="p-1 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block" 
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> Log Out
                                </a>
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
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block"> Dashboard</span>
                            </a>
                        </li>
                        <li class="mr-3 flex-1">
                            <a href="{{ route('pos') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-gray-500">
                                <i class="fa fa-cart-shopping pr-0 md:pr-3"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block"> POS</span>
                            </a>
                        </li>
                        <li class="relative mr-3 flex-1">
                            <button onclick="toggleDropdown('inventoryDropdown')" class="block w-full text-left py-1 md:py-3 pl-0 md:pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500 flex items-center">
                                <i class="fas fa-sharp fa-boxes-stacked pr-0 md:pr-3"></i>
                                <span class="flex items-center pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200">
                                    Inventory
                                    <svg class="h-5 w-5 text-gray-400 ml-12" width="10" height="10" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z"/>
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </span>
                            </button>
                            <!-- Dropdown menu -->
                            <ul id="inventoryDropdown" class="absolute left-0 hidden mt-2 w-full bg-gray-800 shadow-lg">
                                <li>
                                    <a href="{{ route('pending') }}" class="block py-1 md:py-3 pl-2 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                        <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Pending Products</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('inventory') }}" class="block py-1 md:py-3 pl-2 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                        <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Approved Products</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="mr-3 flex-1">
                            <a href="{{ route('supplier') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                <i class="fas fa-sharp fa-users pr-0 md:pr-3:"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-white md:text-gray-200 block md:inline-block"> Supplier</span>
                            </a>
                        </li>
                        <li class="mr-3 flex-1">
                            <a href="{{ route('user') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                <i class="fas fa-sharp fa-user pr-0 md:pr-3:"></i>
                                <span class="pb-1 md:pb-0 text-xs md:text-base text-white md:text-gray-200 block md:inline-block"> User Information</span>
                            </a>
                        </li>
                        <li class="relative mr-3 flex-1">
                            <button onclick="toggleDropdown('reportDropdown')" class="block w-full text-left py-1 md:py-3 pl-0 md:pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500 flex items-center">
                                <i class="fa fa-square-poll-vertical pr-0 md:pr-3"></i>
                                <span class="flex items-center pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200">
                                    Reports 
                                    <svg class="h-5 w-5 text-gray-400 ml-12" width="10" height="10" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z"/>
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </span>
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
    <script src="{{ asset('js/side.js') }}"></script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{asset('js/inventory.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
</body>
</html>
