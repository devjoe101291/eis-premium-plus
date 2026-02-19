import { ref, computed } from 'vue'
import { employeeService, type Employee, type CreateEmployeeData, type UpdateEmployeeData } from '@/services/employeeService'

export function useEmployees() {
  const employees = ref<Employee[]>([])
  const currentEmployee = ref<Employee | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)
  const total = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)

  const hasEmployees = computed(() => employees.value.length > 0)
  const totalPages = computed(() => Math.ceil(total.value / perPage.value))

  async function fetchEmployees(params?: {
    page?: number
    per_page?: number
    search?: string
    status?: string
    role?: string
    gender?: 'male' | 'female' | 'other'
  }) {
    loading.value = true
    error.value = null

    try {
      const response = await employeeService.getEmployees({
        page: params?.page || currentPage.value,
        per_page: params?.per_page || perPage.value,
        search: params?.search,
        status: params?.status,
        role: params?.role,
        gender: params?.gender
      })

      // Handle response structure - ensure we have the expected format
      if (response && response.data) {
        employees.value = response.data
        total.value = response.total || 0
        currentPage.value = response.current_page || 1
        perPage.value = response.per_page || 10
      } else {
        // Fallback if response structure is different
        employees.value = Array.isArray(response) ? response : []
        total.value = Array.isArray(response) ? response.length : 0
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'Failed to fetch employees'
      employees.value = []
      total.value = 0
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchEmployee(id: number) {
    loading.value = true
    error.value = null

    try {
      currentEmployee.value = await employeeService.getEmployee(id)
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch employee'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createEmployee(data: CreateEmployeeData) {
    loading.value = true
    error.value = null

    try {
      const newEmployee = await employeeService.createEmployee(data)
      employees.value.unshift(newEmployee)
      return newEmployee
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to create employee'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateEmployee(id: number, data: UpdateEmployeeData) {
    loading.value = true
    error.value = null

    try {
      const updatedEmployee = await employeeService.updateEmployee(id, data)
      const index = employees.value.findIndex(emp => emp.id === id)
      if (index !== -1) {
        employees.value[index] = updatedEmployee
      }
      if (currentEmployee.value?.id === id) {
        currentEmployee.value = updatedEmployee
      }
      return updatedEmployee
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to update employee'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function toggleEmployeeStatus(id: number, status: 'active' | 'inactive') {
    loading.value = true
    error.value = null

    try {
      const updatedEmployee = await employeeService.updateEmployeeStatus(id, status)
      const index = employees.value.findIndex(emp => emp.id === id)
      if (index !== -1) {
        employees.value[index] = updatedEmployee
      }
      if (currentEmployee.value?.id === id) {
        currentEmployee.value = updatedEmployee
      }
      return updatedEmployee
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to update employee status'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteEmployee(id: number) {
    loading.value = true
    error.value = null

    try {
      await employeeService.deleteEmployee(id)
      employees.value = employees.value.filter(emp => emp.id !== id)
      if (currentEmployee.value?.id === id) {
        currentEmployee.value = null
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to delete employee'
      throw err
    } finally {
      loading.value = false
    }
  }

  function clearError() {
    error.value = null
  }

  return {
    employees,
    currentEmployee,
    loading,
    error,
    total,
    currentPage,
    perPage,
    hasEmployees,
    totalPages,
    fetchEmployees,
    fetchEmployee,
    createEmployee,
    updateEmployee,
    toggleEmployeeStatus,
    deleteEmployee,
    clearError
  }
}
