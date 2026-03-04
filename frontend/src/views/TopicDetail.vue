<template>
<div class="px-8 py-6 space-y-6">

    <!-- TOP HEADER CARD -->
    <div
      class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
             rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50
             px-6 py-5 flex items-center gap-4"
    >
      <!-- Back -->
      <button
        type="button"
        @click="goBack"
        class="h-11 w-11 flex items-center justify-center rounded-xl
               border border-slate-200/70 dark:border-slate-700/60
               bg-white/60 dark:bg-slate-900/30
               hover:bg-slate-50 dark:hover:bg-slate-900/50 transition"
        aria-label="Back"
        title="Back"
      >
        <span class="text-xl leading-none">←</span>
      </button>

      <!-- Title -->
      <div class="min-w-0">
<div class="min-w-0">
  <Skeleton
    v-if="!topicName"
    w="w-64"
    h="h-9"
  />

  <h1
    v-else
    class="text-4xl font-bold bg-gradient-to-tr from-primary to-secondary dark:from-primary-light dark:to-secondary-light bg-clip-text text-transparent"
  >
    {{ topicName }}
  </h1>
</div>


        <p class="text-sm text-slate-500 dark:text-slate-400">
          Topic Details / Materials
        </p>
      </div>
    </div>

    <!-- TABS STRIP -->
    <div
      class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
             rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50
             overflow-hidden"
    >
      <div class="grid grid-cols-2">
        <button
          class="py-4 font-semibold flex items-center justify-center gap-2 transition"
          :class="activeTab === 'materials'
            ? 'bg-white/70 dark:bg-slate-900/30 border-b-4 border-secondary'
            : 'bg-white/40 dark:bg-slate-900/10 hover:bg-white/60 dark:hover:bg-slate-900/20'"
          @click="activeTab = 'materials'"
          type="button"
        >
          <span class="inline-block"><FolderIcon /> </span>
          <span>Materials</span>
        </button>

        <button
          class="py-4 font-semibold flex items-center justify-center gap-2 transition"
          :class="activeTab === 'exams'
            ? 'bg-white/70 dark:bg-slate-900/30 border-b-4 border-secondary'
            : 'bg-white/40 dark:bg-slate-900/10 hover:bg-white/60 dark:hover:bg-slate-900/20'"
          @click="activeTab = 'exams'"
          type="button"
        >
          <span class="inline-block"><PaperIcon /></span>
          <span>Exams</span>
        </button>
      </div>
    </div>

    <!-- CONTENT AREA -->
     <TableControls
  v-model:search="activeSearchQuery"
  v-model:status="activeStatusFilter"
  class="mb-5"
>
  <template #action>
    <button
      v-if="activeTab === 'materials'"
      @click="openAddModal"
      class="px-[1em] py-2 rounded-lg font-semibold text-white transition
             bg-gradient-to-tr from-primary to-secondary shadow-md hover:opacity-90
             dark:bg-gradient-to-tr dark:from-primary/80 dark:to-secondary/80"
    >
      Add New Material
    </button>

    <button
      v-else
      @click="goToExamCreator"
      class="px-[1em] py-2 rounded-lg font-semibold text-white transition
             bg-gradient-to-tr from-primary to-secondary shadow-md hover:opacity-90
             dark:bg-gradient-to-tr dark:from-primary/80 dark:to-secondary/80"
    >
      Add New Exam
    </button>
  </template>
</TableControls>
    <!-- ✅ ONE CardTable for both tabs -->
<CardTable :title="activeTab === 'materials' ? 'Materials' : 'Exams'">

  <!-- Loading Screen -->
  <LoadingScreen
    v-if="isCurrentTabLoading"
    :loading="isCurrentTabLoading"
    :message="activeTab === 'materials' ? 'Loading materials...' : 'Loading exams...'"
  />

  <template v-else>
  <table class="min-w-full">
    <!-- ✅ THEAD switches -->
    <thead class="bg-gradient-to-r from-primary/5 via-primary/3 to-transparent dark:from-primary-dark/10 dark:via-primary-dark/5 dark:to-transparent">
      <tr v-if="activeTab === 'materials'">
        <th class="px-6 py-4 text-left text-xs font-bold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Title</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">File Type</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Status</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Description</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Actions</th>
      </tr>

      <tr v-else>
        <th class="px-6 py-4 text-left text-xs font-bold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Title</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Instructions</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Passing Rate</th>
        <!-- <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Time Limit (min)</th> -->
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Status</th>
        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-text dark:text-primary-dark-text uppercase tracking-wider">Actions</th>
      </tr>
    </thead>

    <!-- ✅ TBODY switches -->
    <tbody class="bg-white dark:bg-slate-900/50 divide-y divide-slate-200/50 dark:divide-slate-700/50">
      <!-- ================= MATERIALS ================= -->
      <template v-if="activeTab === 'materials'">
        <tr v-if="error">
          <td colspan="5" class="px-6 py-12 text-center text-red-600 dark:text-red-400">
            {{ error }}
          </td>
        </tr>
        <tr v-else-if="filtered.length === 0">
          <td colspan="5">
            <div class="text-center py-16 px-4">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
                <FileText class="w-8 h-8 text-slate-400 dark:text-slate-500" />
              </div>
              <p class="text-base font-medium text-slate-600 dark:text-slate-300">No records found</p>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Try adjusting your search or filters</p>
            </div>
          </td>
        </tr>
        <tr v-else v-for="m in paginated" :key="m.id">
          <td class="px-6 py-4 text-primary">{{ m.title }}</td>
          <td class="px-6 py-4">{{ m.file_type }}</td>
          <td class="px-6 py-4">
            <span
              class="px-3 py-1 text-xs font-semibold rounded-full"
              :class="m.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
            >
              {{ m.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td class="px-6 py-4 text-sm max-w-xs truncate">{{ m.description || '—' }}</td>
          <td class="px-6 py-4">
            <div class="flex gap-2">
              <IconButton
                :disabled="!(m.file_type === 'file' ? m.file_path : m.video_link)"
                title="View"
                variant="neutral"
                @click="openMaterial(m)"
              >
                <ViewIcon />
              </IconButton>

              <IconButton
                v-if="m.file_type === 'file' && m.file_path"
                title="Download"
                variant="neutral"
                @click="downloadMaterial(m)"
              >
                <DownloadIcon />
              </IconButton>

              <IconButton title="Edit" variant="primary" @click="onEdit(m)">
                <EditIcon />
              </IconButton>

              <IconButton
                :title="m.is_active ? 'Deactivate' : 'Activate'"
                :variant="m.is_active ? 'warning' : 'success'"
                @click="toggleStatus(m)"
              >
                <DeactivateIcon v-if="m.is_active" />
                <ActivateIcon v-else />
              </IconButton>

<IconButton title="Delete" variant="danger" @click="openDeleteMaterialModal(m)">
  <DeleteIcon />
</IconButton>

            </div>
          </td>
        </tr>
      </template>

      <!-- ================= EXAMS ================= -->
      <template v-else>
       <tr v-if="examsError">
    <td colspan="6" class="px-6 py-12 text-center text-red-600">{{ examsError }}</td>
  </tr>

  <tr v-else-if="topicExams.length === 0">
    <td colspan="6">
      <div class="text-center py-16 px-4">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
          <FileText class="w-8 h-8 text-slate-400 dark:text-slate-500" />
        </div>
        <p class="text-base font-medium text-slate-600 dark:text-slate-300">No exams found for this topic</p>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Try adjusting your search or filters</p>
      </div>
    </td>
  </tr>
<tr v-else v-for="e in examsPaginated" :key="e.id">
  <td class="px-6 py-4 text-primary">{{ e.title }}</td>

  <td class="px-6 py-4 text-sm max-w-xs truncate">
    {{ e.instructions || '—' }}
  </td>

  <!-- ✅ Passing Rate as percent -->
  <td class="px-6 py-4">
    {{ formatPassingRate(e) }}
  </td>

  <!-- <td class="px-6 py-4">
    {{ e.time_limit ?? '—' }}
  </td> -->

  <!-- ✅ Status badge: Active/Inactive -->
  <td class="px-6 py-4">
    <span
      class="px-3 py-1 text-xs font-semibold rounded-full"
      :class="isExamActive(e) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
    >
      {{ isExamActive(e) ? 'Active' : 'Inactive' }}
    </span>
  </td>

  <!-- ✅ Actions -->
  <td class="px-6 py-4">
    <div class="flex gap-2">
      <IconButton title="View" variant="neutral" @click="openExamModal(e.id)">
        <ViewIcon />
      </IconButton>

      <IconButton
        title="Edit"
        variant="primary"
        @click="() => router.push({ name: 'exam-edit', params: { id: e.id } })"
      >
        <EditIcon />
      </IconButton>

      <!-- ✅ NEW: Toggle Status icon -->
      <IconButton
        :title="isExamActive(e) ? 'Deactivate' : 'Activate'"
        :variant="isExamActive(e) ? 'warning' : 'success'"
        @click="toggleExamStatus(e)"
      >
        <DeactivateIcon v-if="isExamActive(e)" />
        <ActivateIcon v-else />
      </IconButton>

<IconButton title="Delete" variant="danger" @click="openDeleteExamModal(e)">
  <DeleteIcon />
</IconButton>

    </div>
  </td>
</tr>

      </template>

      
    </tbody>
  </table>
  </template>

</CardTable>

<!-- Pagination outside CardTable -->
<div
  v-if="activeTotalItems > 0"
  class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm mt-[1em]"
>
  <div class="card-body">
    <PaginationBar
      :page="activePage"
      :total-pages="activeTotalPages"
      :total-items="activeTotalItems"
      @update:page="activePage = $event"
    />
  </div>
</div>
      <!-- Add / Edit Material Modal (fused) -->
      <Modal v-model="isModalOpen" :title="modalMode === 'add' ? 'Add Material' : 'Edit Material'">
        <div
          class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
                 rounded-2xl p-5 shadow-lg border border-slate-200/50 dark:border-slate-700/50
                 space-y-4"
        >
          <!-- Title -->
          <div>
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Title</label>
            <input
              v-model="form.title"
              type="text"
              class="mt-1 w-full rounded-xl border px-3 py-2 bg-white dark:bg-slate-900"
              placeholder="Enter title"
            />
          </div>

          <!-- File Type -->
          <div class="mt-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">File Type</label>
            <select
              v-model="form.source_type"
              class="mt-1 w-full rounded-xl border px-3 py-2 bg-white dark:bg-slate-900"
            >
              <option value="file">file</option>
              <option value="url">url</option>
            </select>
          </div>

          <!-- File Upload OR URL -->
          <div class="mt-4" v-if="form.source_type === 'file'">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">File</label>

            <!-- Dropzone -->
            <div
              class="mt-2 rounded-2xl border-2 border-dashed p-6 cursor-pointer select-none
                     bg-white/60 dark:bg-slate-900/30
                     border-slate-300/70 dark:border-slate-700/60
                     hover:bg-white/80 dark:hover:bg-slate-900/40
                     transition"
              :class="isDragOver ? 'ring-2 ring-primary/50 border-primary/60' : ''"
              @click="openFilePicker"
              @dragenter.prevent="onDragEnter"
              @dragover.prevent="onDragOver"
              @dragleave.prevent="onDragLeave"
              @drop.prevent="onDrop"
            >
<div class="flex items-center gap-4 w-full">
  <div
    class="h-11 w-11 rounded-xl flex items-center justify-center
           bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-200"
  >
    <UploadIcon />
  </div>

  <div class="min-w-0 flex-1">
    <!-- If there's a preview, show it -->
    <template v-if="filePreviewUrl && filePreviewType">
      <div class="flex items-start gap-4">
        <!-- IMAGE PREVIEW (non-scroll) -->
<div
  v-if="filePreviewType === 'image'"
  class="w-full max-w-l h-48 rounded-xl overflow-hidden
         border border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/30"
>
  <img
    :src="filePreviewUrl"
    alt="Selected image preview"
    class="w-full h-full object-contain"
  />
</div>


        <!-- PDF PREVIEW -->
        <iframe
          v-else-if="filePreviewType === 'pdf'"
          :src="filePreviewUrl"
          class="w-full h-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white"
        />

        <!-- <div class="min-w-0">
          <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
            Selected file
          </p>
          <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
            {{ selectedFileName }}
          </p>
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
            Click to replace
          </p>
        </div> -->
      </div>
    </template>

    <!-- No file selected -->
    <template v-else>
      <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
        Drag &amp; drop your file here
      </p>
      <p class="text-xs text-slate-500 dark:text-slate-400">
        or click to browse (backend expects key: <b>file</b>)
      </p>
    </template>
  </div>
</div>

            </div>

            <!-- Hidden input -->
            <input
              ref="fileInput"
              type="file"
              class="hidden"
              @change="handleFileChange"
            />
          </div>

          <div class="mt-4" v-else>
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Video Link</label>
            <input
              v-model="form.source"
              type="text"
              class="mt-1 w-full rounded-xl border px-3 py-2 bg-white dark:bg-slate-900"
              placeholder="https://..."
            />
          </div>

          <!-- Description -->
          <div class="mt-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Description</label>
            <textarea
              v-model="form.description"
              class="mt-1 w-full rounded-xl border px-3 py-2 bg-white dark:bg-slate-900"
              rows="3"
              placeholder="Optional description"
            />
            <p class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
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

      <Modal v-model="isExamModalOpen" :title="examModalTitle">
  <div v-if="isExamModalLoading" class="py-10 text-center text-slate-500">
    Loading exam...
  </div>

  <div v-else-if="examModalError" class="py-10 text-center text-red-600">
    {{ examModalError }}
  </div>

<div v-else class="space-y-5">
    <!-- Summary row (like screenshot) -->
    <div class="rounded-xl bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700 p-4 flex flex-wrap gap-6 text-sm">
      <div><b>Time Limit:</b> {{ examModalData.time_limit ?? '—' }} minutes</div>
      <div><b>Passing Rate:</b> {{ examModalData.passing_rate ?? examModalData.passing_score ?? '—' }}%</div>
      <div><b>Questions:</b> {{ examModalQuestions.length }}</div>
    </div>

    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Questions &amp; Answers</h3>

    <!-- Questions list -->
    <div v-for="(q, idx) in examModalQuestions" :key="q.id" class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
      <!-- header -->
      <div class="px-5 py-4 bg-slate-50 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold">
            {{ idx + 1 }}
          </span>
          <span class="font-semibold text-slate-800 dark:text-slate-100">
            {{ labelType(q.type) }}
          </span>
          <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">
            {{ Number(q.points || 0) }} points
          </span>
        </div>
      </div>

      <!-- body -->
      <div class="p-5 space-y-4">
        <p class="font-semibold text-slate-800 dark:text-slate-100">
          {{ q.text }}
        </p>

        <!-- OPTIONS (multiple / multiple-answer) -->
        <div v-if="q.type === 'multiple' || q.type === 'multiple-answer'" class="space-y-2">
          <p class="text-xs font-semibold text-slate-500">OPTIONS:</p>

          <div
            v-for="opt in (q.options || [])"
            :key="opt.letter"
            class="flex items-center gap-3 p-3 rounded-xl border"
            :class="isCorrectOption(q, opt.letter)
              ? 'border-emerald-300 bg-emerald-50 dark:bg-emerald-500/10'
              : 'border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/20'"
          >
            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold">
              {{ opt.letter }}
            </span>
            <span class="flex-1 text-slate-800 dark:text-slate-100">{{ opt.text }}</span>

            <span v-if="isCorrectOption(q, opt.letter)" class="text-emerald-600 font-bold">
              ✓
            </span>
          </div>

          <div class="mt-2 text-sm font-semibold text-emerald-700 dark:text-emerald-300">
            Correct Answer: {{ correctAnswerLabel(q) }}
          </div>
        </div>

        <!-- TRUE/FALSE -->
        <div v-else-if="q.type === 'true-false'" class="text-sm">
          <div class="text-emerald-700 dark:text-emerald-300 font-semibold">
            Correct Answer: {{ correctAnswerLabel(q) }}
          </div>
        </div>

        <!-- SHORT -->
        <div v-else class="text-sm space-y-1">
          <div class="text-emerald-700 dark:text-emerald-300 font-semibold">
            Expected Answer: {{ correctAnswerLabel(q) }}
          </div>
          <div v-if="q.keywords" class="text-slate-500">
            Keywords: {{ q.keywords }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <template #footer>
    <div class="flex justify-end gap-2">
      <button
        type="button"
        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700"
        @click="isExamModalOpen = false"
      >
        Close
      </button>

      <button
        v-if="examModalData?.id"
        type="button"
        class="px-4 py-2 rounded-xl font-semibold text-white bg-gradient-to-r from-primary to-secondary"
        @click="router.push({ name: 'exam-edit', params: { id: examModalData.id } })"
      >
        Edit Exam
      </button>
    </div>
  </template>
</Modal>
<!-- ✅ Delete Confirmation Modal (Material OR Exam) -->
<Modal v-model="isDeleteModalOpen" :title="deleteTitle">
  <div
    class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm
           rounded-2xl p-5 shadow-lg border border-slate-200/50 dark:border-slate-700/50
           space-y-3"
  >
    <p class="text-sm text-slate-700 dark:text-slate-200">
      {{ deleteMessage }}
    </p>

    <div class="rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 p-3">
      <p class="text-xs font-semibold text-rose-700 dark:text-rose-200">
        This action cannot be undone.
      </p>

      <p class="text-sm mt-1 text-slate-700 dark:text-slate-200">
        {{ deleteLabel }}:
        <span class="font-semibold">{{ deleteTargetTitle || '—' }}</span>
      </p>
    </div>
  </div>

  <template #footer>
    <div class="flex justify-end gap-2">
      <button
        type="button"
        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700"
        @click="isDeleteModalOpen = false"
        :disabled="isDeleting"
      >
        Cancel
      </button>

      <button
        type="button"
        class="px-4 py-2 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700 transition disabled:opacity-60"
        @click="confirmDelete"
        :disabled="isDeleting || !deleteTargetId"
      >
        <span v-if="isDeleting">Deleting...</span>
        <span v-else>Delete</span>
      </button>
    </div>
  </template>
</Modal>

  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTitle } from '@/composables/ui/useTitle'
import UploadIcon from '@/components/icons/UploadIcon.vue'
import CardTable from '@/components/layout/CardTable.vue'
import IconButton from '@/components/ui/IconButton.vue'
import ViewIcon from '@/components/icons/ViewIcon.vue'
import EditIcon from '@/components/icons/EditIcon.vue'
import DeleteIcon from '@/components/icons/DeleteIcon.vue'
import ActivateIcon from '@/components/icons/ActivateIcon.vue'
import DeactivateIcon from '@/components/icons/DeactivateIcon.vue'
import Modal from '@/components/layout/Modal.vue'
import DownloadIcon from '@/components/icons/DownloadIcon.vue'
import type { Material } from '@/config/types/material'
import type { MaterialForm } from '@/config/types/material-form'
import { useToast } from '@/composables/ui/useToast'
import { useAuthStore } from '@/stores/auth'
import { useMaterials } from '@/composables/useMaterials'
import api from '@/services/api'
import PaginationBar from '@/components/layout/PaginationBar.vue'
import TableControls from '@/components/layout/TableControls.vue'
import { useSearch } from '@/composables/ui/useSearch'
import { usePagination } from '@/composables/ui/usePaginations'
import Skeleton from '@/components/ui/Skeleton.vue'
import LoadingScreen from '@/components/ui/LoadingScreen.vue'
import FolderIcon from '@/components/icons/FolderIcon.vue'
import PaperIcon from '@/components/icons/PaperIcon.vue'
import { FileText } from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
const { setTitle } = useTitle(`${appTitle.value} - Topic Details`)

// ✅ TopicDetail page: route.params.id is the TOPIC id
const topicId = computed(() => Number(route.params.id))

// UI state
const activeTab = ref<'materials' | 'exams'>('materials')
const isModalOpen = ref(false)
const modalMode = ref<'add' | 'edit'>('add')
const editingMaterialId = ref<number | null>(null)
const isSubmitting = ref(false)

// materials loading state
const isLoading = ref(true)
const error = ref<string | null>(null)

// ✅ Materials filter
const materialStatusFilter = ref<'all' | 'active' | 'inactive'>('all')

// ✅ Exams filter
const examStatusFilter = ref<'all' | 'active' | 'inactive'>('all')
const examSearchQuery = ref('')

// auth
const authStore = useAuthStore()
const currentUserId = computed(() => authStore.user?.id ?? null)
const currentUserLabel = computed(() => authStore.user?.name ?? '—')

// materials store/composable
const { materials, fetchMaterials, addMaterial, removeMaterial } = useMaterials()
const { error: showError, success: showSuccess } = useToast()

// ---------- Drag & Drop Upload state ----------
const fileInput = ref<HTMLInputElement | null>(null)
const isDragOver = ref(false)

const openFilePicker = () => fileInput.value?.click()
const onDragEnter = () => (isDragOver.value = true)
const onDragOver = () => (isDragOver.value = true)
const onDragLeave = () => (isDragOver.value = false)

const onDrop = (e: DragEvent) => {
  isDragOver.value = false
  const file = e.dataTransfer?.files?.[0] ?? null
  if (!file) return

  form.value.source = file
  setFilePreview(file)

  if (fileInput.value) fileInput.value.value = ''
}

const selectedFileName = computed(() => {
  const src = form.value.source

  if (src instanceof File) {
    return src.name // ✅ filename only
  }

  if (typeof src === 'string' && src.length) {
    return src.split('/').pop() || ''
  }

  return ''
})

// Preview for selected file (image/pdf)
const filePreviewUrl = ref<string>('')
const filePreviewType = ref<'image' | 'pdf' | ''>('')

const setFilePreview = (file: File | null) => {
  // cleanup old url
  if (filePreviewUrl.value) URL.revokeObjectURL(filePreviewUrl.value)

  filePreviewUrl.value = ''
  filePreviewType.value = ''

  if (!file) return

  const t = file.type || ''
  filePreviewUrl.value = URL.createObjectURL(file)

  if (t.startsWith('image/')) filePreviewType.value = 'image'
  else if (t === 'application/pdf') filePreviewType.value = 'pdf'
  else filePreviewType.value = ''
}

// --------------------------------------------

// ✅ Only materials for clicked topic
const topicMaterials = computed(() => {
  const tid = Number(topicId.value)
  return (materials.value || []).filter((m: any) => Number(m.topic_id) === tid)
})

// ✅ status filtered materials
const statusFiltered = computed(() => {
  const list = topicMaterials.value || []
  if (materialStatusFilter.value === 'active') return list.filter((m) => m.is_active)
  if (materialStatusFilter.value === 'inactive') return list.filter((m) => !m.is_active)
  return list
})

// ✅ materials search + pagination
const perPage = ref(5)
const { query: searchQuery, filtered } = useSearch(() => statusFiltered.value, 'title')
const { page, paginated } = usePagination(() => filtered.value, perPage.value)

const totalItems = computed(() => filtered.value.length)
const totalPages = computed(() => Math.max(1, Math.ceil(totalItems.value / perPage.value)))

// ✅ reset materials paging when filters change
watch([materialStatusFilter, searchQuery, perPage], () => {
  page.value = 1
})

// ====================== EXAMS ======================
const exams = ref<any[]>([])
const isExamsLoading = ref(false)
const examsError = ref<string | null>(null)

// ✅ exams list filtered by topic + status + search
const topicExams = computed(() => {
  const tid = Number(topicId.value)

  let list = (exams.value || []).filter((e: any) => {
    const examTopicId = Number(e.category_id ?? e.topic_id ?? e.fk_topic_id ?? 0)
    return examTopicId === tid
  })

  if (examStatusFilter.value === 'active') list = list.filter((e: any) => isExamActive(e))
  if (examStatusFilter.value === 'inactive') list = list.filter((e: any) => !isExamActive(e))

  const q = examSearchQuery.value.trim().toLowerCase()
  if (q) {
    list = list.filter((e: any) => {
      const title = String(e.title ?? '').toLowerCase()
      const instr = String(e.instructions ?? e.description ?? '').toLowerCase()
      return title.includes(q) || instr.includes(q)
    })
  }

  return list
})


// ✅ exams pagination (NEW)
const examsPerPage = ref(5)
const examsPage = ref(1)

const examsTotalItems = computed(() => topicExams.value.length)
const examsTotalPages = computed(() => Math.max(1, Math.ceil(examsTotalItems.value / examsPerPage.value)))

const examsPaginated = computed(() => {
  const start = (examsPage.value - 1) * examsPerPage.value
  return topicExams.value.slice(start, start + examsPerPage.value)
})

// ✅ reset exams paging when filters change
watch([examStatusFilter, examSearchQuery, examsPerPage], () => {
  examsPage.value = 1
})

// ✅ ONE TableControls for BOTH tabs
const activeSearchQuery = computed({
  get: () => (activeTab.value === 'materials' ? searchQuery.value : examSearchQuery.value),
  set: (v: string) => {
    if (activeTab.value === 'materials') searchQuery.value = v
    else examSearchQuery.value = v
  },
})

const activeStatusFilter = computed({
  get: () => (activeTab.value === 'materials' ? materialStatusFilter.value : examStatusFilter.value),
  set: (v: 'all' | 'active' | 'inactive') => {
    if (activeTab.value === 'materials') materialStatusFilter.value = v
    else examStatusFilter.value = v
  },
})

// ✅ ONE PaginationBar for BOTH tabs
const activePage = computed({
  get: () => (activeTab.value === 'materials' ? page.value : examsPage.value),
  set: (v: number) => {
    if (activeTab.value === 'materials') page.value = v
    else examsPage.value = v
  },
})

const activeTotalItems = computed(() =>
  activeTab.value === 'materials' ? totalItems.value : examsTotalItems.value
)

const activeTotalPages = computed(() =>
  activeTab.value === 'materials' ? totalPages.value : examsTotalPages.value
)

// ✅ computed loading state for the active tab
const isCurrentTabLoading = computed(() =>
  activeTab.value === 'materials' ? isLoading.value : isExamsLoading.value
)

// ✅ reset current tab page when switching tab (nice UX)
watch(
  () => activeTab.value,
  (v) => {
    if (v === 'materials') page.value = 1
    else examsPage.value = 1
  }
)

// form
const form = ref<MaterialForm>({
  topic_id: null,
  title: '',
  description: '',
  source_type: 'file',
  source: null,
  is_active: true,
})

const openAddModal = () => {
  modalMode.value = 'add'
  editingMaterialId.value = null
  form.value = {
    topic_id: Number.isFinite(topicId.value) ? topicId.value : null,
    title: '',
    description: '',
    source_type: 'file',
    source: null,
    is_active: true,
  }

  isDragOver.value = false
  if (fileInput.value) fileInput.value.value = ''
  isModalOpen.value = true
}

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0] ?? null
  form.value.source = file
  setFilePreview(file)
}

const goBack = () => router.back()

const downloadMaterial = async (m: Material) => {
  try {
    if (m.file_type !== 'file') return
    const res = await api.get(`/materials/${m.id}/download`, { responseType: 'blob' })
    const blob = new Blob([res.data])
    const blobUrl = URL.createObjectURL(blob)
    const filename = m.file_path?.split('/').pop() ?? 'download'

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

// ✅ Fetch materials for this topic
const fetchMaterialsForTopic = async () => {
  isLoading.value = true
  error.value = null

  try {
    if (!Number.isFinite(topicId.value)) {
      const msg = 'Invalid topic id in route.'
      error.value = msg
      showError(msg)
      return
    }

    await fetchMaterials({ topic_id: topicId.value })
  } catch (e: any) {
    console.error('fetchMaterialsForTopic failed:', e)
    const msg = e?.response?.data?.message ?? e?.message ?? 'Failed to load materials.'
    error.value = msg
    showError(msg)
  } finally {
    isLoading.value = false
  }
}

const submitMaterial = async () => {
  if (!currentUserId.value) {
    showError('You must be logged in.')
    return
  }

  if (!form.value.topic_id) {
    showError('Topic is required. Please select a topic.')
    return
  }

  isSubmitting.value = true
  error.value = null

  try {
    const fd = new FormData()
    fd.append('topic_id', String(form.value.topic_id))
    fd.append('title', form.value.title)
    fd.append('description', form.value.description || '')
    fd.append('file_type', form.value.source_type)
    fd.append('is_active', form.value.is_active ? '1' : '0')
    fd.append('created_by', String(currentUserId.value))

    if (form.value.source_type === 'file') {
      if (!(form.value.source instanceof File)) {
        showError('Please select a file.')
        return
      }
      fd.append('file', form.value.source)
    } else {
      let link = typeof form.value.source === 'string' ? form.value.source.trim() : ''
      if (!link) {
        showError('Please enter a video link.')
        return
      }
      if (!/^[a-zA-Z][a-zA-Z\d+\-.]*:\/\//.test(link)) link = 'https://' + link
      fd.append('video_link', link)
    }

    await addMaterial(fd)
    isModalOpen.value = false
    showSuccess('Material added.')
    await fetchMaterialsForTopic()
  } catch (e: any) {
    console.error('submitMaterial failed:', e)
    showError(e?.response?.data?.message ?? 'Failed to create material.')
  } finally {
    isSubmitting.value = false
  }
}

const submitModal = async () => {
  if (modalMode.value === 'add') return await submitMaterial()
  if (!editingMaterialId.value) return

  isSubmitting.value = true
  error.value = null

  try {
    const fd = new FormData()
    fd.append('topic_id', String(form.value.topic_id))
    fd.append('title', form.value.title)
    fd.append('description', form.value.description || '')
    fd.append('file_type', form.value.source_type)
    fd.append('is_active', form.value.is_active ? '1' : '0')

    if (form.value.source_type === 'file') {
      if (form.value.source instanceof File) fd.append('file', form.value.source)
    } else {
      let link = typeof form.value.source === 'string' ? form.value.source.trim() : ''
      if (!link) {
        showError('Please enter a video link.')
        return
      }
      if (!/^[a-zA-Z][a-zA-Z\d+\-.]*:\/\//.test(link)) link = 'https://' + link
      fd.append('video_link', link)
    }

    fd.append('_method', 'PUT')
    await api.post(`/materials/${editingMaterialId.value}`, fd)

    isModalOpen.value = false
    editingMaterialId.value = null
    showSuccess('Material updated.')
    await fetchMaterialsForTopic()
  } catch (e: any) {
    console.error('submitModal (edit) failed:', e)
    showError(e?.response?.data?.message ?? 'Failed to update material.')
  } finally {
    isSubmitting.value = false
  }
}

const onEdit = (item: Material) => {
  modalMode.value = 'edit'
  editingMaterialId.value = item.id

  form.value = {
    topic_id: Number.isFinite(topicId.value) ? topicId.value : null,
    title: item.title || '',
    description: item.description || '',
    source_type: item.file_type === 'file' ? 'file' : 'url',
    source: item.file_type === 'url' ? (item.video_link || '') : null,
    is_active: !!item.is_active,
  }


  isDragOver.value = false
  if (fileInput.value) fileInput.value.value = ''
  isModalOpen.value = true
}

const onDelete = async (materialId: number) => {
  try {
    await removeMaterial(materialId)
    showSuccess('Material deleted.')
    await fetchMaterialsForTopic()
  } catch (e: any) {
    console.error('onDelete failed:', e)
    showError(e?.response?.data?.message ?? 'Failed to delete material.')
  }
}

const getMaterialUrl = (mat: any) => {
  if (!mat?.file_path) return ''
  if (/^https?:\/\//i.test(mat.file_path)) return mat.file_path

  const rawBase = String(api.defaults.baseURL || '').replace(/\/$/, '')
  const baseNoApi = rawBase.replace(/\/api$/i, '')
  return `${baseNoApi}/storage/${String(mat.file_path).replace(/^\//, '')}`
}

const openMaterial = (mat: any) => {
  const type = mat.file_type || mat.source_type

  if (type === 'url' || type === 'video') {
    const url = mat.video_link || mat.file_path
    if (!url) return
    window.open(url, '_blank')
    return
  }

  if (!mat.file_path) {
    showError('File not available for download.')
    return
  }

  window.open(getMaterialUrl(mat), '_blank')
}

const toggleStatus = async (m: Material) => {
  try {
    const fd = new FormData()
    fd.append('topic_id', String((m as any).topic_id ?? topicId.value ?? ''))
    fd.append('title', m.title || '')
    fd.append('description', (m as any).description || '')
    fd.append('file_type', (m as any).file_type || ((m as any).video_link ? 'url' : 'file'))
    fd.append('is_active', m.is_active ? '0' : '1')
    if ((m as any).file_type === 'url' || (!(m as any).file_type && (m as any).video_link)) {
      fd.append('video_link', (m as any).video_link || '')
    }
    fd.append('_method', 'PUT')

    await api.post(`/materials/${m.id}`, fd)
    m.is_active = !m.is_active
    showSuccess('Status updated.')
  } catch (e: any) {
    console.error('toggleStatus failed:', e)
    showError(e?.response?.data?.message ?? 'Failed to update status')
  }
}

// ✅ fetch exams
const fetchExamsForTopic = async () => {
  isExamsLoading.value = true
  examsError.value = null

  try {
    if (!Number.isFinite(topicId.value)) {
      const msg = 'Invalid topic id in route.'
      examsError.value = msg
      showError(msg)
      return
    }

    const res = await api.get('/exams', { params: { topic_id: topicId.value } })

    const d = res?.data
    if (Array.isArray(d)) exams.value = d
    else if (Array.isArray(d?.data)) exams.value = d.data
    else if (Array.isArray(d?.exams)) exams.value = d.exams
    else if (Array.isArray(d?.data?.data)) exams.value = d.data.data
    else {
      const maybeArray = Object.values(d || {}).find((v) => Array.isArray(v))
      exams.value = Array.isArray(maybeArray) ? (maybeArray as any[]) : []
    }
  } catch (e: any) {
    console.error('fetchExamsForTopic failed:', e)
    const msg = e?.response?.data?.message ?? e?.message ?? 'Failed to load exams.'
    examsError.value = msg
    showError(msg)
  } finally {
    isExamsLoading.value = false
  }
}

// if topic changes, refresh both
watch(
  () => activeTab.value,
  async (v) => {
    if (v === 'materials') {
      await fetchMaterialsForTopic()
    } else {
      await fetchExamsForTopic()
    }
  }
)

// placeholder for "Add New Exam"
const goToExamCreator = () => {
  router.push({ name: 'exam-create', query: { topic_id: String(topicId.value) } })
}

// ============ Exam View Modal State ============
const isExamModalOpen = ref(false)
const isExamModalLoading = ref(false)
const examModalError = ref<string | null>(null)

const examModalData = ref<any>({})
const examModalQuestions = ref<any[]>([])

const examModalTitle = computed(() => String(examModalData.value?.title ?? 'Exam'))

// helper: normalize answer_json vs answers_json
const getAnswerArray = (data: any) => {
  if (Array.isArray(data?.answer_json)) return data.answer_json
  if (Array.isArray(data?.answers_json)) return data.answers_json
  return []
}

const labelType = (t: string) => {
  if (t === 'multiple') return 'Multiple Choice'
  if (t === 'multiple-answer') return 'Multiple Answer'
  if (t === 'true-false') return 'True / False'
  return 'Short Answer'
}

const openExamModal = async (examId: number) => {
  isExamModalOpen.value = true
  isExamModalLoading.value = true
  examModalError.value = null
  examModalData.value = {}
  examModalQuestions.value = []

  try {
    const res = await api.get(`/exams/${examId}`)
    const data = res?.data?.data ?? res?.data

    examModalData.value = data || {}

    const questionJson = Array.isArray(data?.question_json) ? data.question_json : []
    const answerJson = getAnswerArray(data)

    // attach answers to questions by question_id
    examModalQuestions.value = questionJson.map((q: any) => {
      const qid = Number(q?.id)
      const a = (answerJson as any[]).find((x) => Number(x?.question_id) === qid)

      return {
        id: qid,
        type: q?.type,
        text: q?.text ?? '',
        points: Number(q?.points ?? 0),
        options: Array.isArray(q?.options) ? q.options : [],
        keywords: q?.keywords ?? '',
        caseSensitive: !!q?.caseSensitive,
        // keep original answer row
        _answer: a ?? null,
      }
    })
  } catch (e: any) {
    console.error('openExamModal failed:', e)
    examModalError.value = e?.response?.data?.message ?? e?.message ?? 'Failed to load exam.'
  } finally {
    isExamModalLoading.value = false
  }
}

// correct option check for highlighting
const isCorrectOption = (q: any, letter: string) => {
  const a = q?._answer
  if (!a) return false

  if (q.type === 'multiple') {
    return String(a.answer ?? '') === String(letter)
  }

  if (q.type === 'multiple-answer') {
    return Array.isArray(a.answer) && a.answer.map(String).includes(String(letter))
  }

  return false
}

const correctAnswerLabel = (q: any) => {
  const a = q?._answer
  if (!a) return '—'

  if (q.type === 'multiple') return String(a.answer ?? '—')
  if (q.type === 'multiple-answer') return Array.isArray(a.answer) ? a.answer.join(', ') : '—'
  if (q.type === 'true-false') return String(a.answer ?? '—')

  // short: answer is object
  if (q.type === 'short') {
    return String(a?.answer?.expected ?? '—')
  }

  return '—'
}

const topicName = ref('')

watch(
  topicName,
  (name) => {
    const suffix = String(name || '').trim() || 'Topic Details'
    setTitle(`${appTitle.value} - ${suffix}`)
  },
  { immediate: true }
)

const fetchTopicName = async () => {
  const id = Number(topicId.value)
  if (!Number.isFinite(id) || id <= 0) {
    topicName.value = ''
    return
  }

  try {
    const res = await api.get(`/topics/${id}`)
    const data = res?.data?.data ?? res?.data

    // adjust these fields depending on your DB column
    topicName.value = String(data?.title ?? data?.name ?? '')
  } catch (e) {
    console.error('fetchTopicName failed:', e)
    topicName.value = ''
  }
}

// ✅ determine active status reliably (supports different backend shapes)
const isExamActive = (e: any) => {
  // preferred: boolean is_active
  if (typeof e?.is_active === 'boolean') return e.is_active

  // sometimes status may be "active"/"inactive"
  if (typeof e?.status === 'string') return e.status.toLowerCase() === 'active'

  // sometimes status is numeric (0=active, 1=inactive) - adjust if your backend differs
  if (typeof e?.status === 'number') return e.status === 0

  return false
}

// ✅ Passing rate as "NN%"
const formatPassingRate = (e: any) => {
  const nRaw = e?.passing_rate ?? e?.passing_score
  const n = Number(nRaw)

  if (!Number.isFinite(n)) return '—'
  // force 0-100 (optional safety)
  const clamped = Math.max(0, Math.min(100, n))
  return `${clamped}%`
}
const toggleExamStatus = async (e: any) => {
  try {
    const nextActive = !isExamActive(e)

    // ✅ build payload (keep existing values so backend validation won't fail)
    const payload = {
      topic_id: e.topic_id ?? e.fk_topic_id ?? topicId.value,
      title: e.title ?? '',
      instructions: e.instructions ?? e.description ?? null,
      passing_rate: Number(e.passing_rate ?? e.passing_score ?? 0) || 0,
      time_limit: Number(e.time_limit ?? 0) || 0,
      is_active: nextActive,
      // keep json if backend requires it (safe fallback)
      question_json: e.question_json ?? [],
      answers_json: e.answers_json ?? e.answer_json ?? [],
    }

    // ✅ update
    await api.put(`/exams/${e.id}`, payload)

    // ✅ update UI immediately
    e.is_active = nextActive
    e.status = nextActive ? 'active' : 'inactive'

    showSuccess('Exam status updated.')
  } catch (err: any) {
    console.error('toggleExamStatus failed:', err)
    showError(err?.response?.data?.message ?? 'Failed to update exam status.')
  }
}

type DeleteKind = 'material' | 'exam'

const isDeleteModalOpen = ref(false)
const isDeleting = ref(false)

const deleteKind = ref<DeleteKind>('material')
const deleteTargetId = ref<number | null>(null)
const deleteTargetTitle = ref<string>('')

// computed text
const deleteTitle = computed(() => (deleteKind.value === 'material' ? 'Delete Material' : 'Delete Exam'))

const deleteLabel = computed(() => (deleteKind.value === 'material' ? 'Material' : 'Exam'))

const deleteMessage = computed(() =>
  deleteKind.value === 'material'
    ? 'Are you sure you want to delete this material?'
    : 'Are you sure you want to delete this exam?'
)

// openers
const openDeleteMaterialModal = (m: any) => {
  deleteKind.value = 'material'
  deleteTargetId.value = Number(m?.id) || null
  deleteTargetTitle.value = String(m?.title ?? '')
  isDeleteModalOpen.value = true
}

const openDeleteExamModal = (e: any) => {
  deleteKind.value = 'exam'
  deleteTargetId.value = Number(e?.id) || null
  deleteTargetTitle.value = String(e?.title ?? '')
  isDeleteModalOpen.value = true
}

// confirm handler (does the correct delete)
const confirmDelete = async () => {
  if (!deleteTargetId.value) return

  isDeleting.value = true
  try {
    if (deleteKind.value === 'material') {
      // uses your existing removeMaterial
      await removeMaterial(deleteTargetId.value)
      showSuccess('Material deleted.')
      await fetchMaterialsForTopic()
    } else {
      await api.delete(`/exams/${deleteTargetId.value}`)
      showSuccess('Exam deleted.')
      await fetchExamsForTopic()

      // close exam view modal if it's showing the deleted exam
      if (isExamModalOpen.value && Number(examModalData.value?.id) === Number(deleteTargetId.value)) {
        isExamModalOpen.value = false
      }
    }

    isDeleteModalOpen.value = false
    deleteTargetId.value = null
    deleteTargetTitle.value = ''
  } catch (err: any) {
    console.error('confirmDelete failed:', err)
    showError(err?.response?.data?.message ?? err?.message ?? 'Failed to delete.')
  } finally {
    isDeleting.value = false
  }
}

// optional cleanup if user cancels/esc
watch(isDeleteModalOpen, (open) => {
  if (!open) {
    deleteTargetId.value = null
    deleteTargetTitle.value = ''
  }
})

const resetMaterialForm = () => {
  form.value = {
    topic_id: Number.isFinite(topicId.value) ? topicId.value : null,
    title: '',
    description: '',
    source_type: 'file',
    source: null,
    is_active: true,
  }

  // ✅ reset upload UI state
  isDragOver.value = false

  // clear file input so selecting same file again triggers change event
  if (fileInput.value) fileInput.value.value = ''

  // ✅ clear preview
  if (filePreviewUrl.value) URL.revokeObjectURL(filePreviewUrl.value)
  filePreviewUrl.value = ''
  filePreviewType.value = ''
}

const activePaginated = computed(() =>
  activeTab.value === 'materials' ? paginated.value : examsPaginated.value
)

const activePerPage = computed({
  get: () => (activeTab.value === 'materials' ? perPage.value : examsPerPage.value),
  set: (v: number) => {
    if (activeTab.value === 'materials') perPage.value = v
    else examsPerPage.value = v
  },
})



onMounted(() => {
  fetchTopicName()
  fetchMaterialsForTopic()
})

</script>
