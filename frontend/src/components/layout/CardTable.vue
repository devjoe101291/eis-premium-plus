<template>
  <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
              rounded-2xl shadow-lg border border-slate-200/50
              dark:border-slate-700/50 overflow-hidden">
    
    <!-- Header -->
    <!-- <div v-if="title || $slots.toolbar" class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4"> -->
      <!-- <h3 v-if="title" class="text-lg font-semibold text-slate-800 dark:text-white">{{ title }}</h3> -->
      <!-- <div class="flex items-center gap-2">
        <slot name="toolbar" />
      </div> -->
    <!-- </div> -->

    <!-- Table Body -->
    <div class="overflow-x-auto">
      <slot />
    </div>

    <!-- Footer / Pagination -->
    <div v-if="$slots.footer" class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup lang="ts">
// Props
defineProps<{
  title?: string
}>()
</script>
