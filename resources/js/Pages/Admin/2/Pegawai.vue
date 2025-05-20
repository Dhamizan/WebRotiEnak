<script setup>
  import { onMounted, ref } from 'vue'
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  import axios from 'axios'
  import { useAuth } from '@/Composables/useAuth'

  const pegawai = ref(null)
  const id = new URLSearchParams(window.location.search).get('id')

  const showDeleteModal = ref(false)
  const showResultDeleteModal = ref(false)
  const resultMessage = ref('')

  onMounted(async () => {
    await useAuth('admin')

    try {
      const res = await axios.get(`/api/pegawai/${id}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })

      if (res.data.success) {
        pegawai.value = res.data.data
      }
    } catch (error) {
      console.error('Gagal ambil data pegawai:', error)
    }
  })

  const hapusPegawai = async () => {
    showDeleteModal.value = false

  try {
    await axios.delete(`/api/pegawai/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    resultMessage.value = 'Pegawai berhasil dihapus.'
    showResultDeleteModal.value = true

    setTimeout(() => {
      goBack()
    }, 2000)
    } catch (error) {
      console.error('Gagal menghapus pegawai:', error)
      resultMessage.value = 'Gagal menghapus pegawai.'
      showResultDeleteModal.value = true
    }
  }

    const showDeleteModals = () => {
      showDeleteModal.value = true
    } 

    const closeDeleteModal = () => {
    showResultDeleteModal.value = false
  }

  const goBack = () => {
    window.history.back()
  }
</script>

<template>
  <AdminLayout>
    <div class="p-6 mt-10 w-full">
      <div class="max-w-6xl w-full mx-auto bg-white shadow-lg rounded-lg p-8 mt-10">
        <!-- Profile Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-4">
          <div class="flex items-center space-x-4">
            <img :src="pegawai?.gambar_profil ? 'http://192.168.63.63:8000/storage/' + pegawai.gambar_profil : 'https://via.placeholder.com/80'" alt="Profile" class="w-16 h-16 rounded-full object-cover" />
            <div>
              <h2 class="text-xl font-semibold">{{ pegawai?.nama }}</h2>
              <p class="text-gray-500 text-sm">{{ pegawai?.email }}</p>
            </div>
          </div>
          <div>
              <button class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 shadow transition"
                @click="showDeleteModals"
                >
                  Hapus
              </button>
            </div>
        </div>

        <!-- Profile Details -->
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
              :value="pegawai.id_sidik_jari ? 'Sudah terdaftar' : 'Belum menambahkan sidik jari'"
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
            <input type="text" :value="pegawai.gerai?.gerai ?? '-'" disabled class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base" />
          </div>
          <div class="w-full">
            <label class="text-gray-600 text-sm">Jenis Kelamin</label>
            <input type="text" :value="pegawai.jenis_kelamin" disabled class="w-full mt-1 p-3 border rounded-lg bg-gray-100 text-gray-700 text-base" />
          </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6 flex justify-end">
          <button class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600"
          @click="goBack"
          >
            Back
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
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
              @click="closeDeleteModal"
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
          <p class="text-gray-700">Yakin ingin menghapus data pegawai ini?</p>
          <div class="flex justify-end space-x-2">
            <button
              @click="showDeleteModal = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg shadow"
            >
              Batal
            </button>
            <button
              @click="hapusPegawai"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow"
            >
              Hapus
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
    