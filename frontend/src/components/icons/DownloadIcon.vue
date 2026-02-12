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
    <!-- Arrow -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M12 3v12m0 0l4-4m-4 4l-4-4"
    />
    <!-- Tray -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M4.5 15.75v2.25A2.25 2.25 0 006.75 20.25h10.5A2.25 2.25 0 0019.5 18v-2.25"
    />
  </svg>
</template>

<script setup lang="ts">
import { defineOptions } from 'vue'
defineOptions({ inheritAttrs: false })
</script>
