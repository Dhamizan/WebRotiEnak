<script setup>
import { ref } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import {
  LayoutDashboard,
  Store,
  Users,
  CalendarCheck,
  Wallet,
  Plane,
  LogOut
} from 'lucide-vue-next'

const isSidebarOpen = ref(false)

function logout() {
  router.post('/logout')
}

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value
}

const page = usePage()
const currentRoute = page.url
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Navbar -->
    <header class="shadow px-6 py-4 flex justify-between items-center text-white"
            style="background: linear-gradient(to right, #FEB412, #EC8119);">
      <div class="flex items-center space-x-4">
        <!-- Hamburger (mobile only) -->
        <button class="sm:hidden focus:outline-none" @click="toggleSidebar">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div class="text-xl font-bold">
          Roti Enak
        </div>
      </div>

      <div class="block sm:block text-white">Halo, Admin</div>
    </header>

    <div class="flex flex-1">
      <!-- Sidebar Desktop -->
      <aside class="w-64 bg-white shadow-md hidden sm:flex flex-col justify-between">
        <nav class="mt-6">
          <Link href="/admin/dashboard" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/dashboard') }">
            <LayoutDashboard class="w-6 h-6" /> Dashboard
          </Link>
          <Link href="/admin/gerai" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/gerai') }">
            <Store class="w-6 h-6" /> Gerai
          </Link>
          <Link href="/admin/pegawai" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/pegawai') }">
            <Users class="w-6 h-6" /> Pegawai
          </Link>
          <Link href="/admin/absensi" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/absensi') }">
            <CalendarCheck class="w-6 h-6" /> Absensi
          </Link>
          <Link href="/admin/gaji" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/gaji') }">
            <Wallet class="w-6 h-6" /> Gaji
          </Link>
          <Link href="/admin/cuti" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/cuti') }">
            <Plane class="w-6 h-6" /> Cuti
          </Link>
        </nav>

        <div class="p-6">
          <button @click="logout" class="flex items-center gap-3 w-full text-left text-red-500 hover:text-red-700">
            <LogOut class="w-6 h-6" /> Logout
          </button>
        </div>
      </aside>

      <!-- Sidebar Mobile -->
      <transition name="fade">
        <div v-if="isSidebarOpen" class="fixed inset-0 z-40 flex sm:hidden">
          <!-- Overlay -->
          <div class="fixed inset-0 bg-black opacity-50" @click="isSidebarOpen = false"></div>

          <!-- Sidebar -->
          <aside class="w-64 bg-white shadow-md flex flex-col justify-between fixed top-0 left-0 h-full z-50 sm:hidden">
            <div>
                <nav class="mt-8">
                    <Link href="/admin/dashboard" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/dashboard') }">
                        <LayoutDashboard class="w-6 h-6" /> Dashboard
                    </Link>
                    <Link href="/admin/gerai" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/gerai') }">
                        <Store class="w-6 h-6" /> Gerai
                    </Link>
                    <Link href="/admin/pegawai" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/pegawai') }">
                        <Users class="w-6 h-6" /> Pegawai
                    </Link>
                    <Link href="/admin/absensi" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/absensi') }">
                        <CalendarCheck class="w-6 h-6" /> Absensi
                    </Link>
                    <Link href="/admin/gaji" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/gaji') }">
                        <Wallet class="w-6 h-6" /> Gaji
                    </Link>
                    <Link href="/admin/cuti" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/admin/cuti') }">
                        <Plane class="w-6 h-6" /> Cuti
                    </Link>
                </nav>
            </div>

            <div class="p-6">
              <button @click="() => { logout(); isSidebarOpen = false }"
                      class="flex items-center gap-6 w-full text-left text-red-500 hover:text-red-700">
                <LogOut class="w-6 h-6" /> Logout
              </button>
            </div>
          </aside>
        </div>
      </transition>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto p-6">
        <slot name="header" />
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
