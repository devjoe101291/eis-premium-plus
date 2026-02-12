<template>
  <div class="relative min-h-screen w-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">

   

    <div class="w-full min-h-screen flex bg-white dark:bg-gray-800">
      <!-- Left Branding / Logo Section -->
      <div
        class="hidden md:flex w-1/2 bg-gradient-to-tr from-primary to-secondary dark:from-primary-light/10 dark:to-secondary-light/10 items-center justify-center p-8"
      >
        <div class="text-center text-white space-y-6">
          <img src="images/main-logo1.png" alt="App Logo" class="mx-auto h-24 w-[18rem] object-contain drop-shadow-md" />
          <h2 class="text-3xl font-bold">{{ appTitle }}</h2>
          <p class="text-lg opacity-90">Sign in to your account.</p>
        </div>
      </div>

      <!-- Right Form Section -->
      <div class="flex flex-col justify-center w-full md:w-1/2 p-8 sm:p-10 lg:p-12 relative">
 <!-- 🌗 Dark/Light Mode Toggle -->
    <button
      @click="themeStore.toggleTheme()"
      class="absolute top-6 right-6 p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
      aria-label="Toggle dark mode"
    >
      <!-- Sun Icon -->
      <svg
        v-if="isDarkMode"
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
        />
      </svg>

      <!-- Moon Icon -->
      <svg
        v-else
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
        />
      </svg>
    </button>
        <form class="space-y-6" @submit.prevent="handleLogin">
          <!-- Email -->
          <!-- <div>
            <label
              for="email-address"
              class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
            >Email address</label>
            <input
              id="email-address"
              name="email"
              type="email"
              autocomplete="email"
              required
              v-model="email"
              placeholder="Enter your email address"
              class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
            />
          </div> -->

          <!-- Password -->
          <!-- <div>
            <label
              for="password"
              class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
            >Password</label>
            <div class="relative">
              <input
                id="password"
                name="password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                required
                v-model="password"
                placeholder="••••••••"
                class="block w-full rounded-md border border-gray-300 p-3 pr-10 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                tabindex="-1"
              >
                <component :is="showPassword ? EyeOff : Eye" class="w-5 h-5" />
              </button>
            </div>
          </div> -->

<!-- Role Select -->
<!-- Role -->
<div>
  <label
    for="role"
    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
  >
    Select Role
  </label>

  <select
    id="role"
    v-model="role"
    class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm
           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
  >
    <option value="">-- Select Role --</option>
    <option value="admin">Admin</option>
    <option value="employee">Employee</option>
  </select>
</div>

<!-- Username / Email (auto-filled when role changes) -->
<div class="mt-4">
  <label
    for="email"
    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
  >
    Email
  </label>

  <input
    id="email"
    type="text"
    v-model="email"
    readonly
    class="block w-full rounded-md border border-gray-300 p-3 bg-gray-100 text-gray-900 sm:text-sm
           focus:outline-none dark:bg-gray-600 dark:border-gray-500 dark:text-white"
  />
</div>

<!-- Password (auto-filled when role changes) -->
<div class="mt-4">
  <label
    for="password"
    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
  >
    Password
  </label>

  <input
    id="password"
    type="password"
    v-model="password"
    readonly
    class="block w-full rounded-md border border-gray-300 p-3 bg-gray-100 text-gray-900 sm:text-sm
           focus:outline-none dark:bg-gray-600 dark:border-gray-500 dark:text-white"
  />
</div>
          <!-- Remember me + Forgot password -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center">
              <input
                id="remember-me"
                name="remember-me"
                type="checkbox"
                v-model="rememberMe"
                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
              />
              <label
                for="remember-me"
                class="ml-2 block text-sm text-gray-600 dark:text-gray-400"
              >
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <a
                href="/portal/forgot-password"
                class="font-medium text-primary hover:underline dark:text-primary-dark dark:hover:underline"
              >Forgot your password?</a>
            </div>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            @click="handleLogin"
            :disabled="loading"
            class="w-full py-3 bg-gradient-to-tr from-primary to-secondary hover:opacity-90 text-white font-semibold rounded-lg shadow-md transition"
          >
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </button>
        </form>

        <!-- Register redirect -->
        <p class="mt-6 text-sm text-center text-gray-600 dark:text-gray-400">
          Don't have an account?
          <a
            href="/portal/register"
            class="font-medium text-primary hover:underline dark:text-primary-dark dark:hover:underline"
          >Register</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useThemeStore } from '@/stores/theme'
import { useLogin } from '@/composables/auth/useLogin'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')

const themeStore = useThemeStore()
const isDarkMode = computed(() => themeStore.isDarkMode)

// ✅ local refs (THIS is what the template binds to)
const role = ref('')
const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const { loading, error, handleLogin } = useLogin({ role, email, password, rememberMe })

watch(
  role,
  (newRole) => {
    if (newRole === 'admin') {
      email.value = 'admin@example.com'
      password.value = 'proweaver'
    } else if (newRole === 'employee') {
      email.value = 'employee@example.com'
      password.value = 'proweaver'
    } else {
      email.value = ''
      password.value = ''
    }
  },
  { immediate: true }
)
</script>

