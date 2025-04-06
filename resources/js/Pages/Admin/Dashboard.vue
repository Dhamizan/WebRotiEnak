<script setup>
    import { Head, useForm } from '@inertiajs/vue3';
    import { onMounted } from 'vue';
    import { useAuth } from '@/Composables/useAuth';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { ref, computed } from 'vue';

    // Saat halaman di-mount
    onMounted(async () => {
    const auth = await useAuth('admin')
    if (auth) {
        user.value = auth.user
    }
    })

    const props = defineProps({
        email: {
            type: String,
            required: true,
        },
        token: {
            type: String,
            required: true,
        },
    });

    const form = useForm({
        token: props.token,
        email: props.email,
        password: '',
        password_confirmation: '',
    });

    const submit = () => {
        form.post(route('password.store'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };

    const pegawaiList = ref([
    { nama: 'Rina', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Budi', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Tina', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Rudi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Dina', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Andi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Siti', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Wawan', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Hana', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Dina', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Andi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Siti', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Wawan', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Hana', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Dina', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Andi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Siti', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Wawan', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Hana', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Dina', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Andi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Siti', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Wawan', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Hana', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Andi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Siti', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Wawan', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Hana', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Dina', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Andi', outlet: 'Outlet C', status: 'Aktif' },
    { nama: 'Siti', outlet: 'Outlet A', status: 'Aktif' },
    { nama: 'Wawan', outlet: 'Outlet B', status: 'Aktif' },
    { nama: 'Hana', outlet: 'Outlet C', status: 'Aktif' },
    ])

    const currentPage = ref(1)
    const itemsPerPage = 6

    const selectedOutlet = ref('')

    const filteredPegawai = computed(() => {
    if (!selectedOutlet.value) return pegawaiList.value
    return pegawaiList.value.filter(p => p.outlet === selectedOutlet.value)
    })

    const totalPages = computed(() => Math.ceil(pegawaiList.value.length / itemsPerPage))

    const paginatedPegawai = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredPegawai.value.slice(start, start + itemsPerPage)
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

    const cutiList = [
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Rizki Hidayat', outlet: 'Outlet C' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Rizki Hidayat', outlet: 'Outlet C' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Rizki Hidayat', outlet: 'Outlet C' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Rizki Hidayat', outlet: 'Outlet C' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Rizki Hidayat', outlet: 'Outlet C' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Aulia Rahma', outlet: 'Outlet B' },
    { nama: 'Rizki Hidayat', outlet: 'Outlet C' },
    ]

    function lihatCuti(cuti) {
    // contoh: tampilkan alert, nanti bisa diganti modal atau navigasi
    alert(`Lihat permintaan cuti dari ${cuti.nama} - ${cuti.outlet}`)
    }

    const cutiCurrentPage = ref(1)
    const cutiItemsPerPage = 5

    const totalCutiPages = computed(() => Math.ceil(cutiList.length / cutiItemsPerPage))

    const paginatedCuti = computed(() => {
        const start = (cutiCurrentPage.value - 1) * cutiItemsPerPage
        return cutiList.slice(start, start + cutiItemsPerPage)
    })

    function goToCutiPage(page) {
    if (page >= 1 && page <= totalCutiPages.value) {
        cutiCurrentPage.value = page
    }
    }

    const visibleCutiPages = computed(() => {
        const pages = []
        const maxVisible = 3
        const total = totalCutiPages.value
        const current = cutiCurrentPage.value

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
</script>
<template>
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-0">
                <!-- Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 text-blue-500 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h4l3 10h8l3-10h4" />
                            </svg>
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Pegawai</h3>
                            <p class="text-sm text-gray-500">Total: 120 Pegawai</p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Card 2 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-green-100 text-green-500 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c1.333-1.333 4-1.333 5.333 0C18.667 9.333 18.667 12 17.333 13.333L12 18l-5.333-4.667C5.333 12 5.333 9.333 6.667 8c1.333-1.333 4-1.333 5.333 0z" />
                            </svg>
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Gerai</h3>
                            <p class="text-sm text-gray-500">Total: 14 Gerai</p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Card 3 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h13" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11l6 6" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h6" />
                            </svg>
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Kehadiran</h3>
                            <p class="text-sm text-gray-500">Saat ini: 32 Pegawai</p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Card 4 -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-red-100 text-red-500 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 14l2-2 4 4M7 10h.01M12 10h.01M17 10h.01" />
                            </svg>
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">Cuti</h3>
                            <p class="text-sm text-gray-500">Saat ini: 12 orang</p>
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
                                <th class="px-6 py-4 font-medium">Outlet</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                            <tr v-for="(pegawai, index) in paginatedPegawai" :key="index" :class="['hover:bg-gray-100 transition', index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50']">
                                <td class="px-6 py-4 text-sm">
                                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm">{{ pegawai.nama }}</td>
                                <td class="px-6 py-4 text-sm">{{ pegawai.outlet }}</td>
                                <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">
                                    {{ pegawai.status }}
                                </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Pagination Pegawai -->
                        <div class="flex justify-between items-center px-6 py-4 bg-white border-t">
                            <div class="text-sm text-gray-600"> {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ Math.min(currentPage * itemsPerPage, pegawaiList.length) }} dari {{ pegawaiList.length }}</div>
                            <select id="outletFilter" v-model="selectedOutlet" class="w-[30%] max-w-xs border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Outlet</option>
                                <option value="Outlet A">Outlet A</option>
                                <option value="Outlet B">Outlet B</option>
                                <option value="Outlet C">Outlet C</option>
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
                    <div class="bg-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-800">Permintaan Cuti</h3>
                    </div>
    
                    <ul class="divide-y divide-gray-200">
                        <li
                            v-for="(cuti, index) in paginatedCuti" :key="index" :class="[index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50', 'flex items-center justify-between px-6 py-4']">
                            <div>
                            <p class="text-sm font-medium text-gray-900">{{ cuti.nama }}</p>
                            <p class="text-xs text-gray-500">{{ cuti.outlet }}</p>
                            </div>
                            <button
                            class="text-blue-600 text-sm hover:underline focus:outline-none"
                            @click="lihatCuti(cuti)"
                            >
                            Lihat
                            </button>
                        </li>
                    </ul>
    
                    <!-- Pagination Cuti -->
                    <div v-if="cutiList.length > cutiItemsPerPage" class="flex justify-between items-center px-6 py-4 bg-white border-t rounded-b-lg">
                        <div class="text-sm text-gray-600">
                            Data {{ (cutiCurrentPage - 1) * cutiItemsPerPage + 1 }} -
                            {{ Math.min(cutiCurrentPage * cutiItemsPerPage, cutiList.length) }} dari
                            {{ cutiList.length }}
                        </div>
                        <div class="space-x-2">
                            <button
                            v-for="page in visibleCutiPages"
                            :key="page"
                            @click="goToCutiPage(page)"
                            class="px-3 py-1 text-sm border rounded"
                            :class="{ 'bg-yellow-500 text-white': page === cutiCurrentPage }"
                            >
                            {{ page }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
  </template>
  

  