// src/config/types/material-form.ts
export interface MaterialForm {
  topic_id: number | null // ✅ required at submit-time
  title: string
  description: string
  source_type: 'file' | 'url'
  source: File | string | null
  is_active: boolean
}
