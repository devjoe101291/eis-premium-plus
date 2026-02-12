export type Topic = {
  id: number
  title: string
  description: string | null
  is_active: boolean
  created_by: number
  created_at?: string
  updated_at?: string
}

export type CreateTopicPayload = {
  title: string
  description: string | null
  is_active: boolean
  created_by: number
}

export type TopicForm = {
  name: string
  description: string
}