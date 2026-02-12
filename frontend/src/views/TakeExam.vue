<template>
  <div class="min-h-screen bg-gradient-to-br">
    <!-- LOADING STATE -->
    <div v-if="isLoading" class="max-w-4xl mx-auto text-center py-16 px-4">
      <div class="inline-flex items-center gap-3">
        <div class="w-8 h-8 rounded-full border-4 border-primary/20 border-t-primary animate-spin"></div>
        <span class="text-lg font-semibold text-slate-600 dark:text-slate-300">Loading exam...</span>
      </div>
    </div>

    <!-- ERROR STATE -->
    <div v-else-if="error" class="max-w-4xl mx-auto px-4">
      <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 rounded-xl p-6 text-center">
        <p class="text-red-600 dark:text-red-400 font-semibold mb-4">{{ error }}</p>
        <button @click="goBack" class="px-6 py-2 bg-primary hover:opacity-90 text-white rounded-lg transition-colors">
          Back
        </button>
      </div>
    </div>

    <!-- EXAM TAKING SCREEN -->
    <div v-else-if="!examSubmitted" class="mx-auto px-4">
      <!-- FIXED HEADER WITH TIMER -->
      <div class="sticky top-0 z-20 bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm border-b border-slate-200 dark:border-slate-700 shadow-lg rounded-b-xl">
        <div class="p-4">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex-1">
              <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ examTitle }}</h2>
              <p class="text-sm text-slate-600 dark:text-slate-400">Question {{ currentQuestionIndex + 1 }} of {{ questions.length }}</p>
            </div>
            
            <div class="flex items-center gap-6 w-full sm:w-auto">
              <!-- PROGRESS -->
              <div class="text-center flex-1 sm:flex-none">
                <div class="text-xs text-slate-600 dark:text-slate-400">Progress</div>
                <div class="text-lg font-bold text-primary">{{ answeredCount }}/{{ questions.length }}</div>
              </div>

              <!-- TIMER -->
              <div class="flex items-center gap-2 bg-primary/10 px-4 py-2 rounded-xl flex-1 sm:flex-none" :class="{ 'animate-pulse': timeRemaining < 300 }">
                <svg class="w-5 h-5" :class="timeRemaining < 300 ? 'text-red-500' : 'text-primary'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H7a1 1 0 100 2h4a1 1 0 001-1V7z" clip-rule="evenodd"></path></svg>
                <div>
                  <div class="text-xs text-slate-600 dark:text-slate-400">Time Left</div>
                  <div :class="['text-lg font-bold', timeRemaining < 300 ? 'text-red-500' : 'text-primary']">
                    {{ formatTime(timeRemaining) }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PROGRESS BAR -->
          <div class="mt-3 bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
            <div class="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-300" :style="{ width: (answeredCount / questions.length * 100) + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- CONTENT -->
      <div class="pt-24 pb-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          <!-- LEFT: QUESTION NAVIGATION -->
          <div class="lg:col-span-3 order-2 lg:order-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden sticky top-28">
              <div class="p-4">
                <h4 class="font-bold text-slate-800 dark:text-slate-100 mb-3">Questions</h4>
                <div class="grid grid-cols-6 gap-1.5">
                  <button v-for="(q, index) in questions" :key="q.id" @click="currentQuestionIndex = index"
                    class="w-8 h-8 rounded-lg font-semibold text-xs transition-all duration-200 hover:scale-105 flex items-center justify-center"
                    :class="getQuestionNavClass(index, q.id)">
                    {{ index + 1 }}
                  </button>
                </div>

                <!-- LEGEND -->
                <div class="space-y-2 text-xs pt-4 border-t border-slate-200 dark:border-slate-700 mt-4">
                  <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-gradient-to-r from-primary to-secondary flex-shrink-0"></div>
                    <span class="text-slate-600 dark:text-slate-400">Current</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-secondary flex-shrink-0"></div>
                    <span class="text-slate-600 dark:text-slate-400">Answered</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-slate-200 dark:bg-slate-700 flex-shrink-0"></div>
                    <span class="text-slate-600 dark:text-slate-400">Unanswered</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT: QUESTION CARD -->
          <div class="lg:col-span-9 order-1 lg:order-2">
            <!-- QUESTION -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
              <div class="p-8">
                <div class="flex items-start gap-4 mb-6">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                    {{ currentQuestionIndex + 1 }}
                  </div>
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                      <span class="px-3 py-1 text-xs font-semibold rounded-full bg-primary/10 text-primary dark:bg-primary/20">
                        {{ labelType(currentQuestion.type) }}
                      </span>
                      <span class="text-xs px-2 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
                        {{ Number(currentQuestion.points || 1) }} points
                      </span>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 leading-relaxed">{{ currentQuestion.title || currentQuestion.text }}</h3>
                  </div>
                </div>

                <!-- OPTIONS FOR MULTIPLE CHOICE / MULTIPLE ANSWER -->
                <div v-if="currentQuestion.options && currentQuestion.options.length > 0" class="space-y-3">
                  <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-4">OPTIONS:</p>
                  <div v-for="(opt, optIdx) in currentQuestion.options" :key="`${currentQuestion.id}-${optIdx}`" 
                    @click="selectAnswer(currentQuestion.id, String.fromCharCode(65 + (optIdx as number)), currentQuestion.type)"
                    class="group cursor-pointer border-2 rounded-xl p-4 transition-all duration-200 hover:shadow-md"
                    :class="getOptionClass(currentQuestion.id, String.fromCharCode(65 + (optIdx as number)))">
                    <div class="flex items-center gap-4">
                      <!-- CHECKBOX / RADIO -->
                      <div v-if="currentQuestion.type === 'multiple-answer'" 
                        class="w-10 h-10 rounded-lg border-2 flex items-center justify-center font-bold text-sm transition-all duration-200 flex-shrink-0"
                        :class="getOptionCircleClass(currentQuestion.id, String.fromCharCode(65 + (optIdx as number)))">
                        <svg v-if="isAnswerSelected(currentQuestion.id, String.fromCharCode(65 + (optIdx as number)))" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span v-else>{{ String.fromCharCode(65 + (optIdx as number)) }}</span>
                      </div>
                      <div v-else class="w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold text-sm transition-all duration-200 flex-shrink-0"
                        :class="getOptionCircleClass(currentQuestion.id, String.fromCharCode(65 + (optIdx as number)))">
                        {{ String.fromCharCode(65 + (optIdx as number)) }}
                      </div>
                      <span class="text-slate-800 dark:text-slate-100 font-medium">{{ typeof opt === 'object' && opt.text ? opt.text : opt }}</span>
                    </div>
                  </div>
                </div>

                <!-- TRUE/FALSE -->
                <div v-else-if="currentQuestion.type === 'true-false'" class="space-y-3">
                  <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-4">ANSWER:</p>
                  <div class="flex gap-3">
                    <button @click="selectAnswer(currentQuestion.id, 'True', 'true-false')"
                      class="flex-1 px-4 py-3 rounded-lg border-2 transition-all duration-200 font-semibold"
                      :class="getQuestionAnswer(currentQuestion.id) === 'True' ? 'border-primary dark:border-primary bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary' : 'border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/20 hover:border-primary/50'">
                      True
                    </button>
                    <button @click="selectAnswer(currentQuestion.id, 'False', 'true-false')"
                      class="flex-1 px-4 py-3 rounded-lg border-2 transition-all duration-200 font-semibold"
                      :class="getQuestionAnswer(currentQuestion.id) === 'False' ? 'border-primary dark:border-primary bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary' : 'border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/20 hover:border-primary/50'">
                      False
                    </button>
                  </div>
                </div>

                <!-- SHORT ANSWER -->
                <div v-else class="space-y-3">
                  <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">YOUR ANSWER:</p>
                  <textarea v-model="userAnswers[currentQuestion.id]" placeholder="Type your answer here..." rows="5"
                    class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100" />
                </div>
              </div>
            </div>

            <!-- NAVIGATION BUTTONS -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden mt-6">
              <div class="p-6">
                <div class="flex items-center justify-between gap-4">
                  <button @click="previousQuestion" :disabled="currentQuestionIndex === 0"
                    class="px-6 py-3 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    ← Previous
                  </button>

                  <button v-if="currentQuestionIndex < questions.length - 1" @click="nextQuestion"
                    class="px-6 py-3 bg-gradient-to-r from-primary to-secondary hover:opacity-90 text-white rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 shadow-md">
                    Next →
                  </button>

                  <button v-else @click="submitExam" :disabled="isSubmitting"
                    class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 shadow-lg disabled:opacity-50">
                    <svg v-if="!isSubmitting" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span v-if="isSubmitting">Submitting...</span>
                    <span v-else>Submit Exam</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RESULTS SCREEN -->
    <div v-else-if="examSubmitted" class="max-w-4xl mx-auto px-4 py-8">
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <!-- RESULTS HEADER -->
        <div class="p-8 text-center" :class="results.passed ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 mb-4">
            <svg v-if="results.passed" class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <svg v-else class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
          </div>
          <h2 class="text-3xl font-bold text-white mb-2">{{ results.passed ? 'Congratulations!' : 'Exam Complete' }}</h2>
          <p class="text-white/90">{{ results.passed ? 'You have passed the exam!' : 'You did not pass this time. Keep learning and try again!' }}</p>
        </div>

        <!-- SCORE DETAILS -->
        <div class="p-8">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="text-center p-6 bg-primary/10 rounded-xl">
              <div class="text-4xl font-bold text-primary mb-2">{{ results.score }}%</div>
              <div class="text-sm text-slate-600 dark:text-slate-400">Your Score</div>
            </div>
            <div class="text-center p-6 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
              <div class="text-4xl font-bold text-slate-800 dark:text-slate-100 mb-2">{{ results.earnedPoints }}/{{ results.totalPoints }}</div>
              <div class="text-sm text-slate-600 dark:text-slate-400">Points Earned</div>
            </div>
            <div class="text-center p-6 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
              <div class="text-4xl font-bold text-slate-800 dark:text-slate-100 mb-2">{{ formatPassingRatePercent(examData?.passing_rate ?? 0) }}%</div>
              <div class="text-sm text-slate-600 dark:text-slate-400">Passing Score</div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-4">
            <button @click="goBack" class="flex-1 bg-gradient-to-r from-primary to-secondary hover:opacity-90 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300">
              Back to Exams
            </button>
            <button @click="retakeExam" class="flex-1 border-2 border-primary text-primary hover:bg-primary hover:text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300">
              Retake Exam
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const route = useRoute()

const examId = computed(() => Number(route.params.id))
const examData = ref<any>({})
const questions = ref<any[]>([])
const userAnswers = ref<Record<number, any>>({})

const isLoading = ref(true)
const error = ref<string | null>(null)
const isSubmitting = ref(false)

const examSubmitted = ref(false)
const currentQuestionIndex = ref(0)
const timeRemaining = ref(0)
let timer: number | null = null

const results = ref({
  score: 0,
  earnedPoints: 0,
  totalPoints: 0,
  passed: false
})

const examTitle = computed(() => String(examData.value?.title ?? 'Exam'))

const currentQuestion = computed(() => questions.value[currentQuestionIndex.value] || {})

const answeredCount = computed(() => {
  return Object.entries(userAnswers.value).filter(([, answer]) => {
    if (Array.isArray(answer)) return answer.length > 0
    if (typeof answer === 'string') return answer.trim().length > 0
    return answer !== null && answer !== undefined
  }).length
})

const formatPassingRatePercent = (value: any) => {
  const num = Number(value)
  if (isNaN(num)) return '—'
  return num.toFixed(2)
}

const formatTime = (seconds: number) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
}

const labelType = (t: string) => {
  if (t === 'multiple') return 'Multiple Choice'
  if (t === 'multiple-answer') return 'Multiple Answer'
  if (t === 'true-false') return 'True / False'
  return 'Short Answer'
}

const getQuestionAnswer = (questionId: number): any => {
  return userAnswers.value[questionId] || null
}

const isAnswerSelected = (questionId: number, option: string): boolean => {
  const answer = userAnswers.value[questionId]
  if (!answer) return false

  if (Array.isArray(answer)) {
    return answer.includes(option)
  }

  return answer === option
}

const selectAnswer = (questionId: number, answer: string, questionType: string) => {
  if (questionType === 'multiple') {
    userAnswers.value[questionId] = answer
  } else if (questionType === 'multiple-answer') {
    const current = userAnswers.value[questionId] || []
    const arr = Array.isArray(current) ? [...current] : []

    if (arr.includes(answer)) {
      arr.splice(arr.indexOf(answer), 1)
    } else {
      arr.push(answer)
    }

    userAnswers.value[questionId] = arr.length > 0 ? arr : null
  } else if (questionType === 'true-false') {
    userAnswers.value[questionId] = answer
  }
}

const getOptionClass = (questionId: number, letter: string) => {
  const isSelected = isAnswerSelected(questionId, letter)
  return isSelected
    ? 'border-primary bg-primary/10 dark:bg-primary/20'
    : 'border-slate-200 dark:border-slate-700 hover:border-primary/50 hover:bg-slate-50 dark:hover:bg-slate-700/50'
}

const getOptionCircleClass = (questionId: number, letter: string) => {
  const isSelected = isAnswerSelected(questionId, letter)
  return isSelected
    ? 'border-primary bg-primary text-white'
    : 'border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-400 group-hover:border-primary group-hover:text-primary'
}

const getQuestionNavClass = (index: number, questionId: number) => {
  const isAnswered = Object.prototype.hasOwnProperty.call(userAnswers.value, questionId)
  const isCurrent = index === currentQuestionIndex.value

  if (isCurrent) {
    return 'bg-gradient-to-r from-primary to-secondary text-white'
  } else if (isAnswered) {
    return 'bg-secondary text-white hover:opacity-90'
  } else {
    return 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600'
  }
}

const goBack = () => router.back()

const fetchExam = async () => {
  isLoading.value = true
  error.value = null

  try {
    const res = await api.get(`/exams/${examId.value}`)
    const data = res?.data?.data ?? res?.data

    examData.value = data || {}

    const questionJson = Array.isArray(data?.question_json) ? data.question_json : []
    questions.value = questionJson
    
    // Auto-start exam timer
    timeRemaining.value = (data?.time_limit ?? 0) * 60
    startTimer()
  } catch (e: any) {
    console.error('fetchExam failed:', e)
    error.value = e?.response?.data?.message ?? e?.message ?? 'Failed to load exam.'
  } finally {
    isLoading.value = false
  }
}

const startTimer = () => {
  timer = window.setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--
    } else {
      timeUp()
    }
  }, 1000)
}

const timeUp = () => {
  if (timer) clearInterval(timer)
  alert('Time\'s up! Your exam will be submitted.')
  submitExam()
}

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--
  }
}

const nextQuestion = () => {
  if (currentQuestionIndex.value < questions.value.length - 1) {
    currentQuestionIndex.value++
  }
}

const submitExam = async () => {
  if (isSubmitting.value) return

  isSubmitting.value = true
  if (timer) clearInterval(timer)

  try {
    const answerPayload: any[] = []

    for (const [questionId, answer] of Object.entries(userAnswers.value)) {
      answerPayload.push({
        question_id: Number(questionId),
        answer: answer
      })
    }

    const res = await api.post(`/exams/${examId.value}/submit`, {
      answers: answerPayload
    })

    const data = res?.data?.data ?? res?.data

    results.value = {
      score: data?.score ?? 0,
      earnedPoints: data?.earned_points ?? data?.score ?? 0,
      totalPoints: data?.total_points ?? 100,
      passed: data?.passed ?? (data?.status === 'passed')
    }

    examSubmitted.value = true
  } catch (e: any) {
    console.error('submitExam failed:', e)
    const errorMsg = e?.response?.data?.message ?? e?.message ?? 'Failed to submit exam.'
    alert(errorMsg)
  } finally {
    isSubmitting.value = false
  }
}

const retakeExam = () => {
  userAnswers.value = {}
  examSubmitted.value = false
  currentQuestionIndex.value = 0
  timeRemaining.value = (examData.value?.time_limit ?? 0) * 60
  results.value = { score: 0, earnedPoints: 0, totalPoints: 0, passed: false }
  startTimer()
}

onMounted(() => {
  fetchExam()
})

onBeforeUnmount(() => {
  if (timer) clearInterval(timer)
})
</script>
