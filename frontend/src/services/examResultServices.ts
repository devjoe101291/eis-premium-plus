import api from './api'

export interface ExamResult {
  result_id: number
  exam_id: number
  exam_name: string

  module_id: number
  module_name: string

  fk_exam_id: number
  fk_employee_id: number

  employee_username: string
  employee_name: string
  employee_email: string

  total_score: number
  employee_score: number
  passing_rate: number

  result_status: 'passed' | 'failed' | 'pending'

  date_added: string
}

export interface ExamResultDetailQuestion {
  id: number
  question: string
  type: string
  points: number
  options: any[]
  employee_answer: any
  is_correct: boolean
}

export interface ExamResultDetailResponse {
  success: boolean
  data: {
    exam_id: number
    title: string
    questions: ExamResultDetailQuestion[]
  }
}

export interface ExamResultListResponse {
  data: ExamResult[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}


export const examResultService = {
  /**
   * Get paginated exam results
   */
  async getExamResults(params?: {
    page?: number
    per_page?: number
    search?: string
    sort_by?: string
    sort_dir?: 'asc' | 'desc'
    status?: 'passed' | 'failed' | 'pending'
  }): Promise<ExamResultListResponse> {
    const response = await api.get<ExamResultListResponse>(
      '/exam-results',
      { params }
    )
    return response.data
  },

  /**
   * Get single exam result (if implemented backend-side)
   */
  async getExamResult(id: number): Promise<ExamResultDetailResponse> {
    const response = await api.get<ExamResultDetailResponse>(`/exam-results/${id}`)
    return response.data
  },
}

