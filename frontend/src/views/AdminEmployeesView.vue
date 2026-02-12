<template>
  <div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-primary-text dark:text-primary-dark-text">
          Employees Management
        </h1>
        <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-2">
          Manage and monitor all employee accounts
        </p>
      </div>
      <button
        @click="showCreateModal = true"
        class="btn btn-primary mt-4 sm:mt-0"
      >
        <svg
          class="h-5 w-5 mr-2"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
            clip-rule="evenodd"
          />
        </svg>
        Add New Employee
      </button>
    </div>

    <!-- Search and Filters -->
    <div class="card">
      <div class="card-body">
        <div class="flex flex-col sm:flex-row gap-5">
          <div class="flex-1">
            <SearchBar
              v-model="searchQuery"
              placeholder="Search employees..."
              @update:modelValue="handleSearch"
            />
          </div>
          <FilterDropdown
            v-model="statusFilter"
            label="Status"
            :options="statusOptions"
            @update:modelValue="handleFilter"
          />
          <FilterDropdown
            v-model="roleFilter"
            label="Role"
            :options="roleOptions"
            @update:modelValue="handleFilter"
          />
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div
      v-if="loading"
      class="text-center py-16 text-secondary-text/70 dark:text-secondary-dark-text/70"
    >
      <p class="text-base">Loading employees...</p>
    </div>

    <!-- Error State -->
    <div
      v-else-if="error"
      class="card"
    >
      <div class="card-body">
        <div class="flex items-center space-x-3 text-danger dark:text-danger-dark">
          <svg
            class="h-5 w-5"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"
            />
          </svg>
          <span>{{ error }}</span>
        </div>
      </div>
    </div>

    <!-- Employee Table -->
    <EmployeeTable
      v-else
      :employees="employees"
      @view="handleView"
      @edit="handleEdit"
      @activate="handleActivate"
      @deactivate="handleDeactivate"
      @delete="handleDelete"
    />

    <!-- Pagination -->
    <div
      v-if="totalPages > 1"
      class="flex flex-col sm:flex-row items-center justify-between gap-4"
    >
      <div class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70">
        Showing <span class="font-semibold text-primary-text dark:text-primary-dark-text">{{ (currentPage - 1) * perPage + 1 }}</span> to
        <span class="font-semibold text-primary-text dark:text-primary-dark-text">{{ Math.min(currentPage * perPage, total) }}</span> of
        <span class="font-semibold text-primary-text dark:text-primary-dark-text">{{ total }}</span> employees
      </div>
      <div class="flex space-x-3">
        <button
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="btn btn-secondary"
        >
          Previous
        </button>
        <button
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="btn btn-secondary"
        >
          Next
        </button>
      </div>
    </div>

    <!-- View/Edit Modal -->
    <EmployeeModal
      :is-open="showModal"
      :employee="selectedEmployee"
      @close="closeModal"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useTitle } from '@/composables/ui/useTitle'
import { useEmployees } from '@/composables/useEmployees'
import SearchBar from '@/components/ui/SearchBar.vue'
import FilterDropdown from '@/components/ui/FilterDropdown.vue'
import EmployeeTable from '@/components/employees/EmployeeTable.vue'
import EmployeeModal from '@/components/employees/EmployeeModal.vue'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Employees`)

const {
  employees,
  currentEmployee,
  loading,
  error,
  total,
  currentPage,
  perPage,
  totalPages,
  fetchEmployees,
  fetchEmployee,
  toggleEmployeeStatus,
  deleteEmployee
} = useEmployees()

const searchQuery = ref('')
const statusFilter = ref('all')
const roleFilter = ref('all')
const showModal = ref(false)
const showCreateModal = ref(false)
const selectedEmployee = ref(null)

const statusOptions = [
  { value: 'all', label: 'All Status' },
  { value: 'active', label: 'Active' },
  { value: 'inactive', label: 'Inactive' },
  { value: 'pending', label: 'Pending' }
]

const roleOptions = [
  { value: 'all', label: 'All Roles' },
  { value: 'admin', label: 'Admin' },
  { value: 'employee', label: 'Employee' },
  { value: 'student', label: 'Student' }
]

const handleSearch = () => {
  fetchEmployees({
    search: searchQuery.value || undefined,
    status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
    role: roleFilter.value !== 'all' ? roleFilter.value : undefined
  })
}

const handleFilter = () => {
  fetchEmployees({
    search: searchQuery.value || undefined,
    status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
    role: roleFilter.value !== 'all' ? roleFilter.value : undefined
  })
}

const handleView = async (id: number) => {
  try {
    await fetchEmployee(id)
    selectedEmployee.value = currentEmployee.value
    showModal.value = true
  } catch (err) {
    console.error('Failed to fetch employee:', err)
  }
}

const handleEdit = (id: number) => {
  // TODO: Implement edit functionality
  console.log('Edit employee:', id)
}

const handleActivate = async (id: number) => {
  if (confirm('Are you sure you want to activate this employee?')) {
    try {
      await toggleEmployeeStatus(id, 'active')
      await fetchEmployees()
    } catch (err) {
      console.error('Failed to activate employee:', err)
    }
  }
}

const handleDeactivate = async (id: number) => {
  if (confirm('Are you sure you want to deactivate this employee?')) {
    try {
      await toggleEmployeeStatus(id, 'inactive')
      await fetchEmployees()
    } catch (err) {
      console.error('Failed to deactivate employee:', err)
    }
  }
}

const handleDelete = async (id: number) => {
  if (confirm('Are you sure you want to delete this employee? This action cannot be undone.')) {
    try {
      await deleteEmployee(id)
      await fetchEmployees()
    } catch (err) {
      console.error('Failed to delete employee:', err)
    }
  }
}

const closeModal = () => {
  showModal.value = false
  selectedEmployee.value = null
}

const goToPage = (page: number) => {
  fetchEmployees({ page })
}

onMounted(async () => {
  try {
    await fetchEmployees()
  } catch (err) {
    console.error('Failed to load employees:', err)
  }
})
</script>

