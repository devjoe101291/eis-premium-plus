<template>
  <div
    class="flex items-center justify-between
           bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
           rounded-2xl shadow-lg border border-slate-200/50
           dark:border-slate-700/50 p-5"
  >
    <!-- Text -->
    <div>
      <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
        {{ label }}
      </p>
      <p class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">
        {{ value }}
      </p>
    </div>

    <!-- Icon -->
    <div class="flex items-center justify-center w-12 h-12 rounded-xl" :class="color">
      <!-- If icon is a component -->
      <component v-if="isComponent" :is="icon" class="w-6 h-6" />

      <!-- If icon is a sprite string like '#icon-book' -->
      <svg v-else class="w-6 h-6" aria-hidden="true">
        <use :href="icon" />
      </svg>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { PropType, Component } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  value: { type: [Number, String], required: true },
  icon: { type: [String, Object] as PropType<string | Component>, required: true },
  color: { type: String, required: true },
})

const isComponent = computed(() => typeof props.icon !== 'string')
</script>
