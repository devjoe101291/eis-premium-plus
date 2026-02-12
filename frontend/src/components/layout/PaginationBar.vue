<!-- src/components/ui/PaginationBar.vue -->
<template>
  <div
    class="flex items-center justify-between px-6 py-4 border-t border-slate-200 dark:border-slate-700"
  >
    <div class="text-sm text-slate-600 dark:text-slate-300">
      Page <span class="font-semibold">{{ page }}</span>
      of <span class="font-semibold">{{ totalPages }}</span>
      <span class="ml-2 text-slate-400">({{ totalItems }} items)</span>
    </div>

    <div class="flex items-center gap-2">
      <button
        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
               disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="page <= 1"
        @click="goPrev"
      >
        Previous
      </button>

      <button
        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
               disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="page >= totalPages"
        @click="goNext"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">

const props = defineProps<{
  page: number
  totalPages: number
  totalItems: number
}>()

const emit = defineEmits<{
  (e: 'update:page', value: number): void
  (e: 'prev'): void
  (e: 'next'): void
}>()

const goPrev = () => {
  if (props.page <= 1) return
  emit('update:page', props.page - 1)
  emit('prev')
}

const goNext = () => {
  if (props.page >= props.totalPages) return
  emit('update:page', props.page + 1)
  emit('next')
}
</script>
