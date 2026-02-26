<template>
  <Modal 
    :modelValue="isOpen" 
    @update:modelValue="$emit('update:isOpen', $event)"
    title="Exam Result Details"
  >
    <div v-if="result" class="space-y-6 text-sm text-primary-text dark:text-primary-dark-text pb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Employee Info -->
            <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-primary dark:text-primary-dark mb-2 text-base">Employee</h4>
                <div class="space-y-1">
                    <p><span class="opacity-70">Name:</span> <strong>{{ result.employee_name || 'Unknown' }}</strong></p>
                    <p><span class="opacity-70">Username:</span> {{ result.employee_username }}</p>
                    <p><span class="opacity-70">Email:</span> {{ result.employee_email }}</p>
                </div>
            </div>

            <!-- Exam Info -->
            <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-primary dark:text-primary-dark mb-2 text-base">Assessment</h4>
                <div class="space-y-1">
                    <p><span class="opacity-70">Exam:</span> <strong>{{ result.exam_name }}</strong></p>
                    <p><span class="opacity-70">Module:</span> {{ result.module_name }}</p>
                    <p><span class="opacity-70">Date Completed:</span> {{ formatDate(result.date_added) }}</p>
                </div>
            </div>
        </div>

        <!-- Grade Info -->
        <div class="bg-gradient-to-br border border-gray-200 dark:border-gray-700 p-6 rounded-xl relative overflow-hidden"
            :class="[
                result.result_status === 'passed' 
                    ? 'from-success/10 to-success/5 border-success/20 dark:border-success/20' 
                    : result.result_status === 'failed'
                        ? 'from-danger/10 to-danger/5 border-danger/20 dark:border-danger/20'
                        : 'from-warning/10 to-warning/5 border-warning/20 dark:border-warning/20'
            ]"
        >
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div>
                    <h5 class="text-xs uppercase tracking-wider opacity-60 mb-1">Score</h5>
                    <p class="text-2xl font-bold">{{ result.employee_score }} <span class="text-sm opacity-60 font-normal">/ {{ result.total_score }}</span></p>
                </div>
                <div>
                    <h5 class="text-xs uppercase tracking-wider opacity-60 mb-1">Percentage</h5>
                    <p class="text-2xl font-bold">{{ scorePercent }}%</p>
                </div>
                <div>
                    <h5 class="text-xs uppercase tracking-wider opacity-60 mb-1">Passing Rate</h5>
                    <p class="text-2xl font-bold">{{ result.passing_rate }}%</p>
                </div>
                <div>
                    <h5 class="text-xs uppercase tracking-wider opacity-60 mb-1">Status</h5>
                    <div class="mt-1 flex justify-center">
                        <StatusBadge :status="result.result_status" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Answers Section -->
        <div v-if="isLoading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        </div>
        <div v-else-if="detailedQuestions.length > 0" class="mt-8">
            <h4 class="font-semibold text-lg text-primary-text dark:text-primary-dark-text mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                Exam Breakdown
            </h4>
            <div class="space-y-4">
                <div 
                    v-for="(q, index) in detailedQuestions" 
                    :key="q.id" 
                    class="p-4 rounded-xl border"
                    :class="[
                        q.is_correct 
                            ? 'bg-success/5 border-success/30 dark:border-success/30' 
                            : 'bg-danger/5 border-danger/30 dark:border-danger/30'
                    ]"
                >
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex-shrink-0">
                            <!-- Icon check vs x -->
                            <svg v-if="q.is_correct" class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="w-5 h-5 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h5 class="font-medium text-base mb-1">
                                <span class="opacity-60 mr-1">{{ index + 1 }}.</span>
                                {{ q.question }}
                            </h5>
                            <div class="text-sm mt-2 flex flex-col gap-1">
                                <span class="opacity-70 font-medium">Employee's Answer:</span>
                                <span class="px-3 py-1.5 rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 break-words font-medium"
                                      :class="q.is_correct ? 'text-success' : 'text-danger'">
                                    {{ formatArrayAnswer(q.employee_answer) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="!isLoading && errorMsg" class="text-danger p-4 text-center mt-4">
            {{ errorMsg }}
        </div>
    </div>

    <!-- Custom Footer Replacement for Modal -->
    <template #footer>
        <button
            @click="$emit('update:isOpen', false)"
            class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition-colors"
        >
            Close
        </button>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import Modal from '@/components/layout/Modal.vue'
import StatusBadge from './StatusBadge.vue'
import { examResultService } from '@/services/examResultServices'
import type { ExamResult, ExamResultDetailQuestion } from '@/services/examResultServices'

const props = defineProps<{
  isOpen: boolean
  result: ExamResult | null
}>()

defineEmits<{
  (e: 'update:isOpen', val: boolean): void
}>()

const isLoading = ref(false)
const detailedQuestions = ref<ExamResultDetailQuestion[]>([])
const errorMsg = ref('')

const scorePercent = computed(() => {
  if (!props.result) return 0
  const total = Number(props.result.total_score) || 0
  const score = Number(props.result.employee_score) || 0
  if (total <= 0) return 0
  return Math.round((score / total) * 100)
})

const fetchDetails = async () => {
    if (!props.result?.result_id) return
    
    isLoading.value = true
    errorMsg.value = ''
    detailedQuestions.value = []
    
    try {
        const res = await examResultService.getExamResult(props.result.result_id)
        if (res.success && res.data?.questions) {
            detailedQuestions.value = res.data.questions
        } else {
            errorMsg.value = 'Failed to load detailed answers.'
        }
    } catch (err: any) {
        errorMsg.value = err?.response?.data?.message || 'Failed to fetch exam details.'
    } finally {
        isLoading.value = false
    }
}

watch(() => props.isOpen, (newVal) => {
    if (newVal && props.result) {
        fetchDetails()
    } else {
        detailedQuestions.value = []
    }
})

const formatArrayAnswer = (val: any) => {
    if (val === null || val === undefined) return '(No answer provided)'
    if (Array.isArray(val)) return val.join(', ')
    return String(val)
}

const formatDate = (dateString: string): string => {
  if (!dateString) return '—'
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
