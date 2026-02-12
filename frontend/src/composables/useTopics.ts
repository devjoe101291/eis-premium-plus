import { ref } from 'vue'
import { topicService } from '@/services/topicServices'
import type { Topic, CreateTopicPayload } from '@/config/types/topic'

export function useTopics() {
  const topics = ref<Topic[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchTopics = async () => {
    const data = await topicService.getAll()
    topics.value = data
  }

  // ✅ THIS is the missing piece
  const createTopic = async (payload: CreateTopicPayload) => {
    return await topicService.create(payload)
  }

  const deleteTopic = async (id: number) => {
    await topicService.remove(id)
  }

    const toggleTopicStatus = async (id: number, isActive: boolean) => {
    return await topicService.update(id, {
      is_active: isActive,
    })
  }

  // ✅ Make sure it is RETURNED
  return {
    topics,
    loading,
    error,
    fetchTopics,

    // expose create under BOTH names so view can call either
    createTopic,
    addTopic: createTopic,
    toggleTopicStatus,
    deleteTopic,
  }
}
