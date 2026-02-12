<template>
  <div class="card transform hover:-translate-y-1 transition-all duration-300 ease-out group">
    <div class="card-body">
      <div class="flex items-start justify-between">
        <div class="flex-1">
          <h3 class="text-lg font-bold text-primary-text dark:text-primary-dark-text">
            {{ exam.title }}
          </h3>
          <p
            v-if="exam.description"
            class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-2 line-clamp-2"
          >
            {{ exam.description }}
          </p>
          <div class="flex items-center space-x-4 mt-3">
            <span
              v-if="exam.time_limit"
              class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70 flex items-center"
            >
              <svg
                class="h-4 w-4 mr-1"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                  clip-rule="evenodd"
                />
              </svg>
              {{ exam.time_limit }} min
            </span>
            <span
              class="text-xs text-secondary-text/70 dark:text-secondary-dark-text/70 flex items-center"
            >
              <svg
                class="h-4 w-4 mr-1"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"
                />
                <path
                  fill-rule="evenodd"
                  d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                  clip-rule="evenodd"
                />
              </svg>
              Passing: {{ exam.passing_score }}{{ exam.passing_criteria_type === 'percentage' ? '%' : ' pts' }}
            </span>
          </div>
        </div>
      </div>
      <div class="mt-4">
        <button
          @click="$emit('take-exam')"
          class="btn btn-primary w-full"
        >
          Take Exam
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Exam } from '@/services/examService'

defineProps<{
  exam: Exam
}>()

defineEmits<{
  (e: 'take-exam'): void
}>()
</script>
