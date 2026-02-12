<template>
  <button
    type="button"
    @click="$emit('click')"
    class="card w-full text-left transform hover:-translate-y-1 transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-primary/50 dark:focus:ring-primary-dark/50 group"
  >
    <div class="card-body">
      <div class="flex items-center space-x-5">
        <div
          class="flex-shrink-0 rounded-xl p-4 shadow-elegant-lg bg-gradient-to-br transition-transform duration-300 group-hover:scale-110"
          :class="iconBgClass"
        >
          <component :is="icon" class="h-8 w-8 text-white" />
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-bold text-primary-text dark:text-primary-dark-text">
            {{ title }}
          </h3>
          <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-1.5">
            {{ description }}
          </p>
        </div>
        <div class="flex-shrink-0 transition-transform duration-300 group-hover:translate-x-1">
          <svg
            class="h-5 w-5 text-primary/60 dark:text-primary-dark/60 group-hover:text-primary dark:group-hover:text-primary-dark"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
      </div>
    </div>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  title: string
  description: string
  icon: any
  color?: 'primary' | 'success' | 'danger' | 'warning' | 'info'
}>()

defineEmits<{
  (e: 'click'): void
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
