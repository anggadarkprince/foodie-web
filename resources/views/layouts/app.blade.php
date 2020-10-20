<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/icon.css') }}" rel="stylesheet">
</head>
<body class="antialiased">
<div class="flex bg-gray-100 text-gray-700" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-white flex flex-col min-h-screen shadow-sm" id="sidebar-wrapper" style="transition: margin .15s ease-in-out;">
        <div class="px-4 py-2 bg-green-100 rounded-full mx-3 mt-3 text-center group">
            <a href="{{ route('admin.dashboard') }}" class="text-green-500 flex items-center justify-center hover:text-green-600">
                <i class="mdi mdi-storefront-outline text-xl mr-1"></i>
                <span class="text-lg uppercase">{{ config('app.name') }}</span>
            </a>
        </div>
        <ul class="overflow-hidden list-none flex flex-col flex-auto pb-5" style="width: 245px">
            <li class="mt-2">
                <a href="{{ route('admin.account') }}" class="flex items-center py-2 px-5 group">
                    <img src="{{ url(auth()->user()->avatar) }}"
                         alt="avatar" class="rounded-full object-cover h-12 w-12 flex-shrink-0">
                    <div class="flex flex-col truncate ml-3">
                        <p class="group-hover:text-green-500">{{ auth()->user()->name }}</p>
                        <small class="text-gray-500 text-opacity-75 truncate group-hover:text-gray-400">{{ auth()->user()->email }}</small>
                    </div>
                </a>
            </li>

            <li class="flex items-center py-2 px-5 text-xs text-gray-400">
                {{ __('MAIN MENU') }} <i class="mdi mdi-arrow-right ml-auto"></i>
            </li>
            <li>
                <a class="flex items-center py-2 px-5 hover:bg-green-100{{ request()->is('dashboard') ? ' bg-green-100' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="mdi mdi-speedometer mr-2"></i>
                    {{ __('Dashboard') }}
                </a>
            </li>
            @if(request()->user()->can('view-any', \App\Models\Group::class) || request()->user()->can('view-any', \App\Models\User::class))
                <li>
                    <a href="#submenu-user-access" class="flex items-center py-2 px-5 hover:bg-green-100 menu-toggle{{ request()->is('groups*') || request()->is('users*') ? ' bg-green-100' : ' collapsed' }}">
                        <i class="mdi mdi-lock-outline mr-2 pointer-events-none"></i>
                        {{ __('User Access') }}
                        <i class="mdi mdi-chevron-down ml-auto pointer-events-none menu-arrow"></i>
                    </a>
                    <div id="submenu-user-access" class="sidebar-submenu{{ request()->is('groups*') || request()->is('users*') ? '' : ' submenu-hide' }}">
                        <ul class="overflow-hidden flex flex-col pb-2">
                            @can('view-any', \App\Models\Group::class)
                                <li>
                                    <a class="flex items-center py-1 pl-12 pr-5 hover:bg-green-100{{ request()->is('groups*') ? ' text-green-500' : '' }}" href="{{ url('groups') }}">
                                        <i class="mdi mdi-shield-account-outline mr-2"></i>
                                        {{ __('Groups') }}
                                    </a>
                                </li>
                            @endcan
                            @can('view-any', \App\Models\User::class)
                                <li>
                                    <a class="flex items-center py-1 pl-12 pr-5 hover:bg-green-100{{ request()->is('users*') ? ' text-green-500' : '' }}" href="{{ url('users') }}">
                                        <i class="mdi mdi-account-multiple-outline mr-2"></i>
                                        {{ __('Users') }}
                                        @if($_totalNewCustomer > 0)
                                            <span class="ml-auto text-xs text-white uppercase bg-red-500 px-1 rounded-sm">
                                                {{ $_totalNewCustomer }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endif

            @if(request()->user()->can('view-any', \App\Models\Category::class)
                || request()->user()->can('view-any', \App\Models\Restaurant::class)
                || request()->user()->can('view-any', \App\Models\Cuisine::class))
                <li>
                    <a href="#submenu-foodie" class="flex items-center py-2 px-5 hover:bg-green-100 menu-toggle{{ request()->is('categories*') || request()->is('restaurants*') || request()->is('cuisines*') ? ' bg-green-100' : ' collapsed' }}">
                        <i class="mdi mdi-package-variant mr-2 pointer-events-none"></i>
                        {{ __('Foodie') }}
                        <i class="mdi mdi-chevron-down ml-auto pointer-events-none menu-arrow"></i>
                    </a>
                    <div id="submenu-foodie" class="sidebar-submenu{{ request()->is('categories*') || request()->is('restaurants*') || request()->is('cuisines*') ? '' : ' submenu-hide' }}">
                        <ul class="overflow-hidden flex flex-col pb-2">
                            @can('view-any', \App\Models\Category::class)
                                <li>
                                    <a class="flex items-center py-1 pl-12 pr-5 hover:bg-green-100{{ request()->is('categories*') ? ' text-green-500' : '' }}" href="{{ route('admin.categories.index') }}">
                                        <i class="mdi mdi-cube-outline mr-2"></i>
                                        {{ __('Categories') }}
                                    </a>
                                </li>
                            @endcan
                            @can('view-any', \App\Models\Restaurant::class)
                                <li>
                                    <a class="flex items-center py-1 pl-12 pr-5 hover:bg-green-100{{ request()->is('restaurants*') ? ' text-green-500' : '' }}" href="{{ route('admin.restaurants.index') }}">
                                        <i class="mdi mdi-storefront-outline mr-2"></i>
                                        {{ __('Restaurant') }}
                                        @if($_totalNewRestaurant > 0)
                                            <span class="ml-auto text-xs text-white uppercase bg-blue-500 px-1 rounded-sm">
                                                {{ $_totalNewRestaurant }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                            @can('view-any', \App\Models\Cuisine::class)
                                <li>
                                    <a class="flex items-center py-1 pl-12 pr-5 hover:bg-green-100{{ request()->is('cuisines*') ? ' text-green-500' : '' }}" href="{{ route('admin.cuisines.index') }}">
                                        <i class="mdi mdi-food-apple-outline mr-2"></i>
                                        {{ __('Cuisines') }}
                                        @if($_totalNewCuisine > 0)
                                            <span class="ml-auto text-xs text-white uppercase bg-green-500 px-1 rounded-sm">
                                                {{ $_totalNewCuisine }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endif

            @can('view-any', \App\Models\Courier::class)
                <li>
                    <a class="flex items-center py-2 px-5 hover:bg-green-100{{ request()->is('couriers*') ? ' text-green-500' : '' }}" href="{{ route('admin.couriers.index') }}">
                        <i class="mdi mdi-truck-delivery-outline mr-2"></i>
                        {{ __('Couriers') }}
                    </a>
                </li>
            @endcan

            @can('view-any', \App\Models\Order::class)
                <li>
                    <a class="flex items-center py-2 px-5 hover:bg-green-100{{ request()->is('orders*') ? ' text-green-500' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="mdi mdi-clipboard-file-outline mr-2"></i>
                        {{ __('Orders') }}
                    </a>
                </li>
            @endcan

            <li class="flex items-center py-2 px-5 text-xs text-gray-400">
                {{ __('PREFERENCES') }} <i class="mdi mdi-arrow-right ml-auto"></i>
            </li>
            @can('edit-account', \App\Models\User::class)
                <li>
                    <a class="flex items-center py-2 px-5 hover:bg-green-100{{ request()->is('account') ? ' text-green-500' : '' }}" href="{{ route('admin.account') }}">
                        <i class="mdi mdi-account-reactivate-outline mr-2"></i>
                        {{ __('Account') }}
                    </a>
                </li>
            @endcan
            @can('edit-setting', \App\Models\Setting::class)
                <li>
                    <a class="flex items-center py-2 px-5 hover:bg-green-100{{ request()->is('settings') ? ' text-green-500' : '' }}" href="{{ route('admin.settings') }}">
                        <i class="mdi mdi-cog-outline mr-2"></i>
                        {{ __('Settings') }}
                    </a>
                </li>
            @endcan
            @auth
                <li>
                    <a class="flex items-center py-2 px-5 hover:bg-green-100 cursor-pointer"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout-variant mr-2"></i>
                        {{ __('Sign out') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        {{ csrf_field() }}
                    </form>
                </li>
            @else
                <li>
                    <a class="flex items-center py-2 px-5 hover:bg-green-100" href="{{ route('login') }}">
                        <i class="mdi mdi-login-variant mr-2"></i>
                        {{ __('Login') }}
                    </a>
                </li>

                @if (Route::has('register'))
                    <li>
                        <a class="flex items-center py-2 px-5 hover:bg-green-100" href="{{ route('register') }}">
                            <i class="mdi mdi-account-plus-outline mr-2"></i>
                            {{ __('Register') }}
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </div>
    <div id="content-wrapper" class="flex flex-col w-full min-h-screen h-full">
        <div class="flex items-center px-4 py-2 bg-green-500 text-white sm:h-16">
            <i class="mdi mdi-menu text-xl sm:text-2xl py-1 cursor-pointer sidebar-toggle"></i>
            <div class="ml-4 opacity-50 select-none" id="search-placeholder">
                <i class="mdi mdi-magnify text-md mr-1"></i>
                <span class="hidden sm:inline-block">Search over the app...</span>
            </div>
            <form action="search" class="flex flex-grow w-auto">
                <input type="search" class="form-input border-none rounded-full ml-4 transition-all duration-500 ease-in-out max-w-sm opacity-0 hidden" id="input-navbar-search"
                       placeholder="Search over the app..." aria-label="Search">
            </form>
            <ul class="list-none ml-auto">
                <li class="inline-block py-2 px-3 cursor-pointer relative">
                    <i class="mdi mdi-message-outline text-xl"></i>
                    <span class="bg-red-500 w-4 h-4 text-white rounded-full absolute top-0 right-0 text-xs flex items-center justify-center">
                        5
                    </span>
                </li>
                <li class="inline-block p-2 cursor-pointer relative">
                    <i class="mdi mdi-bell-outline text-xl"></i>
                    <span class="bg-red-500 w-4 h-4 text-white rounded-full absolute top-0 right-0 text-xs flex items-center justify-center">
                        13
                    </span>
                </li>
                <li class="inline-block py-2 px-3 cursor-pointer leading-7 align-top">
                    <div class="dropdown">
                        <button class="dropdown-toggle">
                            {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                <i class="mdi mdi-speedometer mr-2"></i>Dashboard
                            </a>
                            @can('edit-account', \App\Models\User::class)
                                <a href="{{ route('admin.account') }}" class="dropdown-item">
                                    <i class="mdi mdi-account-outline mr-2"></i>Account
                                </a>
                            @endcan
                            @auth
                                <hr class="border-gray-200">
                                <a class="dropdown-item cursor-pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout-variant mr-2"></i>Sign Out
                                </a>
                            @endauth
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="p-4 flex-grow">

            @if (session('status'))
                <div class="mb-3 {{ session('status') == 'success' ? 'alert-green' : 'alert-orange' }}" role="alert">
                    {{ session('message') ?: ucfirst(preg_replace('/(_|\-)/', ' ', session('status'))) }}
                </div>
            @endif

            @yield('content')
        </div>
        <footer class="px-4 py-5 border-t mt-3 text-sm text-gray-600">
            <div class="sm:flex content-center justify-between">
                <span class="text-muted text-center sm:text-left">
                    Copyright &copy; {{ date('Y') }} <a href="{{ config('app.url') }}" target="_blank" class="font-bold">{{ config('app.name') }}</a>
                    <span class="hidden sm:inline-block">all rights reserved.</span>
                </span>
                <span class="text-center">
                    Hand-crafted &amp; made with <i class="mdi mdi-heart text-red-400"></i>
                </span>
            </div>
        </footer>
    </div>
</div>

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
@yield('scripts')

</body>
</html>
