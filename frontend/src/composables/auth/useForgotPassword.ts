import { ref } from 'vue'
import { forgotPasswordService } from '@/services/authServices'

export function useForgotPassword() {
  const email = ref('')
  const otp = ref('')
  const newPassword = ref('')
  const confirmPassword = ref('')
  const step = ref(1)
  const loading = ref(false)
  const error = ref('')
  const otpEnabled = ref(false)

  const sendOtp = async () => {
     if (!otpEnabled.value) {
    error.value = 'OTP sending is currently disabled'
    return
  }
    loading.value = true
    error.value = ''
    try {
      await forgotPasswordService.sendOtp(email.value)
      step.value = 2
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to send OTP'
    } finally {
      loading.value = false
    }
  }

  const verifyOtp = async () => {
    loading.value = true
    error.value = ''
    try {
      await forgotPasswordService.verifyOtp(email.value, otp.value)
      step.value = 3
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Invalid OTP'
    } finally {
      loading.value = false
    }
  }

  const changePassword = async () => {
    if (newPassword.value !== confirmPassword.value) {
      error.value = 'Passwords do not match'
      return
    }

    loading.value = true
    error.value = ''
    try {
      await forgotPasswordService.resetPassword(
        email.value,
        otp.value,
        newPassword.value,
        confirmPassword.value
      )
      step.value = 4
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to reset password'
    } finally {
      loading.value = false
    }
  }

  return {
    email,
    otp,
    newPassword,
    confirmPassword,
    step,
    loading,
    error,
    sendOtp,
    verifyOtp,
    changePassword,
  }
}
