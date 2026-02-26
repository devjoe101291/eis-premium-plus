<template>
    <tr class="hover:bg-primary/5 dark:hover:bg-primary-dark/5 transition-all duration-300 ease-out group">
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-semibold text-primary-text dark:text-primary-dark-text">
          {{ examResult.employee_name || 'Unknown' }}
        </div>
        <!-- <div class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70">
          {{ examResult.employee_username }}
        </div> -->
      </td>

      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-semibold text-primary-text dark:text-primary-dark-text">
          {{ examResult.exam_name }}
        </div>
      </td>
  
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
          {{ examResult.module_name }}
        </div>
      </td>
  
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
          {{ examResult.employee_score }} / {{ examResult.total_score }}
          <span class="text-xs text-secondary-text/60 dark:text-secondary-dark-text/60">
            ({{ scorePercent }}%)
          </span>
        </div>
      </td>
  
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
          {{ examResult.passing_rate }}%
        </div>
      </td>
  
      <td class="px-6 py-4 whitespace-nowrap">
        <StatusBadge :status="examResult.result_status" />
      </td>
  
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70">
          {{ formatDate(examResult.date_added) }}
        </div>
      </td>
  
      <td class="px-6 py-4 whitespace-nowrap text-right">
        <div class="flex items-center justify-start space-x-2">
          <button
            @click="$emit('view', examResult.result_id)"
            class="p-2.5 rounded-lg text-primary/70 dark:text-primary-dark/70 hover:text-primary dark:hover:text-primary-dark hover:bg-primary/10 dark:hover:bg-primary-dark/10 transition-all duration-200 hover:scale-110"
            title="View Details"
          >
            <ClipboardList class="h-5 w-5" />
          </button>
        </div>
      </td>
    </tr>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue'
  import type { ExamResult } from '@/services/examResultServices'
  import { ClipboardList } from 'lucide-vue-next'
  import StatusBadge from './StatusBadge.vue'
  
  const props = defineProps<{
    examResult: ExamResult
  }>()
  
  defineEmits<{
    (e: 'view', resultId: number): void
  }>()
  
  const scorePercent = computed(() => {
    const total = Number(props.examResult.total_score) || 0
    const score = Number(props.examResult.employee_score) || 0
    if (total <= 0) return 0
    return Math.round((score / total) * 100)
  })
  
  const formatDate = (dateString: string): string => {
    if (!dateString) return '—'
  
    // Handle Laravel "YYYY-MM-DD HH:mm:ss" (not always parsed by Date reliably)
    const normalized = dateString.includes(' ') && !dateString.includes('T')
      ? dateString.replace(' ', 'T')
      : dateString
  
    const date = new Date(normalized)
    if (isNaN(date.getTime())) return '—'
  
    return date.toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
    })
  }
  </script>
