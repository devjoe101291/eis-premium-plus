// src/config/types/material.ts
export interface Material {
  id: number
  topic_id: number // ✅ REQUIRED (DB foreign key)

  title: string
  description?: string | null

  file_type: 'file' | 'url'
  file_path?: string | null
  video_link?: string | null
  file_size?: number | null

  is_active: boolean
  created_by: number

  created_at?: string
  updated_at?: string
}
