<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: new URLSearchParams(window.location.search).get('email') || '',
      kata_sandi: '',
      konfirmasi_kata_sandi: '',
      loading: false,
      message: '',
      showModal: false,
      isError: false
    };
  },
  methods: {
    async aturUlangKataSandi() {
      if (this.kata_sandi !== this.konfirmasi_kata_sandi) {
        this.message = 'Kata sandi dan konfirmasi kata sandi tidak cocok';
        this.isError = true;
        this.showModal = true;
        return;
      }

      this.loading = true;
      const urlParams = new URLSearchParams(window.location.search);
      try {
        const response = await axios.post('/api/reset-password', {
          email: this.email,
          kata_sandi: this.kata_sandi,
          signature: urlParams.get('signature')
        });
        this.message = response.data.message;
        this.isError = false;
      } catch (error) {
        this.isError = true;
        if (error.response) {
          this.message = error.response.data.message || 'Terjadi kesalahan.';
        } else {
          this.message = 'Terjadi kesalahan.';
        }
      } finally {
        this.loading = false;
        this.showModal = true;
      }
    },
    closeModal() {
      this.showModal = false;
    }
  }
};
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Lupa Kata Sandi</h2>
      <p class="text-sm text-center text-gray-500 mb-6">
        Masukkan email Anda untuk mengganti kata sandi
      </p>
      <form @submit.prevent="aturUlangKataSandi">
        <div class="mb-2">
          <input
            v-model="email"
            id="email"
            type="email"
            placeholder="Masukkan email Anda"
            class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            required
          />
        </div>
        <div class="mb-2">
          <input
            v-model="kata_sandi"
            type="password"
            id="kata_sandi"
            placeholder="Masukkan Kata Sandi Baru"
            class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            required
          />
        </div>
        <div class="mb-4">
          <input
            v-model="konfirmasi_kata_sandi"
            type="password"
            id="konfirmasi_kata_sandi"
            placeholder="Konfirmasi Kata Sandi Baru"
            class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            required
          />
        </div>
        <button
          type="submit"
          class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-3 rounded-lg transition-all"
          :disabled="loading"
        >
          {{ loading ? 'Memproses...' : 'Ubah Kata Sandi' }}
        </button>
      </form>
    </div>
  </div>

  <!-- Modal -->
  <transition name="fade">
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
    >
      <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm space-y-4 text-center">
        <h3 class="text-lg font-semibold" :class="isError ? 'text-red-600' : 'text-green-600'">
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

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
