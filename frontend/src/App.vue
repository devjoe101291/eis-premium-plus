<template>
  <router-view />
  <ToastContainer />
</template>

<script setup lang="ts">
import { useTitle } from '@/composables/ui/useTitle'
import { useColorScheme } from '@/composables/ui/useColorScheme'
import { computed, onMounted } from 'vue'
import { useThemeStore } from '@/stores/theme'
import ToastContainer from '@/components/layout/ToastContainer.vue'
const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(appTitle.value)

useColorScheme()

const themeStore = useThemeStore()

onMounted(() => {
  themeStore.initializeTheme()
})
</script>

<style>
/* Global styles */
html {
  font-family: 'Poppins', sans-serif;
}

body {
  font-family: 'Poppins', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Dark mode styles */
.dark {
  color-scheme: dark;
}

/* Color scheme transitions */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style> 
