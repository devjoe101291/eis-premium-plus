<template>
  <div v-if="loading" class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
    <div class="card-body">
      <div class="flex flex-col items-center justify-center py-16">
        <!-- Spinner Icon -->
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
          <Loader2 class="w-6 h-6 text-primary dark:text-primary animate-spin" />
        </div>
        
        <!-- Loading Text -->
        <p class="text-base font-medium text-slate-600 dark:text-slate-300">
          {{ message }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next'

interface Props {
  loading: boolean
  message?: string
}

withDefaults(defineProps<Props>(), {
  message: 'Loading...',
})
</script>