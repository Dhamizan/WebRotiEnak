<script setup>
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth';
import AuthenticatedLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import {
    Users,
    Store,
    CalendarCheck,
    Plane
} from 'lucide-vue-next';

const absensiToday = ref([]);
const pegawaiList = ref([]);
const totalPegawai = ref(0);
const geraiList = ref([]);
const totalGerai = ref(0);
const cutiPending = ref([]);
const selectedGerai = ref(null);
const currentPage = ref(1);
const itemsPerPage = 6;
const cutiCurrentPage = ref(1);
const cutiItemsPerPage = 4;

const paginatedPegawai = computed(() => {
    let filtered = selectedGerai.value
        ? pegawaiList.value.filter(p => p.gerai === selectedGerai.value)
        : pegawaiList.value;
    return filtered.slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
});

const visiblePages = computed(() => {
    const totalPages = Math.ceil(pegawaiList.value.length / itemsPerPage);
    return Array.from({ length: totalPages }, (_, i) => i + 1);
});

const goToPage = (page) => {
    currentPage.value = page;
};

const paginatedCuti = computed(() => {
    return cutiPending.value.slice((cutiCurrentPage.value - 1) * cutiItemsPerPage, cutiCurrentPage.value * cutiItemsPerPage);
});

const visibleCutiPages = computed(() => {
    const totalPages = Math.ceil(cutiPending.value.length / cutiItemsPerPage);
    return Array.from({ length: totalPages }, (_, i) => i + 1);
});

const goToCutiPage = (page) => {
    cutiCurrentPage.value = page;
};

const lihatCuti = (cuti) => {
    router.visit(`/admin/cuti/rincian?id=${cuti.id_pengguna}`)
};

onMounted(async () => {
    await useAuth('admin')
    await fetchAbsensiToday()
    await fetchGerai()
    await fetchCutiPending();
    await fetchPegawai();

    // setInterval(async () => {
    //     await fetchAbsensiToday();
    //     await fetchCutiPending();
    //     await fetchPegawai();
    // }, 5000);

    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/pegawai', {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            });
            const todayIds = absensiToday.value
                .filter(a => a.jam_masuk !== null)
                .map(a => a.id_pengguna);


        pegawaiList.value = response.data.data.map(p => {
            const absensi = absensiToday.value.find(a => a.id_pengguna === p.id);
            let status = 'Tidak Hadir';

            if (absensi) {
                if (absensi.jam_masuk && absensi.jam_keluar) {
                    status = 'Hadir';
                } else if (absensi.jam_masuk && !absensi.jam_keluar) {
                    status = 'Kerja';
                }
            }

            return {
                id: p.id,
                nama: p.nama,
                gerai: p.gerai?.gerai || 'Tidak diketahui',
                status
            };
        });

        totalPegawai.value = pegawaiList.value.length;

    } catch (error) {
        console.error('Gagal mengambil data:', error);
    }
});

const fetchPegawai = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/pegawai', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const todayIds = absensiToday.value
            .filter(a => a.jam_masuk !== null)
            .map(a => a.id_pengguna);

        pegawaiList.value = response.data.data.map(p => {
            const absensi = absensiToday.value.find(a => a.id_pengguna === p.id);
            let status = 'Tidak Hadir';

            if (absensi) {
                if (absensi.jam_masuk && absensi.jam_keluar) {
                    status = 'Hadir';
                } else if (absensi.jam_masuk && !absensi.jam_keluar) {
                    status = 'Kerja';
                }
            }

            return {
                id: p.id,
                nama: p.nama,
                gerai: p.gerai?.gerai || 'Tidak diketahui',
                status
            };
        });

        totalPegawai.value = pegawaiList.value.length;

    } catch (error) {
        console.error('Gagal mengambil data:', error);
    }
};

const fetchAbsensiToday = async () => {
  try {
    const response = await axios.get('/api/absensi-today')
    absensiToday.value = response.data.data
  } catch (error) {
    console.error('Gagal ambil data absensi hari ini:', error)
  }
}

const fetchGerai = async () => {
  try {
    const response = await axios.get('/api/gerai');
    geraiList.value = response.data.data;
    totalGerai.value = geraiList.value.length;
  } catch (error) {
    console.error('Gagal ambil data gerai:', error);
  }
}

const fetchCutiPending = async () => {
  try {
    const response = await axios.get('/api/cuti-pending')
    cutiPending.value = response.data.data
  } catch (error) {
    console.error('Gagal ambil data cuti pending:', error)
  }
}

</script>
<template>
    <AuthenticatedLayout>
        <div class="py-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-0">
                <!-- Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                            <Users class="w-6 h-6" />
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Pegawai</h3>
                            <p class="text-sm text-gray-500">Total: {{ totalPegawai }} Pegawai</p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Card 2 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                            <Store class="w-6 h-6" />
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Gerai</h3>
                            <p class="text-sm text-gray-500">Total: {{ totalGerai }} Gerai</p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Card 3 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                            <CalendarCheck class="w-6 h-6" />
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Kehadiran</h3>
                            <p class="text-sm text-gray-500">Total: {{ absensiToday.length }} Pegawai</p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Card 4 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                                <Plane class="w-6 h-6" />
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Cuti</h3>
                            <p class="text-sm text-gray-500">Total: {{ cutiPending.length }} Pegawai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Tabel Pegawai -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-1 lg:col-span-2 bg-white shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-white text-left text-sm text-gray-800">
                            <tr>
                                <th class="px-6 py-4 font-medium">No</th>
                                <th class="px-6 py-4 font-medium">Pegawai</th>
                                <th class="px-6 py-4 font-medium">Gerai</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                            <tr v-for="(pegawai, index) in paginatedPegawai" :key="index" :class="['hover:bg-gray-200 transition', index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50']">
                                <td class="px-6 py-4 text-sm">
                                    {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm">{{ pegawai.nama }}</td>
                                <td class="px-6 py-4 text-sm">{{ pegawai.gerai }}</td>
                                <td class="px-6 py-4">
                                    <span 
                                    class="inline-block px-3 py-1 text-xs rounded-full font-medium"
                                    :class="{'bg-green-100 text-green-700': pegawai.status === 'Hadir','bg-yellow-100 text-yellow-700': pegawai.status === 'Kerja','bg-red-100 text-red-700': pegawai.status === 'Tidak Hadir'}">
                                    {{ pegawai.status }}
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Pagination Pegawai -->
                        <div class="flex justify-between items-center px-6 py-4 bg-white border-t">
                            <div class="text-sm text-gray-600"> {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ Math.min(currentPage * itemsPerPage, pegawaiList.length) }} dari {{ pegawaiList.length }}</div>
                            <select
                                id="geraiFilter"
                                v-model="selectedGerai"
                                class="w-full md:w-[200px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                >
                                <option value="">Daftar Gerai</option>
                                <option
                                    v-for="gerai in geraiList"
                                    :key="gerai.id"
                                    :value="gerai.gerai"
                                >
                                    {{ gerai.gerai }}
                                </option>
                            </select>
                            <div class="space-x-2">
                                <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" class="px-3 py-1 text-sm border rounded" :class="{ 'bg-yellow-500 text-white': page === currentPage }">
                                    {{ page }}
                                </button>
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
                                    Status: <span class="text-yellow-600 font-medium">Belum diproses</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="cutiPending.length > cutiItemsPerPage" class="flex justify-between items-center px-6 py-5 bg-white border-t rounded-b-lg">
                        <div class="text-sm text-gray-600">
                            Data {{ (cutiCurrentPage - 1) * cutiItemsPerPage + 1 }} - 
                            {{ Math.min(cutiCurrentPage * cutiItemsPerPage, cutiPending.length) }} dari 
                            {{ cutiPending.length }}
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
    </AuthenticatedLayout>
  </template>