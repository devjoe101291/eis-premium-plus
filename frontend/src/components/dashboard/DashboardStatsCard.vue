<template>
  <div class="card transform hover:-translate-y-1 transition-all duration-300 ease-out group">
    <div class="card-body">
      <div class="flex items-center">
        <div
          class="flex-shrink-0 rounded-xl p-3.5 shadow-elegant-lg bg-gradient-to-br"
          :class="iconBgClass"
        >
          <component :is="icon" class="h-6 w-6 text-white" />
        </div>
        <div class="ml-5 flex-1">
          <h2 class="text-sm font-medium text-secondary-text/70 dark:text-secondary-dark-text/70 uppercase tracking-wide">
            {{ title }}
          </h2>
          <p class="text-2xl sm:text-3xl font-bold text-primary-text dark:text-primary-dark-text mt-2">
            {{ value }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  title: string
  value: string | number
  icon: any
  color?: 'primary' | 'success' | 'danger' | 'warning' | 'info'
}>()

const iconBgClass = computed(() => {
  const colors = {
    primary: 'from-primary to-primary-hover dark:from-primary-dark dark:to-primary-dark-hover',
    success: 'from-success to-success-hover dark:from-success-dark dark:to-success-dark-hover',
    danger: 'from-danger to-danger-hover dark:from-danger-dark dark:to-danger-dark-hover',
    warning: 'from-warning to-warning-hover dark:from-warning-dark dark:to-warning-dark-hover',
    info: 'from-info to-info-hover dark:from-info-dark dark:to-info-dark-hover'
  }
  return colors[props.color || 'primary']
})
</script>
