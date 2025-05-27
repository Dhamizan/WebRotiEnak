  <script setup>
      import { onMounted } from 'vue';
      import { useAuth } from '@/Composables/useAuth';
      import AdminLayout from '@/Layouts/AdminLayout.vue';
      import { ref, computed } from 'vue';
      import axios from 'axios';
      import 'leaflet/dist/leaflet.css';
      import { router } from '@inertiajs/vue3';
      import L from 'leaflet';

      onMounted(async () => {
        const auth = await useAuth('admin')
        const user = ref(null)

        if (auth && auth.props?.auth?.user) {
            user.value = auth.props.auth.user
        }

      await getGeraiList();
      })

      const geraiList = ref([])

      async function getGeraiList() {
        try {
          const response = await axios.get('/api/gerai', {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`,
              Accept: 'application/json'
            }
          });
          console.log('DATA RESPONSE:', response.data)
          geraiList.value = response.data.data || []; // tergantung response-mu
        } catch (error) {
          console.error('Gagal mengambil data gerai:', error);
        }
      }

      const map = ref(null);
      const marker = ref(null);
      const currentPage = ref(1)
      const itemsPerPage = 7
      const selectedGerai = ref('')
      const searchQuery = ref('')
      const showResultModal = ref(false)
      const showResultDeleteModal = ref(false)
      const showDeleteModal = ref(false)
      const showGagalTambahModal = ref(false)
      const selectedGeraiId = ref(null)
      const resultMessage = ref('')
      const lokasiIcon = L.icon({
        iconUrl: '/icons/pin.png',
        iconSize: [30, 40],
        iconAnchor: [15, 40],
        popupAnchor: [0, -40]
      });


      function initMap() {
        if (map.value) return;

        map.value = L.map('map').setView([-6.369028, 106.895912], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map.value);

        map.value.on('click', function (e) {
          const { lat, lng } = e.latlng;
          newGerai.value.lat = lat.toFixed(6);
          newGerai.value.long = lng.toFixed(6);

          if (marker.value) {
            map.value.removeLayer(marker.value);
          }

          marker.value = L.marker([lat, lng], { icon: lokasiIcon }).addTo(map.value);
        });
      }

      const filteredGerai = computed(() => {
          let result = geraiList.value
          if (selectedGerai.value) {
              result = result.filter(p => p.gerai === selectedGerai.value)
          }
          if (searchQuery.value.trim()) {
              result = result.filter(p => 
              p.gerai.toLowerCase().includes(searchQuery.value.toLowerCase())
              )
          }
          return result
      })


      const totalPages = computed(() => Math.ceil(geraiList.value.length / itemsPerPage))

      const paginatedGerai = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage
      return filteredGerai.value.slice(start, start + itemsPerPage)
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

      const newGerai = ref({
        gerai: '',
        alamat: '',
        lat: null,
        long: null,
        jenis_gerai: ''
      })

      function bukaTambahModal() {
      showModal.value = true
          setTimeout(() => {
          initMap();
        }, 200);
      }

      function closeModal() {
      showModal.value = false
      newGerai.value = { gerai: '', alamat: '', lat: null, long: null, jenis_gerai: '' }
      }

    async function tambahGerai() {
      const latBaru = parseFloat(newGerai.value.lat);
      const longBaru = parseFloat(newGerai.value.long);

      for (const gerai of geraiList.value) {
        const jarak = hitungJarak(latBaru, longBaru, parseFloat(gerai.lat), parseFloat(gerai.long));
        if (jarak < 1.5) {
          showGagalTambahModal.value = true;
          return;
        }
      }
        
      try {
        const response = await axios.post('/api/gerai', {
          gerai: newGerai.value.gerai,
          alamat: newGerai.value.alamat,
          lat: newGerai.value.lat,
          long: newGerai.value.long,
          jenis_gerai: newGerai.value.jenis_gerai,
        }, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            Accept: 'application/json'
          }
        });

        closeModal();
        await getGeraiList();
        resultMessage.value = 'Gerai berhasil ditambahkan!';
        showResultModal.value = true;
      } catch (error) {
        if (error.response) {
          console.log('Detail error:', error.response.data); 
          alert('Gagal menyimpan: ' + (error.response.data.message || 'Periksa input.'));
        } else {
          alert('Terjadi kesalahan saat menghubungi server.');
        }
      }
    }

    // const editingId = ref(null)

    // const showEditModal = ref(false)
    // const editedGerai = ref({
    //   gerai: '',
    //   alamat: '',
    //   lat: null,
    //   long: null,
    //   jenis_gerai: ''
    // })

    // function bukaSuntingModal(gerai) {
    //   editedGerai.value = {
    //     gerai: gerai.gerai,
    //     alamat: gerai.alamat,
    //     lat: gerai.lat,
    //     long: gerai.long,
    //     jenis_gerai: gerai.jenis_gerai
    //   }
    //   editingId.value = gerai.id
    //   showEditModal.value = true
    // }

    //   function closeEditModal() {
    //   showEditModal.value = false
    //   editedGerai.value = { gerai: '', alamat: '', lat: null, long: null, jenis_gerai: '' }
    //   }

    // async function updateGerai() {
    //   try {
    //     const response = await axios.put(`/api/gerai/${editingId.value}`, {
    //       gerai: editedGerai.value.gerai,
    //       alamat: editedGerai.value.alamat,
    //       lat: editedGerai.value.lat,
    //       long: editedGerai.value.long,
    //       jenis_gerai: editedGerai.value.jenis_gerai
    //     }, {
    //       headers: {
    //         Authorization: `Bearer ${localStorage.getItem('token')}`,
    //         Accept: 'application/json'
    //       }
    //     });

    //     console.log('Response from update:', response.data);
        
    //     closeEditModal();
    //     await getGeraiList(); 
    //     resultMessage.value = 'Gerai berhasil diperbarui!';
    //     showResultModal.value = true;
    //   } catch (error) {
    //     alert('Gagal memperbarui: ' + (error.response?.data?.message || 'Terjadi kesalahan.'));
    //   }
    // }



    function konfirmasiHapus(id) {
      selectedGeraiId.value = id
      showDeleteModal.value = true
    }

    async function hapusGerai(id) {
      try {
        const response = await axios.delete(`/api/gerai/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            Accept: 'application/json'
          }
        });

        resultMessage.value = response.data.message || 'Gerai berhasil dihapus.';
        showDeleteModal.value = false;
        showResultDeleteModal.value = true;
        await getGeraiList();
        } catch (error) {
          alert('Gagal menghapus: ' + (error.response?.data?.message || 'Terjadi kesalahan.'));
        }
      }

      function mapGerai() {
        router.visit(`/admin/gerai/peta`)
      }

    function closeResultModal() {
      showResultModal.value = false
      resultMessage.value = ''
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
      const R = 6371; // Radius bumi dalam kilometer
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      const jarak = R * c;
      return jarak;
    }

    function closeDeleteiModal() {
      showResultDeleteModal.value = false
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
                  placeholder="Cari nama gerai..."
                  class="md:w-[300px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
              />
              <div class="flex justify-between items-center">
                  <button
                      class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 text-sm rounded-lg shadow transition"
                      @click="bukaTambahModal"
                  >
                      Tambah Gerai
                  </button>
              </div>
              <div class="flex justify-between items-center">
                  <button
                      class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 text-sm rounded-lg shadow transition"
                      @click="mapGerai"
                  >
                      Peta Gerai
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
                      <th class="px-6 py-4 font-medium">Gerai</th>
                      <th class="px-6 py-4 font-medium">Alamat</th>
                      <th class="px-6 py-4 font-medium">Pegawai</th>
                      <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-700 divide-y divide-gray-200">
                    <tr
                      v-for="(gerai, index) in paginatedGerai"
                      :key="index"
                      :class="[
                        'hover:bg-gray-100 transition',
                        index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-50',
                      ]"
                    >
                      <td class="px-6 py-4 text-sm">
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </td>
                      <td class="px-6 py-4 text-sm">{{ gerai.gerai }}</td>
                      <td class="px-6 py-4 text-sm">{{ gerai.alamat }}</td>
                      <td class="px-6 py-4 text-sm">{{ gerai.jumlah_pegawai }}</td>
                      <td class="px-6 py-4 space-x-2">
                          <!-- <button
                              class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-full shadow-sm transition"
                              @click="bukaSuntingModal(gerai)"
                          >
                              Edit
                          </button> -->
                          <button
                              class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-full shadow-sm transition"
                              @click="konfirmasiHapus(gerai.id)"
                          >
                              Hapus
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
                    {{ Math.min(currentPage * itemsPerPage, geraiList.length) }}
                    dari {{ geraiList.length }}
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
        
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-gray-800">Tambah Gerai</h3>

          <div class="flex gap-6">
            <div class="w-1/2 space-y-3">
              <input
                type="text"
                v-model="newGerai.gerai"
                placeholder="Nama Gerai"
                class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <input
                type="text"
                v-model="newGerai.alamat"
                placeholder="Alamat"
                class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <select
                v-model="newGerai.jenis_gerai"
                class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              >
                <option value="gerai">Gerai</option>
                <option value="stan">Stan</option>
              </select>
              <input
                type="number"
                v-model="newGerai.lat"
                placeholder="Koordinat X"
                class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
              <input
                type="number"
                v-model="newGerai.long"
                placeholder="Koordinat Y"
                class="w-full input-field focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 rounded-lg"
              />
            </div>

            <!-- Peta Leaflet -->
            <div class="w-1/2">
              <div id="map" class="w-full h-80 rounded-lg shadow"></div>
            </div>
          </div>

          <!-- Tombol -->
          <div class="flex justify-end gap-2 pt-4">
            <button @click="closeModal" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
            <button @click="tambahGerai" class="px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">Simpan</button>
          </div>

          <!-- Tombol Tutup -->
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
    <transition name="fade">
      <div
        v-if="showResultDeleteModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center"
      >
        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-green-600">Berhasil</h3>
          <p class="text-gray-700">{{ resultMessage }}</p>
          <div class="flex justify-end">
            <button
              @click="closeDeleteiModal"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center"
      >
        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-yellow-500">Konfirmasi Hapus</h3>
          <p class="text-gray-700">Yakin ingin menghapus gerai ini?</p>
          <div class="flex justify-end space-x-2">
            <button
              @click="showDeleteModal = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg shadow"
            >
              Batal
            </button>
            <button
              @click="hapusGerai(selectedGeraiId)"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div
        v-if="showGagalTambahModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center"
      >
        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-semibold text-yellow-500">Gerai Terlalu Dekat</h3>
          <p class="text-gray-700">Gerai Baru Harus Lebih Dari 1,5 Km Jaraknya dengan Gerai yang Sudah Ada</p>
          <div class="flex justify-end space-x-2">
            <button
              @click="showGagalTambahModal = false"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow transition"
            >
              Ubah
            </button>
          </div>
        </div>
      </div>
    </transition>

  </template>  
    <style scoped>
      #map {
      height: 400px;
    }

    .fade-enter-active, .fade-leave-active {
      transition: opacity 0.3s;
    }
    .fade-enter-from, .fade-leave-to {
      opacity: 0;
    }
    </style>
    