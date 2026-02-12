<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :title="title"
    :aria-label="ariaLabel || title || 'Action'"
    class="icon-action-btn"
    :class="[
      sizeClass,
      variantClass,
      (disabled || loading) ? 'icon-action-btn--disabled' : ''
    ]"
    @click="onClick"
  >
    <!-- Spinner when loading -->
    <svg
      v-if="loading"
      class="animate-spin"
      :class="spinnerSizeClass"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      aria-hidden="true"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
      />
    </svg>

    <!-- Icon slot -->
    <span v-else class="inline-flex items-center justify-center">
      <slot />
    </span>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  title?: string
  ariaLabel?: string
  type?: 'button' | 'submit' | 'reset'
  variant?: 'neutral' | 'primary' | 'danger' | 'success' | 'warning'
  size?: 'sm' | 'md' | 'lg'
  shape?: 'icon' | 'pill'
  disabled?: boolean
  loading?: boolean
}>()

const emit = defineEmits<{
  (e: 'click', event: MouseEvent): void
}>()

const onClick = (e: MouseEvent) => {
  if (props.disabled || props.loading) return
  emit('click', e)
}

const sizeClass = computed(() => {
  const shape = props.shape ?? 'icon'

  if (shape === 'pill') {
    switch (props.size) {
      case 'sm':
        return 'icon-action-btn--pill-sm'
      case 'lg':
        return 'icon-action-btn--pill-lg'
      default:
        return 'icon-action-btn--pill-md'
    }
  }

  // default icon-only
  switch (props.size) {
    case 'sm':
      return 'icon-action-btn--sm'
    case 'lg':
      return 'icon-action-btn--lg'
    default:
      return 'icon-action-btn--md'
  }
})

const spinnerSizeClass = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'w-4 h-4'
    case 'lg':
      return 'w-6 h-6'
    default:
      return 'w-5 h-5'
  }
})

const variantClass = computed(() => {
  switch (props.variant) {
    case 'primary':
      return 'icon-action-btn--primary'
    case 'danger':
      return 'icon-action-btn--danger'
    case 'success':
      return 'icon-action-btn--success'
    case 'warning':
      return 'icon-action-btn--warning'
    default:
      return 'icon-action-btn--neutral'
  }
})
</script>

<style scoped>
/* Pill sizes (auto width) */
.icon-action-btn--pill-sm { @apply h-8 px-3 gap-1.5 rounded-full; }
.icon-action-btn--pill-md { @apply h-9 px-3.5 gap-2 rounded-full; }
.icon-action-btn--pill-lg { @apply h-10 px-4 gap-2 rounded-full; }


/* Base layout */
.icon-action-btn {
  @apply inline-flex items-center justify-center
         gap-2
         whitespace-nowrap
         rounded-xl border
         transition-all duration-200
         focus:outline-none focus:ring-2 focus:ring-offset-2
         focus:ring-primary/40 focus:ring-offset-white dark:focus:ring-offset-slate-900
         shadow-sm hover:shadow-md active:scale-[0.98];
}

/* Sizes (big enough hit area for tables) */
.icon-action-btn--sm { @apply w-8 h-8; }
.icon-action-btn--md { @apply w-9 h-9; }
.icon-action-btn--lg { @apply w-10 h-10; }

/* Variants */
.icon-action-btn--neutral {
  @apply bg-white/70 dark:bg-slate-800/60
         border-slate-200/70 dark:border-slate-700/60
         text-slate-600 dark:text-slate-300
         hover:bg-slate-50 dark:hover:bg-slate-700/60
         hover:text-slate-900 dark:hover:text-white;
}

.icon-action-btn--primary {
  @apply bg-primary/10
         border-primary/20
         text-primary
         hover:bg-primary/15;
}

.icon-action-btn--danger {
  @apply bg-red-50 dark:bg-red-900/20
         border-red-200/70 dark:border-red-800/50
         text-red-600 dark:text-red-300
         hover:bg-red-100 dark:hover:bg-red-900/30;
}

.icon-action-btn--success {
  @apply bg-green-50 dark:bg-green-900/20
         border-green-200/70 dark:border-green-800/50
         text-green-600 dark:text-green-300
         hover:bg-green-100 dark:hover:bg-green-900/30;
}

.icon-action-btn--warning {
  @apply bg-amber-50 dark:bg-amber-900/20
         border-amber-200/70 dark:border-amber-800/50
         text-amber-700 dark:text-amber-300
         hover:bg-amber-100 dark:hover:bg-amber-900/30;
}

/* Disabled */
.icon-action-btn--disabled {
  @apply opacity-50 cursor-not-allowed shadow-none hover:shadow-none active:scale-100;
}
</style>
