// src/services/material.service.ts
import api from './api'
import type { Material } from '@/config/types/material'

export const materialService = {
  // GET /materials
  async fetchAll(): Promise<Material[]> {
    const res = await api.get<Material[]>('/materials')
    return res.data
  },

  // GET /materials/:id
  async fetchById(id: number): Promise<Material> {
    const res = await api.get<Material>(`/materials/${id}`)
    return res.data
  },

  // POST /materials (multipart/form-data)
  async create(formData: FormData): Promise<Material> {
    const res = await api.post<Material>('/materials', formData, {
      headers: {
        Accept: 'application/json',
        // 🚫 DO NOT set Content-Type manually
      },
    })
    return res.data
  },

  // DELETE /materials/:id
  async delete(id: number): Promise<void> {
    await api.delete(`/materials/${id}`)
  },

  // OPTIONAL: PATCH /materials/:id/status (only if you have this endpoint)
  // async toggleStatus(id: number): Promise<Material> {
  //   const res = await api.patch<Material>(`/materials/${id}/toggle-status`)
  //   return res.data
  // },
}
