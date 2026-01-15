<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --warm-white: #FAFAF8;
                --text-primary: #3A3A36;
                --text-secondary: #6B6B63;
                --coral: #E8997A;
                --teal: #7CB9C8;
                --light-gray: #E8E8E4;
                --subtle-glow: rgba(255, 255, 255, 0.5);
            }
            
            * {
                color: var(--text-primary);
            }
            
            body {
                background-color: var(--warm-white);
            }
            
            .card {
                background: white;
                border-radius: 20px;
                padding: 24px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                position: relative;
                overflow: hidden;
            }
            
            .card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0) 100%);
                pointer-events: none;
                border-radius: 20px;
            }
            
            .card > * {
                position: relative;
                z-index: 1;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--coral) 0%, var(--teal) 100%);
                color: white;
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(232, 153, 122, 0.3);
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(232, 153, 122, 0.4);
            }
            
            .btn-secondary {
                background: var(--light-gray);
                color: var(--text-primary);
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .btn-secondary:hover {
                background: #DCDCD8;
            }
            
            .btn-danger {
                background: #E8997A;
                color: white;
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(232, 153, 122, 0.3);
            }
            
            .btn-danger:hover {
                background: #D67A5F;
                transform: translateY(-2px);
            }
            
            input, textarea, select {
                background: var(--light-gray);
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-size: 16px;
                transition: all 0.3s ease;
                font-family: inherit;
            }
            
            input:focus, textarea:focus, select:focus {
                outline: none;
                background: white;
                box-shadow: 0 0 0 3px rgba(232, 153, 122, 0.1);
            }
            
            label {
                color: var(--text-primary);
                font-weight: 600;
                font-size: 14px;
                display: block;
                margin-bottom: 8px;
            }
            
            .nav-link {
                display: flex;
                align-items: center;
                padding: 12px 16px;
                border-radius: 12px;
                transition: all 0.3s ease;
                color: var(--text-primary);
                text-decoration: none;
                font-weight: 500;
            }
            
            .nav-link i {
                width: 20px;
                height: 20px;
                margin-right: 12px;
            }
            
            .nav-link.active {
                background: linear-gradient(135deg, var(--coral) 0%, var(--teal) 100%);
                color: white;
            }
        </style>
    </head>
    <body class="font-sans antialiased" style="background-color: var(--warm-white);">
        <div class="flex h-screen" style="background-color: var(--warm-white);">
            <!-- Sidebar -->
            <div class="w-64 bg-white shadow-lg flex flex-col" style="box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);">
                <!-- Logo/Brand -->
                <div class="px-6 py-8 border-b" style="border-color: var(--light-gray);">
                    <h1 class="text-2xl font-bold" style="background: linear-gradient(135deg, var(--coral) 0%, var(--teal) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Inventory</h1>
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">Management System</p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                        <i class="fas fa-tag"></i>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                    
                    <!-- Trash Section -->
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--light-gray);">
                        <p style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 0 12px; margin-bottom: 8px;">
                            <i class="fas fa-trash mr-2"></i>Trash
                        </p>
                        <a href="{{ route('categories.trash') }}" class="nav-link {{ request()->routeIs('categories.trash') ? 'active' : '' }}" style="padding-left: 32px; font-size: 14px;">
                            <i class="fas fa-tag"></i>
                            <span>Categories Trash</span>
                        </a>
                        <a href="{{ route('products.trash') }}" class="nav-link {{ request()->routeIs('products.trash') ? 'active' : '' }}" style="padding-left: 32px; font-size: 14px;">
                            <i class="fas fa-box"></i>
                            <span>Products Trash</span>
                        </a>
                    </div>
                </nav>

                <!-- User Section -->
                @auth
                <div class="px-4 py-6 border-t" style="border-color: var(--light-gray);">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full" style="background: linear-gradient(135deg, var(--coral) 0%, var(--teal) 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold" style="color: var(--text-primary);">{{ Auth::user()->name }}</p>
                            <p class="text-xs" style="color: var(--text-secondary);">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium rounded-lg transition-all" style="background: #FFE8E0; color: var(--coral); border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-white border-b px-8 py-4" style="border-color: var(--light-gray); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
                                @if(request()->routeIs('dashboard'))
                                    Dashboard
                                @elseif(request()->routeIs('categories.*'))
                                    Categories
                                @else
                                    Products
                                @endif
                            </h2>
                        </div>
                        <div class="text-sm" style="color: var(--text-secondary);">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-auto" style="background-color: var(--warm-white);">
                    <div class="p-8">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
