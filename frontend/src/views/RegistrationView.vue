<template>
  <div class="relative min-h-screen w-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="w-full min-h-screen flex bg-white dark:bg-gray-800">
      <!-- Left Branding / Logo Section -->
      <div
        class="hidden md:flex w-1/2 bg-gradient-to-tr from-primary to-secondary dark:from-primary-light/10 dark:to-secondary-light/10 items-center justify-center p-8"
      >
        <div class="text-center text-white space-y-6">
          <img src="images/main-logo1.png" alt="App Logo" class="mx-auto h-24 w-[18rem] object-contain drop-shadow-md" />
          <h2 class="text-3xl font-bold">{{ appTitle }}</h2>
          <p class="text-lg opacity-90">Create your account to get started.</p>
        </div>
      </div>

      <!-- Right Form Section -->
      <div class="flex flex-col justify-center w-full md:w-1/2 p-8 sm:p-10 lg:p-12 relative overflow-y-auto max-h-screen">
        <!-- Dark/Light Mode Toggle -->
        <button
          @click="themeStore.toggleTheme()"
          class="absolute top-6 right-6 p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
          aria-label="Toggle dark mode"
        >
          <!-- Sun Icon -->
          <svg
            v-if="isDarkMode"
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
            />
          </svg>

          <!-- Moon Icon -->
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
            />
          </svg>
        </button>

        <!-- Success Message -->
        <div
          v-if="success"
          class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg"
        >
          <p class="text-green-800 dark:text-green-200 font-medium">Account created successfully! Redirecting to login...</p>
        </div>

        <!-- Error Message -->
        <div
          v-if="error"
          class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg"
        >
          <p class="text-red-800 dark:text-red-200 font-medium">{{ error }}</p>
        </div>

        <!-- Registration Form -->
        <form class="space-y-4" @submit.prevent="handleSubmit">
          <!-- First Name & Last Name (Two Column) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
              <label for="firstName" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                First Name <span class="text-red-500">*</span>
              </label>
              <input
                id="firstName"
                v-model="firstName"
                type="text"
                placeholder="John"
                required
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.firstName" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.firstName }}
              </p>
            </div>

            <!-- Last Name -->
            <div>
              <label for="lastName" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Last Name <span class="text-red-500">*</span>
              </label>
              <input
                id="lastName"
                v-model="lastName"
                type="text"
                placeholder="Doe"
                required
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.lastName" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.lastName }}
              </p>
            </div>
          </div>

          <!-- Email & Username (Two Column) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Email -->
            <div>
              <label for="email" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Email <span class="text-red-500">*</span>
              </label>
              <input
                id="email"
                v-model="email"
                type="email"
                placeholder="john@example.com"
                required
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.email" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.email }}
              </p>
            </div>

            <!-- Username -->
            <div>
              <label for="username" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Username <span class="text-red-500">*</span>
              </label>
              <input
                id="username"
                v-model="username"
                type="text"
                placeholder="johndoe"
                required
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.username" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.username }}
              </p>
            </div>
          </div>

          <!-- Phone & Date of Birth (Two Column) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Phone -->
            <div>
              <label for="phone" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Phone Number
              </label>
              <input
                id="phone"
                v-model="phone"
                type="tel"
                placeholder="+1 (555) 123-4567"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.phone" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.phone }}
              </p>
            </div>

            <!-- Date of Birth -->
            <div>
              <label for="dateOfBirth" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Date of Birth
              </label>
              <input
                id="dateOfBirth"
                v-model="dateOfBirth"
                type="date"
                placeholder="mm/dd/yyyy"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.dateOfBirth" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.dateOfBirth }}
              </p>
            </div>
          </div>

          <!-- Gender (Full Width) -->
          <div>
            <label for="gender" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
              Gender
            </label>
            <select
              id="gender"
              v-model="gender"
              class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
            >
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <p v-if="validationErrors.gender" class="mt-1 text-sm text-red-500 dark:text-red-400">
              {{ validationErrors.gender }}
            </p>
          </div>

          <!-- Street (Full Width) -->
          <div>
            <label for="street" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
              Street Address
            </label>
            <input
              id="street"
              v-model="street"
              type="text"
              placeholder="123 Main Street"
              class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
            />
            <p v-if="validationErrors.street" class="mt-1 text-sm text-red-500 dark:text-red-400">
              {{ validationErrors.street }}
            </p>
          </div>

          <!-- City, State & Zipcode (Three Column) -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- City -->
            <div>
              <label for="city" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                City
              </label>
              <input
                id="city"
                v-model="city"
                type="text"
                placeholder="New York"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.city" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.city }}
              </p>
            </div>

            <!-- State -->
            <div>
              <label for="state" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                State
              </label>
              <input
                id="state"
                v-model="state"
                type="text"
                placeholder="NY"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.state" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.state }}
              </p>
            </div>

            <!-- Zipcode -->
            <div>
              <label for="zipcode" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Zipcode
              </label>
              <input
                id="zipcode"
                v-model="zipcode"
                type="text"
                placeholder="10001"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.zipcode" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.zipcode }}
              </p>
            </div>
          </div>

          <!-- Password & Confirm Password (Two Column) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
              <label for="password" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Password <span class="text-red-500">*</span>
              </label>
              <input
                id="password"
                v-model="password"
                type="password"
                placeholder="••••••••"
                required
                minlength="8"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.password" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.password }}
              </p>
            </div>

            <!-- Confirm Password -->
            <div>
              <label for="confirmPassword" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                Confirm Password <span class="text-red-500">*</span>
              </label>
              <input
                id="confirmPassword"
                v-model="confirmPassword"
                type="password"
                placeholder="••••••••"
                required
                minlength="8"
                class="block w-full rounded-md border border-gray-300 p-3 bg-gray-50 text-gray-900 sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-dark dark:focus:border-primary-dark"
              />
              <p v-if="validationErrors.confirmPassword" class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ validationErrors.confirmPassword }}
              </p>
            </div>
          </div>

          <!-- Submit Button (DISABLED) -->
          <button
            type="submit"
            disabled
            class="w-full py-3 bg-gradient-to-tr from-primary to-secondary text-white font-semibold rounded-lg shadow-md transition opacity-50 cursor-not-allowed"
          >
            Create Account
          </button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-sm text-center text-gray-600 dark:text-gray-400">
          Already have an account?
          <a
            href="/portal/login"
            class="font-medium text-primary hover:underline dark:text-primary-dark dark:hover:underline"
          >Login</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useRegistration } from '@/composables/auth/useRegistration'
import { useThemeStore } from '@/stores/theme'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')

// Router
const router = useRouter()

// Theme Store
const themeStore = useThemeStore()
const isDarkMode = computed(() => themeStore.isDarkMode)

// Form Fields - Individual Refs (All employee fields)
const firstName = ref('')
const lastName = ref('')
const email = ref('')
const username = ref('')
const phone = ref('')
const dateOfBirth = ref('')
const gender = ref('')
const street = ref('')
const city = ref('')
const state = ref('')
const zipcode = ref('')
const password = ref('')
const confirmPassword = ref('')
const termsAgreed = ref(true) // Auto-agree to terms (checkbox removed from UI)

// Registration Composable - Pass refs as parameter
const {
  loading,
  error,
  success,
  validationErrors,
  handleRegister
} = useRegistration({
  firstName,
  lastName,
  email,
  phone,
  password,
  confirmPassword,
  termsAgreed
})

// Local validation helpers (used by local validateForm)
const validateEmailFormat = (emailValue: string): boolean => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(emailValue)
}

const validatePasswordLength = (passwordValue: string): boolean => {
  return passwordValue.length >= 8
}

const validatePasswordMatch = (pass: string, confirmPass: string): boolean => {
  return pass === confirmPass
}

const validatePhoneFormat = (phoneValue: string): boolean => {
  if (!phoneValue.trim()) return true // Phone is optional
  const phoneRegex = /^[+]?[(]?[0-9]{3}[)]?[-\s.]*[0-9]{3}[-\s.]*[0-9]{4,6}$/
  return phoneRegex.test(phoneValue)
}

// Local Form Validation
const validateForm = (): boolean => {
  // Reset errors
  validationErrors.value = {}

  let isValid = true

  // First Name validation
  if (!firstName.value.trim()) {
    validationErrors.value.firstName = 'First name is required'
    isValid = false
  }

  // Last Name validation
  if (!lastName.value.trim()) {
    validationErrors.value.lastName = 'Last name is required'
    isValid = false
  }

  // Username validation
  if (!username.value.trim()) {
    validationErrors.value.username = 'Username is required'
    isValid = false
  }

  // Email validation
  if (!email.value.trim()) {
    validationErrors.value.email = 'Email is required'
    isValid = false
  } else if (!validateEmailFormat(email.value)) {
    validationErrors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  // Phone validation (optional)
  if (phone.value.trim() && !validatePhoneFormat(phone.value)) {
    validationErrors.value.phone = 'Please enter a valid phone number'
    isValid = false
  }

  // Password validation
  if (!password.value) {
    validationErrors.value.password = 'Password is required'
    isValid = false
  } else if (!validatePasswordLength(password.value)) {
    validationErrors.value.password = 'Password must be at least 8 characters'
    isValid = false
  }

  // Confirm Password validation
  if (!confirmPassword.value) {
    validationErrors.value.confirmPassword = 'Please confirm your password'
    isValid = false
  } else if (!validatePasswordMatch(password.value, confirmPassword.value)) {
    validationErrors.value.confirmPassword = 'Passwords do not match'
    isValid = false
  }

  return isValid
}

// Form Submission (disabled but keeping logic)
const handleSubmit = async () => {
  error.value = ''
  success.value = false

  if (!validateForm()) {
    return
  }

  try {
    await handleRegister()

    success.value = true
    
    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } catch (err: any) {
    error.value = err?.message || 'An error occurred during registration. Please try again.'
  }
}

// Cleanup on mount
onMounted(() => {
  success.value = false
  error.value = ''
})
</script>