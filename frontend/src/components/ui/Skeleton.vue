<template>
  <!-- Single skeleton block -->
  <div
    v-if="variant === 'block'"
    :class="[
      'animate-pulse rounded-xl',
      baseBg,
      wClass,
      hClass,
      className,
    ]"
    aria-busy="true"
    aria-live="polite"
  />

  <!-- Text lines skeleton -->
  <div v-else-if="variant === 'text'" :class="['space-y-2', className]" aria-busy="true">
    <div
      v-for="i in lines"
      :key="i"
      :class="[
        'animate-pulse rounded-lg',
        baseBg,
        'h-3',
        lineWidthClass(i),
      ]"
    />
  </div>

  <!-- Card skeleton -->
  <div
    v-else-if="variant === 'card'"
    :class="[
      'rounded-2xl border border-slate-200/60 dark:border-slate-700/60',
      'bg-white/70 dark:bg-slate-900/20 backdrop-blur-sm',
      'p-5 shadow-sm',
      className,
    ]"
    aria-busy="true"
  >
    <div class="flex items-start gap-4">
      <div v-if="avatar" :class="['animate-pulse rounded-xl', baseBg, 'h-12 w-12 shrink-0']" />
      <div class="flex-1 space-y-3">
        <div :class="['animate-pulse rounded-lg', baseBg, 'h-4 w-1/2']" />
        <div :class="['animate-pulse rounded-lg', baseBg, 'h-3 w-2/3']" />
        <div :class="['animate-pulse rounded-lg', baseBg, 'h-3 w-3/4']" />
      </div>
    </div>

    <div class="mt-5 grid grid-cols-2 gap-3" v-if="stats">
      <div :class="['animate-pulse rounded-xl', baseBg, 'h-10']" />
      <div :class="['animate-pulse rounded-xl', baseBg, 'h-10']" />
    </div>

    <div class="mt-5 space-y-2" v-if="extraLines > 0">
      <div
        v-for="i in extraLines"
        :key="i"
        :class="['animate-pulse rounded-lg', baseBg, 'h-3', i === extraLines ? 'w-2/3' : 'w-full']"
      />
    </div>
  </div>

  <!-- Table skeleton -->
  <div
    v-else-if="variant === 'table'"
    :class="[
      'rounded-2xl border border-slate-200/60 dark:border-slate-700/60',
      'bg-white/70 dark:bg-slate-900/20 backdrop-blur-sm',
      'overflow-hidden',
      className,
    ]"
    aria-busy="true"
  >
    <div class="p-4 border-b border-slate-200/60 dark:border-slate-700/60">
      <div :class="['animate-pulse rounded-lg', baseBg, 'h-4 w-1/3']" />
    </div>

    <div class="p-4 space-y-3">
      <div v-for="r in rows" :key="r" class="grid grid-cols-12 gap-3 items-center">
        <div
          v-for="c in cols"
          :key="c"
          :class="[
            'animate-pulse rounded-lg',
            baseBg,
            'h-3',
            colWidthClass(c),
          ]"
        />
      </div>
    </div>
  </div>

  <!-- Page skeleton (layout skeleton) -->
  <div v-else-if="variant === 'page'" :class="['space-y-6', className]" aria-busy="true">
    <!-- header -->
    <div class="flex items-center justify-between gap-4">
      <div class="space-y-2 w-full">
        <div :class="['animate-pulse rounded-xl', baseBg, 'h-7 w-1/3']" />
        <div :class="['animate-pulse rounded-lg', baseBg, 'h-4 w-1/2']" />
      </div>
      <div class="flex gap-2 shrink-0">
        <div :class="['animate-pulse rounded-xl', baseBg, 'h-10 w-28']" />
        <div :class="['animate-pulse rounded-xl', baseBg, 'h-10 w-28']" />
      </div>
    </div>

    <!-- stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div v-for="i in 3" :key="i" :class="['animate-pulse rounded-2xl', baseBg, 'h-24']" />
    </div>

    <!-- main card/table -->
    <div :class="['animate-pulse rounded-2xl', baseBg, 'h-72']" />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

type Variant = 'block' | 'text' | 'card' | 'table' | 'page'

const props = withDefaults(
  defineProps<{
    variant?: Variant

    /** For block */
    w?: string
    h?: string

    /** For text */
    lines?: number

    /** For card */
    avatar?: boolean
    stats?: boolean
    extraLines?: number

    /** For table */
    rows?: number
    cols?: number

    /** Extra classes */
    className?: string
  }>(),
  {
    variant: 'block',
    w: 'w-full',
    h: 'h-4',
    lines: 3,
    avatar: false,
    stats: false,
    extraLines: 0,
    rows: 6,
    cols: 6,
    className: '',
  }
)

const baseBg = computed(() => 'bg-slate-200/80 dark:bg-slate-700/50')

const wClass = computed(() => props.w || 'w-full')
const hClass = computed(() => props.h || 'h-4')

const lineWidthClass = (i: number) => {
  if (i === props.lines) return 'w-2/3'
  if (i % 3 === 0) return 'w-3/4'
  return 'w-full'
}

const colWidthClass = (c: number) => {
  // Make columns look “natural”
  if (c === 1) return 'col-span-3'
  if (c === 2) return 'col-span-2'
  if (c === props.cols) return 'col-span-1'
  return 'col-span-2'
}
</script>
