<template>
    <div class="flex h-screen bg-gray-100">
      <Sidebar />
  
      <div class="flex-1 flex flex-col">
        <Navbar />
  
        <main class="p-6 overflow-y-auto bg-[#F4F7FE] flex-1">
          <slot />
        </main>
      </div>
    </div>
  </template>
  
  <script setup>
  import Sidebar from '@/Components/Sidebar.vue'
  import Navbar from '@/Components/Navbar.vue'
  </script>
  