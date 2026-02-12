<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    class="w-4 h-4"
    aria-hidden="true"
  >
    <!-- Book cover + pages -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M4.5 6.75c0-1.243 1.007-2.25 2.25-2.25H18a1.5 1.5 0 011.5 1.5v13.5A1.5 1.5 0 0118 21H6.75A2.25 2.25 0 004.5 23.25V6.75z"
    />
    <!-- Spine / inner fold -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M4.5 19.5c0-1.243 1.007-2.25 2.25-2.25H19.5"
    />
    <!-- Page lines -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M8.25 8.25H16.5M8.25 11.25H16.5M8.25 14.25H14.25"
    />
  </svg>
</template>

<script setup lang="ts">
import { defineOptions } from 'vue'

defineOptions({
  inheritAttrs: false
})
</script>
