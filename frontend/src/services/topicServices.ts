import api from './api'
import type { Topic, CreateTopicPayload } from '@/config/types/topic'

export const topicService = {
  async getAll(): Promise<Topic[]> {
    const { data } = await api.get<Topic[]>('/topics')
    return data
  },

  async create(payload: CreateTopicPayload): Promise<Topic> {
    const { data } = await api.post<Topic>('/topics', payload)
    return data
  },

  async remove(id: number): Promise<void> {
    await api.delete(`/topics/${id}`)
  },

  async update(id: number, payload: Partial<CreateTopicPayload>): Promise<Topic> {
    const { data } = await api.put<Topic>(`/topics/${id}`, payload)
    return data
  },
}