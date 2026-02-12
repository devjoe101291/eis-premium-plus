<template>
  <span
    class="badge"
    :class="badgeClass"
  >
    {{ role.charAt(0).toUpperCase() + role.slice(1) }}
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  role: 'admin' | 'employee' | 'student'
}>()

const badgeClass = computed(() => {
  switch (props.role) {
    case 'admin':
      return 'badge-danger'
    case 'employee':
      return 'badge-primary'
    case 'student':
      return 'badge-info'
    default:
      return 'badge-secondary'
  }
})
</script>
