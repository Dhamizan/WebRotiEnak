<script setup>
    import { Head, useForm, router } from '@inertiajs/vue3';
    import { onMounted } from 'vue';
    import { useAuth } from '@/Composables/useAuth';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { ref, computed } from 'vue';
    import {
        LucideEye,
        LucideEyeOff
    } from 'lucide-vue-next';

    // Saat halaman di-mount
    onMounted(async () => {
      const auth = await useAuth('admin')
      const user = ref(null)

      if (auth && auth.props?.auth?.user) {
          user.value = auth.props.auth.user
      }
    try {
        const response = await axios.get('/api/gerai', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });
        geraiList.value = response.data.data;

    } catch (error) {
        console.error('Gagal memuat daftar gerai:', error);
    }

    await getPegawai();
    })

    const pegawaiList = ref([])

    async function getPegawai() {
      try {
          const response = await axios.get('/api/pegawai', {
              headers: {
                  Authorization: `Bearer ${localStorage.getItem('token')}`
              }
          });
          // mapping data untuk ditampilkan di tabel
          pegawaiList.value = response.data.data.map(p => ({
              id: p.id,
              nama: p.nama,
              gerai: p.gerai?.gerai || 'Tidak diketahui'
          }));
        } catch (error) {
            console.error('Gagal mengambil data pegawai:', error);
        }
    }

    const currentPage = ref(1)
    const itemsPerPage = 7
    const selectedGerai = ref('')
    const searchQuery = ref('')

    const filteredPegawai = computed(() => {
        let result = pegawaiList.value
        if (selectedGerai.value) {
            result = result.filter(p => p.gerai === selectedGerai.value)
        }
        if (searchQuery.value.trim()) {
            result = result.filter(p => 
            p.nama.toLowerCase().includes(searchQuery.value.toLowerCase())
            )
        }
        return result
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

    const showModal = ref(false)

    const newPegawai = ref({
        nama: '',
        email: '',
        notelp: '',
        alamat: '',
        gerai_id: '',
        kata_sandi: '',
        peran: 2,
        jenis_kelamin: '',
    })


    function openModal() {
    showModal.value = true
    }

    function closeModal() {
    showModal.value = false
    newPegawai.value = {
      nama: '',
      email: '',
      notelp: '',
      alamat: '',
      gerai_id: '',
      kata_sandi: '',
      peran: 2,
      jenis_kelamin: '',
      }
    }

    const showPassword = ref(false); // Menambahkan deklarasi showPassword

    const geraiList = ref([])

    function lihatGajiPegawai(pegawai) {
      router.visit(`/admin/gaji/rincian?id=${pegawai.id}`)
    }

    const showResultModal = ref(false)
    const resultMessage = ref('')

    function closeResultModal() {
      showResultModal.value = false
      resultMessage.value = ''
    }

</script>
<template>
    <AdminLayout>
      <div class="py-2">
        <div class="flex flex-col md:flex-row gap-5 items-center gap-0 px-0 py-0">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari nama pegawai..."
                class="md:w-[300px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
            />
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
        </div>
        <!-- Tabel Pegawai -->
        <div class="mt-4">
          <div class="bg-white shadow rounded-lg overflow-hidden w-full">
            <div class="overflow-x-auto">
              <table class="w-full table-auto">
                <thead class="bg-white text-left text-sm text-gray-800">
                  <tr>
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Pegawai</th>
                    <th class="px-6 py-4 font-medium">Gerai</th>
                    <th class="px-6 py-4 font-medium">Detail</th>
                  </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                  <tr
                    v-for="(pegawai, index) in paginatedPegawai"
                    :key="index"
                    :class="[
                      'hover:bg-gray-100 transition',
                      index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50',
                    ]"
                  >
                    <td class="px-6 py-4 text-sm">
                      {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-sm">{{ pegawai.nama }}</td>
                    <td class="px-6 py-4 text-sm">{{ pegawai.gerai }}</td>
                    <td class="px-6 py-4">
                        <button
                            class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-full shadow-sm transition"
                            @click="lihatGajiPegawai(pegawai)"
                        >
                            Lihat
                        </button>
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
                  {{ Math.min(currentPage * itemsPerPage, pegawaiList.length) }}
                  dari {{ pegawaiList.length }}
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