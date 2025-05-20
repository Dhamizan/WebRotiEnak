<script setup>
import { ref, onMounted } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import {
  LayoutDashboard,
  CalendarCheck,
  Wallet,
  Plane,
  LogOut
} from 'lucide-vue-next'

onMounted(async () => {
  try {
    const res = await axios.get('/api/pegawai-profil', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (res.data.success && res.data.data.length > 0) {
      pegawai.value = res.data.data[0]
    }
  } catch (err) {
    console.error('Gagal ambil data pegawai di PegawaiLayout:', err)
  }
})

const pegawai = ref(null);

const isSidebarOpen = ref(false)

const logout = async () => {
  try {
    const token = localStorage.getItem('token');
    await axios.post('/api/keluar', {}, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    localStorage.removeItem('token');
    router.visit('/masuk'); // redirect ke halaman login
  } catch (error) {
    console.error('Logout gagal:', error.response);
  }
};

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value
}

const page = usePage()
const currentRoute = page.url
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Navbar -->
    <header class="shadow px-6 py-4 flex justify-between items-center text-white" style="background: linear-gradient(to right, #FEB412, #EC8119);">
      <div class="flex items-center space-x-4">
        <!-- Hamburger (mobile only) -->
        <button class="sm:hidden focus:outline-none" @click="toggleSidebar">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div class="flex items-center space-x-3">
          <img src="/images/logo.png" alt="Logo" class="w-8 h-8 rounded-full" />
          <div class="text-xl font-bold">Roti Enak</div>
        </div>
      </div>
      <Link href="/pegawai/profil" class="flex items-center space-x-3 text-white hover:opacity-90">
        <span v-if="pegawai" class="font-medium">Hai, {{ pegawai.nama }}</span> 
          <img :src="pegawai?.gambar_profil ? 'http://192.168.63.63:8000/storage/' + pegawai.gambar_profil : 'https://via.placeholder.com/80'" alt="Profile" class="w-8 h-8 rounded-full object-cover" />
      </Link>
    </header>

    <div class="flex flex-1">
      <!-- Sidebar Desktop -->
      <aside class="mt-[50px] w-64 bg-white shadow-md hidden sm:flex flex-col justify-between">
        <nav class="mt-6">
          <Link href="/pegawai/dashboard" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/dashboard') }">
            <LayoutDashboard class="w-6 h-6" /> Dashboard
          </Link>
          <Link href="/pegawai/absensi" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/absensi') }">
            <CalendarCheck class="w-6 h-6" /> Absensi
          </Link>
          <Link href="/pegawai/gaji" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/gaji') }">
            <Wallet class="w-6 h-6" /> Gaji
          </Link>
          <Link href="/pegawai/cuti" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/cuti') }">
            <Plane class="w-6 h-6" /> Cuti
          </Link>
        </nav>

        <div class="p-6">
          <button @click="logout" class="flex items-center gap-3 w-full text-left text-red-500 hover:text-red-700">
            <LogOut class="w-6 h-6" /> Keluar
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
            <div class="p-6">
                <nav class="mt-16">
                    <Link href="/pegawai/dashboard" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawaI/dashboard') }">
                        <LayoutDashboard class="w-6 h-6" /> Dashboard
                    </Link>
                    <Link href="/pegawai/absensi" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/absensi') }">
                        <CalendarCheck class="w-6 h-6" /> Absensi
                    </Link>
                    <Link href="/pegawai/gaji" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/gaji') }">
                        <Wallet class="w-6 h-6" /> Gaji
                    </Link>
                    <Link href="/pegawai/cuti" class="flex items-center gap-6 px-6 py-4 transition duration-200 ease-in-out  hover:text-yellow-500 text-lg"
                            :class="{ 'bg-white font-semibold text-yellow-500 text-lg': currentRoute.startsWith('/pegawai/cuti') }">
                        <Plane class="w-6 h-6" /> Cuti
                    </Link>
                </nav>
            </div>

            <div class="p-6">
              <button @click="() => { logout(); isSidebarOpen = false }"
                      class="flex items-center gap-6 w-full text-left text-red-500 hover:text-red-700">
                <LogOut class="w-6 h-6" /> Keluar
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
header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 50; /* Pastikan navbar di atas konten lainnya */
}

main {
  margin-top: 70px; /* Sesuaikan dengan tinggi navbar */
  flex: 1;
  overflow-y: auto;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
