<template>
    <div class="card relative z-10 overflow-hidden shadow-lg border border-gray-200/50 dark:border-gray-700/50">
      <!-- Desktop Table View -->
      <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
          <thead
            class="bg-gradient-to-r from-primary/5 via-primary/3 to-transparent dark:from-primary-dark/10 dark:via-primary-dark/5 dark:to-transparent"
          >
            <tr>
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-bold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Employee
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Exam
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Module
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Score
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Passing Rate
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Result
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Date
              </th>
  
              <th
                scope="col"
                class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
              >
                Actions
              </th>
            </tr>
          </thead>
  
          <tbody class="bg-white dark:bg-gray-900/50 divide-y divide-gray-200/50 dark:divide-gray-700/50">
            <ExamResultRow
              v-for="examResult in examResults"
              :key="`${examResult.fk_employee_id}-${examResult.fk_exam_id}`"
              :examResult="examResult"
              @view="(employeeId, examId) => $emit('view', employeeId, examId)"
            />
          </tbody>
        </table>
      </div>
  
      <!-- Mobile Card View -->
      <div class="lg:hidden divide-y divide-gray-200/50 dark:divide-gray-700/50">
        <ExamResultCard
          v-for="examResult in examResults"
          :key="`${examResult.fk_employee_id}-${examResult.fk_exam_id}`"
          :examResult="examResult"
          @view="(employeeId, examId) => $emit('view', employeeId, examId)"
        />
      </div>
  
      <!-- Empty State -->
      <div v-if="examResults.length === 0" class="text-center py-16 px-4">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
          <FileText class="w-8 h-8 text-secondary-text/50 dark:text-secondary-dark-text/50" />
        </div>
        <p class="text-base font-medium text-secondary-text dark:text-secondary-dark-text">
          No exam results found
        </p>
        <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-1">
          Try adjusting your search or filters
        </p>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
import { FileText } from 'lucide-vue-next'
import type { ExamResult } from '@/services/examResultServices'
import ExamResultCard from './ExamResultCard.vue'
import ExamResultRow from './ExamResultRow.vue'

withDefaults(
  defineProps<{
    examResults?: ExamResult[]
  }>(),
  {
    examResults: () => [],
  }
)

defineEmits<{
  (e: 'view', employeeId: number, examId: number): void
}>()
</script>

