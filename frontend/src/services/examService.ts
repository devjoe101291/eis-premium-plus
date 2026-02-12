import api from './api'

export interface Exam {
  id: number
  title: string
  description?: string
  category_id?: number
  exam_type: string
  time_limit?: number
  passing_score: number
  passing_criteria_type: 'percentage' | 'points'
  is_active: boolean
  created_by: number
  created_at: string
  updated_at: string
  category?: {
    id: number
    name: string
  }
}

export interface ExamAttempt {
  id: number
  user_id: number
  exam_id: number
  started_at: string
  submitted_at?: string
  time_taken?: number
  score: number
  percentage: number
  passed: boolean
  created_at: string
  updated_at: string
  exam?: Exam
}

export interface Material {
  id: number
  title: string
  description?: string
  file_path?: string
  file_type?: 'image' | 'document' | 'compressed' | 'video'
  file_size?: number
  video_link?: string
  is_active: boolean
  created_by: number
  created_at: string
  updated_at: string
}

export interface Certificate {
  id: number
  user_id: number
  exam_id: number
  attempt_id: number
  certificate_path: string
  certificate_number: string
  issued_at: string
  created_at: string
  updated_at: string
  exam?: Exam
}

export const examService = {
  /**
   * Get available exams for the current user
   */
  async getAvailableExams(): Promise<Exam[]> {
    const response = await api.get<Exam[]>('/exams')
    return response.data
  },

  /**
   * Get exam by ID
   */
  async getExam(id: number): Promise<Exam> {
    const response = await api.get<Exam>(`/exams/${id}`)
    return response.data
  },

  /**
   * Get user's exam attempts
   */
  async getExamAttempts(): Promise<ExamAttempt[]> {
    const response = await api.get<ExamAttempt[]>('/exam-attempts')
    return response.data
  },

  /**
   * Get exam attempt by ID
   */
  async getExamAttempt(id: number): Promise<ExamAttempt> {
    const response = await api.get<ExamAttempt>(`/exam-attempts/${id}`)
    return response.data
  },

  /**
   * Start an exam attempt
   */
  async startExam(examId: number): Promise<ExamAttempt> {
    const response = await api.post<ExamAttempt>(`/exams/${examId}/start`)
    return response.data
  },

  /**
   * Submit exam attempt
   */
  async submitExam(attemptId: number, answers: Record<number, number | string>): Promise<ExamAttempt> {
    const response = await api.post<ExamAttempt>(`/exam-attempts/${attemptId}/submit`, { answers })
    return response.data
  },

  /**
   * Get available materials
   */
  async getMaterials(): Promise<Material[]> {
    const response = await api.get<Material[]>('/materials')
    return response.data
  },

  /**
   * Get material by ID
   */
  async getMaterial(id: number): Promise<Material> {
    const response = await api.get<Material>(`/materials/${id}`)
    return response.data
  },

  /**
   * Download material
   */
  async downloadMaterial(id: number): Promise<Blob> {
    const response = await api.get(`/materials/${id}/download`, {
      responseType: 'blob'
    })
    return response.data
  },

  /**
   * Get user's certificates
   */
  async getCertificates(): Promise<Certificate[]> {
    const response = await api.get<Certificate[]>('/certificates')
    return response.data
  },

  /**
   * Download certificate
   */
  async downloadCertificate(id: number): Promise<Blob> {
    const response = await api.get(`/certificates/${id}/download`, {
      responseType: 'blob'
    })
    return response.data
  }
}
