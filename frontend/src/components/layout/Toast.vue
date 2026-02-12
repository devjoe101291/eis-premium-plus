<template>
  <div
    v-if="show"
    class="rounded-2xl border shadow-lg backdrop-blur-sm px-4 py-3 flex gap-3 items-start"
    :class="wrapperClass"
    role="status"
    aria-live="polite"
  >
    <div class="mt-0.5">
      <span class="inline-block w-2.5 h-2.5 rounded-full" :class="dotClass"></span>
    </div>

    <div class="flex-1 min-w-0">
      <p class="text-sm font-semibold leading-5" :class="titleClass">
        {{ title }}
      </p>
      <p v-if="message" class="text-sm mt-1 leading-5" :class="messageClass">
        {{ message }}
      </p>
    </div>

    <button
      type="button"
      class="rounded-lg px-2 py-1 text-sm font-semibold hover:opacity-80 transition"
      :class="closeClass"
      @click="$emit('close')"
      aria-label="Close toast"
    >
<CloseIcon />
    </button>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import CloseIcon from '../icons/CloseIcon.vue';

type ToastType = 'error' | 'success' | 'info' | 'warning'

const props = defineProps<{
  show: boolean
  type?: ToastType
  title?: string
  message?: string
}>()

defineEmits<{
  (e: 'close'): void
}>()

const t = computed<ToastType>(() => props.type ?? 'info')

const wrapperClass = computed(() => {
  switch (t.value) {
    case 'error':
      return 'bg-red-50/95 dark:bg-red-500/10 border-red-200 dark:border-red-500/20'
    case 'success':
      return 'bg-emerald-50/95 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20'
    case 'warning':
      return 'bg-amber-50/95 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20'
    default:
      return 'bg-slate-50/95 dark:bg-slate-800/80 border-slate-200 dark:border-slate-700/50'
  }
})

const dotClass = computed(() => {
  switch (t.value) {
    case 'error':
      return 'bg-red-500'
    case 'success':
      return 'bg-emerald-500'
    case 'warning':
      return 'bg-amber-500'
    default:
      return 'bg-slate-400'
  }
})

const titleClass = computed(() => {
  switch (t.value) {
    case 'error':
      return 'text-red-800 dark:text-red-200'
    case 'success':
      return 'text-emerald-800 dark:text-emerald-200'
    case 'warning':
      return 'text-amber-800 dark:text-amber-200'
    default:
      return 'text-slate-800 dark:text-slate-100'
  }
})

const messageClass = computed(() => {
  switch (t.value) {
    case 'error':
      return 'text-red-700 dark:text-red-200/90'
    case 'success':
      return 'text-emerald-700 dark:text-emerald-200/90'
    case 'warning':
      return 'text-amber-700 dark:text-amber-200/90'
    default:
      return 'text-slate-600 dark:text-slate-300'
  }
})

const closeClass = computed(() => {
  switch (t.value) {
    case 'error':
      return 'text-red-700 dark:text-red-200'
    case 'success':
      return 'text-emerald-700 dark:text-emerald-200'
    case 'warning':
      return 'text-amber-700 dark:text-amber-200'
    default:
      return 'text-slate-600 dark:text-slate-200'
  }
})
</script>
