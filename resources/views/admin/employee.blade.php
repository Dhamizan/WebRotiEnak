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
        <ul class="mt-10 space-y-8">
        <li>
                <a href="/dashboard" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('dashboard') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2L2 8v10h6v-6h4v6h6V8l-8-6z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/employee" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('employee') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                    <span>Employee</span>
                </a>
            </li>
            <li>
                <a href="/absense" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('absense') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Absense</span>
                </a>
            </li>
            <li>
                <a href="/salary" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('salary') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
                   <span>Salary</span>
                </a>
            </li>
            <li>
                <a href="/leave" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('leave') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                </svg>
                   <span>Leave</span>
                </a>
            </li>
        </ul>
        <li class="mt-auto mb-6">
        <a href="/logout" class="flex items-center space-x-3 px-6 text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                </svg>
                    Sign Out
                </a>
        </li>
    </div>
    <!-- revisi -->
    <!-- Content Area -->
    <div class="w-full h-16 bg-customYellow shadow-md flex items-center px-6 fixed top-0 left-0 right-0 z-10">
    <!-- Logo di Kiri -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
    <div class="space-x-3 px-6">
    <span class="text-lg font-bold text-white">Roti Enak</span>
    </div>

    <!-- Teks di Tengah -->
    <div class="flex-1 text-center">
    </div>

    <!-- Ikon di Kanan -->
    <div class="flex items-center space-x-4">
        <!-- Notifikasi -->
        <button class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c3.866 0 7 3.134 7 7v3.5l1.5 2V17H3v-1.5l1.5-2V10c0-3.866 3.134-7 7-7zM10.5 21h3a1.5 1.5 0 0 1-3 0z"/>
            </svg>
        </button>

        <button class="relative">
            <a href="/profile">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            </a>
        </button>

        <span class="text-lg text-white">Hi, Robby</span>
    </div>
</div>

    <!-- Main Content -->
    <div class="flex-1 p-6 mt-20 ml-64 overflow-x-auto">
        <h3 class="text-lg">Hello, Admin</h3>
        <h1 class="text-3xl font-bold">Welcome to Employee!</h1>
        
        <div class="space-y-4 w-full mt-12 overflow-x-auto">
            <div class="flex justify-between items-center mb-6">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" placeholder="Search..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1111.293 3.707l3.853 3.854a1 1 0 11-1.414 1.414l-3.854-3.853A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <!-- Add Button -->
                <button id="openModalBtn" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Add
                </button>
            </div>

            <!-- Modal (Popup) -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold text-center">Add Employee</h2>
            <p class="text-sm text-gray-500 text-center mb-4">Fill in the required details to add an employee</p>

            <!-- Form -->
            <form>
                <div class="mb-3">
                    <label class="block text-gray-700">Employee Name</label>
                    <input type="text" class="w-full p-2 border rounded-lg bg-gray-100">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" class="w-full p-2 border rounded-lg bg-gray-100">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700">Phone</label>
                    <input type="text" class="w-full p-2 border rounded-lg bg-gray-100">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700">Address</label>
                    <input type="text" class="w-full p-2 border rounded-lg bg-gray-100">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700">Gender</label>
                    <select class="w-full p-2 border rounded-lg bg-gray-100">
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700">Role</label>
                    <select class="w-full p-2 border rounded-lg bg-gray-100">
                        <option>Admin</option>
                        <option>Employee</option>
                    </select>
                </div>

                <!-- Tombol Action -->
                <div class="flex justify-between mt-4">
                    <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600">
                        Initiate Recall
                    </button>
                    <button id="closeModalBtn" type="button" class="border border-yellow-500 text-yellow-500 px-6 py-2 rounded-lg hover:bg-yellow-500 hover:text-white">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
        </div>

            <table class="text-left w-full border-collapse">
                <thead>
                    <tr>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">No</th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">
                            <div class="flex items-center space-x-4">
                                <span>Outlet</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">
                        <div class="flex items-center space-x-4">
                                <span>Leader Area</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">
                        <div class="flex items-center space-x-4">
                                <span>Phone</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">
                        <div class="flex items-center space-x-4">
                                <span>Address</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25" />
</svg>
</td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25" />
</svg>
</td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25" />
</svg>
</td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Outlet1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Lian Smith</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">622322662</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm">Jl. Outlet 1</td>
                        <td class="py-4 px-6 bg-white font-bold uppercase text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25" />
</svg>
</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
            </div>

            <script>
        // Ambil elemen
        const modal = document.getElementById("modal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");

        // Buka modal
        openModalBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        // Tutup modal
        closeModalBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Tutup modal jika klik di luar modal
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    </script>
    </body>
</html>