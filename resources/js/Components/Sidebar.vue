<template>
    <aside class="w-64 bg-white shadow h-full fixed">
      <div class="p-4 flex items-center gap-2">
        <img src="/logo.png" alt="Logo" class="w-6 h-6" />
        <span class="font-bold text-yellow-500">Roti Enak</span>
      </div>
  
      <nav class="mt-4 flex flex-col gap-2 px-4">
        <SidebarLink icon="lucide:home" text="Dashboard" />
        <SidebarLink icon="lucide:user" text="Pegawai" />
        <SidebarLink icon="lucide:check-square" text="Absensi" />
        <SidebarLink icon="lucide:credit-card" text="Gaji" />
        <SidebarLink icon="lucide:calendar" text="Cuti" />
      </nav>
  
      <div class="mt-auto px-4 pb-4">
        <SidebarLink icon="lucide:log-out" text="Keluar" />
      </div>
    </aside>
  </template>
  
  <script setup>
  </script>
  