// src/composables/ui/useToast.ts

import { ref } from 'vue'

type ToastType = 'success' | 'error' | 'info' | 'warning'

interface ToastItem {
  id: number
  title: string
  message?: string
  type: ToastType
}

const toasts = ref<ToastItem[]>([])
const timers = new Map<number, number>()
let idCounter = 0

const removeToast = (id: number) => {
  const timer = timers.get(id)
  if (timer) {
    window.clearTimeout(timer)
    timers.delete(id)
  }
  toasts.value = toasts.value.filter(t => t.id !== id)
}

const show = (
  type: ToastType,
  title: string,
  message = '',
  duration = 3500
) => {
  const id = idCounter++
  toasts.value.push({ id, title, message, type })
  const timer = window.setTimeout(() => removeToast(id), duration)
  timers.set(id, timer)
}

const success = (message: string, title = 'Success', duration = 3500) => {
  show('success', title, message, duration)
}

const error = (message: string, title = 'Error', duration = 3500) => {
  show('error', title, message, duration)
}

const info = (message: string, title = 'Info', duration = 3500) => {
  show('info', title, message, duration)
}

const warning = (message: string, title = 'Warning', duration = 3500) => {
  show('warning', title, message, duration)
}

export function useToast() {
  return {
    toasts,
    removeToast,
    success,
    error,
    info,
    warning,
  }
}
