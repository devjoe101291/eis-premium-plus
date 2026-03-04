<template>
  <div
    class="p-4 hover:bg-gray-50/80 dark:hover:bg-gray-800/30 transition-all duration-300 border-b border-gray-200/50 dark:border-gray-700/50">
    <div class="flex flex-col gap-3">
      <!-- Avatar and Info Section -->
      <div class="flex items-start gap-3 w-full min-w-0">
        <!-- Avatar -->
        <div
          class="h-10 w-10 sm:h-12 sm:w-12 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md bg-gradient-to-br flex-shrink-0"
          :class="avatarBgClass">
          {{ employeeInitials }}
        </div>
        <!-- Employee Info -->
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2 mb-0.5">
            <h3
              class="text-sm sm:text-base font-semibold text-primary-text dark:text-primary-dark-text truncate max-w-full">
              {{ displayName }}</h3>
            <StatusBadge :status="employee.status" size="sm" class="flex-shrink-0" />
          </div>
          <p class="text-xs sm:text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 truncate mb-1.5">{{
            employee.email }}</p>
          <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-secondary-text/70 dark:text-secondary-dark-text/70">
            <span v-if="employee.phone" class="truncate">{{ employee.phone }}</span>
            <span>{{ formatDate(employee.updated_at) }}</span>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div
        class="flex items-center justify-start gap-2 sm:gap-1 mt-1 border-t border-gray-100 dark:border-gray-800 pt-3 sm:border-none sm:pt-0 sm:mt-0">
        <button @click="handleAction('view', employee.id)"
          class="p-2 bg-gray-50 sm:bg-transparent dark:bg-gray-800 sm:dark:bg-transparent rounded-md text-primary/70 hover:text-primary hover:bg-primary/10 transition-all duration-200"
          title="View">
          <ClipboardList class="h-4 w-4 sm:h-5 sm:w-5" />
        </button>
        <button v-if="employee.status === 'active'" @click="handleAction('deactivate', employee.id)"
          class="p-2 bg-gray-50 sm:bg-transparent dark:bg-gray-800 sm:dark:bg-transparent rounded-md text-warning/70 hover:text-warning hover:bg-warning/10 transition-all duration-200"
          title="Deactivate">
          <PowerOff class="h-4 w-4 sm:h-5 sm:w-5" />
        </button>
        <button v-else @click="handleAction('activate', employee.id)"
          class="p-2 bg-gray-50 sm:bg-transparent dark:bg-gray-800 sm:dark:bg-transparent rounded-md text-success/70 hover:text-success hover:bg-success/10 transition-all duration-200"
          title="Activate">
          <Power class="h-4 w-4 sm:h-5 sm:w-5" />
        </button>
        <button @click="handleAction('delete', employee.id)"
          class="p-2 bg-gray-50 sm:bg-transparent dark:bg-gray-800 sm:dark:bg-transparent rounded-md text-danger/70 hover:text-danger hover:bg-danger/10 transition-all duration-200"
          title="Delete">
          <Trash class="h-4 w-4 sm:h-5 sm:w-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { ClipboardList, PowerOff, Power, Trash } from 'lucide-vue-next'
import type { Employee } from '@/services/employeeService'
import StatusBadge from './StatusBadge.vue'
import { useToast } from '@/composables/ui/useToast'

const props = defineProps<{ employee: Employee }>()

const emit = defineEmits<{
  (e: 'view', id: number): void
  (e: 'activate', id: number): void
  (e: 'deactivate', id: number): void
  (e: 'delete', id: number): void
}>()

const { warning } = useToast()

const handleAction = (action: 'view' | 'activate' | 'deactivate' | 'delete', id: number) => {
  if (props.employee.email === 'employee@example.com') {
    warning('This account cannot be edited as it is used for demo purposes only.', 'Demo Account')
    return
  }

  switch (action) {
    case 'view':
      emit('view', id)
      break
    case 'activate':
      emit('activate', id)
      break
    case 'deactivate':
      emit('deactivate', id)
      break
    case 'delete':
      emit('delete', id)
      break
  }
}

const displayName = computed(() => {
  if (props.employee.first_name && props.employee.last_name) {
    return `${props.employee.first_name} ${props.employee.last_name}`
  }
  return props.employee.name
})

const employeeInitials = computed(() => {
  if (props.employee.first_name && props.employee.last_name) {
    return (props.employee.first_name[0] + props.employee.last_name[0]).toUpperCase()
  }
  const name = (props.employee.name || '').trim()
  if (!name) return 'EM'
  const parts = name.split(/\s+/).filter(Boolean)
  if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
})

const avatarBgClass = computed(() => {
  const colors = ['from-blue-400 to-blue-600', 'from-purple-400 to-purple-600', 'from-pink-400 to-pink-600', 'from-indigo-400 to-indigo-600', 'from-cyan-400 to-cyan-600', 'from-green-400 to-green-600', 'from-orange-400 to-orange-600']
  let hash = 0
  const str = displayName.value
  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash)
  }
  return colors[Math.abs(hash) % colors.length]
})

const formatDate = (dateString: string) => {
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
  } catch {
    return 'N/A'
  }
}
</script>
