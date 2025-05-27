<script setup>
  import { onMounted, ref } from 'vue'
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  import axios from 'axios'
  import { useAuth } from '@/Composables/useAuth'
  import { router } from '@inertiajs/vue3'

  const pegawai = ref({
    nama: '',
    email: '',
    notelp: '',
    alamat: '',
    gerai_id: '',
    kata_sandi: '',
    peran: 2,
    jenis_kelamin: '',
    gambar_profil: '', // tetap string untuk keperluan tampilan
  })

  const showModal = ref(false)
  const selectedFile = ref(null)
  const geraiList = ref([])

  onMounted(async () => {
    await useAuth('admin')

    try {
      const res = await axios.get('/api/admin', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })

      if (res.data.success && res.data.data.length > 0) {
        pegawai.value = res.data.data[0]
      }
    } catch (error) {
      console.error('Gagal ambil data pegawai:', error)
    }

    try {
      const response = await axios.get('/api/gerai', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })

      if (response.data.success) {
        geraiList.value = response.data.data
      }
    } catch (error) {
      console.error('Gagal ambil data gerai:', error)
    }
  })

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
      alert('File harus berupa gambar JPG, JPEG, atau PNG');
      selectedFile.value = null;
      return;
    }

    selectedFile.value = file;
  };

  const simpanProfil = async () => {
    const formData = new FormData()
    formData.append('nama', pegawai.value.nama)
    formData.append('email', pegawai.value.email ?? '')
    formData.append('notelp', pegawai.value.notelp ?? '')
    formData.append('alamat', pegawai.value.alamat ?? '')
    formData.append('gerai_id', pegawai.value.gerai_id ?? '')
    formData.append('jenis_kelamin', pegawai.value.jenis_kelamin ?? '')

    if (selectedFile.value) {
      formData.append('gambar_profil', selectedFile.value)
    }

    try {
      const res = await axios.post('/api/sunting/profil', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      })

      resultMessage.value = 'Profil kamu berhasil di perbarui!';
      showModal.value = false;
      showResultModal.value = true;

      if (res.data.data) {
        pegawai.value = res.data.data
      }
    } catch (err) {
      console.error('Gagal update profil:', err.response?.data ?? err)
      alert('Gagal update profil.')
    }
  }

  const showResultModal = ref(false)
  const resultMessage = ref('')

  function closeResultModal() {
    showResultModal.value = false
    resultMessage.value = ''
  }

  const goToVerifikasiEmail = () => {
    router.visit('/verifikasi-email')
  }

  const kirimResetPasswordLink = () => {
    router.visit('/verifikasi-akun')
  }

  const goBack = () => {
    window.history.back()
  }
</script>

<template>
  <AdminLayout>
    <div class="p-6 mt-10 w-full">
      <div class="max-w-6xl w-full mx-auto bg-white shadow-lg rounded-lg p-8 mt-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-4">
          <div class="flex items-center space-x-4">
            <img :src="pegawai?.gambar_profil ? 'http://192.168.195.63:8000/storage/' + pegawai.gambar_profil : 'https://via.placeholder.com/80'" alt="Profile" class="w-16 h-16 rounded-full object-cover" />
            <div class="max w-full">
              <h2 class="text-xl font-semibold truncate max-w-full">{{ pegawai?.nama }}</h2>
              <p class="text-gray-500 text-sm truncate max-w-full">{{ pegawai?.email }}</p>
            </div>
          </div>
          <div class="flex flex-col md:flex-row gap-5 items-center gap-0 px-0 py-2">
            <div class="flex justify-between items-center">
              <button
                class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600"
                @click="kirimResetPasswordLink">Edit Password
              </button>
            </div>
            <div>
              <button
                class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600"
                @click="goToVerifikasiEmail">Verifikasi Akun
              </button>
            </div>
            <div>
              <button
                class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600"
                @click="showModal = true">Edit Profil
              </button>
            </div>
          </div>
        </div>

        <div v-if="pegawai" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
          <div class="w-full">
            <label class="text-gray-600 text-sm">Posisi</label>
            <input
              type="text"
              :value="pegawai.peran === 1 ? 'Admin' : (pegawai.peran === 2 ? 'Pegawai' : 'Tidak diketahui')"
              disabled
              class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base"
            />
          </div>
          <div class="w-full">
            <label class="text-gray-600 text-sm">Sidik Jari</label>
            <input
              type="text"
              value="Tidak Perlu Absensi"
              disabled
              class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base"
            />
          </div>
          <div class="w-full">
            <label class="text-gray-600 text-sm">No. Telepon</label>
            <input type="text" :value="pegawai.notelp" disabled class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base" />
          </div>
          <div class="w-full">
            <label class="text-gray-600 text-sm">Alamat</label>
            <input type="text" :value="pegawai.alamat" disabled class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base" />
          </div>
          <div class="w-full">
            <label class="text-gray-600 text-sm">Gerai</label>
            <input type="text" value="Pusat" disabled class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base" />
          </div>
          <div class="w-full">
            <label class="text-gray-600 text-sm">Jenis Kelamin</label>
            <input type="text" :value="pegawai.jenis_kelamin" disabled class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base" />
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600" @click="goBack">
            Kembali
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
  <transition name="fade">
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg w-full max-w-xl p-6 relative">
        <h2 class="text-lg font-semibold mb-4">Edit Profil</h2>

        <div class="space-y-4">
          <input v-model="pegawai.nama" type="text" placeholder="Nama" class="w-full p-2 border rounded" />
          <input v-model="pegawai.email" type="text" placeholder="Email" class="w-full p-2 border rounded" />
          <input v-model="pegawai.notelp" type="text" placeholder="No. Telepon" class="w-full p-2 border rounded" />
          <input v-model="pegawai.alamat" type="text" placeholder="Alamat" class="w-full p-2 border rounded" />
          <select v-model="pegawai.gerai_id" class="w-full p-2 border rounded">
            <option value="">Pilih Gerai</option>
            <option v-for="gerai in geraiList" :key="gerai.id" :value="gerai.id">
              {{ gerai.gerai }}
            </option>
          </select>
          <select v-model="pegawai.jenis_kelamin" class="w-full p-2 border rounded">
            <option value="">Pilih Jenis Kelamin</option>
            <option value="Pria">Pria</option>
            <option value="Wanita">Wanita</option>
          </select>
          <input type="file" @change="handleFileChange" class="w-full p-2 border rounded" />
        </div>
        <div class="flex justify-end mt-4 space-x-2">
          <button @click="showModal = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
            Batal
          </button>
          <button @click="simpanProfil" class="px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">
            Simpan
          </button>
        </div>
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
