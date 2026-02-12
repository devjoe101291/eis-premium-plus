<template>
  <div class="px-8 py-6 space-y-6">
    <!-- ✅ PAGE SKELETON -->
    <Skeleton v-if="isLoading" variant="page" />

    <template v-else>
      <!-- TOP HEADER CARD -->
      <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
               rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50
               px-6 py-5 flex items-center gap-4">
        <!-- Title -->
        <div class="min-w-0">
          <h1 class="text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
            Materials
          </h1>
          <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Browse and download available materials
          </p>
        </div>
      </div>

      <!-- SEARCH + FILTER CONTROLS -->
      <div>
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center sm:justify-between">
          <input type="text" v-model="searchQuery" placeholder="Search materials..." class="flex-1 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                   focus:outline-none focus:ring-2 focus:ring-blue-400
                   dark:bg-slate-700 dark:text-slate-200" />

          <!-- Filter by type -->
          <select v-model="filterType" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                   dark:bg-slate-700 dark:text-slate-200">
            <option value="">All Types</option>
            <option value="file">Files</option>
            <option value="url">Links</option>
          </select>

          <!-- Items per page -->
          <select v-model.number="perPage" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                   dark:bg-slate-700 dark:text-slate-200">
            <option :value="5">5 / page</option>
            <option :value="10">10 / page</option>
            <option :value="15">15 / page</option>
          </select>
        </div>
      </div>

      <!-- TABLE -->
      <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
               rounded-2xl shadow-lg border border-slate-200/50
               dark:border-slate-700/50 overflow-hidden">
        <CardTable title="Available Materials">
          <!-- Table -->
          <table class="w-full">
            <thead class="bg-slate-100 dark:bg-slate-700">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Title</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Topic</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Type</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Description</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Actions</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
              <!-- ✅ TABLE SKELETON -->
              <tr v-if="isLoading">
                <td colspan="5" class="px-6 py-8">
                  <Skeleton variant="table" :rows="5" :cols="5" />
                </td>
              </tr>

              <!-- ✅ REAL ROWS -->
              <tr v-else v-for="item in filteredPaginatedItems" :key="item.id"
                class="hover:bg-slate-50/60 dark:hover:bg-slate-900/20 transition">
                <td class="px-6 py-4 text-primary font-medium">
                  {{ item.title }}
                </td>

                <td class="px-6 py-4 text-sm">
                  {{ getTopicTitle(item.topic_id) || '—' }}
                </td>

                <td class="px-6 py-4 text-sm">
                  <span class="px-2 py-1 rounded text-xs font-semibold"
                    :class="item.file_type === 'file' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300'">
                    {{ item.file_type === 'file' ? 'File' : 'Link' }}
                  </span>
                </td>

                <td class="px-6 py-4 text-sm truncate max-w-xs">
                  {{ item.description || '—' }}
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <!-- ✅ Actions (use your reusable IconButton like the reference) -->
                    <div class="flex items-center justify-center gap-2">
                      <!-- View -->
                      <IconButton title="View" variant="neutral" @click="openMaterial(item)">
                        <ViewIcon class="h-4 w-[2em]" />
                      </IconButton>

                      <!-- Download (files only) -->
                      <IconButton v-if="item.file_type === 'file'" title="Download" variant="neutral"
                        @click="downloadMaterial(item)">
                        <DownloadIcon class="h-4 w-[2em]" />
                      </IconButton>
                    </div>

                  </div>
                </td>
              </tr>

              <!-- ✅ EMPTY STATE -->
              <tr v-if="!isLoading && filteredPaginatedItems.length === 0">
                <td colspan="5" class="text-center py-12 text-slate-500">
                  No materials found
                </td>
              </tr>
            </tbody>
          </table>

          <template #footer>
            <div class="justify-center mt-4">
              <PaginationBar :page="page" :total-pages="totalPages" :total-items="totalItems"
                @update:page="page = $event" />
            </div>
          </template>
        </CardTable>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useTitle } from '@/composables/ui/useTitle'
import DownloadIcon from '@/components/icons/DownloadIcon.vue'
import { useTopics } from '@/composables/useTopics'
import { useMaterials } from '@/composables/useMaterials'
import { useSearch } from '@/composables/ui/useSearch'
import { usePagination } from '@/composables/ui/usePaginations'
import api from '@/services/api'
import ViewIcon from '@/components/icons/ViewIcon.vue'
import CardTable from '@/components/layout/CardTable.vue'
import PaginationBar from '@/components/layout/PaginationBar.vue'
import Skeleton from '@/components/ui/Skeleton.vue'
import IconButton from '@/components/ui/IconButton.vue'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Materials`)

// ✅ Skeleton loader
const isLoading = ref(true)

// ✅ per page
const perPage = ref(10)

// ✅ topics data (from composable)
const { topics, fetchTopics } = useTopics()

// ✅ materials data (from composable)
const { materials, fetchMaterials } = useMaterials()

// ✅ only active materials from active topics
const activeMaterials = computed(() => {
  // Get list of active topic IDs
  const activeTopics = (topics.value || []).filter(t => !!t.is_active)
  const activeTopicIds = new Set(activeTopics.map(t => t.id))

  // Filter materials: must be active AND belong to an active topic
  return (materials.value || []).filter(m => {
    return !!m.is_active && activeTopicIds.has(m.topic_id)
  })
})

// ✅ filter by type
const filterType = ref('')

const typeFiltered = computed(() => {
  const list = activeMaterials.value
  if (!filterType.value) return list
  return list.filter(m => m.file_type === filterType.value)
})

// ✅ search
const { query: searchQuery, filtered } = useSearch(() => typeFiltered.value, 'title')

// ✅ pagination
const { page, paginated } = usePagination(() => filtered.value, perPage.value)
const filteredPaginatedItems = computed(() => paginated.value || [])

// ✅ pagination meta
const totalItems = computed(() => filtered.value.length)
const totalPages = computed(() => Math.max(1, Math.ceil(totalItems.value / perPage.value)))

// ✅ get topic title by topic_id
const getTopicTitle = (topicId: number): string => {
  const topic = (topics.value || []).find(t => t.id === topicId)
  return topic?.title || '—'
}

// ✅ get material URL
const getMaterialUrl = (mat: any): string => {
  if (!mat?.file_path) return ''
  if (/^https?:\/\//i.test(mat.file_path)) return mat.file_path

  const rawBase = String(api.defaults.baseURL || '').replace(/\/$/, '')
  const baseNoApi = rawBase.replace(/\/api$/i, '')
  return `${baseNoApi}/storage/${String(mat.file_path).replace(/^\//, '')}`
}

// ✅ open/view material
const openMaterial = (mat: any) => {
  const type = mat.file_type || mat.source_type

  if (type === 'url' || type === 'video') {
    const url = mat.video_link || mat.file_path
    if (!url) return
    window.open(url, '_blank')
    return
  }

  if (!mat.file_path) {
    console.error('File not available for viewing.')
    return
  }

  window.open(getMaterialUrl(mat), '_blank')
}

// ✅ download material
const downloadMaterial = async (material: any) => {
  try {
    const url = getMaterialUrl(material)
    if (!url) {
      console.error('File URL not available for download.')
      return
    }

    const link = document.createElement('a')
    link.href = url
    link.download = material.title || 'material'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (error) {
    console.error('Download failed:', error)
  }
}

// ✅ reset page when filters change
watch([searchQuery, perPage, filterType], () => {
  page.value = 1
})

// ✅ load topics and materials
const refreshData = async () => {
  isLoading.value = true
  try {
    await Promise.all([fetchTopics(), fetchMaterials()])
  } finally {
    isLoading.value = false
  }
}

onMounted(refreshData)
</script>
