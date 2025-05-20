  <template>
    <div>
      <form @submit.prevent="aturUlangKataSandi">
        <div>
          <label for="email">Email</label>
          <input
            type="email"
            v-model="email"
            id="email"
            required
            :disabled="true"
          />
        </div>
  
        <div>
          <label for="kata_sandi">Kata Sandi Baru</label>
          <input
            type="password"
            v-model="kata_sandi"
            id="kata_sandi"
            required
            placeholder="Masukkan kata sandi baru"
          />
        </div>
  
        <div>
          <label for="konfirmasi_kata_sandi">Konfirmasi Kata Sandi</label>
          <input
            type="password"
            v-model="konfirmasi_kata_sandi"
            id="konfirmasi_kata_sandi"
            required
            placeholder="Konfirmasi kata sandi baru"
          />
        </div>
  
        <button type="submit" :disabled="loading">Ubah Kata Sandi</button>
  
        <div v-if="message" class="message">{{ message }}</div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: new URLSearchParams(window.location.search).get('email') || '',
        kata_sandi: '',
        konfirmasi_kata_sandi: '',
        loading: false,
        message: ''
      };
    },
    methods: {
      async aturUlangKataSandi() {
        if (this.kata_sandi !== this.konfirmasi_kata_sandi) {
          this.message = 'Kata sandi dan konfirmasi kata sandi tidak cocok';
          return;
        }
  
        this.loading = true;
        const urlParams = new URLSearchParams(window.location.search);
        try {
          const response = await axios.post('/api/reset-password', {
            email: this.email,
            kata_sandi: this.kata_sandi,
            signature: urlParams.get('signature') // Dapatkan signature dari URL
          });
          this.message = response.data.message;
        } catch (error) {
          if (error.response) {
            this.message = error.response.data.message || 'Terjadi kesalahan.';
          }
        } finally {
          this.loading = false;
        }
      }
    }
  };
  </script>