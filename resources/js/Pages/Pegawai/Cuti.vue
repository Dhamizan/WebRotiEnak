<script setup>
    import { onMounted, ref, computed } from 'vue';
    import axios from 'axios';
    import { useAuth } from '@/Composables/useAuth';
    import PegawaiLayout from '@/Layouts/PegawaiLayout.vue';

    onMounted(async () => {
        await useAuth('pegawai');
        await fetchCutisList();
    });

    const pegawaiList = ref ([]);
    const cutiList = ref([]);
    const currentPage = ref(1);
    const itemsPerPage = 8;
    const selectedBulan = ref('');
    const bulanList = computed(() => {
        const bulanSet = new Set();

        cutiList.value.forEach(cuti => {
            const date = new Date(cuti.created_at);
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            bulanSet.add(`${month}-${year}`);
        });

        return Array.from(bulanSet).map((bulanTahun, index) => {
            const [month, year] = bulanTahun.split('-');
            const monthName = getMonthName(month);
            return {
                id: index + 1,
                bulan: `${monthName} ${year}`,
                kode: `${month}-${year}`
            };
        });
    });

    function getMonthName(month) {
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return monthNames[parseInt(month, 10) - 1];
    }

    const totalPages = computed(() => Math.ceil(pegawaiList.value.length / itemsPerPage));

    const paginatedCuti = computed(() => {
        let filtered = cutiList.value;

        if (selectedBulan.value) {
            filtered = filtered.filter(p => {
                const date = new Date(p.created_at);
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                const bulanTahun = `${month}-${year}`;
                return bulanTahun === selectedBulan.value;
            });
        }

        return filtered.slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
    });

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

    const visibleCutiPages = computed(() => {
        const totalPages = Math.ceil(cutiList.value.length / itemsPerPage);
        return Array.from({ length: totalPages }, (_, i) => i + 1);
    });

    const goToCutiPage = (page) => {
        currentPage.value = page;
    };


    const fetchCutisList = async () => {
        try {
            const response = await axios.get(`/api/lihatcuti-pegawai`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            cutiList.value = response.data.data;
        } catch (error) {
            console.error('Gagal ambil data cuti hari ini:', error)
        }
    }

    function formatTanggal(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    const showModal = ref(false)

    const newCuti = ref({
    waktu_mulai: '',
    waktu_selesai: '',
    alasan: '',
    dokumen_pendukung: null,
  });

  const openModal = () => {
      showModal.value = true;
  };

  const closeModal = () => {
      showModal.value = false;
      newCuti.value = {
          waktu_mulai: '',
          waktu_selesai: '',
          alasan: '',
          dokumen_pendukung: null,
      };
  };

  const handleFileChange = (e) => {
      const file = e.target.files[0];
      newCuti.value.dokumen_pendukung = file;
  };

  const ajukanCuti = async () => {
      try {
          const formData = new FormData();
          formData.append('waktu_mulai', newCuti.value.waktu_mulai);
          formData.append('waktu_selesai', newCuti.value.waktu_selesai);
          formData.append('alasan', newCuti.value.alasan);
          if (newCuti.value.dokumen_pendukung) {
              formData.append('dokumen_pendukung', newCuti.value.dokumen_pendukung);
          }

          await axios.post('/api/pengajuan-cuti', formData, {
              headers: {
                  Authorization: `Bearer ${localStorage.getItem('token')}`,
                  'Content-Type': 'multipart/form-data'
              }
          });

          closeModal();
          fetchCutisList(); // refresh list cuti
          alert('Pengajuan cuti berhasil!');
      } catch (error) {
          console.error('Gagal mengajukan cuti:', error);
          alert('Terjadi kesalahan saat mengajukan cuti.');
      }
  };
</script>
<template>
    <PegawaiLayout>
      <div class="mt-[-10px]">
        <!-- Tabel Pegawai -->
        <div class="flex flex-col md:flex-row gap-5 items-center gap-0 px-0 py-0">
          <select
            id="bulanFilter"
            v-model="selectedBulan"
            class="w-full md:w-[200px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
            >
            <option value="">Daftar Bulan</option>
            <option
                v-for="bulan in bulanList"
                :key="bulan.id"
                :value="bulan.kode"
            >
                {{ bulan.bulan }}
            </option>
          </select>
          <div class="flex justify-between items-center">
            <button
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 text-sm rounded-lg shadow transition"
                @click="openModal"
            >
                Ajukan Cuti
            </button>
          </div>
        </div>
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
                    <th class="px-6 py-4 font-medium">Dokumenn Pendukung</th>
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
                    <td class="px-6 py-4 text-sm text-center">
                      <div v-if="cuti.dokumen_pendukung">
                        <a
                          :href="`/storage/${cuti.dokumen_pendukung}`"
                          target="_blank"
                          class="bg-orange-100 text-orange-700 px-4 py-1 rounded-full text-xs font-semibold hover:bg-orange-200 transition cursor-pointer"
                        >
                          Lihat Dokumen
                        </a>
                      </div>
                      <div v-else>
                        <span class="text-gray-400 text-xs">Tidak ada</span>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                      <div v-if="cuti.status === 0">
                        <button disabled class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full text-xs font-semibold">
                          Proses
                        </button>
                      </div>
                      <div v-else-if="cuti.status === 1">
                        <button disabled class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-xs font-semibold">
                          Diterima
                        </button>
                      </div>
                      <div v-else-if="cuti.status === 2">
                        <button disabled class="bg-red-100 text-red-700 px-4 py-1 rounded-full text-xs font-semibold">
                          Ditolak
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
  
              <!-- Pagination + Filter -->
              <div class="flex justify-between items-center px-6 py-4 bg-white border-t">
                <div class="text-sm text-gray-600">
                    {{ (currentPage - 1) * itemsPerPage + 1 }} - 
                    {{ Math.min(currentPage * itemsPerPage, cutiList.length) }} dari 
                    {{ cutiList.length }}
                </div>
                <div v-if="cutiList.length > itemsPerPage" class="flex justify-between items-center px-0 py-0 bg-white border-t rounded-b-lg">
                  <div class="text-sm text-gray-600"></div>
                  <div class="space-x-2">
                      <button v-for="page in visibleCutiPages" :key="page" @click="goToCutiPage(page)" class="px-3 py-1 text-sm border rounded" :class="{ 'bg-yellow-500 text-white': page === currentPage }">
                          {{ page }}
                      </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </PegawaiLayout>
    <transition name="fade">
      <div v-if="showModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-gray-800">Ajukan Cuti</h3>

          <div class="space-y-3">
            <input
              type="date"
              v-model="newCuti.waktu_mulai"
              placeholder="Waktu Mulai"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
            />
            <input
              type="date"
              v-model="newCuti.waktu_selesai"
              placeholder="Waktu Selesai"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
            />
            <textarea
              v-model="newCuti.alasan"
              placeholder="Alasan Cuti"
              rows="3"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
            ></textarea>
            <input
              type="file"
              accept="image/*,application/pdf"
              @change="handleFileChange"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 placeholder-gray-400
                file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700
                hover:file:bg-yellow-100 focus:outline-none focus:ring-0 focus:border-transparent"
            />
          </div>

          <div class="flex justify-end gap-2 pt-4">
            <button @click="closeModal" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
              Batal
            </button>
            <button @click="ajukanCuti" class="px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">
              Simpan
            </button>
          </div>

          <button @click="closeModal" class="absolute top-2 right-6 text-gray-400 hover:text-gray-700">✕</button>
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
  