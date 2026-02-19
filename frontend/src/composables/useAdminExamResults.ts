import { ref, computed } from 'vue'
import {
  examResultService,
  type ExamResult,
  type ExamResultListResponse,
} from '@/services/examResultServices'

export function useAdminExamResults() {
  const examResults = ref<ExamResult[]>([])
  const currentExamResult = ref<ExamResult | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const total = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)

  const hasExamResults = computed(() => examResults.value.length > 0)
  const totalPages = computed(() => Math.ceil(total.value / perPage.value))

  async function fetchExamResults(params?: {
    page?: number
    per_page?: number
    search?: string
    sort_by?: string
    sort_dir?: 'asc' | 'desc'
    status?: 'passed' | 'failed' | 'pending'
  }) {
    loading.value = true
    error.value = null

    try {
      const response: ExamResultListResponse = await examResultService.getExamResults({
        page: params?.page ?? currentPage.value,
        per_page: params?.per_page ?? perPage.value,
        search: params?.search,
        sort_by: params?.sort_by,
        sort_dir: params?.sort_dir,
        status: params?.status,
      } as any)

      examResults.value = response.data ?? []
      total.value = response.total ?? 0
      currentPage.value = response.current_page ?? 1
      perPage.value = response.per_page ?? 10
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'Failed to fetch exam results'
      examResults.value = []
      total.value = 0
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchExamResult(id: number) {
    loading.value = true
    error.value = null

    try {
      currentExamResult.value = await examResultService.getExamResult(id)
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'Failed to fetch exam result'
      throw err
    } finally {
      loading.value = false
    }
  }

  function clearError() {
    error.value = null
  }

  return {
    examResults,
    currentExamResult,
    loading,
    error,
    total,
    currentPage,
    perPage,
    hasExamResults,
    totalPages,
    fetchExamResults,
    fetchExamResult,
    clearError,
  }
}

