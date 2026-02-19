<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 overflow-y-auto"
    @click.self="$emit('close')"
  >
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-opacity-50" @click="$emit('close')"></div>

      <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Modal Header -->
        <div class="bg-gradient-to-tr from-primary to-secondary dark:from-primary-light/10 dark:to-secondary-light/10 px-4 py-4 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-white">
            {{ isEditMode ? 'Edit Employee' : employee ? 'Employee Details' : 'Add New Employee' }}
          </h3>
        </div>

        <!-- Modal Body -->
        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <!-- Creation/Edit Form -->
              <form v-if="!employee || isEditMode" @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Left Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="label">First Name <span class="text-danger">*</span></label>
                      <input
                        v-model="formData.first_name"
                        type="text"
                        class="input w-full"
                        placeholder="Enter first name"
                        required
                      />
                    </div>

                    <div>
                      <label class="label">Email <span class="text-danger">*</span></label>
                      <input
                        v-model="formData.email"
                        type="email"
                        class="input w-full"
                        placeholder="Enter email address"
                        required
                      />
                    </div>

                    <div>
                      <label class="label">Date of Birth</label>
                      <input
                        v-model="formData.date_of_birth"
                        type="date"
                        class="input w-full"
                        placeholder="mm / dd / yyyy"
                      />
                    </div>

                    <div>
                      <label class="label">Street</label>
                      <input
                        v-model="formData.street"
                        type="text"
                        class="input w-full"
                        placeholder="Enter street address"
                      />
                    </div>

                    <div>
                      <label class="label">State</label>
                      <input
                        v-model="formData.state"
                        type="text"
                        class="input w-full"
                        placeholder="Enter state"
                      />
                    </div>

                    <div>
                      <label class="label">Username <span class="text-danger">*</span></label>
                      <input
                        v-model="formData.username"
                        type="text"
                        class="input w-full"
                        placeholder="Enter username"
                        required
                      />
                    </div>
                  </div>

                  <!-- Right Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="label">Last Name <span class="text-danger">*</span></label>
                      <input
                        v-model="formData.last_name"
                        type="text"
                        class="input w-full"
                        placeholder="Enter last name"
                        required
                      />
                    </div>

                    <div>
                      <label class="label">Phone Number</label>
                      <input
                        v-model="formData.phone"
                        type="tel"
                        class="input w-full"
                        placeholder="Enter phone number"
                      />
                    </div>

                    <div>
                      <label class="label">Gender</label>
                      <select v-model="formData.gender" class="input w-full">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                      </select>
                    </div>

                    <div>
                      <label class="label">City</label>
                      <input
                        v-model="formData.city"
                        type="text"
                        class="input w-full"
                        placeholder="Enter city"
                      />
                    </div>

                    <div>
                      <label class="label">Zipcode</label>
                      <input
                        v-model="formData.zipcode"
                        type="text"
                        class="input w-full"
                        placeholder="Enter zipcode"
                      />
                    </div>

                    <div v-if="!isEditMode">
                      <label class="label">Password <span class="text-danger">*</span></label>
                      <input
                        v-model="formData.password"
                        type="password"
                        class="input w-full"
                        placeholder="Enter password (min. 8 characters)"
                        required
                        minlength="8"
                      />
                    </div>
                    <div v-else>
                      <label class="label">Password</label>
                      <input
                        v-model="formData.password"
                        type="password"
                        class="input w-full"
                        placeholder="Leave blank to keep current password"
                        minlength="8"
                      />
                      <p class="text-xs text-secondary-text/60 dark:text-secondary-dark-text/60 mt-1">
                        Leave blank to keep the current password
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Confirm Password (Full Width) -->
                <div v-if="formData.password">
                  <label class="label">Confirm Password <span class="text-danger">*</span></label>
                  <input
                    v-model="formData.password_confirmation"
                    type="password"
                    class="input w-full"
                    placeholder="Confirm password"
                    :required="!!formData.password"
                    minlength="8"
                  />
                </div>
              </form>

              <!-- View Details - Same layout as edit form but disabled -->
              <form v-else-if="employee && !isEditMode" class="space-y-6">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Left Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="label">First Name <span class="text-danger">*</span></label>
                      <input
                        :value="employee.first_name || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter first name"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Email <span class="text-danger">*</span></label>
                      <input
                        :value="employee.email || ''"
                        type="email"
                        class="input w-full"
                        placeholder="Enter email address"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Date of Birth</label>
                      <input
                        :value="employee.date_of_birth || ''"
                        type="date"
                        class="input w-full"
                        placeholder="mm / dd / yyyy"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Street</label>
                      <input
                        :value="employee.street || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter street address"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">State</label>
                      <input
                        :value="employee.state || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter state"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Username <span class="text-danger">*</span></label>
                      <input
                        :value="employee.username || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter username"
                        disabled
                      />
                    </div>
                  </div>

                  <!-- Right Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="label">Last Name <span class="text-danger">*</span></label>
                      <input
                        :value="employee.last_name || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter last name"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Phone Number</label>
                      <input
                        :value="employee.phone || ''"
                        type="tel"
                        class="input w-full"
                        placeholder="Enter phone number"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Gender</label>
                      <select :value="employee.gender || ''" class="input w-full" disabled>
                        <option value="">{{ employee.gender || 'Select Gender' }}</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                      </select>
                    </div>

                    <div>
                      <label class="label">City</label>
                      <input
                        :value="employee.city || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter city"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Zipcode</label>
                      <input
                        :value="employee.zipcode || ''"
                        type="text"
                        class="input w-full"
                        placeholder="Enter zipcode"
                        disabled
                      />
                    </div>

                    <div>
                      <label class="label">Password</label>
                      <input
                        type="password"
                        class="input w-full"
                        placeholder="••••••••"
                        value="••••••••"
                        disabled
                      />
                      <p class="text-xs text-secondary-text/60 dark:text-secondary-dark-text/60 mt-1">
                        Password is hidden for security
                      </p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            v-if="!employee || isEditMode"
            type="submit"
            @click="handleSubmit"
            :disabled="loading"
            class="btn btn-primary w-full sm:w-auto sm:ml-3"
          >
            {{ loading ? (isEditMode ? 'Updating...' : 'Creating...') : (isEditMode ? 'Update Employee' : 'Create Employee') }}
          </button>
          <button
            v-if="employee && !isEditMode"
            type="button"
            @click="startEdit"
            class="btn btn-info w-full sm:w-auto sm:ml-3"
          >
            Edit
          </button>
          <button
            type="button"
            @click="handleClose"
            class="btn btn-secondary w-full sm:w-auto sm:ml-3"
          >
            {{ isEditMode ? 'Cancel' : 'Close' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import type { Employee, CreateEmployeeData, UpdateEmployeeData } from '@/services/employeeService'
import StatusBadge from './StatusBadge.vue'
import RoleBadge from './RoleBadge.vue'

const props = defineProps<{
  isOpen: boolean
  employee: Employee | null
  editMode?: boolean
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', data: CreateEmployeeData & { password_confirmation?: string }): void
  (e: 'update', id: number, data: any): void
  (e: 'edit'): void
}>()

// Form data for creating new employee
const formData = reactive<CreateEmployeeData & { password_confirmation?: string }>({
  first_name: '',
  last_name: '',
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'employee',
  status: 'active',
  phone: '',
  date_of_birth: '',
  gender: undefined,
  street: '',
  city: '',
  state: '',
  zipcode: ''
})

const loading = ref(false)
const isEditMode = ref(false)

// Populate form with employee data
const populateFormFromEmployee = (emp: Employee) => {
  formData.first_name = emp.first_name || ''
  formData.last_name = emp.last_name || ''
  formData.username = emp.username || ''
  formData.email = emp.email || ''
  formData.phone = emp.phone || ''
  formData.date_of_birth = emp.date_of_birth || ''
  formData.gender = emp.gender
  formData.street = emp.street || ''
  formData.city = emp.city || ''
  formData.state = emp.state || ''
  formData.zipcode = emp.zipcode || ''
  formData.role = emp.role || 'employee'
  formData.status = emp.status || 'active'
  // Don't populate password fields
  formData.password = ''
  formData.password_confirmation = ''
}

// Reset form to initial state
const resetForm = () => {
  formData.first_name = ''
  formData.last_name = ''
  formData.username = ''
  formData.email = ''
  formData.password = ''
  formData.password_confirmation = ''
  formData.role = 'employee'
  formData.status = 'active'
  formData.phone = ''
  formData.date_of_birth = ''
  formData.gender = undefined
  formData.street = ''
  formData.city = ''
  formData.state = ''
  formData.zipcode = ''
}

// Watch for modal open/close and editMode prop
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    if (props.editMode && props.employee) {
      isEditMode.value = true
      populateFormFromEmployee(props.employee)
    } else if (props.employee && !props.editMode) {
      isEditMode.value = false
    } else if (!props.employee) {
      isEditMode.value = false
      resetForm()
    }
  } else {
    isEditMode.value = false
    resetForm()
  }
}, { immediate: true })

// Watch for employee changes and populate form when editing
watch(() => props.employee, (newEmployee) => {
  if (newEmployee && isEditMode.value) {
    populateFormFromEmployee(newEmployee)
  } else if (!newEmployee) {
    resetForm()
    isEditMode.value = false
  }
}, { immediate: true })

// Watch for editMode prop changes
watch(() => props.editMode, (editMode) => {
  if (editMode && props.employee) {
    isEditMode.value = true
    populateFormFromEmployee(props.employee)
  } else if (!editMode && props.employee) {
    isEditMode.value = false
  }
})

// Start edit mode
const startEdit = () => {
  if (props.employee) {
    isEditMode.value = true
    populateFormFromEmployee(props.employee)
    emit('edit')
  }
}

// Handle close
const handleClose = () => {
  if (isEditMode.value) {
    isEditMode.value = false
    if (props.employee) {
      // Reset form but don't close modal - go back to view mode
      populateFormFromEmployee(props.employee)
    }
  } else {
    resetForm()
    emit('close')
  }
}

// Handle form submission
const handleSubmit = async () => {
  if (props.employee && isEditMode.value) {
    // Update mode
    try {
      loading.value = true
      const updateData: UpdateEmployeeData = {
        first_name: formData.first_name,
        last_name: formData.last_name,
        username: formData.username,
        email: formData.email,
        phone: formData.phone || undefined,
        date_of_birth: formData.date_of_birth || undefined,
        gender: formData.gender,
        street: formData.street || undefined,
        city: formData.city || undefined,
        state: formData.state || undefined,
        zipcode: formData.zipcode || undefined,
        role: formData.role,
        status: formData.status
      }
      
      // Only include password if it's provided
      if (formData.password) {
        (updateData as any).password = formData.password
        if (formData.password_confirmation) {
          (updateData as any).password_confirmation = formData.password_confirmation
        }
      }
      
      emit('update', props.employee.id, updateData)
    } catch (error) {
      console.error('Error updating employee:', error)
    } finally {
      loading.value = false
    }
  } else if (!props.employee) {
    // Create mode
    try {
      loading.value = true
      emit('save', { ...formData })
    } catch (error) {
      console.error('Error creating employee:', error)
    } finally {
      loading.value = false
    }
  }
}
</script>
