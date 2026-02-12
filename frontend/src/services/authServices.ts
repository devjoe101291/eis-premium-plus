// src/services/authService.ts
import api from '../config/axios';

export interface LoginResponse {
  token: string;
  user: {
    id: number;
    name: string;
    email: string;
    role?: string;
    status?: string;
  };
}

export const authApi = {
  register: async (formData: FormData) => {
    const response = await api.post('/register', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    return response.data
  },

  login: async (email: string, password: string) => {
    try {
      const response = await api.post('/login', { email, password })
      const { token, user } = response.data

      // Save token in localStorage
      localStorage.setItem('token', token)

      return { token, user }
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  },

  logout: async () => {
    try {
      await api.post('/logout')
      localStorage.removeItem('token')
    } catch (error) {
      console.error('Logout error:', error)
      throw error
    }
  },

  me: async () => {
    try {
      const response = await api.get('/profile')
      return response.data
    } catch (error) {
      console.error('Profile fetch error:', error)
      throw error
    }
  }
}




export const forgotPasswordService = {
  // Step 1: Send OTP
async sendOtp(email: string) {
  const { data } = await api.post(
    '/forgot-password',
    { email },
    {
      headers: {
        'Content-Type': 'application/json'
      }
    }
  )
  return data
},

  // Step 2: Verify OTP
  async verifyOtp(email: string, otp: string) {
    const { data } = await api.post('/verify-otp', { email, otp })
    return data
  },

  // Step 3: Reset Password
  async resetPassword(email: string, otp: string, password: string, password_confirmation: string) {
    const { data } = await api.post('/reset-password', {
      email,
      otp,
      password,
      password_confirmation
    })
    return data
  }
}