<script setup>
    import { router } from '@inertiajs/vue3';
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

    const newPegawai = ref({
        nama: '',
        email: '',
        notelp: '',
        alamat: '',
        gerai_id: '',
        kata_sandi: '',
        peran: 2,
        jenis_kelamin: '',
        gambar_profil: '',
    })

    const pegawaiList = ref([])
    
    async function tambahPegawai() {
        try {
            const formData = new FormData();
            formData.append('nama', newPegawai.value.nama);
            formData.append('email', newPegawai.value.email);
            formData.append('notelp', newPegawai.value.notelp);
            formData.append('alamat', newPegawai.value.alamat);
            formData.append('gerai_id', newPegawai.value.gerai_id);
            formData.append('kata_sandi', newPegawai.value.kata_sandi);
            formData.append('peran', newPegawai.value.peran);
            formData.append('jenis_kelamin', newPegawai.value.jenis_kelamin);
            if (newPegawai.value.gambar_profil) {
                formData.append('gambar_profil', newPegawai.value.gambar_profil);
            }

            const response = await axios.post('/api/pegawai', formData, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'multipart/form-data'
                }
            });

            closeModal();
            await getPegawai();
            resultMessage.value = 'Pegawai berhasil ditambahkan!, Sekarang minta pegawai mu untuk membuat id sidik';
            showResultModal.value = true;
        } catch (error) {
            if (error.response && error.response.data) {
                alert('Gagal menambah pegawai: ' + error.response.data.message);
            } else {
                alert('Terjadi kesalahan. Coba lagi.');
            }
        }
        console.log(newPegawai.value.gambar_profil);
        console.log([...formData.entries()]);
    }

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
        gambar_profil: '',
      }
    }

    function handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        newPegawai.value.gambar_profil = file;
      }
    }


    const showPassword = ref(false); // Menambahkan deklarasi showPassword

    const geraiList = ref([])

    function lihatProfilPegawai(pegawai) {
      router.visit(`/admin/pegawai/profil?id=${pegawai.id}`)
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
            <div class="flex justify-between items-center">
                <button
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 text-sm rounded-lg shadow transition"
                    @click="openModal"
                >
                    Tambah Pegawai
                </button>
            </div>

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
                            @click="lihatProfilPegawai(pegawai)"
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
    <transition name="fade">
      <div
          v-if="showModal"
          class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
          <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-gray-800">Tambah Pegawai</h3>

          <div class="space-y-3">
              <input
                  type="text"
                  v-model="newPegawai.nama"
                  placeholder="Nama"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <input
                  type="email"
                  v-model="newPegawai.email"
                  placeholder="Email"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <input
                  type="text"
                  v-model="newPegawai.notelp"
                  placeholder="No. Telepon"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <input
                  type="text"
                  v-model="newPegawai.alamat"
                  placeholder="Alamat"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <select
                  v-model="newPegawai.gerai_id"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              >
                  <option value="">Pilih Gerai</option>
                  <option
                      v-for="gerai in geraiList"
                      :key="gerai.id"
                      :value="gerai.id"
                  >
                      {{ gerai.gerai }}
                  </option>
              </select>
              <select
                  v-model="newPegawai.jenis_kelamin"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              >
                  <option value="">Jenis Kelamin</option>
                  <option value="Pria">Pria</option>
                  <option value="Wanita">Wanita</option>
              </select>
              <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="newPegawai.kata_sandi"
                  placeholder="Kata Sandi"
                  class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg">
                  <div
                  class="absolute right-9 bottom-[158px] transform -translate-y-1/2 cursor-pointer"
                  @click="showPassword = !showPassword"
                >
                  <LucideEyeOff
                    v-if="!showPassword"
                    class="text-gray-500 hover:text-yellow-500 transition"
                    size="20"
                  />
                  <LucideEye
                    v-else
                    class="text-yellow-500 hover:text-yellow-600 transition"
                    size="20"
                  />
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleFileChange"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 
                        file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 
                        file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 
                        hover:file:bg-yellow-100 
                        focus:outline-none focus:ring-0 focus:border-transparent"
                />
          </div>
          <div class="flex justify-end gap-2 pt-4">
              <button @click="closeModal" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
              <button @click="tambahPegawai" class="px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">Simpan</button>
          </div>
          <button @click="closeModal" class="absolute top-2 right-6 text-gray-400 hover:text-gray-700">✕</button>
          </div>
      </div>
    </transition>
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
  