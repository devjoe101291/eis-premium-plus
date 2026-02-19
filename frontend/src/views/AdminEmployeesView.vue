<template>
  <div class="space-y-6 sm:space-y-8 px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1
          class="text-3xl sm:text-4xl font-bold bg-gradient-to-tr from-primary to-secondary dark:from-primary-light dark:to-secondary-light bg-clip-text text-transparent">
          Employees Management
        </h1>
        <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-2">
          Manage and monitor all employee accounts
        </p>
      </div>
      <button @click="showCreateModal = true"
        class="btn text-white bg-gradient-to-tr from-primary to-secondary dark:from-primary-light/10 dark:to-secondary-light/10 mt-4 sm:mt-0">
        <Plus class="h-5 w-5 mr-2" />
        Add New Employee
      </button>
    </div>

    <!-- Search and Filters -->
    <div class="card relative z-20 border border-gray-200/50 dark:border-gray-700/50 shadow-sm overflow-visible">
      <div class="card-body">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <SearchBar v-model="searchQuery" placeholder="Search by name, username or email..."
              @update:modelValue="handleSearch" />
          </div>
          <div class="flex w-full sm:w-auto gap-3">
            <FilterDropdown v-model="statusFilter" label="Status" :options="statusOptions"
              @update:modelValue="handleFilter" />
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
      <div class="card-body">
        <div class="flex flex-col items-center justify-center py-16">
          <div
            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 dark:bg-primary-dark/10 mb-4">
            <Loader2 class="w-6 h-6 text-primary dark:text-primary-dark animate-spin" />
          </div>
          <p class="text-base font-medium text-secondary-text dark:text-secondary-dark-text">
            Loading employees...
          </p>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error"
      class="card border border-danger/20 dark:border-danger-dark/20 bg-danger/5 dark:bg-danger-dark/5 shadow-sm">
      <div class="card-body">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            <div
              class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-danger/10 dark:bg-danger-dark/10">
              <AlertCircle class="h-5 w-5 text-danger dark:text-danger-dark" />
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-sm font-semibold text-danger dark:text-danger-dark mb-1">
              Error Loading Employees
            </h3>
            <p class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
              {{ error }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Employee Table -->
    <EmployeeTable v-else :employees="employees" @view="handleView" @activate="handleActivate"
      @deactivate="handleDeactivate" @delete="handleDelete" />

    <!-- Pagination -->
    <div v-if="!loading && !error && total > 0"
      class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
      <div class="card-body">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <!-- Pagination Info -->
          <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 order-2 sm:order-1">
            Showing
            <span class="font-semibold text-primary-text dark:text-primary-dark-text">{{ (currentPage - 1) * perPage + 1
              }}</span>
            to
            <span class="font-semibold text-primary-text dark:text-primary-dark-text">{{ Math.min(currentPage * perPage,
              total) }}</span>
            of
            <span class="font-semibold text-primary-text dark:text-primary-dark-text">{{ total }}</span>
            <span class="hidden xs:inline">employees</span>
          </div>

          <!-- Pagination Controls -->
          <div class="flex items-center space-x-2 order-1 sm:order-2 w-full sm:w-auto justify-center sm:justify-end">
            <!-- Previous Button -->
            <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
              class="px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-primary-text dark:text-primary-dark-text hover:bg-primary/5 dark:hover:bg-primary-dark/5 hover:border-primary dark:hover:border-primary-dark"
              :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-sm'">
              <span class="hidden sm:inline">Previous</span>
              <ChevronLeft class="h-5 w-5 sm:hidden" />
            </button>

            <!-- Page Numbers (Desktop) -->
            <div v-if="totalPages > 1" class="hidden md:flex items-center space-x-1">
              <template v-for="page in visiblePages" :key="page">
                <button v-if="page !== '...'" @click="goToPage(page as number)"
                  class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 min-w-[2.5rem]"
                  :class="page === currentPage
                    ? 'bg-primary text-white dark:bg-primary-dark dark:text-white shadow-md'
                    : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-primary-text dark:text-primary-dark-text hover:bg-primary/5 dark:hover:bg-primary-dark/5 hover:border-primary dark:hover:border-primary-dark'">
                  {{ page }}
                </button>
                <span v-else class="px-2 text-secondary-text/50 dark:text-secondary-dark-text/50">
                  ...
                </span>
              </template>
            </div>

            <!-- Mobile Page Indicator -->
            <div
              class="md:hidden px-3 py-2 text-sm font-medium text-primary-text dark:text-primary-dark-text bg-primary/10 dark:bg-primary-dark/10 rounded-lg">
              {{ currentPage }} / {{ totalPages || 1 }}
            </div>

            <!-- Next Button -->
            <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
              class="px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-primary-text dark:text-primary-dark-text hover:bg-primary/5 dark:hover:bg-primary-dark/5 hover:border-primary dark:hover:border-primary-dark"
              :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-sm'">
              <span class="hidden sm:inline">Next</span>
              <ChevronRight class="h-5 w-5 sm:hidden" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- View/Edit Modal -->
    <EmployeeModal :is-open="showModal || showCreateModal" :employee="selectedEmployee" :edit-mode="editMode"
      @close="closeModalOrCreateModal" @save="handleSave" @update="handleUpdate" />

    <!-- Delete Confirmation Modal -->
    <Modal v-model="showDeleteConfirm" title="Delete Employee">
      <p class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
        Are you sure you want to permanently delete this employee? This action cannot be undone.
      </p>

      <template #footer>
        <button type="button"
          class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-medium text-secondary-text dark:text-secondary-dark-text hover:bg-gray-100/80 dark:hover:bg-gray-700/60 transition-colors duration-200"
          @click="cancelDelete">
          Cancel
        </button>
        <button type="button"
          class="ml-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-danger hover:bg-danger/90 shadow-sm hover:shadow-md transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
          @click="confirmDelete">
          Yes, delete permanently
        </button>
      </template>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Plus, Loader2, AlertCircle, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { useTitle } from '@/composables/ui/useTitle'
import { useToast } from '@/composables/ui/useToast'
import { useEmployees } from '@/composables/useEmployees'
import SearchBar from '@/components/ui/SearchBar.vue'
import FilterDropdown from '@/components/ui/FilterDropdown.vue'
import EmployeeTable from '@/components/employees/EmployeeTable.vue'
import EmployeeModal from '@/components/employees/EmployeeModal.vue'
import Modal from '@/components/layout/Modal.vue'
import type { Employee, CreateEmployeeData, UpdateEmployeeData } from '@/services/employeeService'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Employees`)

const { success: showSuccess, error: showError } = useToast()

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
  createEmployee,
  updateEmployee,
  toggleEmployeeStatus,
  deleteEmployee
} = useEmployees()

const searchQuery = ref('')
const statusFilter = ref('all')
const showModal = ref(false)
const showCreateModal = ref(false)
const selectedEmployee = ref<Employee | null>(null)
const editMode = ref(false)
const showDeleteConfirm = ref(false)
const pendingDeleteId = ref<number | null>(null)

const statusOptions = [
  { value: 'all', label: 'All Status' },
  { value: 'active', label: 'Active' },
  { value: 'inactive', label: 'Inactive' },
  { value: 'pending', label: 'Pending' }
]

const handleSearch = () => {
  fetchEmployees({
    page: 1,
    search: searchQuery.value || undefined,
    status: statusFilter.value !== 'all' ? statusFilter.value : undefined
  })
}

const handleFilter = () => {
  fetchEmployees({
    page: 1,
    search: searchQuery.value || undefined,
    status: statusFilter.value !== 'all' ? statusFilter.value : undefined
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


const handleActivate = async (id: number) => {
  try {
    await toggleEmployeeStatus(id, 'active')
    // Close modal before refresh to prevent rendering conflicts
    closeModalOrCreateModal()
    
    showSuccess('Employee activated successfully', 'Status Updated')
    await fetchEmployees()
  } catch (err: any) {
    const errorMessage = err?.response?.data?.message || 'Failed to activate employee'
    showError(errorMessage, 'Error')
    console.error('Failed to activate employee:', err)
  }
}

const handleDeactivate = async (id: number) => {
  try {
    await toggleEmployeeStatus(id, 'inactive')
    // Close modal before refresh to prevent rendering conflicts
    closeModalOrCreateModal()
    
    showSuccess('Employee deactivated successfully', 'Status Updated')
    await fetchEmployees()
  } catch (err: any) {
    const errorMessage = err?.response?.data?.message || 'Failed to deactivate employee'
    showError(errorMessage, 'Error')
    console.error('Failed to deactivate employee:', err)
  }
}

const handleDelete = (id: number) => {
  pendingDeleteId.value = id
  showDeleteConfirm.value = true
}

const confirmDelete = async () => {
  if (pendingDeleteId.value === null) return

  const id = pendingDeleteId.value

  try {
    await deleteEmployee(id)
    // Close modal before refresh to prevent rendering conflicts
    closeModalOrCreateModal()
    
    showSuccess('Employee deleted successfully', 'Employee Deleted')
    await fetchEmployees()
  } catch (err: any) {
    const errorMessage = err?.response?.data?.message || 'Failed to delete employee'
    showError(errorMessage, 'Error')
    console.error('Failed to delete employee:', err)
  } finally {
    showDeleteConfirm.value = false
    pendingDeleteId.value = null
  }
}

const cancelDelete = () => {
  showDeleteConfirm.value = false
  pendingDeleteId.value = null
}

const closeModalOrCreateModal = () => {
  showModal.value = false
  showCreateModal.value = false
  selectedEmployee.value = null
  editMode.value = false
}

const handleSave = async (data: CreateEmployeeData & { password_confirmation?: string }) => {
  try {
    // Validate required fields
    if (!data.first_name || !data.last_name || !data.username || !data.email || !data.password) {
      showError('Please fill in all required fields (First Name, Last Name, Username, Email, Password)', 'Validation Error')
      return
    }

    if (data.password.length < 8) {
      showError('Password must be at least 8 characters long', 'Validation Error')
      return
    }

    // Validate password confirmation
    if (data.password_confirmation && data.password !== data.password_confirmation) {
      showError('Passwords do not match', 'Validation Error')
      return
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(data.email)) {
      showError('Please enter a valid email address', 'Validation Error')
      return
    }

    // Call the createEmployee function from the composable
    await createEmployee(data);

    // Show success toast
    const employeeName = data.first_name && data.last_name
      ? `${data.first_name} ${data.last_name}`
      : 'Employee'
    showSuccess(`Employee "${employeeName}" has been created successfully!`, 'Employee Created')

    // Refresh the employee list
    await fetchEmployees();

    // Close the modal
    showCreateModal.value = false;
  } catch (error: any) {
    // Extract error message from API response
    const errorMessage = error?.response?.data?.message ||
      error?.response?.data?.error ||
      error?.message ||
      'Failed to create employee. Please try again.'

    // Show validation errors from backend
    if (error?.response?.data?.errors) {
      const errors = error.response.data.errors
      const firstError = Object.values(errors)[0]
      const errorMsg = Array.isArray(firstError) ? firstError[0] : firstError
      showError(errorMsg as string, 'Validation Error')
    } else {
      showError(errorMessage, 'Error Creating Employee')
    }
    console.error('Error creating employee:', error);
  }
}

const handleUpdate = async (id: number, data: any) => {
  try {
    // Validate required fields
    if (!data.first_name || !data.last_name || !data.username || !data.email) {
      showError('Please fill in all required fields (First Name, Last Name, Username, Email)', 'Validation Error')
      return
    }

    // Validate password if provided
    if (data.password) {
      if (data.password.length < 8) {
        showError('Password must be at least 8 characters long', 'Validation Error')
        return
      }

      // Validate password confirmation
      if (data.password_confirmation && data.password !== data.password_confirmation) {
        showError('Passwords do not match', 'Validation Error')
        return
      }
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(data.email)) {
      showError('Please enter a valid email address', 'Validation Error')
      return
    }

    // Call the updateEmployee function from the composable
    await updateEmployee(id, data);

    // Show success toast
    const employeeName = data.first_name && data.last_name
      ? `${data.first_name} ${data.last_name}`
      : 'Employee'
    showSuccess(`Employee "${employeeName}" has been updated successfully!`, 'Employee Updated')

    // Refresh the employee list
    await fetchEmployees();

    // Close the modal and reset
    showModal.value = false
    selectedEmployee.value = null
    editMode.value = false
  } catch (error: any) {
    // Extract error message from API response
    const errorMessage = error?.response?.data?.message ||
      error?.response?.data?.error ||
      error?.message ||
      'Failed to update employee. Please try again.'

    // Show validation errors from backend
    if (error?.response?.data?.errors) {
      const errors = error.response.data.errors
      const firstError = Object.values(errors)[0]
      const errorMsg = Array.isArray(firstError) ? firstError[0] : firstError
      showError(errorMsg as string, 'Validation Error')
    } else {
      showError(errorMessage, 'Error Updating Employee')
    }
    console.error('Error updating employee:', error);
  }
}

const goToPage = (page: number) => {
  fetchEmployees({ page })
}

// Computed property for visible page numbers in pagination
const visiblePages = computed(() => {
  const pages: (number | string)[] = []
  const total = totalPages.value
  const current = currentPage.value

  // If no pages or only 1 page, show just that page
  if (total <= 1) {
    if (total === 1) {
      pages.push(1)
    }
    return pages
  }

  if (total <= 7) {
    // Show all pages if 7 or fewer
    for (let i = 1; i <= total; i++) {
      pages.push(i)
    }
  } else {
    // Always show first page
    pages.push(1)

    if (current <= 3) {
      // Near the start
      for (let i = 2; i <= 4; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(total)
    } else if (current >= total - 2) {
      // Near the end
      pages.push('...')
      for (let i = total - 3; i <= total; i++) {
        pages.push(i)
      }
    } else {
      // In the middle
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(total)
    }
  }

  return pages
})

onMounted(async () => {
  try {
    await fetchEmployees()
  } catch (err) {
    console.error('Failed to load employees:', err)
  }
})
</script>

