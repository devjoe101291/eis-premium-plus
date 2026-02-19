/**
 * useLogin Composable
 * 
 * A composable that handles user authentication functionality including login and logout.
 * It manages the login form state, handles form submission, and provides error handling.
 * 
 * Features:
 * - Form state management (email, password)
 * - Login form submission with validation
 * - Error handling and loading states
 * - Logout functionality
 * - Automatic redirection after successful login
 * 
 * @returns {Object} An object containing form state, loading state, error state, and auth methods
 */

import { ref, Ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'
import api from '@/services/api'

type UseLoginArgs = {
  role: Ref<string>
  email: Ref<string>
  password: Ref<string>
  rememberMe: Ref<boolean>
}

export function useLogin({ role, email, password, rememberMe }: UseLoginArgs) {
  const router = useRouter()
  const route = useRoute()
  const authStore = useAuthStore()

  const loading = ref(false)
  const error = ref('')

  const handleLogin = async () => {
    // console.log('handleLogin fired', {
    //   role: role.value,
    //   email: email.value,
    //   password: password.value
    // })

    error.value = ''

    if (!role.value) {
      error.value = 'Please select a role'
      return
    }
    if (!email.value || !password.value) {
      error.value = 'Missing email/password'
      return
    }

    loading.value = true
    try {
      const { data } = await api.post('/login', {
        email: email.value,
        password: password.value,
        role: role.value,
        remember: rememberMe.value,
      })

      // Expect: { token, user, role? }
if (data?.token) {
  if (rememberMe.value) {
    localStorage.setItem('token', data.token)
    if (data.user) localStorage.setItem('user', JSON.stringify(data.user))
    sessionStorage.removeItem('token') // ✅ remove other token
    sessionStorage.removeItem('user')
  } else {
    sessionStorage.setItem('token', data.token)
    if (data.user) sessionStorage.setItem('user', JSON.stringify(data.user))
    localStorage.removeItem('token') // ✅ remove other token
    localStorage.removeItem('user')
  }

  api.defaults.headers.common.Authorization = `Bearer ${data.token}`
  axios.defaults.headers.common.Authorization = `Bearer ${data.token}`

  authStore.token = data.token
  if (data.user) authStore.user = data.user
}


const roleLower = (data.user?.role || role.value || '').toLowerCase()

const defaultHome =
  roleLower === 'employee' ? '/employee-materials' :
  roleLower === 'admin' ? '/employees' :
  '/login'

// ✅ only allow redirect paths per role
const redirectRaw = (route.query.redirect as string) || ''
const safeRedirect =
  roleLower === 'employee'
    ? (redirectRaw.startsWith('/employee-') || redirectRaw.startsWith('/take-exam')) ? redirectRaw : defaultHome
    : roleLower === 'admin'
      ? (redirectRaw.startsWith('/topic') || redirectRaw.startsWith('/employees') || redirectRaw.startsWith('/exams')) ? redirectRaw : defaultHome
      : defaultHome

router.replace(safeRedirect)

    } catch (err: any) {
      error.value = err?.response?.data?.message || 'Login failed'
      console.error('Login error:', err?.response?.data || err)
    } finally {
      loading.value = false
    }
  }

  const handleLogout = async () => {
    try {
      const token = authStore.token || localStorage.getItem('token') || sessionStorage.getItem('token')
      if (token) {
        await api.post('/logout', {}, { headers: { Authorization: `Bearer ${token}` } })
      }
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      sessionStorage.removeItem('token')
      sessionStorage.removeItem('user')
      delete api.defaults.headers.common.Authorization
      delete axios.defaults.headers.common.Authorization

      authStore.user = null
      authStore.token = null

      router.push('/login')
    }
  }

  return {
    loading,
    error,
    handleLogin,
    handleLogout
  }
}
