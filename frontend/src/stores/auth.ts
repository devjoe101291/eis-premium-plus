import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import router from '@/router'

interface User {
  id: number
  email: string
  name: string
  role?: string // optional if you use roles later
}

interface LoginCredentials {
  email: string
  password: string
}

interface AuthResponse {
  token: string
  user: User
}

export const useAuthStore = defineStore('auth', () => {
  // --------------------
  // State (Setup-store style)
  // --------------------
  const sessionToken = sessionStorage.getItem('token')
  const localToken = localStorage.getItem('token')
  const token = ref<string | null>(sessionToken || localToken)

  // ✅ Load user from the same storage as the token (and only if a token exists) to avoid stale role/user mismatches.
  // Example: employee token in sessionStorage + old admin user in localStorage on refresh.
  const storedUser = sessionToken
    ? sessionStorage.getItem('user')
    : localToken
      ? localStorage.getItem('user')
      : null
  const user = ref<User | null>(storedUser ? (JSON.parse(storedUser) as User) : null)

  const loading = ref(false)
  const error = ref<string | null>(null)

  // ✅ this is the missing piece for your router guard
  const isInitialized = ref(false)

  // --------------------
  // Getters
  // --------------------
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const currentUser = computed(() => user.value)
  const isLoading = computed(() => loading.value)
  const hasError = computed(() => !!error.value)

  // --------------------
  // Helpers
  // --------------------
  function setAuthHeader(t: string) {
    api.defaults.headers.common['Authorization'] = `Bearer ${t}`
  }

  function removeAuthHeader() {
    delete api.defaults.headers.common['Authorization']
  }

  function clearTokenStorage() {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    sessionStorage.removeItem('user')
    sessionStorage.removeItem('token')
  }

  function getStoredToken(): string | null {
    return sessionStorage.getItem('token') || localStorage.getItem('token') || null
  }

  function getTokenStorage(): Storage | null {
    if (sessionStorage.getItem('token')) return sessionStorage
    if (localStorage.getItem('token')) return localStorage
    return null
  }

  function persistUserForCurrentToken(userData: User) {
    const storage = getTokenStorage() ?? localStorage
    const otherStorage = storage === localStorage ? sessionStorage : localStorage

    storage.setItem('user', JSON.stringify(userData))
    otherStorage.removeItem('user')
  }

  function clearError() {
    error.value = null
  }

  // --------------------
  // Actions
  // --------------------
  async function login(credentials: LoginCredentials): Promise<void> {
    loading.value = true
    error.value = null

    try {
      const { data } = await api.post<AuthResponse>('/login', credentials)
      const { token: newToken, user: userData } = data

      token.value = newToken
      user.value = userData

      // persist
      localStorage.setItem('token', newToken)
      localStorage.setItem('user', JSON.stringify(userData))
      sessionStorage.removeItem('token')
      sessionStorage.removeItem('user')

      setAuthHeader(newToken)
      isInitialized.value = true

      // Route based on role
      const role = userData.role?.toLowerCase()
      if (role === 'admin') {
        router.push('/topic')
      } else if (role === 'employee') {
        router.push('/employee-materials')
      } else {
        router.push('/login') // fallback if role is unknown
      }
    } catch (err: any) {
      const msg = err.response?.data?.message || 'Invalid email or password.'
      error.value = msg
      throw new Error(msg)
    } finally {
      loading.value = false
    }
  }

  async function logout(): Promise<void> {
    loading.value = true
    error.value = null

    try {
      await api.post('/auth/logout')
    } catch (err) {
      // ok to ignore api logout failure
    } finally {
      token.value = null
      user.value = null
      isInitialized.value = true

      removeAuthHeader()
      clearTokenStorage()

      router.push('/login')
      loading.value = false
    }
  }

  async function forceLogout(): Promise<void> {
    token.value = null
    user.value = null
    isInitialized.value = true

    removeAuthHeader()
    clearTokenStorage()

    router.push('/login')
  }

  // ✅ This is what your router guard should call
  async function checkAuth(): Promise<boolean> {
    // if we already initialized, don’t spam /auth/me
    if (isInitialized.value) return isAuthenticated.value

    const storedToken = token.value || getStoredToken()
    if (!storedToken) {
      // No token => user should not be considered logged in (avoid stale user from previous sessions)
      user.value = null
      localStorage.removeItem('user')
      sessionStorage.removeItem('user')
      isInitialized.value = true
      return false
    }

    token.value = storedToken
    setAuthHeader(storedToken)

    // If user was loaded from localStorage, we’re done
    if (user.value) {
      isInitialized.value = true
      return true
    }

    try {
      const { data } = await api.get<User>('/auth/me')
      user.value = data
      persistUserForCurrentToken(data)
      isInitialized.value = true
      return true
    } catch (err) {
      await forceLogout()
      isInitialized.value = true
      return false
    }
  }

  return {
    // State
    user,
    token,
    loading,
    error,
    isInitialized,

    // Getters
    isAuthenticated,
    currentUser,
    isLoading,
    hasError,

    // Actions
    login,
    logout,
    checkAuth,
    forceLogout,
    clearError,
  }
})
