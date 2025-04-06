import { router } from '@inertiajs/vue3'
import axios from 'axios'

export const useAuth = async (expectedRole = null) => {
  const token = localStorage.getItem('token')

  if (!token) {
    router.visit('/masuk')
    return
  }

  // 🛠️ Tambahkan ini supaya token ikut dikirim ke backend
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

  try {
    const res = await axios.get('/api/user')
    const user = res.data
    console.log('🧾 User dari API:', user)

    if (expectedRole && user.peran !== expectedRole) {
      router.visit('/masuk')
      return
    }

    return { token, user }
  } catch (err) {
    console.error('❌ Auth error:', err)
    localStorage.removeItem('token')
    router.visit('/masuk')
  }
}
