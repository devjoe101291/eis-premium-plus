<template>
  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
        <thead class="bg-gradient-to-r from-gray-50/50 to-gray-50 dark:from-gray-800/50 dark:to-gray-800/30 backdrop-blur-sm">
          <tr>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider"
            >
              Avatar
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Name
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Email
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Title
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Role
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Status
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Last Updated
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900/50 divide-y divide-gray-200/50 dark:divide-gray-700/50">
          <EmployeeRow
            v-for="employee in employees"
            :key="employee.id"
            :employee="employee"
            @view="$emit('view', $event)"
            @edit="$emit('edit', $event)"
            @activate="$emit('activate', $event)"
            @deactivate="$emit('deactivate', $event)"
            @delete="$emit('delete', $event)"
          />
        </tbody>
      </table>
    </div>
    <div
      v-if="employees.length === 0"
      class="text-center py-16 text-secondary-text/70 dark:text-secondary-dark-text/70"
    >
      <p class="text-base">No employees found</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Employee } from '@/services/employeeService'
import EmployeeRow from './EmployeeRow.vue'

defineProps<{
  employees: Employee[]
}>()

defineEmits<{
  (e: 'view', id: number): void
  (e: 'edit', id: number): void
  (e: 'activate', id: number): void
  (e: 'deactivate', id: number): void
  (e: 'delete', id: number): void
}>()
</script>
