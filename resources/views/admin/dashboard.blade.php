<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/style.css'])

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
            <script src="https://unpkg.com/lucide@latest"></script>
            <script>lucide.createIcons();</script>
        @endif
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="flex h-screen bg-gray-100">
        <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:relative z-50">
            <div class="w-64 bg-white shadow-lg flex flex-col absolute top-16 left-0 bottom-0 z-0">
                <ul class="mt-10 space-y-8">
                    <li>
                        <a href="/admin/dashboard" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('admin/dashboard') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2L2 8v10h6v-6h4v6h6V8l-8-6z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/gerai" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('admin/gerai') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 33 33" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path fill="currentColor" d="m30 10.68l-2-6A1 1 0 0 0 27 4H5a1 1 0 0 0-1 .68l-2 6A1.19 1.19 0 0 0 2 11v6a1 1 0 0 0 1 1h1v10h2V18h6v10h16V18h1a1 1 0 0 0 1-1v-6a1.19 1.19 0 0 0 0-.32ZM26 26H14v-8h12Zm2-10h-4v-4h-2v4h-5v-4h-2v4h-5v-4H8v4H4v-4.84L5.72 6h20.56L28 11.16Z"/>
                        </svg>
                            <span>Gerai</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/pegawai" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('admin/pegawai') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                            <span>Pegawai</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/absensi" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('admin/absensi') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Absensi</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/gaji" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('admin/gaji') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        <span>Gaji</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/cuti" class="flex items-center space-x-3 px-6 hover:text-yellow-500 {{ request()->is('admin/cuti') ? 'text-yellow-500 font-bold' : 'text-gray-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span>Cuti</span>
                        </a>
                    </li>
                </ul>
                <li class="list-none mt-auto mb-6">
                    <button onclick="logout()" class="flex items-center space-x-3 px-6 text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </li>

            </div>
        </aside>
        <!-- navbar -->
        <div id="navbar" class="w-full h-16 bg-customYellow shadow-md flex items-center px-6 fixed top-0 left-0 right-0 z-10">
            <button id="menu-toggle" class="text-white focus:outline-none md:hidden space-x-3 px-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <!-- Logo di Kiri -->
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
            <div class="space-x-3 px-6">
            <span class="text-lg font-bold text-white">Roti Enak</span>
            </div>

            <!-- Teks di Tengah -->
            <div class="flex-1 text-center"></div>

            <!-- Ikon di Kanan -->
            <div class="flex items-center space-x-4">

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
        <div id="content" class="flex gap-6 p-6 mt-16 w-[83.2%]">
            <div class="w-full">
                <!-- Konten 1 -->
                <div class="container mx-auto px-0 py-0 w-[100%]">
                <!-- Card Section -->
                    <div class="flex gap-4 justify-between ">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-6 py-4 rounded-lg flex items-center gap-3 shadow-md w-52">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <div>
                                <p class="font-bold">Daftar</p>
                                <p class="text-sm">5 Pegawai</p>
                            </div>
                        </div>
                        <div class="bg-white text-black px-6 py-4 rounded-lg flex items-center gap-3 shadow-md w-52">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <div>
                                <p class="font-bold">Gerai</p>
                                <p class="text-sm">5 Gerai</p>
                            </div>
                        </div>
                        <div class="bg-white text-black px-6 py-4 rounded-lg flex items-center gap-3 shadow-md w-52">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <div>
                                <p class="font-bold">Cuti</p>
                                <p class="text-sm">1 Pegawai</p>
                            </div>
                        </div>
                        <div class="bg-white text-black px-6 py-4 rounded-lg flex items-center gap-3 shadow-md w-52">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <div>
                                <p class="font-bold">Kehadiran</p>
                                <p class="text-sm">2 Pegawai</p>
                            </div>
                        </div>
                    </div>

                    <!-- Spacing Between Tables -->
                    <div class="my-4"></div>
        
                    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-md">
                        <table id="dataTable" class="min-w-full">
                            <thead class="bg-white">
                                <tr>
                                    <th class="py-4 px-4 text-center font-bold text-gray-600 border-b">No</th>
                                    <th class="py-4 px-4 text-center font-bold text-gray-600 border-b">Pegawai</th>
                                    <th class="py-4 px-4 text-center font-bold text-gray-600 border-b sortable">Outlet</th>
                                    <th class="py-4 px-4 text-center font-bold text-gray-600 border-b">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data akan diisi lewat JS -->
                            </tbody>
                        </table>

                        <!-- Pagination Controls -->
                        <div class="flex justify-between items-center px-4 py-2 border-t border-gray-200">
                            <span class="text-sm font-bold">Halaman:</span>
                            <select id="pageSelect" class="border px-3 py-1 rounded-md text-sm">
                                <!-- Options diisi via JS -->
                            </select>
                            <select id="outletSelect" class="border px-3 py-1 rounded-md text-sm">
                                <!-- Options diisi via JS -->
                            </select>
                            <span class="text-sm" id="pageInfo">1 - X dari Y</span>
                        </div>
                    </div>
                </div>            
            </div>
            <!-- Bagian Kanan: Permintaan yang Belum Diproses -->
            <div class="w-1/1 bg-white border border-gray-200 rounded-lg shadow-md p-4 w-[560px] h-[595px]">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Permintaan Belum Diproses</h2>
                
                <div id="requestList">
                    <!-- Data Permintaan akan diisi lewat JS -->
                    <div class="p-3 bg-yellow-100 rounded-md mb-2">
                        <p class="font-bold text-sm text-gray-700">Robby mengajukan cuti</p>
                        <p class="text-xs text-gray-500">Diajukan pada 2024-04-05</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-md mb-2">
                        <p class="font-bold text-sm text-gray-700">Siti butuh persetujuan lembur</p>
                        <p class="text-xs text-gray-500">Diajukan pada 2024-04-04</p>
                    </div>
                </div>
            </div>
        </div> 
    </div>  
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const menuToggle = document.getElementById('menu-toggle');
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');

                menuToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    mainContent.classList.toggle('sidebar-open'); // Toggle efek margin-left
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                const data = [
                    { no: 1, name: 'Karyawan 1', outlet: 'A', status: 'Belum Hadir', status_class: 'bg-red-500' },
                    { no: 2, name: 'Karyawan 2', outlet: 'A', status: 'Kerja', status_class: 'bg-yellow-500' },
                    { no: 3, name: 'Karyawan 3', outlet: 'A', status: 'Hadir', status_class: 'bg-green-500' },
                    { no: 4, name: 'Karyawan 4', outlet: 'A', status: 'Hadir', status_class: 'bg-green-500' },
                    { no: 5, name: 'Karyawan 5', outlet: 'A', status: 'Cuti', status_class: 'bg-blue-500' },
                    { no: 6, name: 'Karyawan 6', outlet: 'B', status: 'Hadir', status_class: 'bg-green-500' },
                    { no: 7, name: 'Karyawan 7', outlet: 'B', status: 'Belum Hadir', status_class: 'bg-red-500' },
                    { no: 8, name: 'Karyawan 8', outlet: 'B', status: 'Kerja', status_class: 'bg-yellow-500' },
                    { no: 9, name: 'Karyawan 9', outlet: 'B', status: 'Izin', status_class: 'bg-purple-500' },
                    { no: 10, name: 'Karyawan 10', outlet: 'B', status: 'Terlambat', status_class: 'bg-orange-500' },
                    { no: 11, name: 'Karyawan 11', outlet: 'C', status: 'Hadir', status_class: 'bg-green-500' },
                    { no: 12, name: 'Karyawan 12', outlet: 'C', status: 'Belum Hadir', status_class: 'bg-red-500' },
                    { no: 13, name: 'Karyawan 13', outlet: 'C', status: 'Hadir', status_class: 'bg-green-500' },
                    { no: 14, name: 'Karyawan 14', outlet: 'C', status: 'Cuti', status_class: 'bg-blue-500' },
                    { no: 15, name: 'Karyawan 15', outlet: 'C', status: 'Kerja', status_class: 'bg-yellow-500' },
                    { no: 16, name: 'Karyawan 13', outlet: 'C', status: 'Hadir', status_class: 'bg-green-500' },
                    { no: 17, name: 'Karyawan 14', outlet: 'D', status: 'Cuti', status_class: 'bg-blue-500' },
                    { no: 18, name: 'Karyawan 15', outlet: 'D', status: 'Kerja', status_class: 'bg-yellow-500' }
                ];

                const perPage = 8;
                let currentPage = 1;
                const totalPages = Math.ceil(data.length / perPage);
                const tableBody = document.getElementById("tableBody");
                const pageSelect = document.getElementById("pageSelect");
                const outletSelect = document.getElementById("outletSelect")
                const pageInfo = document.getElementById("pageInfo");

                function renderTable() {
                    tableBody.innerHTML = "";
                    const start = (currentPage - 1) * perPage;
                    const end = start + perPage;
                    const paginatedData = data.slice(start, end);

                    paginatedData.forEach(item => {
                        const row = `
                            <tr class="border-b">
                                <td class="py-4 px-6">${item.no}</td>
                                <td class="py-4 px-6">${item.name}</td>
                                <td class="py-4 px-6">${item.outlet}</td>
                                <td class="py-4 px-6">
                                    <span class="px-6 py-1 text-xs font-bold text-white rounded-full ${item.status_class}">
                                        ${item.status}
                                    </span>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });

                    pageInfo.innerText = `${start + 1} - ${Math.min(end, data.length)} dari ${data.length}`;
                }

                function renderPagination() {
                    pageSelect.innerHTML = "";
                    for (let i = 1; i <= totalPages; i++) {
                        const option = document.createElement("option");
                        option.value = i;
                        option.innerText = i;
                        pageSelect.appendChild(option);
                    }
                    pageSelect.value = currentPage;
                }

                pageSelect.addEventListener("change", function () {
                    currentPage = parseInt(this.value);
                    renderTable();
                });

                renderPagination();
                renderTable();
            });
            document.addEventListener('DOMContentLoaded', () => {
                const token = localStorage.getItem('token');

                if (!token) {
                    return window.location.href = '/login';
                }

                axios.get('/api/user', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                .then(res => {
                    // tampilkan data user misal: nama, peran
                    document.getElementById('welcome').textContent = `Hai, ${res.data.nama}`;
                })
                .catch(err => {
                    // token invalid, redirect login
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                });
            });
            function logout() {
                const token = localStorage.getItem('token'); // Ganti kalau token-nya kamu simpan di tempat lain

                fetch('http://127.0.0.1:8000/api/keluar', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        localStorage.removeItem('token'); // Hapus token dari localStorage
                        window.location.href = '/'; // Redirect ke halaman login/home
                    } else {
                        alert('Gagal logout!');
                    }
                });
            }
        </script>
    </body>
</html>