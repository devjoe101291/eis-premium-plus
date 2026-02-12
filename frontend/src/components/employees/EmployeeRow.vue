<template>
  <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-all duration-300 ease-out group border-b border-gray-100/50 dark:border-gray-700/30">
    <td class="px-6 py-5 whitespace-nowrap">
      <div class="flex items-center">
        <div
          class="h-11 w-11 rounded-full flex items-center justify-center text-white font-bold shadow-sm bg-gradient-to-br"
          :class="avatarBgClass"
        >
          {{ employeeInitials }}
        </div>
      </div>
    </td>
    <td class="px-6 py-5 whitespace-nowrap">
      <div class="text-sm font-semibold text-primary-text dark:text-primary-dark-text">
        {{ employee.name }}
      </div>
    </td>
    <td class="px-6 py-5 whitespace-nowrap">
      <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
        {{ employee.email }}
      </div>
    </td>
    <td class="px-6 py-5 whitespace-nowrap">
      <div class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70">
        {{ employee.title || '-' }}
      </div>
    </td>
    <td class="px-6 py-5 whitespace-nowrap">
      <RoleBadge :role="employee.role" />
    </td>
    <td class="px-6 py-5 whitespace-nowrap">
      <StatusBadge :status="employee.status" />
    </td>
    <td class="px-6 py-5 whitespace-nowrap text-sm text-secondary-text/70 dark:text-secondary-dark-text/70">
      {{ formatDate(employee.updated_at) }}
    </td>
    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
      <div class="flex items-center justify-end space-x-3">
        <button
          @click="$emit('view', employee.id)"
          class="p-2 rounded-lg text-primary/70 dark:text-primary-dark/70 hover:text-primary dark:hover:text-primary-dark hover:bg-primary/10 dark:hover:bg-primary-dark/10 transition-all duration-200"
          title="View"
        >
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
          </svg>
        </button>
        <button
          @click="$emit('edit', employee.id)"
          class="p-2 rounded-lg text-info/70 dark:text-info-dark/70 hover:text-info dark:hover:text-info-dark hover:bg-info/10 dark:hover:bg-info-dark/10 transition-all duration-200"
          title="Edit"
        >
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
          </svg>
        </button>
        <button
          v-if="employee.status === 'active'"
          @click="$emit('deactivate', employee.id)"
          class="p-2 rounded-lg text-warning/70 dark:text-warning-dark/70 hover:text-warning dark:hover:text-warning-dark hover:bg-warning/10 dark:hover:bg-warning-dark/10 transition-all duration-200"
          title="Deactivate"
        >
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
          </svg>
        </button>
        <button
          v-else
          @click="$emit('activate', employee.id)"
          class="p-2 rounded-lg text-success/70 dark:text-success-dark/70 hover:text-success dark:hover:text-success-dark hover:bg-success/10 dark:hover:bg-success-dark/10 transition-all duration-200"
          title="Activate"
        >
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </button>
        <button
          @click="$emit('delete', employee.id)"
          class="p-2 rounded-lg text-danger/70 dark:text-danger-dark/70 hover:text-danger dark:hover:text-danger-dark hover:bg-danger/10 dark:hover:bg-danger-dark/10 transition-all duration-200"
          title="Delete"
        >
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Employee } from '@/services/employeeService'
import StatusBadge from './StatusBadge.vue'
import RoleBadge from './RoleBadge.vue'

const props = defineProps<{
  employee: Employee
}>()

defineEmits<{
  (e: 'view', id: number): void
  (e: 'edit', id: number): void
  (e: 'activate', id: number): void
  (e: 'deactivate', id: number): void
  (e: 'delete', id: number): void
}>()

const employeeInitials = computed(() => {
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
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>
