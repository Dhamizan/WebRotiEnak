<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-4" viewBox="0 0 20 20" fill="currentColor">
        <path
          fill-rule="evenodd"
          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9 13.414l4.707-4.707z"
          clip-rule="evenodd"
        />
      </svg>

      <h2 class="text-2xl font-bold text-gray-800 mb-2">Tautan Dikirim</h2>
      <p class="text-gray-600">
        Kami telah mengirim tautan reset kata sandi ke email Anda. Silakan periksa kotak masuk atau folder spam Anda.
      </p>

      <Link href="/login" class="mt-6 inline-block bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded-lg transition-all">
        Kembali ke Login
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
