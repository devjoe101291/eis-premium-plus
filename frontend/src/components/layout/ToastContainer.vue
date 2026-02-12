<template>
  <div class="fixed z-50 right-6 top-6 w-[360px] max-w-[calc(100vw-3rem)]">
    <transition-group
      tag="div"
      class="space-y-3"
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-2"
    >
      <Toast
        v-for="toast in toasts"
        :key="toast.id"
        :show="true"
        :type="toast.type"
        :title="toast.title"
        :message="toast.message"
        @close="removeToast(toast.id)"
      />
    </transition-group>
  </div>
</template>

<script setup lang="ts">
import Toast from '@/components/layout/Toast.vue'
import { useToast } from '@/composables/ui/useToast'

const { toasts, removeToast } = useToast()
</script>
