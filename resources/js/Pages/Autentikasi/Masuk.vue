  <script setup>
    import { useForm, router } from '@inertiajs/vue3'
    import { ref, onMounted } from 'vue'
    import {
      User,
      Lock,
      LucideEye,
      LucideEyeOff,
    } from 'lucide-vue-next'

    onMounted(async () => {
      const token = localStorage.getItem('token')
      if (token) {
        try {
          const res = await axios.get('/api/user', {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: 'application/json',
            },
          })

          const { peran } = res.data

          if (peran === 'admin') {
            router.get('/admin/dashboard')
          } else if (peran === 'pegawai') {
            router.get('/pegawai/dashboard')
          }
        } catch {
          // Token invalid, biarkan tetap di halaman login
          localStorage.removeItem('token')
        }
      }
    })

    const showPassword = ref(false)
    const togglePassword = () => {
      showPassword.value = !showPassword.value
    }

    const showResultModal = ref(false)
    const resultMessage = ref('')

    const closeResultModal = () => {
      showResultModal.value = false
    }

    defineProps({
      canResetPassword: Boolean,
      status: String,
    })

    const form = useForm({
      nama: '',
      kata_sandi: '',
      remember: false,
    })

    const error = ref(null)

    const submit = async () => {
      try {
        const res = await axios.post('/api/masuk', {
          nama: form.nama,
          kata_sandi: form.kata_sandi,
        })

        const { token, peran } = res.data
        localStorage.setItem('token', token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

        if (peran === 'admin') {
          await router.visit('/admin/dashboard')
        } else if (peran === 'pegawai') {
          await router.visit('/pegawai/dashboard')
        }
      } catch (err) {
        const message = err.response?.data?.message ?? 'Terjadi kesalahan saat masuk.'
        error.value = message
        resultMessage.value = message
        showResultModal.value = true
      }
    }
  </script>
  <template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
      <div class="rounded-lg shadow-lg w-full max-w-4xl flex flex-col md:flex-row overflow-hidden bg-white">
        <div class="md:w-1/2 w-full flex items-center justify-center p-6 md:p-10">
          <form @submit.prevent="submit" class="w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-black text-center">Selamat Datang!</h1>
            <p class="text-gray-600 text-sm text-center">Tolong Masukkan Data Akunmu</p>
            <div class="mt-2">
              <label class="block text-sm font-medium text-gray-700">Nama</label>
              <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <User class="h-5 w-5" />
                </span>
                <input
                  v-model="form.nama"
                  type="text"
                  placeholder="Masukkan Nama . . ."
                  class="w-full pl-10 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  required
                />
              </div>
            </div>
            <div class="mt-2">
              <label class="block text-sm font-medium text-gray-700">Kata Sandi</label>
              <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <Lock class="h-5 w-5" />
                </span>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="form.kata_sandi"
                  placeholder="Masukkan Kata Sandi . . ."
                  class="w-full pl-10 pr-10 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  required
                />
                <span @click="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 cursor-pointer">
                  <LucideEyeOff v-if="!showPassword" class="h-5 w-5" />
                  <LucideEye v-else class="h-5 w-5" />
                </span>
              </div>
            </div>

            <div class="mt-2 text-left">
              <!-- <Link
                href="/lupa-kata-sandi"
                class="text-sm text-yellow-500 hover:underline"
              >
                Lupa Password?
              </Link> -->
            </div>
            <!-- Tombol Login -->
            <button type="submit" class="mt-2 w-full bg-yellow-400 text-white font-bold py-3 rounded-lg shadow hover:bg-yellow-500">
              Login
            </button>
          </form>
        </div>

        <!-- Gambar -->
        <div class="md:w-1/2 w-full h-60 md:h-auto relative">
          <img src="/images/logo login.png" class="w-full h-full object-cover rounded-b-lg md:rounded-b-none md:rounded-r-lg" />
        </div>
      </div>
    </div>
    <transition name="fade">
      <div
        v-if="showResultModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center"
      >
        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 space-y-4 relative">
          <h3 class="text-lg font-bold text-red-600">Gagal Masuk</h3>
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
    