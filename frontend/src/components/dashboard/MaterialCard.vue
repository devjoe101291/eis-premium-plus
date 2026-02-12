<template>
  <div class="card transform hover:-translate-y-1 transition-all duration-300 ease-out group">
    <div class="card-body">
      <div class="flex items-start justify-between">
        <div class="flex-1">
          <div class="flex items-center space-x-3">
            <div
              class="flex-shrink-0 rounded-xl p-2.5 bg-gradient-to-br shadow-sm"
              :class="iconBgClass"
            >
              <component :is="materialIcon" class="h-5 w-5 text-white" />
            </div>
            <div class="flex-1">
              <h3 class="text-base font-bold text-primary-text dark:text-primary-dark-text">
                {{ material.title }}
              </h3>
              <p
                v-if="material.description"
                class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-1.5 line-clamp-2"
              >
                {{ material.description }}
              </p>
            </div>
          </div>
          <div class="mt-3 flex items-center space-x-4">
            <span
              v-if="material.file_size"
              class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70"
            >
              {{ formatFileSize(material.file_size) }}
            </span>
            <span
              v-if="material.file_type"
              class="badge badge-info text-xs"
            >
              {{ material.file_type }}
            </span>
          </div>
        </div>
      </div>
      <div class="mt-4 flex space-x-2">
        <button
          v-if="material.video_link"
          @click="$emit('view-video')"
          class="btn btn-secondary flex-1"
        >
          View Video
        </button>
        <button
          v-if="material.file_path"
          @click="$emit('download')"
          class="btn btn-primary flex-1"
        >
          Download
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, h } from 'vue'
import type { Material } from '@/services/examService'

const props = defineProps<{
  material: Material
}>()

defineEmits<{
  (e: 'view-video'): void
  (e: 'download'): void
}>()

const materialIcon = computed(() => {
  const iconPath = (d: string) => h('svg', {
    class: 'h-5 w-5',
    xmlns: 'http://www.w3.org/2000/svg',
    viewBox: '0 0 20 20',
    fill: 'currentColor'
  }, [
    h('path', { 'fill-rule': 'evenodd', d, 'clip-rule': 'evenodd' })
  ])

  switch (props.material.file_type) {
    case 'image':
      return iconPath('M4 3a2 2 0 100 4h12a2 2 0 100-4H4zm11 4H3a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2z')
    case 'document':
      return iconPath('M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z')
    case 'video':
      return iconPath('M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z')
    default:
      return iconPath('M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z')
  }
})

const iconBgClass = computed(() => {
  switch (props.material.file_type) {
    case 'image':
      return 'from-info to-info-hover dark:from-info-dark dark:to-info-dark-hover'
    case 'document':
      return 'from-primary to-primary-hover dark:from-primary-dark dark:to-primary-dark-hover'
    case 'video':
      return 'from-danger to-danger-hover dark:from-danger-dark dark:to-danger-dark-hover'
    default:
      return 'from-secondary to-secondary-hover dark:from-secondary-dark dark:to-secondary-dark-hover'
  }
})

const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}
</script>
