import { ref, computed } from 'vue'
import { examService, type Exam, type ExamAttempt, type Material, type Certificate } from '@/services/examService'

export function useExams() {
  const availableExams = ref<Exam[]>([])
  const examAttempts = ref<ExamAttempt[]>([])
  const materials = ref<Material[]>([])
  const certificates = ref<Certificate[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const passedExams = computed(() => examAttempts.value.filter(attempt => attempt.passed))
  const failedExams = computed(() => examAttempts.value.filter(attempt => !attempt.passed))
  const hasAvailableExams = computed(() => availableExams.value.length > 0)
  const hasAttempts = computed(() => examAttempts.value.length > 0)
  const hasMaterials = computed(() => materials.value.length > 0)
  const hasCertificates = computed(() => certificates.value.length > 0)

  async function fetchAvailableExams() {
    loading.value = true
    error.value = null

    try {
      availableExams.value = await examService.getAvailableExams()
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch available exams'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchExamAttempts() {
    loading.value = true
    error.value = null

    try {
      examAttempts.value = await examService.getExamAttempts()
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch exam attempts'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchMaterials() {
    loading.value = true
    error.value = null

    try {
      materials.value = await examService.getMaterials()
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch materials'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchCertificates() {
    loading.value = true
    error.value = null

    try {
      certificates.value = await examService.getCertificates()
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch certificates'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function startExam(examId: number) {
    loading.value = true
    error.value = null

    try {
      const attempt = await examService.startExam(examId)
      return attempt
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to start exam'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function submitExam(attemptId: number, answers: Record<number, number | string>) {
    loading.value = true
    error.value = null

    try {
      const attempt = await examService.submitExam(attemptId, answers)
      // Refresh attempts after submission
      await fetchExamAttempts()
      return attempt
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to submit exam'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function downloadMaterial(id: number) {
    loading.value = true
    error.value = null

    try {
      const blob = await examService.downloadMaterial(id)
      return blob
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to download material'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function downloadCertificate(id: number) {
    loading.value = true
    error.value = null

    try {
      const blob = await examService.downloadCertificate(id)
      return blob
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to download certificate'
      throw err
    } finally {
      loading.value = false
    }
  }

  function clearError() {
    error.value = null
  }

  return {
    availableExams,
    examAttempts,
    materials,
    certificates,
    loading,
    error,
    passedExams,
    failedExams,
    hasAvailableExams,
    hasAttempts,
    hasMaterials,
    hasCertificates,
    fetchAvailableExams,
    fetchExamAttempts,
    fetchMaterials,
    fetchCertificates,
    startExam,
    submitExam,
    downloadMaterial,
    downloadCertificate,
    clearError
  }
}
