<template>
  <div class="card relative z-10 overflow-hidden shadow-lg border border-gray-200/50 dark:border-gray-700/50">
    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
        <thead class="bg-gradient-to-r from-primary/5 via-primary/3 to-transparent dark:from-primary-dark/10 dark:via-primary-dark/5 dark:to-transparent">
          <tr>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-bold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Profile
            </th>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Name
            </th>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Email
            </th>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Phone
            </th>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Status
            </th>
            <th
              scope="col"
              class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Last Updated
            </th>
            <th
              scope="col"
              class="px-6 py-4 text-right text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
            >
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900/50 divide-y divide-gray-200/50 dark:divide-gray-700/50">
          <EmployeeRow
            v-for="employee in safeEmployees"
            :key="employee.id"
            :employee="employee"
            @view="$emit('view', $event)"
            @activate="$emit('activate', $event)"
            @deactivate="$emit('deactivate', $event)"
            @delete="$emit('delete', $event)"
          />
        </tbody>
      </table>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden divide-y divide-gray-200/50 dark:divide-gray-700/50">
      <EmployeeCard
        v-for="employee in safeEmployees"
        :key="employee.id"
        :employee="employee"
        @view="$emit('view', $event)"
        @activate="$emit('activate', $event)"
        @deactivate="$emit('deactivate', $event)"
        @delete="$emit('delete', $event)"
      />
    </div>

    <!-- Empty State -->
    <div
      v-if="safeEmployees.length === 0"
      class="text-center py-16 px-4"
    >
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
        <Users class="w-8 h-8 text-secondary-text/50 dark:text-secondary-dark-text/50" />
      </div>
      <p class="text-base font-medium text-secondary-text dark:text-secondary-dark-text">
        No employees found
      </p>
      <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-1">
        Try adjusting your search or filters
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Users } from 'lucide-vue-next'
import type { Employee } from '@/services/employeeService'
import EmployeeRow from './EmployeeRow.vue'
import EmployeeCard from './EmployeeCard.vue'

const props = defineProps<{
  employees: Employee[]
}>()

const safeEmployees = computed(() =>
  (props.employees ?? []).filter((e): e is Employee => !!e && typeof (e as any).id === 'number')
)

defineEmits<{
  (e: 'view', id: number): void
  (e: 'activate', id: number): void
  (e: 'deactivate', id: number): void
  (e: 'delete', id: number): void
}>()
</script>


