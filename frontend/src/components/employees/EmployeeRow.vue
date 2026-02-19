<template>
  <tr class="hover:bg-primary/5 dark:hover:bg-primary-dark/5 transition-all duration-300 ease-out group">
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center">
        <div
          class="h-12 w-12 rounded-full flex items-center justify-center text-white font-bold shadow-md bg-gradient-to-br"
          :class="avatarBgClass"
        >
          {{ employeeInitials }}
        </div>
      </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="text-sm font-semibold text-primary-text dark:text-primary-dark-text">
        {{ displayName }}
      </div>
    </td>
    <td class="px-6 py-4">
      <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 max-w-xs truncate">
        {{ employee.email }}
      </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 max-w-xs truncate">
        {{ employee.phone || '—' }}
      </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      <StatusBadge :status="employee.status" />
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70">
        {{ formatDate(employee.updated_at) }}
      </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right">
      <div class="flex items-center justify-end space-x-2">
        <button
          @click="$emit('view', employee.id)"
          class="p-2.5 rounded-lg text-primary/70 dark:text-primary-dark/70 hover:text-primary dark:hover:text-primary-dark hover:bg-primary/10 dark:hover:bg-primary-dark/10 transition-all duration-200 hover:scale-110"
          title="View Details"
        >
          <ClipboardList class="h-5 w-5" />
        </button>
        <button
          v-if="employee.status === 'active'"
          @click="$emit('deactivate', employee.id)"
          class="p-2.5 rounded-lg text-warning/70 dark:text-warning-dark/70 hover:text-warning dark:hover:text-warning-dark hover:bg-warning/10 dark:hover:bg-warning-dark/10 transition-all duration-200 hover:scale-110"
          title="Deactivate"
        >
          <PowerOff class="h-5 w-5" />
        </button>
        <button
          v-else
          @click="$emit('activate', employee.id)"
          class="p-2.5 rounded-lg text-success/70 dark:text-success-dark/70 hover:text-success dark:hover:text-success-dark hover:bg-success/10 dark:hover:bg-success-dark/10 transition-all duration-200 hover:scale-110"
          title="Activate"
        >
          <Power class="h-5 w-5" />
        </button>
        <button
          @click="$emit('delete', employee.id)"
          class="p-2.5 rounded-lg text-danger/70 dark:text-danger-dark/70 hover:text-danger dark:hover:text-danger-dark hover:bg-danger/10 dark:hover:bg-danger-dark/10 transition-all duration-200 hover:scale-110"
          title="Delete"
        >
          <Trash class="h-5 w-5" />
        </button>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { ClipboardList, PowerOff, Power, Trash } from 'lucide-vue-next'
import type { Employee } from '@/services/employeeService'
import StatusBadge from './StatusBadge.vue'
import RoleBadge from './RoleBadge.vue'

const props = defineProps<{
  employee: Employee
}>()

defineEmits<{
  (e: 'view', id: number): void
  (e: 'activate', id: number): void
  (e: 'deactivate', id: number): void
  (e: 'delete', id: number): void
}>()

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

  const names = props.employee.name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase()
  }
  return props.employee.name.substring(0, 2).toUpperCase()
})

const avatarBgClass = computed(() => {
  const gradients = [
    'from-primary to-primary-hover dark:from-primary-dark dark:to-primary-dark-hover',
    'from-success to-success-hover dark:from-success-dark dark:to-success-dark-hover',
    'from-info to-info-hover dark:from-info-dark dark:to-info-dark-hover',
    'from-warning to-warning-hover dark:from-warning-dark dark:to-warning-dark-hover',
    'from-danger to-danger-hover dark:from-danger-dark dark:to-danger-dark-hover'
  ]
  const index = props.employee.id % gradients.length
  return gradients[index]
})

const formatDate = (dateString: string): string => {
  if (!dateString) return '—'
  const date = new Date(dateString)

  // Fallback if date is invalid
  if (isNaN(date.getTime())) return '—'

  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
