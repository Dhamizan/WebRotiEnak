<script setup>
    import { Head, useForm } from '@inertiajs/vue3';
    import { onMounted } from 'vue';
    import { useAuth } from '@/Composables/useAuth';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { ref, computed } from 'vue';

    const id = new URLSearchParams(window.location.search).get('id')

    onMounted(async () => {
      await useAuth('admin') // validasi auth

      try {
        const [gajiRes, pegawaiRes] = await Promise.all([
          axios.get(`/api/gaji/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          }),
          axios.get(`/api/pegawai/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          })
        ])

        if (gajiRes.data.success) {
          gajiList.value = gajiRes.data.data
        }

        if (pegawaiRes.data.success) {
          pegawaiData.value = pegawaiRes.data.data
        }

      } catch (error) {
        console.error('Gagal ambil data pegawai:', error)
      }
    })

    const gajiList = ref([])
    const pegawaiData = ref([])
    const currentPage = ref(1)
    const itemsPerPage = 7
    const selectedMonth = ref('')

    const filteredGaji = computed(() => {
        let result = gajiList.value
        if (selectedMonth.value) {
            result = result.filter(p => p.outlet === selectedMonth.value)
        }
        return result
    })


    const totalPages = computed(() => Math.ceil(gajiList.value.length / itemsPerPage))

    const paginatedGaji = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredGaji.value.slice(start, start + itemsPerPage)
    })

    function formatTanggal(dateString) {
      const tanggal = new Date(dateString)
      return tanggal.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }

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

    const goBack = () => {
      window.history.back()
    }
</script>
<template>
    <AdminLayout>
      <div class="mt-[-10px]">
        <div class="flex justify-between items-center">
          <div v-if="filteredGaji.length" class="flex items-center gap-4 w-full">
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
        <!-- Tabel Gaji -->
        <div class="mt-4">
          <div class="bg-white shadow rounded-lg overflow-hidden w-full">
            <div class="overflow-x-auto">
              <table class="w-full table-auto">
                <thead class="bg-white text-left text-sm text-gray-800">
                  <tr>
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Gaji</th>
                  </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                  <tr
                    v-for="(gaji, index) in paginatedGaji"
                    :key="index"
                    :class="[
                      'hover:bg-gray-100 transition',
                      index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50',
                    ]"
                  >
                    <td class="px-6 py-4 text-sm">
                      {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-sm">{{ formatTanggal(gaji.created_at) }}</td>
                    <td class="px-6 py-4 text-sm">{{ gaji.gaji }}</td>
                  </tr>
                </tbody>
              </table>
  
              <!-- Pagination + Filter -->
              <div
                class="flex flex-col md:flex-row justify-between items-center gap-4 px-6 py-4 bg-white border-t"
              >
                <div class="text-sm text-gray-600">
                  {{ (currentPage - 1) * itemsPerPage + 1 }} -
                  {{ Math.min(currentPage * itemsPerPage, gajiList.length) }}
                  dari {{ gajiList.length }}
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