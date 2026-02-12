import { ref, onMounted } from 'vue'
// import axios from 'axios'
import api from '@/services/api'

export interface MaterialStats {
  total: number
  active: number
  inactive: number
}

export function useStatCards() {
  
  const stats = ref<MaterialStats>({
    total: 0,
    active: 0,
    inactive: 0,
  })

  const loading = ref<boolean>(false)
  const error = ref<string | null>(null)

  const fetchStats = async (): Promise<void> => {
    loading.value = true
    error.value = null

    try {
      const { data } = await api.get<MaterialStats>(
        '/api/materials/stats'
      )
      stats.value = data
    } catch (err: any) {
      error.value = err?.message ?? 'Failed to load stats'
    } finally {
      loading.value = false
    }
  }

  onMounted(fetchStats)

  return {
    stats,
    loading,
    error,
    fetchStats,
  }
}
