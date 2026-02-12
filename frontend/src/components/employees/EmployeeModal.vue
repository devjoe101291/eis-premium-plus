<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 overflow-y-auto"
    @click.self="$emit('close')"
  >
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-opacity-50" @click="$emit('close')"></div>

      <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-primary-text dark:text-primary-dark-text mb-4">
                {{ employee ? 'Employee Details' : 'Add New Employee' }}
              </h3>

              <div v-if="employee" class="space-y-4">
                <div>
                  <label class="label">Name</label>
                  <p class="text-sm text-secondary-text dark:text-secondary-dark-text">{{ employee.name }}</p>
                </div>
                <div>
                  <label class="label">Email</label>
                  <p class="text-sm text-secondary-text dark:text-secondary-dark-text">{{ employee.email }}</p>
                </div>
                <div>
                  <label class="label">Role</label>
                  <RoleBadge :role="employee.role" />
                </div>
                <div>
                  <label class="label">Status</label>
                  <StatusBadge :status="employee.status" />
                </div>
                <div v-if="employee.title">
                  <label class="label">Title</label>
                  <p class="text-sm text-secondary-text dark:text-secondary-dark-text">{{ employee.title }}</p>
                </div>
                <div v-if="employee.phone">
                  <label class="label">Phone</label>
                  <p class="text-sm text-secondary-text dark:text-secondary-dark-text">{{ employee.phone }}</p>
                </div>
                <div v-if="employee.address">
                  <label class="label">Address</label>
                  <p class="text-sm text-secondary-text dark:text-secondary-dark-text">{{ employee.address }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            @click="$emit('close')"
            class="btn btn-secondary w-full sm:w-auto sm:ml-3"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Employee } from '@/services/employeeService'
import StatusBadge from './StatusBadge.vue'
import RoleBadge from './RoleBadge.vue'

defineProps<{
  isOpen: boolean
  employee: Employee | null
}>()

defineEmits<{
  (e: 'close'): void
}>()
</script>
