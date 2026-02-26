<template>
    <div class="p-4 hover:bg-gray-50/80 dark:hover:bg-gray-800/30 transition-all duration-300">
      <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-2">
            <span class="px-2 py-1 text-xs font-medium bg-primary/10 dark:bg-primary-dark/10 text-primary dark:text-primary-dark rounded">
              {{ examResult.employee_username }}
            </span>
          </div>
          
          <h3 class="text-base font-semibold text-primary-text dark:text-primary-dark-text">
            {{ examResult.employee_name || 'Unknown Employee' }}
          </h3>

          <h4 class="text-sm font-medium text-primary-text dark:text-primary-dark-text truncate mt-1">
            {{ examResult.exam_name }}
          </h4>
  
          <p class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 truncate">
            Topic: {{ examResult.module_name }}
          </p>
  
          <div class="mt-2 flex flex-wrap gap-2 items-center">
            <span class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70">
              Score: {{ examResult.employee_score }}/{{ examResult.total_score }}
            </span>
            <span class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70">
              Passing: {{ examResult.passing_rate }}%
            </span>
            <StatusBadge :status="examResult.result_status" />
          </div>
  
          <p class="mt-2 text-xs text-secondary-text/60 dark:text-secondary-dark-text/60">
            {{ formatDate(examResult.date_added) }}
          </p>
        </div>
  
        <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
          <button
            @click="$emit('view', examResult.result_id)"
            class="p-2.5 rounded-lg text-primary/70 dark:text-primary-dark/70 hover:text-primary dark:hover:text-primary-dark hover:bg-primary/10 dark:hover:bg-primary-dark/10 transition-all duration-200 hover:scale-110"
            title="View Details"
          >
            <ClipboardList class="h-5 w-5" />
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ClipboardList } from 'lucide-vue-next'
  import type { ExamResult } from '@/services/examResultServices'
  import StatusBadge from './StatusBadge.vue'
  
  defineProps<{
    examResult: ExamResult
  }>()
  
  defineEmits<{
    (e: 'view', resultId: number): void
  }>()
  
  const formatDate = (dateString: string): string => {
    if (!dateString) return '—'
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return '—'
    return date.toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit'
    })
  }
  </script>
