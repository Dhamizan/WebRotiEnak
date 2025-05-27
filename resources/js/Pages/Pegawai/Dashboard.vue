<script setup>
    import { onMounted, ref, computed } from 'vue';
    import axios from 'axios';
    import { useAuth } from '@/Composables/useAuth';
    import PegawaiLayout from '@/Layouts/PegawaiLayout.vue';
    import { router } from '@inertiajs/vue3';

    onMounted(async () => {
        await useAuth('pegawai');
        await fetchAbsensiList();
        await fetchCuti();
    });

    const pegawaiList = ref ([]);
    const absensiList = ref([]);
    const currentPage = ref(1);
    const itemsPerPage = 8;
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
                kode: `${month}-${year}`,
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

    const cutiList = ref([])

    const cutiCurrentPage = ref(1)
    const cutiItemsPerPage = 5

    const paginatedCuti = computed(() => {
        return cutiList.value.slice((cutiCurrentPage.value - 1) * cutiItemsPerPage, cutiCurrentPage.value * cutiItemsPerPage);
    });

    const visibleAbsensiPages = computed(() => {
        const totalPages = Math.ceil(absensiList.value.length / itemsPerPage);
        return Array.from({ length: totalPages }, (_, i) => i + 1);
    });

    const visibleCutiPages = computed(() => {
        const totalPages = Math.ceil(cutiList.value.length / cutiItemsPerPage);
        return Array.from({ length: totalPages }, (_, i) => i + 1);
    });

    const goToCutiPage = (page) => {
        cutiCurrentPage.value = page;
    };

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

    const fetchCuti = async () => {
        try {
            const response = await axios.get(`/api/lihatcuti-pegawai`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            cutiList.value = response.data.data;
        } catch (error) {
            console.error('Gagal ambil data cuti:', error);
        }
    };

    const lihatCuti = () => {
        router.visit(`/pegawai/cuti`)
    };
</script>
<template>
    <PegawaiLayout>
        <div class="py-0">
            <!-- Tabel Pegawai -->
            <div class="mt-0 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-1 lg:col-span-2 bg-white shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-white text-left text-sm text-gray-800">
                            <tr>
                                <th class="px-6 py-4 font-medium">No</th>
                                <th class="px-6 py-4 font-medium">Tanggal</th>
                                <th class="px-6 py-4 font-medium">Jam Kerja</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                                <tr v-for="(absensi, index) in paginatedAbsensi" :key="index" :class="['hover:bg-gray-100 transition', index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50']">
                                    <td class="px-6 py-4 text-sm">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm">{{ formatTanggal(absensi.created_at) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ absensi.jam_kerja }}</td>
                                    <td class="px-6 py-4 text-sm">{{ absensi.status }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Pagination Pegawai -->
                        <div class="flex justify-between items-center px-6 py-4 bg-white border-t">
                            <div class="text-sm text-gray-600">
                                {{ (currentPage - 1) * itemsPerPage + 1 }} - 
                                {{ Math.min(currentPage * itemsPerPage, absensiList.length) }} dari 
                                {{ absensiList.length }}
                            </div>
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
                <!-- Permintaan Cuti -->
                <div class="shadow rounded-lg">
                    <div class="bg-white px-6 py-3 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-800">Permintaan Cuti</h3>
                    </div>

                    <div v-for="(cuti, index) in paginatedCuti" :key="index" @click="lihatCuti(cuti)" 
                        :class="[ 
                            index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50', 
                            'px-6 py-2.5 border-b hover:bg-gray-200 transition cursor-pointer'
                        ]">
                        <div class="flex items-center space-x-4">
                            <div class="text-gray-600 font-medium">{{ (cutiCurrentPage - 1) * cutiItemsPerPage + index + 1 }}</div>
                            <img :src="cuti?.pengguna?.gambar_profil ? 'http://192.168.195.63:8000/storage/' + cuti.pengguna.gambar_profil : 'https://via.placeholder.com/80'" alt="Profile" class="w-12 h-12 rounded-full object-cover" />
                            <div class="flex-1">
                                <div class="text-lg font-semibold text-gray-800">{{ cuti.pengguna.nama }}</div>
                                <div class="text-sm text-gray-600">
                                    Mengajukan cuti pada 
                                    {{ new Date(cuti.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Status: 
                                    <span v-if="cuti.status === 0" class="text-yellow-600 font-medium">Masih diproses</span>
                                    <span v-if="cuti.status === 1" class="text-green-600 font-medium">Diterima</span>
                                    <span v-if="cuti.status === 2" class="text-red-600 font-medium">Ditolak</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="cutiList.length > cutiItemsPerPage" class="flex justify-between items-center px-6 py-5 bg-white border-t rounded-b-lg">
                        <div class="text-sm text-gray-600">
                            Data {{ (cutiCurrentPage - 1) * cutiItemsPerPage + 1 }} - 
                            {{ Math.min(cutiCurrentPage * cutiItemsPerPage, cutiList.length) }} dari 
                            {{ cutiList.length }}
                        </div>
                        <div class="space-x-2">
                            <button v-for="page in visibleCutiPages" :key="page" @click="goToCutiPage(page)" class="px-3 py-1 text-sm border rounded" :class="{ 'bg-yellow-500 text-white': page === cutiCurrentPage }">
                                {{ page }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PegawaiLayout>
  </template>