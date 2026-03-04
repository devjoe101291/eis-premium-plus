<template>
  <div class="px-8 py-6 space-y-6">
    <!-- 1. THIS IS REQUIRED BEFORE v-else -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold bg-gradient-to-tr from-primary to-secondary dark:from-primary-light dark:to-secondary-light to-secondary bg-clip-text text-transparent">
        Materials
      </h1>
      <p class="text-slate-600 dark:text-slate-400 mt-2">Browse and download available materials</p>
    </div>

    <div class="space-y-4">
      <!-- STATS BAR -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <StatCard
          label="Total Materials"
          :value="stats.total"
          :icon="BookIcon"
          color="bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400"
        />

        <StatCard
          label="Files"
          :value="stats.files"
          :icon="FileIcon"
          color="bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400"
        />

        <StatCard
          label="Links"
          :value="stats.links"
          :icon="LinkIcon"
          color="bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400"
        />
      </div>

      <!-- SEARCH + FILTER CONTROLS -->
      <TableControls 
        v-model:search="searchQuery" 
        v-model:status="filterType"
        :options="typeFilterOptions"
        filterLabel="Material Type"
      />

      <!-- TABLE -->
      <div>
        <CardTable title="Available Materials">
          <!-- ✅ LOADING SCREEN FOR TABLE ONLY -->
          <LoadingScreen 
            :loading="isLoading"
            v-if="isLoading" 
            message="Loading materials..." 
          />

          <!-- Desktop Table View -->
          <template v-else>
            <div class="hidden lg:block overflow-x-auto">
              <table class="min-w-full divide-y divide-slate-200/50 dark:divide-slate-700/50">
                <!-- Gradient Header -->
                <thead
                  class="bg-gradient-to-r from-primary/5 via-primary/3 to-transparent dark:from-primary-dark/10 dark:via-primary-dark/5 dark:to-transparent"
                >
                  <tr>
                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-bold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                    >
                      Title
                    </th>

                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                    >
                      Topic
                    </th>

                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                    >
                      Type
                    </th>

                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                    >
                      Description
                    </th>

                    <th
                      scope="col"
                      class="px-6 py-4 text-center text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white dark:bg-slate-900/50 divide-y divide-slate-200/50 dark:divide-slate-700/50">
                  <!-- REAL ROWS -->
                  <tr v-for="item in filteredPaginatedItems" :key="item.id">
                    <td class="px-6 py-4 text-sm font-semibold text-primary-text dark:text-primary-dark-text">
                      {{ item.title }}
                    </td>

                    <td class="px-6 py-4 text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
                      {{ getTopicTitle(item.topic_id) || '—' }}
                    </td>

                    <td class="px-6 py-4">
                      <span
                        class="px-3 py-1 text-xs font-semibold rounded-full"
                        :class="item.file_type === 'file' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300'"
                      >
                        {{ item.file_type === 'file' ? 'File' : 'Link' }}
                      </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 max-w-xs truncate">
                      {{ item.description || '—' }}
                    </td>

                    <td class="px-6 py-4 text-center">
                      <div class="flex justify-center gap-2">
                        <!-- View -->
                        <IconButton title="View" variant="primary" @click="openMaterial(item)">
                          <ViewIcon :size="16" />
                        </IconButton>

                        <!-- Download (files only) -->
                        <IconButton v-if="item.file_type === 'file'" title="Download" variant="neutral"
                          @click="downloadMaterial(item)">
                          <DownloadIcon :size="16" />
                        </IconButton>
                      </div>
                    </td>
                  </tr>

                  <!-- EMPTY STATE -->
                  <tr v-if="filteredPaginatedItems.length === 0">
                    <td colspan="5">
                      <div class="text-center py-16 px-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
                          <FileText class="w-8 h-8 text-slate-400 dark:text-slate-500" />
                        </div>
                        <p class="text-base font-medium text-slate-600 dark:text-slate-300">
                          No records found
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                          Try adjusting your search or filters
                        </p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </template>

          <!-- Mobile Card View -->
          <div class="lg:hidden divide-y divide-slate-200/50 dark:divide-slate-700/50">
            <!-- Mobile Cards -->
            <div v-for="item in filteredPaginatedItems" :key="item.id" class="p-4">
              <div class="flex justify-between items-start mb-2">
                <span class="font-semibold text-primary">
                  {{ item.title }}
                </span>
                <span
                  class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="item.file_type === 'file' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300'"
                >
                  {{ item.file_type === 'file' ? 'File' : 'Link' }}
                </span>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                {{ item.description || '—' }}
              </p>
              <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">
                Topic: {{ getTopicTitle(item.topic_id) || '—' }}
              </p>
              <div class="flex gap-2">
                <IconButton title="View" variant="primary" @click="openMaterial(item)">
                  <ViewIcon :size="16" />
                </IconButton>
                <IconButton v-if="item.file_type === 'file'" title="Download" variant="neutral"
                  @click="downloadMaterial(item)">
                  <DownloadIcon :size="16" />
                </IconButton>
              </div>
            </div>

            <!-- Mobile Empty State -->
            <div v-if="filteredPaginatedItems.length === 0" class="text-center py-16 px-4">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
                <FileText class="w-8 h-8 text-slate-400 dark:text-slate-500" />
              </div>
              <p class="text-base font-medium text-slate-600 dark:text-slate-300">
                No records found
              </p>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Try adjusting your search or filters
              </p>
            </div>
          </div>
        </CardTable>

        <!-- Pagination -->
        <div
          v-if="!isLoading && totalItems > 0"
          class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm mt-[1em]"
        >
          <div class="card-body">
            <PaginationBar
              :page="page"
              :total-pages="totalPages"
              :total-items="totalItems"
              :per-page="perPage"
              @update:page="page = $event"
            />
          </div>
        </div>
      </div>
    </div>
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
import { useToast } from '@/composables/ui/useToast'
import api from '@/services/api'
import ViewIcon from '@/components/icons/ViewIcon.vue'
import CardTable from '@/components/layout/CardTable.vue'
import PaginationBar from '@/components/layout/PaginationBar.vue'
import IconButton from '@/components/ui/IconButton.vue'
import TableControls from '@/components/layout/TableControls.vue'
import LoadingScreen from '@/components/ui/LoadingScreen.vue'
import StatCard from '@/components/layout/StatCard.vue'
import { FileText, BookOpen as BookIcon, File as FileIcon, Link as LinkIcon } from 'lucide-vue-next'

const { error: showError, success: showSuccess } = useToast()

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Materials`)

// ✅ Skeleton loader
const isLoading = ref(true)

// ✅ per page
const perPage = ref(5)

// ✅ Filter options for TableControls
const typeFilterOptions = [
  { value: 'all', label: 'All Types' },
  { value: 'file', label: 'Files' },
  { value: 'url', label: 'Links' },
]


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
const filterType = ref('all')

const typeFiltered = computed(() => {
  const list = activeMaterials.value
  if (!filterType.value || filterType.value === 'all') return list
  return list.filter(m => m.file_type === filterType.value)
})

// ✅ stats
const stats = computed(() => {
  const list = activeMaterials.value || []
  const total = list.length
  const files = list.filter((m) => m.file_type === 'file').length
  const links = list.filter((m) => m.file_type === 'url').length
  return { total, files, links }
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
    if (material.file_type !== 'file') return
    const res = await api.get(`/materials/${material.id}/download`, { responseType: 'blob' })
    const blob = new Blob([res.data])
    const blobUrl = URL.createObjectURL(blob)
    const filename = material.file_path?.split('/').pop() ?? 'download'

    const a = document.createElement('a')
    a.href = blobUrl
    a.download = filename
    document.body.appendChild(a)
    a.click()
    a.remove()

    URL.revokeObjectURL(blobUrl)
    showSuccess('Download started.')
  } catch (e: any) {
    console.error('downloadMaterial failed:', e)
    showError(e?.response?.data?.message ?? 'Failed to download file.')
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
