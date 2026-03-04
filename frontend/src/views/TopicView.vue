<template>
<div class="px-8 py-6 space-y-6">
  <!-- 1. THIS IS REQUIRED BEFORE v-else -->

  
    <div class="mb-8">
      <h1 class="text-4xl font-bold bg-gradient-to-tr from-primary to-secondary dark:from-primary-light dark:to-secondary-light to-secondary bg-clip-text text-transparent">
        Topic Materials
      </h1>
      <p class="text-slate-600 dark:text-slate-400 mt-2">Manage and organize your learning topics</p>
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
          label="Active"
          :value="stats.active"
          :icon="ActivateIcon"
          color="bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400"
        />

        <StatCard
          label="Inactive"
          :value="stats.inactive"
          :icon="DeactivateIcon"
          color="bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400"
        />
      </div>

      <!-- SEARCH + FILTER + ADD BUTTON -->
      <TableControls 
  v-model:search="searchQuery" 
  v-model:status="topicStatusFilter"
  :options="myCustomOptions"
  filterLabel="Topic Status"
>
  <template #action>
    <button
            @click="(modalMode = 'add', isModalOpen = true)"
            class="
              px-6 py-2 rounded-lg font-semibold text-white transition
              bg-gradient-to-tr from-primary to-secondary
              shadow-md hover:opacity-90
              dark:bg-gradient-to-tr dark:from-primary/80 dark:to-secondary/80
              dark:shadow-none dark:ring-1 dark:ring-white/10
            "
          >
            Add New Topic
          </button>
  </template>
</TableControls>
      <!-- TABLE ONLY -->
      <div>
        <CardTable title="Topics Management">
           <!-- ✅ LOADING SCREEN FOR TABLE ONLY -->
          <LoadingScreen 
          :loading="isLoading"
            v-if="isLoading" 
            message="Loading topics..." 
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
                    class="px-6 py-4 text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                  >
                    Description
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                  >
                    Status
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                  >
                    Created By
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-4 text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider"
                  >
                    Actions
                  </th>
                </tr>
              </thead>

              <!-- Table Body -->
              <tbody class="bg-white dark:bg-slate-900/50 divide-y divide-slate-200/50 dark:divide-slate-700/50">
                <!-- ✅ REMOVED: Table Skeleton -->
                
                <!-- REAL ROWS -->
                <tr v-for="item in filteredPaginatedItems" :key="item.id">
                  <td class="px-6 py-4 text-sm font-semibold text-primary-text dark:text-primary-dark-text cursor-pointer hover:underline" @click="viewTopic(item.id)">
                    {{ item.title }}
                  </td>

                  <td class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 max-w-xs truncate">
                    {{ item.description || '—' }}
                  </td>

                  <td class="px-6 py-4">
                    <span
                      class="px-3 py-1 text-xs font-semibold rounded-full"
                      :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                    >
                      {{ item.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>

                  <td class="px-6 py-4 text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 max-w-xs">
                    {{ item.creator?.name || '-' }}
                  </td>

                  <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                      <IconButton title="Edit" variant="primary" @click="onEdit(item)">
                        <EditIcon :size="16" />
                      </IconButton>

                      <IconButton
                        :title="item.is_active ? 'Deactivate' : 'Activate'"
                        :variant="item.is_active ? 'warning' : 'success'"
                        @click="toggleStatus(item)"
                      >
                        <DeactivateIcon v-if="item.is_active" :size="16" />
                        <ActivateIcon v-else :size="16" />
                      </IconButton>

                      <IconButton title="Delete" variant="danger" @click="openDeleteModal(item)">
                        <DeleteIcon :size="16" />
                      </IconButton>
                    </div>
                  </td>
                </tr>

                <!-- EMPTY STATE -->
                <!-- ✅ SIMPLIFIED: Removed !isLoading check -->
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
            <!-- ✅ REMOVED: Mobile Skeleton -->

            <!-- Mobile Cards -->
            <div v-for="item in filteredPaginatedItems" :key="item.id" class="p-4">
              <div class="flex justify-between items-start mb-2">
                <span class="font-semibold text-primary cursor-pointer hover:underline" @click="viewTopic(item.id)">
                  {{ item.title }}
                </span>
                <span
                  class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                >
                  {{ item.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                {{ item.description || '—' }}
              </p>
              <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">
                Created by: {{ item.creator?.name || '-' }}
              </p>
              <div class="flex gap-2">
                <IconButton title="Edit" variant="primary" @click="onEdit(item)">
                  <EditIcon :size="16" />
                </IconButton>
                <IconButton
                  :title="item.is_active ? 'Deactivate' : 'Activate'"
                  :variant="item.is_active ? 'warning' : 'success'"
                  @click="toggleStatus(item)"
                >
                  <DeactivateIcon v-if="item.is_active" :size="16" />
                  <ActivateIcon v-else :size="16" />
                </IconButton>
                <IconButton title="Delete" variant="danger" @click="openDeleteModal(item)">
                  <DeleteIcon :size="16" />
                </IconButton>
              </div>
            </div>

            <!-- Mobile Empty State -->
            <!-- ✅ SIMPLIFIED: Removed !isLoading check -->
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

          <!-- Toolbar -->
          <template #toolbar>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search..."
              class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
             focus:outline-none focus:ring-2 focus:ring-blue-400
             dark:bg-slate-700 dark:text-slate-200"
            />
            <button
              @click="(modalMode = 'add', isModalOpen = true)"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg"
            >
              Add New
            </button>
          </template>
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

  <!-- Add / Edit Topic Modal (fused) -->
<Modal v-model="isModalOpen" :title="modalMode === 'add' ? 'Add New Topic' : 'Edit Topic'">
  <div
    class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
           rounded-2xl p-5 shadow-lg border border-slate-200/50 dark:border-slate-700/50
           space-y-4"
  >
    <!-- Title -->
    <div class="space-y-1.5">
      <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">
        Title
      </label>
      <input
        v-model="modalTitle"
        type="text"
        placeholder="e.g. Introduction to Algebra"
        class="w-full rounded-xl px-4 py-3 text-sm
               bg-white/70 dark:bg-slate-900/40
               border border-slate-200/60 dark:border-slate-700/60
               text-slate-800 dark:text-slate-100
               placeholder:text-slate-400 dark:placeholder:text-slate-500
               shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-900
               focus:ring-[#ff8e48]/60"
      />
      <p class="text-[11px] text-slate-500 dark:text-slate-400">
        Keep it short and descriptive.
      </p>
    </div>

    <!-- Description -->
    <div class="space-y-1.5">
      <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">
        Description
      </label>
      <textarea
        v-model="modalDescription"
        rows="4"
        placeholder="Add a brief summary or notes for this material..."
        class="w-full rounded-xl px-4 py-3 text-sm
               bg-white/70 dark:bg-slate-900/40
               border border-slate-200/60 dark:border-slate-700/60
               text-slate-800 dark:text-slate-100
               placeholder:text-slate-400 dark:placeholder:text-slate-500
               shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-900
               focus:ring-[#644d9f]/60"
      />
      <p class="text-[11px] text-slate-500 dark:text-slate-400">
        Optional — you can leave this blank.
      </p>
    </div>
  </div>
  <template #footer>
    <div class="flex items-center justify-end gap-2">
      <button
        type="button"
        class="px-4 py-2 rounded-xl font-semibold text-sm
               bg-slate-50 dark:bg-slate-700/30
               text-slate-700 dark:text-slate-200
               border border-slate-200/60 dark:border-slate-700/60
               hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all"
        @click="isModalOpen = false"
      >
        Cancel
      </button>

      <button
        type="button"
        class="px-4 py-2 rounded-xl font-semibold text-sm text-white
               bg-gradient-to-r from-[#ff8e48] to-[#644d9f]
               shadow-sm hover:shadow-md transition-all
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ff8e48]/60"
        @click="submitModal"
      >
        <span v-if="modalMode === 'add'">Add</span>
        <span v-else>Update</span>
      </button>
    </div>
  </template>

</Modal>

<!-- Delete Confirm Modal -->
<Modal v-model="isDeleteModalOpen" title="Delete Topic">
  <div
    class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
           rounded-2xl p-5 shadow-lg border border-slate-200/50 dark:border-slate-700/50
           space-y-3"
  >
    <p class="text-sm text-slate-700 dark:text-slate-200">
      Are you sure you want to delete this topic?
    </p>

    <div class="rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 p-3">
      <p class="text-xs font-semibold text-rose-700 dark:text-rose-200">
        This action cannot be undone.
      </p>
      <p v-if="deleteTargetTitle" class="text-sm mt-1 text-slate-700 dark:text-slate-200">
        Topic: <span class="font-semibold">{{ deleteTargetTitle }}</span>
      </p>
    </div>
  </div>

  <template #footer>
    <div class="flex items-center justify-end gap-2">
      <button
        type="button"
        class="px-4 py-2 rounded-xl font-semibold text-sm
               bg-slate-50 dark:bg-slate-700/30
               text-slate-700 dark:text-slate-200
               border border-slate-200/60 dark:border-slate-700/60
               hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all"
        @click="closeDeleteModal"
        :disabled="isDeleting"
      >
        Cancel
      </button>

      <button
        type="button"
        class="px-4 py-2 rounded-xl font-semibold text-sm text-white
               bg-rose-600 hover:bg-rose-700
               shadow-sm hover:shadow-md transition-all
               disabled:opacity-60 disabled:cursor-not-allowed"
        @click="confirmDelete"
        :disabled="isDeleting"
      >
        <span v-if="isDeleting">Deleting...</span>
        <span v-else>Delete</span>
      </button>
    </div>
  </template>
</Modal>

</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useTitle } from '@/composables/ui/useTitle'
import { useTopics } from '@/composables/useTopics'
import { useAuthStore } from '@/stores/auth'
import { useSearch } from '@/composables/ui/useSearch'
import { usePagination } from '@/composables/ui/usePaginations'
import { useRouter } from 'vue-router'
import TableControls from '@/components/layout/TableControls.vue'
import ActivateIcon from '@/components/icons/ActivateIcon.vue'
import DeactivateIcon from '@/components/icons/DeactivateIcon.vue'
import EditIcon from '@/components/icons/EditIcon.vue'
import DeleteIcon from '@/components/icons/DeleteIcon.vue'
import BookIcon from '@/components/icons/BookIcon.vue'
import Modal from '@/components/layout/Modal.vue'
import StatCard from '@/components/layout/StatCard.vue'
import CardTable from '@/components/layout/CardTable.vue'
import IconButton from '@/components/ui/IconButton.vue'
import PaginationBar from '@/components/layout/PaginationBar.vue'
import type { CreateTopicPayload } from '@/config/types/topic'
import { topicService } from '@/services/topicServices'
import { useToast } from '@/composables/ui/useToast'
import LoadingScreen from '@/components/ui/LoadingScreen.vue'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Topics`)

// ✅ Skeleton loader flag
const isLoading = ref(true)

// auth
const authStore = useAuthStore()
const currentUserId = computed(() => authStore.user?.id ?? null)
const perPage = ref(5)

// topics data (from composable)
const { topics, fetchTopics, deleteTopic, createTopic, toggleTopicStatus } = useTopics()

// router
const router = useRouter()
const viewTopic = (id: number) => {
  if (!id) return
  router.push({ name: 'topic-details', params: { id } })
}

// modal + form (ADD)
const isModalOpen = ref(false)
const modalMode = ref<'add' | 'edit'>('add')
const form = ref({ name: '', description: '' })
const resetForm = () => {
  form.value = { name: '', description: '' }
}
const { success: showSuccess, error: showError } = useToast()

// ✅ Optional: consistent refresh with loading (use it anywhere you want)
const refreshTopics = async () => {
  isLoading.value = true
  try {
    await fetchTopics()
  } catch (e) {
    console.error('fetchTopics failed:', e)
  } finally {
    isLoading.value = false
  }
}

// delete confirm modal state
const isDeleteModalOpen = ref(false)
const deleteTargetId = ref<number | null>(null)
const deleteTargetTitle = ref<string>('') // optional display
const isDeleting = ref(false)

const openDeleteModal = (item: any) => {
  deleteTargetId.value = item.id
  deleteTargetTitle.value = item.title ?? item.name ?? ''
  isDeleteModalOpen.value = true
}

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false
  deleteTargetId.value = null
  deleteTargetTitle.value = ''
}

// computed proxy for modal fields (points to `form` when adding, `editForm` when editing)
const editingTopicId = ref<number | null>(null)
const editForm = ref({ name: '', description: '' })

const modalTitle = computed({
  get() {
    return modalMode.value === 'add' ? form.value.name : editForm.value.name
  },
  set(v: string) {
    if (modalMode.value === 'add') form.value.name = v
    else editForm.value.name = v
  },
})

const modalDescription = computed({
  get() {
    return modalMode.value === 'add' ? form.value.description : editForm.value.description
  },
  set(v: string) {
    if (modalMode.value === 'add') form.value.description = v
    else editForm.value.description = v
  },
})

const submitModal = async () => {
  if (modalMode.value === 'add') await submitTopic()
  else await submitEditTopic()
}

// toggle status
const toggleStatus = async (item: any) => {
  const prev = item.is_active
  const next = !prev

  // optimistic UI
  item.is_active = next

  try {
    await toggleTopicStatus(item.id, next)
    showSuccess(`Topic ${next ? 'activated' : 'deactivated'} successfully.`)
  } catch (e: any) {
    // rollback
    item.is_active = prev
    showError(e?.response?.data?.message ?? 'Failed to change status.')
  }
}

// stats
const stats = computed(() => {
  const list = topics.value || []
  const total = list.length
  const active = list.filter((t) => t.is_active).length
  const inactive = total - active
  return { total, active, inactive }
})

// dropdown filter (topics)
const topicStatusFilter = ref<'all' | 'active' | 'inactive'>('all')
const statusFiltered = computed(() => {
  const list = topics.value || []
  if (topicStatusFilter.value === 'active') return list.filter((t) => !!t.is_active)
  if (topicStatusFilter.value === 'inactive') return list.filter((t) => !t.is_active)
  return list
})

// add topic
const submitTopic = async () => {
  if (!currentUserId.value) return

  const payload: CreateTopicPayload = {
    title: form.value.name,
    description: form.value.description || null,
    is_active: true,
    created_by: Number(currentUserId.value),
  }

  try {
    await createTopic(payload)
    isModalOpen.value = false
    resetForm()
    await refreshTopics()
    showSuccess('Topic created successfully.')
  } catch (error) {
    console.error('Add topic failed:', error)
    showError('Failed to create topic.')
  }
}

const onEdit = (item: any) => {
  editingTopicId.value = item.id
  editForm.value = {
    name: item.title ?? '',
    description: item.description ?? '',
  }
  modalMode.value = 'edit'
  isModalOpen.value = true
}

const submitEditTopic = async () => {
  if (!editingTopicId.value) return

  try {
    await topicService.update(editingTopicId.value, {
      title: editForm.value.name,
      description: editForm.value.description || null,
    })
    isModalOpen.value = false
    editingTopicId.value = null
    editForm.value = { name: '', description: '' }

    await refreshTopics()
    showSuccess('Topic updated successfully.')
  } catch (error) {
    console.error('Update topic failed:', error)
    showError('Failed to update topic.')
  }
}

// delete
const confirmDelete = async () => {
  if (!deleteTargetId.value) return

  isDeleting.value = true
  try {
    await deleteTopic(deleteTargetId.value)
    showSuccess('Topic deleted successfully.')
    closeDeleteModal()
    await refreshTopics()
  } catch (error: any) {
    console.error('Failed to delete topic:', error)
    showError(error?.response?.data?.message ?? 'Failed to delete topic.')
  } finally {
    isDeleting.value = false
  }
}

// ✅ search + pagination pipeline
const { query: searchQuery, filtered } = useSearch(() => statusFiltered.value, 'title')
const { page, paginated } = usePagination(() => filtered.value, perPage.value)
const filteredPaginatedItems = computed(() => paginated.value || [])

// ✅ pagination meta for PaginationBar
const totalItems = computed(() => filtered.value.length)
const totalPages = computed(() => Math.max(1, Math.ceil(totalItems.value / perPage.value)))

// reset page when filter/search/perPage changes
watch([topicStatusFilter, searchQuery, perPage], () => {
  page.value = 1
})

// mounted
onMounted(async () => {
  // ✅ show skeleton while fetching topics
  await refreshTopics()
})
</script>
