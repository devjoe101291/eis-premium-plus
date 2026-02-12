<template>
    <div class="px-8 py-6 space-y-6">
        <!-- HEADER CARD -->
<div
  class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
         rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50
         px-6 py-5 flex items-center"
>
  <!-- LEFT SIDE (Title + subtitle stacked) -->
  <div class="flex flex-col">
    <h1 class="text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
      Exams
    </h1>
    <p class="text-sm text-slate-500 dark:text-slate-400">
      Browse and take available Exams
    </p>
  </div>

  <!-- RIGHT SIDE -->
  <p class="text-sm text-slate-500 dark:text-slate-400 ml-auto">
    {{ filteredExams.length }} exam{{ filteredExams.length !== 1 ? 's' : '' }} available
  </p>
</div>


        <!-- TABS STRIP -->
<div
  class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
         rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50
         overflow-hidden"
>
  <div class="grid grid-cols-2">
    <button
      type="button"
      class="py-4 font-semibold flex items-center justify-center gap-2 transition"
      :class="activeTab === 'exams'
        ? 'bg-white/70 dark:bg-slate-900/30 border-b-4 border-secondary'
        : 'bg-white/40 dark:bg-slate-900/10 hover:bg-white/60 dark:hover:bg-slate-900/20'"
      @click="activeTab = 'exams'"
    >
      <span class="inline-block"><PaperIcon /></span>
      <span>Exam List</span>
    </button>

    <button
      type="button"
      class="py-4 font-semibold flex items-center justify-center gap-2 transition"
      :class="activeTab === 'results'
        ? 'bg-white/70 dark:bg-slate-900/30 border-b-4 border-secondary'
        : 'bg-white/40 dark:bg-slate-900/10 hover:bg-white/60 dark:hover:bg-slate-900/20'"
      @click="activeTab = 'results'"
    >
      <span class="inline-block"><ViewIcon class="h-5 w-5" /></span>
      <span>Exam Results</span>
    </button>
  </div>
</div>


        <template v-if="activeTab === 'exams'">
        <!-- SEARCH & FILTER CONTROLS -->
        <TableControls v-model:search="examSearchQuery" v-model:status="examStatusFilter" class="mb-5">
            <template #action>
                <select v-model.number="examPerPage" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                 dark:bg-slate-700 dark:text-slate-200 text-sm">
                    <option :value="5">5 / page</option>
                    <option :value="10">10 / page</option>
                    <option :value="15">15 / page</option>
                </select>
            </template>
        </TableControls>

        <!-- EXAMS TABLE -->
        <CardTable title="Exams">
            <table class="w-full">
                <!-- TABLE HEAD -->
                <thead class="bg-slate-100 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Topic</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Instructions</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Passing Rate</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Time Limit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Actions</th>
                    </tr>
                </thead>

                <!-- TABLE BODY -->
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    <!-- LOADING STATE -->
                    <tr v-if="loading">
                        <td colspan="7" class="px-6 py-8">
                            <Skeleton variant="table" :rows="5" :cols="7" />
                        </td>
                    </tr>

                    <!-- ERROR STATE -->
                    <tr v-else-if="error">
                        <td colspan="7" class="px-6 py-12 text-center text-red-600 dark:text-red-400">
                            {{ error }}
                        </td>
                    </tr>

                    <!-- EMPTY STATE -->
                    <tr v-else-if="filteredExams.length === 0">
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            No exams found
                        </td>
                    </tr>

                    <!-- EXAM ROWS -->
                    <tr v-else v-for="exam in paginatedExams" :key="exam.id">
                        <!-- Title -->
                        <td class="px-6 py-4 text-primary font-semibold">{{ exam.title }}</td>

                        <td class="px-6 py-4 text-primary font-semibold">{{ exam.topic_id }}</td>

                        <!-- Instructions -->
                        <td class="px-6 py-4 text-sm max-w-xs truncate">
                            {{ exam.instructions || exam.description || '—' }}
                        </td>

                        <!-- Passing Rate -->
                        <td class="px-6 py-4 font-medium">
                            {{ formatPassingRate(exam) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ exam.time_limit }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full"
                                :class="exam.is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'">
                                {{ exam.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <IconButton title="View Questions" variant="neutral"
                                    @click="viewExamQuestions(exam.id)">
                                    <ViewIcon class="h-4 w-[2em]" />
                                </IconButton>

                                <IconButton title="Take Exam" variant="primary" size="sm" shape="pill"
                                    @click="router.push({ name: 'take-exam', params: { id: exam.id } })">
                                    <PlayIcon class="h-4 w-[2rem]" />
                                    <span class="text-sm">Take Exam</span>
                                </IconButton>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- PAGINATION FOOTER -->
            <template #footer>
                <div class="justify-center mt-4">
                    <PaginationBar :page="currentPage" :total-pages="totalPages" :total-items="filteredExams.length"
                        @update:page="currentPage = $event" />
                </div>
            </template>
        </CardTable>
        </template>

        <template v-else>
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg
                border border-slate-200/50 dark:border-slate-700/50 px-6 py-14 text-center">
                <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">Exam Results</div>
                <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">No results to display yet.</div>
            </div>
        </template>

        <!-- EXAM MODAL -->
        <Modal v-model="isExamModalOpen" :title="examModalTitle">
            <div v-if="isExamModalLoading" class="flex justify-center py-8">
                <div class="text-slate-500 dark:text-slate-400">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                </div>
            </div>

            <div v-else-if="examModalError" class="text-center text-red-600 dark:text-red-400 py-8">
                {{ examModalError }}
            </div>

            <div v-else class="space-y-6 max-h-[70vh] overflow-y-auto pr-1">
                <!-- EXAM SUMMARY -->
                <div
                    class="rounded-xl bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700 p-4 flex flex-wrap gap-6 text-sm">
                    <div><span class="font-semibold">Time Limit:</span> {{ examModalData?.time_limit ?? 'Unlimited' }}
                        minutes
                    </div>
                    <div><span class="font-semibold">Passing Rate:</span> {{
                        formatPassingRatePercent(examModalData?.passing_score ?? examModalData?.passing_rate ?? 0) }}%
                    </div>
                    <div><span class="font-semibold">Questions:</span> {{ examModalQuestions.length }}</div>
                </div>

                <!-- QUESTIONS HEADING -->
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Questions &amp; Answers</h3>

                <!-- QUESTIONS LIST -->
                <div v-if="examModalQuestions.length === 0" class="text-center text-slate-500 py-6">
                    No questions found
                </div>

                <div v-else class="space-y-5">
                    <div v-for="(q, idx) in examModalQuestions" :key="q.id"
                        class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <!-- QUESTION HEADER -->
                        <div
                            class="px-5 py-4 bg-slate-50 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                            <!-- Number badge -->
                            <span
                                class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold text-sm">
                                {{ idx + 1 }}
                            </span>
                            <!-- Type badge -->
                            <span class="font-semibold text-slate-800 dark:text-slate-100">
                                {{ labelType(q.type) }}
                            </span>
                            <!-- Points badge -->
                            <span
                                class="text-xs px-2 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
                                {{ Number(q.points || 1) }} points
                            </span>
                        </div>

                        <!-- QUESTION BODY -->
                        <div class="p-5 space-y-4">
                            <!-- Question text -->
                            <p class="font-semibold text-slate-800 dark:text-slate-100">
                                {{ q.title || q.text }}
                            </p>

                            <!-- OPTIONS -->
                            <div v-if="q.type === 'multiple' || q.type === 'multiple-answer'" class="space-y-2">
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">OPTIONS:</p>

                                <div v-for="(opt, optIdx) in q.options" :key="`${q.id}-${optIdx}`"
                                    class="flex items-center gap-3 p-3 rounded-xl border transition" :class="isCorrectOption(q, String.fromCharCode(65 + (optIdx as number)))
                                        ? 'border-emerald-300 dark:border-emerald-600 bg-emerald-50 dark:bg-emerald-500/10'
                                        : 'border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/20'
                                        ">
                                    <!-- Letter badge -->
                                    <span
                                        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-sm">
                                        {{ String.fromCharCode(65 + (optIdx as number)) }}
                                    </span>
                                    <!-- Option text -->
                                    <span class="flex-1 text-slate-800 dark:text-slate-100" :class="{
                                        'font-medium': isCorrectOption(q, String.fromCharCode(65 + (optIdx as number)))
                                    }">
                                        {{ typeof opt === 'object' && opt.text ? opt.text : opt }}
                                    </span>
                                    <!-- Checkmark for correct answer -->
                                    <span v-if="isCorrectOption(q, String.fromCharCode(65 + (optIdx as number)))"
                                        class="text-emerald-600 dark:text-emerald-400 font-bold text-lg">
                                        ✓
                                    </span>
                                </div>

                                <!-- Correct answer label -->
                                <div class="mt-2 text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                                    Correct Answer: {{ correctAnswerLabel(q) }}
                                </div>
                            </div>

                            <!-- TRUE/FALSE -->
                            <div v-else-if="q.type === 'true-false'" class="text-sm">
                                <div class="text-emerald-700 dark:text-emerald-400 font-semibold">
                                    Correct Answer: {{ correctAnswerLabel(q) }}
                                </div>
                            </div>

                            <!-- SHORT ANSWER -->
                            <div v-else class="text-sm space-y-1">
                                <div class="text-emerald-700 dark:text-emerald-400 font-semibold">
                                    Expected Answer: {{ correctAnswerLabel(q) }}
                                </div>
                                <div v-if="q.keywords" class="text-slate-500 dark:text-slate-400">
                                    Keywords: {{ q.keywords }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <button type="button" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700"
                        @click="isExamModalOpen = false">
                        Close
                    </button>
                </div>
            </template>
        </Modal>


    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useTitle } from '@/composables/ui/useTitle'
import { useRouter } from 'vue-router'
import { useExams } from '@/composables/useExams'
import CardTable from '@/components/layout/CardTable.vue'
import TableControls from '@/components/layout/TableControls.vue'
import PaginationBar from '@/components/layout/PaginationBar.vue'
import Skeleton from '@/components/ui/Skeleton.vue'
import IconButton from '@/components/ui/IconButton.vue'
import ViewIcon from '@/components/icons/ViewIcon.vue'
import PaperIcon from '@/components/icons/PaperIcon.vue'
import Modal from '@/components/layout/Modal.vue'
import api from '@/services/api'
import PlayIcon from '@/components/icons/PlayIcon.vue'

const router = useRouter()

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Exams`)

// ✅ Composables
const { availableExams, loading, error, fetchAvailableExams } = useExams()

// ✅ Reactive state for search and filters
const examSearchQuery = ref('')
const examStatusFilter = ref<'all' | 'active' | 'inactive'>('active')
const examPerPage = ref(10)

// ✅ Pagination
const currentPage = ref(1)

// ✅ Modal state
const isExamModalOpen = ref(false)
const isExamModalLoading = ref(false)
const examModalError = ref<string | null>(null)
const examModalData = ref<any>({})
const examModalQuestions = ref<any[]>([])

// ✅ Fetch data on mount
onMounted(fetchAvailableExams)

// ✅ Format passing rate as percentage (from backend)
const formatPassingRate = (exam: any) => {
    const rate = exam.passing_score ?? exam.passing_rate
    if (rate === null || rate === undefined) return '—'
    return `${rate}%`
}

// ✅ Format passing rate with 2 decimals
const formatPassingRatePercent = (value: any) => {
    const num = Number(value)
    if (isNaN(num)) return '—'
    return num.toFixed(2)
}

// ✅ Filter exams by search query and status
const filteredExams = computed(() => {
    let results = availableExams.value || []

    // Search by title or description
    if (examSearchQuery.value.trim()) {
        const query = examSearchQuery.value.trim().toLowerCase()
        results = results.filter((e: any) => {
            const titleMatch = String(e?.title ?? '').toLowerCase().includes(query)
            const descMatch = String(e?.description ?? '').toLowerCase().includes(query)
            return titleMatch || descMatch
        })
    }

    // Filter by status
    if (examStatusFilter.value === 'active') {
        results = results.filter((e: any) => e.is_active)
    } else if (examStatusFilter.value === 'inactive') {
        results = results.filter((e: any) => !e.is_active)
    }

    return results
})

// ✅ Reset pagination when filters change
watch([examSearchQuery, examStatusFilter, examPerPage], () => {
    currentPage.value = 1
})

// ✅ Pagination computed
const paginatedExams = computed(() => {
    const startIdx = (currentPage.value - 1) * examPerPage.value
    const endIdx = startIdx + examPerPage.value
    return filteredExams.value.slice(startIdx, endIdx)
})

// ✅ Total pages
const totalPages = computed(() => {
    return Math.ceil(filteredExams.value.length / examPerPage.value) || 1
})

// ✅ Exam Modal Title
const examModalTitle = computed(() => String(examModalData.value?.title ?? 'Exam'))

// ✅ Helper: normalize answer_json vs answers_json
const getAnswerArray = (data: any) => {
    if (Array.isArray(data?.answer_json)) return data.answer_json
    if (Array.isArray(data?.answers_json)) return data.answers_json
    return []
}

// ✅ Label question type nicely
const labelType = (t: string) => {
    if (t === 'multiple') return 'Multiple Choice'
    if (t === 'multiple-answer') return 'Multiple Answer'
    if (t === 'true-false') return 'True / False'
    return 'Short Answer'
}

// ✅ Check if option is marked as correct
const isCorrectOption = (q: any, letter: string) => {
    const a = q?._answer
    if (!a) return false

    if (q.type === 'multiple') {
        return String(a.answer ?? '') === String(letter)
    }

    if (q.type === 'multiple-answer') {
        return Array.isArray(a.answer) && a.answer.map(String).includes(String(letter))
    }

    return false
}

// ✅ Get label for correct answer(s)
const correctAnswerLabel = (q: any) => {
    const a = q?._answer
    if (!a) return '—'

    if (q.type === 'multiple') return String(a.answer ?? '—')
    if (q.type === 'multiple-answer') return Array.isArray(a.answer) ? a.answer.join(', ') : '—'
    if (q.type === 'true-false') return String(a.answer ?? '—')

    if (q.type === 'short') {
        return String(a?.answer?.expected ?? '—')
    }

    return '—'
}

// ✅ Open and fetch exam details
const openExamModal = async (examId: number) => {
    isExamModalOpen.value = true
    isExamModalLoading.value = true
    examModalError.value = null
    examModalData.value = {}
    examModalQuestions.value = []

    try {
        const res = await api.get(`/exams/${examId}`)
        const data = res?.data?.data ?? res?.data

        examModalData.value = data || {}

        const questionJson = Array.isArray(data?.question_json) ? data.question_json : []
        const answerJson = getAnswerArray(data)

        // Attach answers to questions by question_id
        examModalQuestions.value = questionJson.map((q: any) => {
            const ans = answerJson.find((a: any) => Number(a.question_id) === Number(q.id))
            return { ...q, _answer: ans }
        })
    } catch (e: any) {
        console.error('openExamModal failed:', e)
        examModalError.value = e?.response?.data?.message ?? e?.message ?? 'Failed to load exam.'
    } finally {
        isExamModalLoading.value = false
    }
}

const activeTab = ref<'exams' | 'results'>('exams')

watch(activeTab, (next) => {
    if (next === 'results') isExamModalOpen.value = false
})

// ✅ Actions
const viewExamQuestions = (examId: number) => {
    openExamModal(examId)
}
</script>
