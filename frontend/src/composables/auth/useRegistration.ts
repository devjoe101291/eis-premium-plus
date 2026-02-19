/**
 * useRegistration Composable
 * 
 * A composable that handles user registration functionality.
 * It manages the registration form state, handles form submission, validation, and error handling.
 * 
 * Features:
 * - Form state management (firstName, lastName, email, phone, password, confirmPassword, terms)
 * - Registration form submission with validation
 * - Password strength validation
 * - Password match validation
 * - Error handling and loading states
 * - Success handling with automatic redirection to login
 * 
 * @returns {Object} An object containing form state, loading state, error state, and registration method
 */

import { ref, Ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

type UseRegistrationArgs = {
  firstName: Ref<string>
  lastName: Ref<string>
  email: Ref<string>
  phone: Ref<string>
  password: Ref<string>
  confirmPassword: Ref<string>
  termsAgreed: Ref<boolean>
}

type ValidationErrors = {
  firstName?: string
  lastName?: string
  email?: string
  phone?: string
  password?: string
  confirmPassword?: string
  termsAgreed?: string
}

export function useRegistration({
  firstName,
  lastName,
  email,
  phone,
  password,
  confirmPassword,
  termsAgreed
}: UseRegistrationArgs) {
  const router = useRouter()
  
  const loading = ref(false)
  const error = ref('')
  const success = ref(false)
  const validationErrors = ref<ValidationErrors>({})

  // Validate individual fields
  const validateEmail = (emailValue: string): string => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(emailValue)) {
      return 'Please enter a valid email address'
    }
    return ''
  }

  const validatePassword = (passwordValue: string): string => {
    if (passwordValue.length < 8) {
      return 'Password must be at least 8 characters long'
    }
    return ''
  }

  const validatePhone = (phoneValue: string): string => {
    if (phoneValue && !/^\d{10,}$/i.test(phoneValue.replace(/\D/g, ''))) {
      return 'Please enter a valid phone number'
    }
    return ''
  }

  // Validate entire form
  const validateForm = (): boolean => {
    validationErrors.value = {}
    let isValid = true

    if (!firstName.value.trim()) {
      validationErrors.value.firstName = 'First name is required'
      isValid = false
    }

    if (!lastName.value.trim()) {
      validationErrors.value.lastName = 'Last name is required'
      isValid = false
    }

    if (!email.value.trim()) {
      validationErrors.value.email = 'Email is required'
      isValid = false
    } else {
      const emailError = validateEmail(email.value)
      if (emailError) {
        validationErrors.value.email = emailError
        isValid = false
      }
    }

    if (phone.value) {
      const phoneError = validatePhone(phone.value)
      if (phoneError) {
        validationErrors.value.phone = phoneError
        isValid = false
      }
    }

    if (!password.value) {
      validationErrors.value.password = 'Password is required'
      isValid = false
    } else {
      const passwordError = validatePassword(password.value)
      if (passwordError) {
        validationErrors.value.password = passwordError
        isValid = false
      }
    }

    if (!confirmPassword.value) {
      validationErrors.value.confirmPassword = 'Please confirm your password'
      isValid = false
    } else if (password.value !== confirmPassword.value) {
      validationErrors.value.confirmPassword = 'Passwords do not match'
      isValid = false
    }

    if (!termsAgreed.value) {
      validationErrors.value.termsAgreed = 'You must agree to the Terms & Conditions'
      isValid = false
    }

    return isValid
  }

  const handleRegister = async () => {
    error.value = ''
    success.value = false

    if (!validateForm()) {
      return
    }

    loading.value = true
    try {
      const { data } = await api.post('/register', {
        first_name: firstName.value.trim(),
        last_name: lastName.value.trim(),
        email: email.value.trim(),
        phone: phone.value.trim() || null,
        password: password.value,
        password_confirmation: confirmPassword.value,
        terms_agreed: termsAgreed.value
      })

      success.value = true
      // Redirect to login after 2 seconds
      setTimeout(() => {
        router.push('/login')
      }, 2000)
    } catch (err: any) {
      error.value = err?.response?.data?.message || 'Registration failed. Please try again.'
      console.error('Registration error:', err?.response?.data || err)
      
      // Handle field-specific errors from backend
      if (err?.response?.data?.errors) {
        const backendErrors = err.response.data.errors
        validationErrors.value = {
          firstName: backendErrors.first_name?.[0],
          lastName: backendErrors.last_name?.[0],
          email: backendErrors.email?.[0],
          phone: backendErrors.phone?.[0],
          password: backendErrors.password?.[0]
        }
      }
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    error,
    success,
    validationErrors,
    handleRegister
  }
}
