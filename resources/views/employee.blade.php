<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
        .font-family-karla { font-family: karla; }
        .bg-sidebar { background: #3d68ff; }
        .cta-btn { color: #3d68ff; }
        .upgrade-btn { background: #1947ee; }
        .upgrade-btn:hover { background: #0038fd; }
        .active-nav-link { background: #1947ee; }
        .nav-item:hover { background: #1947ee; }
        .account-link:hover { background: #3d68ff; }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg flex flex-col absolute top-16 left-0 bottom-0 z-0">
        <ul class="mt-10 space-y-2">
        <li>
                <a href="/dashboard" class="flex items-center py-3 px-6 hover:text-yellow-500 {{ request()->is('dashboard') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2L2 8v10h6v-6h4v6h6V8l-8-6z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/employee" class="flex items-center py-3 px-6 hover:text-yellow-500 {{ request()->is('employee') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a4 4 0 11-4 4 4 4 0 014-4zm-8 16v-1a5 5 0 0110 0v1H2z"/>
                    </svg>
                    Employee
                </a>
            </li>
            <li>
                <a href="/absense" class="flex items-center py-3 px-6 hover:text-yellow-500 {{ request()->is('absense') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2zm8 8H6"/>
                    </svg>
                    Absense
                </a>
            </li>
            <li>
                <a href="/salary" class="flex items-center py-3 px-6 hover:text-yellow-500 {{ request()->is('salary') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 10h12m-6-6v12"/>
                    </svg>
                    Salary
                </a>
            </li>
            <li>
                <a href="/leave" class="flex items-center py-3 px-6 hover:text-yellow-500 {{ request()->is('leave') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 7v6l4-3z"/>
                    </svg>
                    Leave
                </a>
            </li>
            <li>
                <a href="/logout" class="flex items-center py-3 px-6 text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 10h8m-4 4l4-4-4-4"/>
                    </svg>
                    Sign Out
                </a>
            </li>
        </ul>
    </div>
    <!-- revisi -->
    <!-- Content Area -->
<div class="flex-1 flex flex-col">
    <!-- Navbar (Tertinggi) -->
    <div class="w-full h-16 bg-customYellow shadow-md flex items-center px-6 fixed top-0 left-0 right-0 z-10">
    </div>

    <!-- Main Content -->

    <div class="flex-1 p-6 mt-20 ml-64 overflow-x-auto">
        <h3 class="text-lg">Hello admin</h3>
        <h1 class="text-3xl font-bold">Welcome to Employee</h1>
        
        <div class="w-full mt-12 overflow-x-auto">
        <div class="relative">
        <input type="text" placeholder="Search..." 
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1111.293 3.707l3.853 3.854a1 1 0 11-1.414 1.414l-3.854-3.853A6 6 0 012 8z" clip-rule="evenodd"/>
        </svg>
    </div>
            <table class="text-left w-full border-collapse">
                <thead>
                    <tr>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">No</th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">Outlet</th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">Leader Outlet</th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">Phone</th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
            </div>

    </body>
</html>
