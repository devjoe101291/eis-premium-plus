<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">
    <!-- Sidebar -->
    <Sidebar v-model:isOpen="isSidebarOpen" />
    
    <div class="lg:pl-64">
      <!-- Header -->
      <header class="sticky top-0 z-40 flex h-16 flex-shrink-0 glass border-b border-gray-200/50 dark:border-gray-700/50 shadow-soft">
        <!-- Mobile Menu Button -->
        <button
          type="button"
          class="px-4 text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
          @click="toggleSidebar"
        >
          <span class="sr-only">Open sidebar</span>
          <svg
            class="h-6 w-6"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            aria-hidden="true"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>

        <!-- Header Content -->
        <div class="flex flex-1 justify-between px-4">
          <div class="flex flex-1">
            <!-- Add your header content here -->
          </div>
          <!-- User Profile Section -->
          <div class="ml-auto flex items-center md:ml-6">
            <div class="relative ml-3">
              <!-- Profile Button -->
              <div>
                <button
                  type="button"
                  class="flex max-w-xs items-center rounded-full bg-gradient-to-br from-primary to-primary-hover dark:from-primary-dark dark:to-primary-dark-hover text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 dark:focus:ring-primary-dark/50 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105"
                  @click="toggleProfile"
                >
                  <span class="sr-only">Open user menu</span>
                  <div class="h-9 w-9 rounded-full bg-white/20 dark:bg-white/10 backdrop-blur-sm flex items-center justify-center text-white font-semibold ring-2 ring-white/30 dark:ring-white/20">
                    {{ userInitials }}
                  </div>
                </button>
              </div>
              
              <!-- Profile Dropdown Menu -->
              <div
                v-if="isProfileOpen"
                class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl glass py-2 shadow-elegant-lg ring-1 ring-gray-200/50 dark:ring-gray-700/50 focus:outline-none backdrop-blur-md"
                role="menu"
              >
                <!-- User Info -->
                <div class="px-4 py-3 text-sm">
                  <p class="font-semibold text-gray-900 dark:text-gray-100 truncate" :title="currentUser?.name">{{ currentUser?.name }}</p>
                  <p class="text-gray-500 dark:text-gray-400 truncate mt-0.5" :title="currentUser?.email">{{ currentUser?.email }}</p>
                </div>
                <div class="border-t border-gray-200/50 dark:border-gray-700/50 my-1"></div>
                
                <!-- Theme Toggle -->
                <button
                  type="button"
                  class="block w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100/80 dark:hover:bg-gray-700/50 focus:outline-none focus:bg-gray-100/80 dark:focus:bg-gray-700/50 transition-colors duration-200 rounded-lg mx-1"
                  @click="toggleTheme"
                >
                  <div class="flex items-center">
                    <span class="mr-3 text-lg flex-shrink-0">{{ isDarkMode ? '🌞' : '🌙' }}</span>
                    <span class="truncate font-medium">{{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}</span>
                  </div>
                </button>

                <!-- Logout Button -->
                <button
                  type="button"
                  class="block w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:bg-red-50 dark:focus:bg-red-900/20 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 rounded-lg mx-1"
                  @click="handleLogout"
                  :disabled="isLoading"
                >
                  <div class="flex items-center">
                    <svg
                      class="mr-3 h-5 w-5 text-gray-400 flex-shrink-0"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v3a1 1 0 102 0V9z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <span class="truncate">{{ isLoading ? 'Logging out...' : 'Sign out' }}</span>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="py-8 sm:py-10">
        <div class="mx-auto max-w-8xl px-4 sm:px-6 lg:px-8">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
// Imports
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import { useClickOutside } from '@/composables/ui/useClickOutside'
import { useUser } from '@/composables/auth/useUser'
import { useLogin } from '@/composables/auth/useLogin'
import Sidebar from './Sidebar.vue'

// Store Instances
const authStore = useAuthStore()
const themeStore = useThemeStore()
const { userInitials } = useUser()
// const { handleLogout } = useLogin()

const role = ref('')
const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const { handleLogout } = useLogin({ role, email, password, rememberMe })

// State
const isProfileOpen = ref(false)
const isSidebarOpen = ref(false)

// Computed Properties
const currentUser = computed(() => authStore.currentUser)
const isLoading = computed(() => authStore.isLoading)
const isDarkMode = computed(() => themeStore.isDarkMode)

// Methods
const toggleProfile = () => {
  isProfileOpen.value = !isProfileOpen.value
}

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

const toggleTheme = () => {
  themeStore.toggleTheme()
}

// Lifecycle Hooks
const { handleClickOutside } = useClickOutside(isProfileOpen)

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script> 
