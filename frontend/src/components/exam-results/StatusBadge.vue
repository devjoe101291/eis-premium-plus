<template>
    <span
      class="badge"
      :class="badgeClass"
    >
      {{ status.charAt(0).toUpperCase() + status.slice(1) }}
    </span>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue'
  
  const props = defineProps<{
    status: 'passed' | 'failed' | 'pending'
  }>()
  
  const badgeClass = computed(() => {
    switch (props.status) {
      case 'passed':
        return 'badge-success'
      case 'failed':
        return 'badge-danger'
      case 'pending':
        return 'badge-warning'
      default:
        return 'badge-secondary'
    }
  })
  </script>
  
  