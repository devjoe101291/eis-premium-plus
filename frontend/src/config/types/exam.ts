export type QuestionType = 'multiple' | 'multiple-answer' | 'true-false' | 'short'

export type Option = {
  letter: string
  text: string
  isCorrect: boolean
}

export type Question = {
  id: number
  type: QuestionType
  text: string
  points: number
  options: Option[]
  correctAnswer: 'true' | 'false'
  expectedAnswer: string
  keywords: string
  caseSensitive: boolean
}

export type ExamUI = {
  title: string
  description: string
  topic_id: number | null
  status: 'active' | 'inactive'
  is_active: boolean
  time_limit: number
  passing_score: number
  category_id: number | null
  exam_type: string | null
  passing_criteria_type: string | null
  questions: Question[] // ✅ ALWAYS EXISTS
}