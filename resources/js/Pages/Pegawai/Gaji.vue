<script setup>
    import { onMounted, ref, computed } from 'vue';
    import axios from 'axios';
    import { useAuth } from '@/Composables/useAuth';
    import PegawaiLayout from '@/Layouts/PegawaiLayout.vue';
    import { router } from '@inertiajs/vue3';

    onMounted(async () => {
        await useAuth('pegawai');
        await fetchGajiList();
    });

    const pegawaiList = ref ([]);
    const gajiList = ref([]);
    const currentPage = ref(1);
    const itemsPerPage = 9;
    const selectedBulan = ref('');
    const bulanList = computed(() => {
        const bulanSet = new Set();

        gajiList.value.forEach(gaji => {
            const date = new Date(gaji.created_at);
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

    const paginatedGaji = computed(() => {
        let filtered = gajiList.value;

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

    const visibleGajiPages = computed(() => {
        const totalPages = Math.ceil(gajiList.value.length / itemsPerPage);
        return Array.from({ length: totalPages }, (_, i) => i + 1);
    });

    const goToGajiPage = (page) => {
        currentPage.value = page;
    };


    const fetchGajiList = async () => {
        try {
            const response = await axios.get(`/api/gaji-pegawai`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            gajiList.value = response.data.data;
        } catch (error) {
            console.error('Gagal ambil data gaji hari ini:', error)
        }
    }


    function formatTanggal(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
</script>
<template>
    <PegawaiLayout>
      <div class="mt-[-10px]">
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
        </div>
        <!-- Tabel Pegawai -->
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
              <div class="flex justify-between items-center px-6 py-4 bg-white border-t">
                <div class="text-sm text-gray-600">
                    {{ (currentPage - 1) * itemsPerPage + 1 }} - 
                    {{ Math.min(currentPage * itemsPerPage, gajiList.length) }} dari 
                    {{ gajiList.length }}
                </div>
                <div v-if="gajiList.length > itemsPerPage" class="flex justify-between items-center px-0 py-0 bg-white border-t rounded-b-lg">
                  <div class="text-sm text-gray-600"></div>
                  <div class="space-x-2">
                      <button v-for="page in visibleGajiPages" :key="page" @click="goToGajiPage(page)" class="px-3 py-1 text-sm border rounded" :class="{ 'bg-yellow-500 text-white': page === currentPage }">
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
  </template>  