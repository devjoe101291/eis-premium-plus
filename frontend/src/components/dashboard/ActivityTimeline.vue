<template>
  <div class="flow-root">
    <ul role="list" class="-mb-8">
      <li
        v-for="(activity, index) in activities"
        :key="index"
        class="transform transition-all duration-300 hover:-translate-x-1"
      >
        <div class="relative pb-8">
          <span
            v-if="index !== activities.length - 1"
            class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-secondary-bg dark:bg-secondary-dark-bg"
            aria-hidden="true"
          ></span>
          <div class="relative flex space-x-3">
            <div>
              <span
                class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800 shadow-lg"
                :class="activityIconBg(activity.type)"
              >
                <component :is="activityIcon(activity.type)" class="h-5 w-5 text-white" />
              </span>
            </div>
            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
              <div>
                <p class="text-sm text-secondary-text dark:text-secondary-dark-text">
                  {{ activity.description }}
                </p>
                <p
                  v-if="activity.details"
                  class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70 mt-1"
                >
                  {{ activity.details }}
                </p>
              </div>
              <div class="text-right text-sm whitespace-nowrap text-secondary-text dark:text-secondary-dark-text">
                <time :datetime="activity.datetime">{{ activity.time }}</time>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <div
      v-if="activities.length === 0"
      class="text-center py-8 text-secondary-text dark:text-secondary-dark-text"
    >
      <p>No recent activity</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { h } from 'vue'

interface Activity {
  type: 'exam' | 'certificate' | 'material' | 'profile'
  description: string
  details?: string
  time: string
  datetime: string
}

const props = defineProps<{
  activities: Activity[]
}>()

const activityIcon = (type: string) => {
  const iconPath = (d: string) => h('svg', {
    class: 'h-5 w-5',
    xmlns: 'http://www.w3.org/2000/svg',
    viewBox: '0 0 20 20',
    fill: 'currentColor'
  }, [
    h('path', { 'fill-rule': 'evenodd', d, 'clip-rule': 'evenodd' })
  ])

  switch (type) {
    case 'exam':
      return iconPath('M9 2a1 1 0 000 2h2a1 1 0 100-2H9z M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z')
    case 'certificate':
      return iconPath('M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z')
    case 'material':
      return iconPath('M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z')
    default:
      return iconPath('M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z')
  }
}

const activityIconBg = (type: string): string => {
  switch (type) {
    case 'exam':
      return 'bg-primary dark:bg-primary-dark'
    case 'certificate':
      return 'bg-success dark:bg-success-dark'
    case 'material':
      return 'bg-info dark:bg-info-dark'
    default:
      return 'bg-secondary dark:bg-secondary-dark'
  }
}
</script>
