<template>
  <div
    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
  >
    <!-- Search -->
    <input
      type="text"
      :value="search"
      @input="$emit('update:search', $event.target.value)"
      placeholder="Search by title..."
      class="w-full sm:w-[72%] px-4 py-2 rounded-lg
             border border-slate-300 dark:border-slate-600
             focus:outline-none focus:ring-2 focus:ring-blue-400
             dark:bg-slate-700 dark:text-slate-200"
    />

    <!-- Status Filter -->
    <select
      :value="status"
      @change="$emit('update:status', $event.target.value)"
      class="w-full sm:w-52 px-4 py-2 rounded-lg
             border border-slate-300 dark:border-slate-600
             bg-white dark:bg-slate-700
             text-slate-800 dark:text-slate-200
             focus:outline-none focus:ring-2 focus:ring-blue-400"
    >
      <option value="all">Total</option>
      <option value="active">Active</option>
      <option value="inactive">Inactive</option>
    </select>

    <!-- Action Slot -->
    <slot name="action" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'TableControls',

  props: {
    search: {
      type: String,
      required: true,
    },
    status: {
      type: String as () => 'all' | 'active' | 'inactive',
      required: true,
    },
  },

  emits: ['update:search', 'update:status'],
})
</script>
