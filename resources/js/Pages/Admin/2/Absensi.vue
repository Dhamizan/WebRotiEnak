<script setup>
    import { onMounted } from 'vue';
    import { useAuth } from '@/Composables/useAuth';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { ref, computed } from 'vue';
    import axios from 'axios';

    const id = new URLSearchParams(window.location.search).get('id')

    onMounted(async () => {
      await useAuth('admin') // validasi auth

      try {
        const [absensiRes, pegawaiRes] = await Promise.all([
          axios.get(`/api/absensi/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          }),
          axios.get(`/api/pegawai/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          })
        ])

        if (absensiRes.data.success) {
          absensiList.value = absensiRes.data.data
        }

        if (pegawaiRes.data.success) {
          pegawaiData.value = pegawaiRes.data.data
        }

      } catch (error) {
        console.error('Gagal ambil data pegawai:', error)
      }
    })

    const pegawaiData = ref({})
    const absensiList = ref([])
    const currentPage = ref(1)
    const itemsPerPage = 7
    const selectedMonth = ref('')

    const filteredAbsensi = computed(() => {
        let result = absensiList.value
        if (selectedMonth.value) {
            result = result.filter(p => p.outlet === selectedMonth.value)
        }
        return result
    })


    const totalPages = computed(() => Math.ceil(absensiList.value.length / itemsPerPage))

    const paginatedAbsensi = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredAbsensi.value.slice(start, start + itemsPerPage)
    })

    function goToPage(page) {
        if (page >= 1 && page <= totalPages.value) {
            currentPage.value = page
        }
    }

    const visiblePages = computed(() => {
    const pages = []
    const maxVisible = 3
    const total = totalPages.value
    const current = currentPage.value

    let start = Math.max(current - Math.floor(maxVisible / 2), 1)
    let end = start + maxVisible - 1

    if (end > total) {
        end = total
        start = Math.max(end - maxVisible + 1, 1)
    }

    for (let i = start; i <= end; i++) {
        pages.push(i)
    }

    return pages
    })

    function getKehadiranBadge(absensi) {
      if (absensi.jam_masuk && absensi.jam_kerja) {
        return { text: 'Hadir', color: 'bg-green-100 text-green-700' }
      } else if (absensi.jam_masuk) {
        return { text: 'Kerja', color: 'bg-yellow-100 text-yellow-700' }
      } else {
        return { text: '-', color: 'bg-gray-100 text-gray-500' }
      }
    }

    function formatTanggal(dateString) {
      const tanggal = new Date(dateString)
      return tanggal.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }

    const goBack = () => {
      window.history.back()
    }
</script>
<template>
    <AdminLayout>
      <div class="mt-[-10px]">
        <div class="flex justify-between items-center">
          <div v-if="filteredAbsensi.length" class="flex items-center gap-4 w-full">
            <img :src="pegawaiData?.gambar_profil ? 'http://192.168.195.63:8000/storage/' + pegawaiData.gambar_profil : 'https://via.placeholder.com/80'" alt="Profile" class="w-16 h-16 rounded-full object-cover" />
            <div>
              <h2 class="text-lg font-semibold text-gray-800">{{ pegawaiData?.nama }}</h2>
              <p class="text-sm text-gray-600">Dari Gerai: {{ pegawaiData?.gerai?.gerai }}</p>
            </div>
          </div>
          <div class="mt-0 flex justify-end">
            <button class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600"
            @click="goBack"
            >
              Back
            </button>
          </div>
        </div>
        <!-- Tabel Absensi -->
        <div class="mt-4">
          <div class="bg-white shadow rounded-lg overflow-hidden w-full">
            <div class="overflow-x-auto">
              <table class="w-full table-auto">
                <thead class="bg-white text-left text-sm text-gray-800">
                  <tr>
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Jam Masuk</th>
                    <th class="px-6 py-4 font-medium">Jam Keluar</th>
                    <th class="px-6 py-4 font-medium">Jam Kerja</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Kehadiran</th>
                  </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                  <tr
                    v-for="(absensi, index) in paginatedAbsensi"
                    :key="index"
                    :class="[
                      'hover:bg-gray-100 transition',
                      index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50',
                    ]"
                  >
                    <td class="px-6 py-4 text-sm">
                      {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-sm">{{ absensi.jam_masuk }}</td>
                    <td class="px-6 py-4 text-sm">{{ absensi.jam_keluar }}</td>
                    <td class="px-6 py-4 text-sm">{{ absensi.jam_kerja }}</td>
                    <td class="px-6 py-4 text-sm">{{ formatTanggal(absensi.created_at) }}</td>
                    <td class="px-6 py-4 text-sm">
                      <span
                        class="px-3 py-1 rounded-full text-xs font-semibold"
                        :class="getKehadiranBadge(absensi).color"
                      >
                        {{ getKehadiranBadge(absensi).text }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
  
              <!-- Pagination + Filter -->
              <div
                class="flex flex-col md:flex-row justify-between items-center gap-4 px-6 py-4 bg-white border-t"
              >
                <div class="text-sm text-gray-600">
                  {{ (currentPage - 1) * itemsPerPage + 1 }} -
                  {{ Math.min(currentPage * itemsPerPage, absensiList.length) }}
                  dari {{ absensiList.length }}
                </div>
                <div class="space-x-2">
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="goToPage(page)"
                    class="px-3 py-1 text-sm border rounded"
                    :class="{ 'bg-yellow-500 text-white': page === currentPage }"
                  >
                    {{ page }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>  