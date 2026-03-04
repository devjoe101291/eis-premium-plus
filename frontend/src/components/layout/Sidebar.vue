<template>
  <aside
    class="fixed inset-y-0 left-0 z-50 glass border-r border-gray-200/50 dark:border-gray-700/50 shadow-elegant-lg transform transition-all duration-300 ease-out lg:translate-x-0"
    :class="[
      isCollapsed ? 'w-20' : 'w-64',
      !isOpen ? '-translate-x-full' : 'translate-x-0'
    ]"
  >
    <div class="flex flex-col h-full">
      <!-- Logo Section -->
      <div class="flex items-center justify-center h-20 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-primary-bg/50 via-primary-bg/30 to-transparent dark:from-primary-dark-bg/30 dark:via-primary-dark-bg/20 dark:to-transparent backdrop-blur-sm">
        <div class="flex items-center justify-center space-x-2 px-2 w-full">
          <div class="h-8 w-8 flex items-center justify-center flex-shrink-0">
            <img src="@/assets/main-logo2.png" alt="Logo" class="h-5 w-5" />
          </div>
          <h1 v-show="!isCollapsed" class="text-base sm:text-lg lg:text-xl font-bold gradient-text from-primary to-primary-hover dark:from-primary-dark dark:to-primary-dark-hover break-words text-center whitespace-nowrap overflow-hidden transition-all duration-300">
            {{ appTitle }}
          </h1>
        </div>
      </div>

      <!-- Navigation Section -->
      <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <router-link
          v-for="item in navigation"
          :key="item.to"
          :to="item.to"
          :title="isCollapsed ? item.name : undefined"
          class="group flex items-center text-secondary-text dark:text-secondary-dark-text rounded-xl transition-all duration-300 ease-out relative overflow-hidden"
          :class="[
            isCollapsed ? 'justify-center px-2 py-3' : 'px-4 py-3',
            {
              'bg-gradient-to-r from-primary/10 via-primary/5 to-transparent dark:from-primary-dark/20 dark:via-primary-dark/10 dark:to-transparent text-primary dark:text-primary-dark shadow-sm': isActive(item.to),
              'hover:bg-gray-100/50 dark:hover:bg-gray-700/30': !isActive(item.to)
            }
          ]"
        >
          <!-- Active Indicator -->
          <div
            v-if="isActive(item.to)"
            class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-primary via-primary-hover to-primary dark:from-primary-dark dark:via-primary-dark-hover dark:to-primary-dark rounded-r-full shadow-lg shadow-primary/50 dark:shadow-primary-dark/50"
          ></div>

          <!-- Navigation Item Content -->
          <div class="flex items-center space-x-3 relative z-10 w-full" :class="{ 'justify-center space-x-0': isCollapsed }">
            <div
              class="flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-300 ease-out"
              :class="{
                'bg-gradient-to-br from-primary/20 to-primary/10 dark:from-primary-dark/30 dark:to-primary-dark/20 shadow-sm': isActive(item.to),
                'bg-gray-100/50 dark:bg-gray-700/30 group-hover:bg-gray-200/50 dark:group-hover:bg-gray-600/30': !isActive(item.to)
              }"
            >
              <component 
                :is="item.icon" 
                :class="[
                  'w-5 h-5 flex-shrink-0 transition-all duration-300 group-hover:scale-110',
                  isActive(item.to) 
                    ? 'text-primary dark:text-primary-dark' 
                    : 'text-secondary-text/60 dark:text-secondary-dark-text/60 group-hover:text-primary/90 dark:group-hover:text-primary-dark/90'
                ]"
              />
            </div>
            <span v-show="!isCollapsed" class="text-sm font-semibold truncate ml-2 whitespace-nowrap transition-all duration-300">{{ item.name }}</span>
          </div>

          <!-- Hover Effect -->
          <div
            class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-r from-primary/5 via-primary/8 to-primary/5 dark:from-primary-dark/5 dark:via-primary-dark/8 dark:to-primary-dark/5 transition-all duration-300 rounded-xl"
            :class="{ 'opacity-0': isActive(item.to) }"
          ></div>
        </router-link>
      </nav>

      <!-- Footer Section -->
      <div v-show="!isCollapsed" class="p-4 border-t border-gray-200/50 dark:border-gray-700/50 whitespace-nowrap transition-all duration-300">
        <div class="flex items-center justify-center space-x-2 text-xs text-secondary-text/70 dark:text-secondary-dark-text/70">
          <span>© {{ new Date().getFullYear() }}</span>
          <span class="w-1 h-1 rounded-full bg-secondary-text/30 dark:bg-secondary-dark-text/30"></span>
          <span>Developed by Proweaver</span>
        </div>
      </div>
    </div>
  </aside>

  <!-- Mobile Overlay -->
  <div
    v-if="isOpen"
    class="fixed inset-0 z-40 bg-black/60 backdrop-blur-md lg:hidden transition-opacity duration-300"
    @click="toggleSidebar"
  ></div>
</template>

<script setup lang="ts">
// Imports
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getNavigationItems } from '@/router'

// Props & Emits
const props = withDefaults(defineProps<{
  isOpen: boolean
  isCollapsed?: boolean
}>(), {
  isCollapsed: false
})

const emit = defineEmits<{
  (e: 'update:isOpen', value: boolean): void
}>()

// Composables
const route = useRoute()
const router = useRouter()

// Computed Properties
const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
const navigation = computed(() => getNavigationItems(router.options.routes))


// Methods
const isActive = (path: string) => route.path === path
const toggleSidebar = () => emit('update:isOpen', !props.isOpen)

</script> 