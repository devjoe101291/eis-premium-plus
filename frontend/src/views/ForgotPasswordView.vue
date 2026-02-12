<template>
  <div class="min-h-screen w-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">

<div class="w-full min-h-screen flex bg-white dark:bg-gray-800">

      <!-- Left Branding -->
      <div
        class="hidden md:flex w-1/2 bg-gradient-to-tr from-primary to-secondary-hover/100 items-center justify-center p-8"
      >
        <div class="text-center text-white space-y-6">
          <img
            src="images/main-logo1.PNG"
            alt="App Logo"
            class="mx-auto h-20 w-[18rem] object-contain"
          />
          <h2 class="text-3xl font-bold">{{ appTitle }}</h2>
          <p class="text-lg opacity-90">Reset your password securely</p>
        </div>
      </div>

      <!-- Right Form Section -->
      <div class="w-full md:w-1/2 p-8 sm:p-10 lg:p-12 flex-col flex justify-center">
        <h2
          v-if="step !== 4"
          class="text-2xl sm:text-3xl font-extrabold text-primary dark:text-primary-dark-text mb-6"
        >
          Forgot Password
        </h2>

        <!-- Step 1 -->
        <form v-if="step === 1" class="space-y-6" @submit.prevent="sendOtp">
          <div>
            <label for="email" class="label">Email address</label>
            <input
              id="email"
              type="email"
              v-model="email"
              required
              class="input w-full"
              placeholder="Enter your email address"
            />
          </div>

<button
  type="submit"
  :disabled="!otpEnabled || loading"
  class="
    w-full py-3 font-semibold rounded-lg shadow-md transition
    text-white
    bg-gradient-to-br from-primary to-primary-hover/50
    hover:opacity-90
    disabled:from-primary/40 disabled:to-primary/30
    disabled:opacity-70 disabled:cursor-not-allowed
    disabled:shadow-none
  "
>
  {{ loading ? 'Sending OTP...' : 'Send OTP' }}
</button>

          <a
            class="flex justify-center hover:underline font-medium text-primary hover:text-secondary"
            href="/portal/login"
            >Back to login</a
          >
        </form>

        <!-- Step 2 -->
        <form v-else-if="step === 2" class="space-y-6" @submit.prevent="verifyOtp">
          <div>
            <label for="otp" class="label">Enter OTP</label>
            <input
              id="otp"
              type="text"
              v-model="otp"
              required
              class="input w-full"
              placeholder="6-digit code"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 bg-gradient-to-br from-primary to-primary-hover/50 hover:bg-secondary hover:opacity-90 text-white font-semibold rounded-lg shadow-md transition"
          >
            {{ loading ? 'Verifying OTP...' : 'Verify OTP' }}
          </button>
        </form>

        <!-- Step 3 -->
        <form v-else-if="step === 3" class="space-y-6" @submit.prevent="changePassword">
          <div>
            <label for="new-password" class="label">New Password</label>
            <input
              id="new-password"
              type="password"
              v-model="newPassword"
              required
              class="input w-full"
              placeholder="••••••••"
            />
          </div>
          <div>
            <label for="confirm-password" class="label">Confirm Password</label>
            <input
              id="confirm-password"
              type="password"
              v-model="confirmPassword"
              required
              class="input w-full"
              placeholder="••••••••"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 bg-gradient-to-r from-primary to-primary-hover/50 hover:bg-secondary hover:opacity-90 text-white font-semibold rounded-lg shadow-md transition"
          >
            {{ loading ? 'Resetting...' : 'Reset Password' }}
          </button>
        </form>

        <!-- Step 4 -->
        <div
          v-else
          class="flex flex-col items-center justify-center text-center h-full space-y-2"
        >
          <h3 class="text-xl font-semibold text-green-600">
            Password Reset Successful
          </h3>
          <p class="text-gray-600 dark:text-gray-400">
            You can now
            <a href="/portal/login" class="text-primary-text hover:underline">log in</a>
            with your new password.
          </p>
        </div>

        <!-- Error -->
        <div
          v-if="error"
          class="rounded-md bg-danger-bg dark:bg-danger-dark-bg p-3 sm:p-4 mt-4"
        >
          <div class="flex">
            <svg
              class="h-5 w-5 text-danger dark:text-danger-dark flex-shrink-0"
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 
                   7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 
                   1.293a1 1 0 101.414 1.414L10 11.414l1.293 
                   1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 
                   1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
            </svg>
            <p class="ml-3 text-sm text-danger-text dark:text-danger-dark-text">
              {{ error }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useTitle } from '@/composables/ui/useTitle'
import { useForgotPassword } from '@/composables/auth/useForgotPassword'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Forgot Password`)

const {
  email,
  otp,
  newPassword,
  confirmPassword,
  step,
  loading,
  // otpEnabled,
  error,
  sendOtp,
  verifyOtp,
  changePassword
} = useForgotPassword()
</script>