<script setup>
  import { ref } from 'vue'
  import axios from 'axios'

  const email = ref('')
  const message = ref('')
  const isError = ref(false)
  const showModal = ref(false)

  const submit = async () => {
    try {
      const res = await axios.post('/api/reset-password-link', {
        email: email.value,
      })

      message.value = res.data.message
      isError.value = false
      showModal.value = true
    } catch (error) {
      const status = error.response?.status
      if (status === 404 || status === 405) {
        message.value = error.response.data.message
      } else {
        message.value = 'Terjadi kesalahan saat mengirim permintaan.'
      }
      isError.value = true
      showModal.value = true
    }
  }

  const closeModal = () => {
    showModal.value = false
  }

  const goBack = () => {
    window.history.back()
  }
</script>
<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Lupa Kata Sandi</h2>
      <p class="text-sm text-center text-gray-500 mb-6">
        Masukkan email Anda untuk mendapatkan tautan reset kata sandi
      </p>
      <form @submit.prevent="submit">
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="Masukkan email Anda"
            class="w-full mt-1 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            required
          />
        </div>

        <div class="grid gap-2">
          <div>
            <button
              type="submit"
              class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-3 rounded-lg transition-all"
            >
              Kirim Tautan Ubah Kata Sandi
            </button>
          </div>
          <div>
            <button
              class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-3 rounded-lg transition-all"
              @click="goBack"
            >
              Kembali
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <transition name="fade">
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
    >
      <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm space-y-4 text-center">
        <h3
          class="text-lg font-semibold"
          :class="isError ? 'text-red-600' : 'text-green-600'"
        >
          {{ isError ? 'Gagal' : 'Berhasil' }}
        </h3>
        <p class="text-gray-700">{{ message }}</p>
        <button
          @click="closeModal"
          class="mt-4 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg"
        >
          Tutup
        </button>
      </div>
    </div>
  </transition>
</template>
