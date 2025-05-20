<script setup>
    import { onMounted, ref, computed } from 'vue';
    import axios from 'axios';
    import { useAuth } from '@/Composables/useAuth';
    import PegawaiLayout from '@/Layouts/PegawaiLayout.vue';
    import { router } from '@inertiajs/vue3';

    onMounted(async () => {
        await useAuth('pegawai');
        await fetchAbsensiList();
    });

    const pegawaiList = ref ([]);
    const absensiList = ref([]);
    const currentPage = ref(1);
    const itemsPerPage = 9;
    const selectedBulan = ref('');
    const bulanList = computed(() => {
        const bulanSet = new Set();

        absensiList.value.forEach(absensi => {
            const date = new Date(absensi.created_at);
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
                kode: `${month}-${year}`, // ini nanti buat value-nya
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

    const filteredPegawai = computed(() => {
        if (!selectedBulan.value) return pegawaiList.value
        return pegawaiList.value.filter(p => p.outlet === selectedBulan.value)
    });

    const totalPages = computed(() => Math.ceil(pegawaiList.value.length / itemsPerPage));

    const paginatedAbsensi = computed(() => {
        let filtered = absensiList.value;

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

    const visibleAbsensiPages = computed(() => {
        const totalPages = Math.ceil(absensiList.value.length / itemsPerPage);
        return Array.from({ length: totalPages }, (_, i) => i + 1);
    });

    const goToAbsensiPage = (page) => {
        currentPage.value = page;
    };


    const fetchAbsensiList = async () => {
        try {
            const response = await axios.get(`/api/absensi-pegawai`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            absensiList.value = response.data.data;
        } catch (error) {
            console.error('Gagal ambil data absensi hari ini:', error)
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
                    <th class="px-6 py-4 font-medium">Jam Masuk</th>
                    <th class="px-6 py-4 font-medium">Jam Keluar</th>
                    <th class="px-6 py-4 font-medium">Jam Kerja</th>
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
                    <td class="px-6 py-4 text-sm">{{ formatTanggal(absensi.created_at) }}</td>
                    <td class="px-6 py-4 text-sm">{{ absensi.jam_masuk }}</td>
                    <td class="px-6 py-4 text-sm">{{ absensi.jam_keluar }}</td>
                    <td class="px-6 py-4 text-sm">{{ absensi.jam_kerja }}</td>
                  </tr>
                </tbody>
              </table>
  
              <!-- Pagination + Filter -->
              <div class="flex justify-between items-center px-6 py-4 bg-white border-t">
                <div class="text-sm text-gray-600">
                    {{ (currentPage - 1) * itemsPerPage + 1 }} - 
                    {{ Math.min(currentPage * itemsPerPage, absensiList.length) }} dari 
                    {{ absensiList.length }}
                </div>
                <div v-if="absensiList.length > itemsPerPage" class="flex justify-between items-center px-0 py-0 bg-white border-t rounded-b-lg">
                  <div class="text-sm text-gray-600"></div>
                  <div class="space-x-2">
                      <button v-for="page in visibleAbsensiPages" :key="page" @click="goToAbsensiPage(page)" class="px-3 py-1 text-sm border rounded" :class="{ 'bg-yellow-500 text-white': page === currentPage }">
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