<script setup>
    import { onMounted } from 'vue';
    import { useAuth } from '@/Composables/useAuth';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { ref, computed } from 'vue';
    import axios from 'axios';

    const id = new URLSearchParams(window.location.search).get('id')

    onMounted(async () => {
      await useAuth('admin')
      
      try {
        const [cutiRes, pegawaiRes] = await Promise.all([
          axios.get(`/api/cuti/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          }),
          axios.get(`/api/pegawai/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          })
        ])

        if (cutiRes.data.success) {
          cutiList.value = cutiRes.data.data
        }

        if (pegawaiRes.data.success) {
          pegawaiData.value = pegawaiRes.data.data
        }

        await Promise.all([
          getCuti(),
          getPegawai()
        ]);
        
      } catch (error) {
        console.error('Gagal ambil data pegawai:', error)
      }
    })

    const pegawaiData = ref({})
    const cutiList = ref([])
    const currentPage = ref(1)
    const itemsPerPage = 7
    const selectedMonth = ref('')

    const filteredCuti = computed(() => {
        let result = cutiList.value
        if (selectedMonth.value) {
            result = result.filter(p => p.outlet === selectedMonth.value)
        }
        return result
    })


    const totalPages = computed(() => Math.ceil(cutiList.value.length / itemsPerPage))

    const paginatedCuti = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredCuti.value.slice(start, start + itemsPerPage)
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

    function formatTanggal(dateString) {
      const tanggal = new Date(dateString)
      return tanggal.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }
    
    async function getCuti() {
      try {
        const response = await axios.get(`/api/cuti/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        if (response.data.success) {
          cutiList.value = response.data.data;
        }
      } catch (error) {
        console.error('Gagal mengambil data cuti:', error);
      }
    }

    const showResultModal = ref(false)
    showResultModal.value = false
    const resultMessage = ref('')

    const updateStatus = async (id, status) => {
      try {
        await axios.post(`/api/cuti/penerimaan/${id}`, { status }, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });

        closeResultModal();
        await getCuti();
        resultMessage.value = 'Status berhasil diperbaharui!';
        showResultModal.value = true;

      } catch (err) {
        console.error('Gagal update status:', err);
      }
    }
    
    function closeResultModal() {
      showResultModal.value = false
      resultMessage.value = ''
    }

    const goBack = () => {
      window.history.back()
    }
</script>
<template>
    <AdminLayout>
      <div class="mt-[-10px]">
        <div class="flex justify-between items-center">
          <div v-if="filteredCuti.length" class="flex items-center gap-4 w-full">
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
        <!-- Tabel Cuti -->
        <div class="mt-4">
          <div class="bg-white shadow rounded-lg overflow-hidden w-full">
            <div class="overflow-x-auto">
              <table class="w-full table-auto">
                <thead class="bg-white text-left text-sm text-gray-800">
                  <tr>
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Waktu Mulai</th>
                    <th class="px-6 py-4 font-medium">Waktu Selesai</th>
                    <th class="px-6 py-4 font-medium">Alasan</th>
                    <th class="px-6 py-4 font-medium">Dokumen Pendukung</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                  </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                  <tr
                    v-for="(cuti, index) in paginatedCuti"
                    :key="index"
                    :class="[
                      'hover:bg-gray-100 transition',
                      index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50',
                    ]"
                  >
                    <td class="px-6 py-4 text-sm">
                      {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-sm">{{ formatTanggal(cuti.created_at) }}</td>
                    <td class="px-6 py-4 text-sm">{{ cuti.waktu_mulai }}</td>
                    <td class="px-6 py-4 text-sm">{{ cuti.waktu_selesai }}</td>
                    <td class="px-6 py-4 text-sm">{{ cuti.alasan }}</td>
                    <td class="px-6 py-4 text-sm">
                      <div v-if="cuti.dokumen_pendukung">
                        <a
                          :href="`/storage/${cuti.dokumen_pendukung}`"
                          target="_blank"
                          class="bg-orange-100 text-orange-700 px-4 py-1 rounded-full text-xs font-semibold hover:bg-orange-200 transition cursor-pointer"
                        >
                          Lihat Dokumen
                        </a>
                      </div>
                      <span v-else class="text-gray-500">Tidak Ada</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                      <div v-if="cuti.status === 0" class="flex gap-2">
                        <button
                          @click="updateStatus(cuti.id, 1)"
                          class="bg-green-500 text-green-700 text-xs px-2 py-1 rounded-lg hover:bg-green-700 text-white transition"
                        >
                          Terima
                        </button>
                        <button
                          @click="updateStatus(cuti.id, 2)"
                          class="bg-red-500 text-red-700 text-xs px-2 py-1 rounded-lg hover:bg-red-700 text-white transition"
                        >
                          Tidak Diterima
                        </button>
                      </div>
                      <span
                        v-else
                        class="px-3 py-1 rounded-full text-xs font-semibold"
                        :class="[
                          cuti.status === 1 ? 'bg-green-100 text-green-700' :
                          cuti.status === 2 ? 'bg-red-100 text-red-700' :
                          'bg-gray-100 text-gray-500'
                        ]"
                      >
                        {{
                          cuti.status === 1 ? 'Diterima' :
                          cuti.status === 2 ? 'Tidak Diterima' :
                          '-'
                        }}
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
                  {{ Math.min(currentPage * itemsPerPage, cutiList.length) }}
                  dari {{ cutiList.length }}
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
    <!-- <transition name="fade">
      <div
        v-if="showResultModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center"
      >
        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-green-600">Berhasil</h3>
          <p class="text-gray-600 font-semibold">Status berhasil diperbarui!</p>
        </div>
      </div>
    </transition> -->
    <transition name="fade">
      <div
        v-if="showResultModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center"
      >
        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-green-600">Berhasil</h3>
          <p class="text-gray-700">{{ resultMessage }}</p>
          <div class="flex justify-end">
            <button
              @click="closeResultModal"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </transition>
  </template>  
  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>
  