<template>
  <div class="space-y-6 sm:space-y-8">
    <!-- Welcome Section -->
    <div class="card">
      <div class="card-body">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-5 sm:space-y-0 sm:space-x-8">
          <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full bg-gradient-to-br from-primary to-primary-hover dark:from-primary-dark dark:to-primary-dark-hover flex items-center justify-center text-white text-3xl sm:text-4xl font-bold shadow-elegant-lg transform transition-transform duration-300 hover:scale-105 ring-4 ring-primary/10 dark:ring-primary-dark/20">
            {{ userInitials }}
          </div>
          <div class="text-center sm:text-left flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-primary-text dark:text-primary-dark-text">
              Welcome back, {{ currentUser?.name }}!
            </h1>
            <p class="text-sm sm:text-base text-secondary-text/70 dark:text-secondary-dark-text/70 mt-2">
              {{ currentUser?.email }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <DashboardStatsCard
        title="Exams Taken"
        :value="examAttempts.length"
        :icon="ExamIcon"
        color="primary"
      />
      <DashboardStatsCard
        title="Exams Passed"
        :value="passedExams.length"
        :icon="CheckIcon"
        color="success"
      />
      <DashboardStatsCard
        title="Certificates"
        :value="certificates.length"
        :icon="CertificateIcon"
        color="info"
      />
      <DashboardStatsCard
        title="Materials"
        :value="materials.length"
        :icon="MaterialIcon"
        color="warning"
      />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-5 sm:gap-6 md:grid-cols-3">
      <QuickActionButton
        title="Take Exam"
        description="Start a new exam"
        :icon="ExamIcon"
        color="primary"
        @click="handleTakeExam"
      />
      <QuickActionButton
        title="View Materials"
        description="Access learning resources"
        :icon="MaterialIcon"
        color="info"
        @click="handleViewMaterials"
      />
      <QuickActionButton
        title="View Results"
        description="Check your exam results"
        :icon="ResultsIcon"
        color="success"
        @click="handleViewResults"
      />
    </div>

    <!-- Search + Filter Controls for Materials -->
    <TableControls
      v-model:search="materialSearchQuery"
      v-model:status="materialStatusFilter"
      class="mb-5"
    >
      <template #status>
        <!-- Type Filter -->
        <select
          v-model="materialFilterType"
          class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                 dark:bg-slate-700 dark:text-slate-200 text-sm"
        >
          <option value="">All Types</option>
          <option value="file">Files</option>
          <option value="url">Links</option>
        </select>
      </template>

      <template #action>
        <!-- Items per page -->
        <select
          v-model.number="materialPerPage"
          class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                 dark:bg-slate-700 dark:text-slate-200 text-sm"
        >
          <option :value="5">5 / page</option>
          <option :value="10">10 / page</option>
          <option :value="15">15 / page</option>
        </select>
      </template>
    </TableControls>

    <!-- Available Exams -->
    <div class="card">
      <div class="card-header">
        <h2 class="text-lg sm:text-xl font-bold text-primary-text dark:text-primary-dark-text">
          Available Exams
        </h2>
      </div>
      <div class="card-body">
        <div
          v-if="loading"
          class="text-center py-12 text-secondary-text/70 dark:text-secondary-dark-text/70"
        >
          <p class="text-base">Loading exams...</p>
        </div>
        <div
          v-else-if="!hasAvailableExams"
          class="text-center py-12 text-secondary-text/70 dark:text-secondary-dark-text/70"
        >
          <p class="text-base">No available exams at the moment</p>
        </div>
        <div
          v-else
          class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
        >
          <ExamCard
            v-for="exam in availableExams"
            :key="exam.id"
            :exam="exam"
            @take-exam="() => handleTakeExam(exam.id)"
          />
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
      <div class="card-header">
        <h2 class="text-lg sm:text-xl font-bold text-primary-text dark:text-primary-dark-text">
          Recent Activity
        </h2>
      </div>
      <div class="card-body">
        <ActivityTimeline :activities="recentActivities" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, h, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useTitle } from '@/composables/ui/useTitle'
import { useUser } from '@/composables/auth/useUser'
import { useAuthStore } from '@/stores/auth'
import { useExams } from '@/composables/useExams'
import DashboardStatsCard from '@/components/dashboard/DashboardStatsCard.vue'
import QuickActionButton from '@/components/dashboard/QuickActionButton.vue'
import ExamCard from '@/components/dashboard/ExamCard.vue'
import ActivityTimeline from '@/components/dashboard/ActivityTimeline.vue'
import TableControls from '@/components/layout/TableControls.vue'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Dashboard`)

const router = useRouter()
const authStore = useAuthStore()
const { currentUser } = useUser()

// ✅ Compute user initials
const userInitials = computed(() => {
  const name = currentUser.value?.name || ''
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const {
  availableExams,
  examAttempts,
  materials,
  certificates,
  loading,
  passedExams,
  hasAvailableExams,
  fetchAvailableExams,
  fetchExamAttempts,
  fetchMaterials,
  fetchCertificates
} = useExams()

// ✅ Material search and filter state
const materialSearchQuery = ref('')
const materialFilterType = ref('')
const materialPerPage = ref(10)
const materialStatusFilter = ref<'all' | 'active' | 'inactive'>('all') // ✅ Required by TableControls

// Icons
const ExamIcon = h('svg', {
  class: 'h-6 w-6',
  xmlns: 'http://www.w3.org/2000/svg',
  viewBox: '0 0 20 20',
  fill: 'currentColor'
}, [
  h('path', {
    'fill-rule': 'evenodd',
    d: 'M9 2a1 1 0 000 2h2a1 1 0 100-2H9z M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z',
    'clip-rule': 'evenodd'
  })
])

const CheckIcon = h('svg', {
  class: 'h-6 w-6',
  xmlns: 'http://www.w3.org/2000/svg',
  viewBox: '0 0 20 20',
  fill: 'currentColor'
}, [
  h('path', {
    'fill-rule': 'evenodd',
    d: 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
    'clip-rule': 'evenodd'
  })
])

const CertificateIcon = h('svg', {
  class: 'h-6 w-6',
  xmlns: 'http://www.w3.org/2000/svg',
  viewBox: '0 0 20 20',
  fill: 'currentColor'
}, [
  h('path', {
    'fill-rule': 'evenodd',
    d: 'M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
    'clip-rule': 'evenodd'
  })
])

const MaterialIcon = h('svg', {
  class: 'h-6 w-6',
  xmlns: 'http://www.w3.org/2000/svg',
  viewBox: '0 0 20 20',
  fill: 'currentColor'
}, [
  h('path', {
    'fill-rule': 'evenodd',
    d: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z',
    'clip-rule': 'evenodd'
  })
])

const ResultsIcon = h('svg', {
  class: 'h-6 w-6',
  xmlns: 'http://www.w3.org/2000/svg',
  viewBox: '0 0 20 20',
  fill: 'currentColor'
}, [
  h('path', {
    'fill-rule': 'evenodd',
    d: 'M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
    'clip-rule': 'evenodd'
  })
])

const recentActivities = computed(() => {
  const activities: Array<{
    type: 'exam' | 'certificate' | 'material' | 'profile'
    description: string
    details?: string
    time: string
    datetime: string
  }> = []

  // Add exam attempts
  examAttempts.value.slice(0, 5).forEach(attempt => {
    const date = new Date(attempt.created_at)
    activities.push({
      type: 'exam',
      description: attempt.passed
        ? `Passed "${attempt.exam?.title || 'Exam'}"`
        : `Completed "${attempt.exam?.title || 'Exam'}"`,
      details: `Score: ${attempt.percentage.toFixed(1)}%`,
      time: formatTimeAgo(date),
      datetime: attempt.created_at
    })
  })

  // Add certificates
  certificates.value.slice(0, 3).forEach(cert => {
    const date = new Date(cert.issued_at)
    activities.push({
      type: 'certificate',
      description: `Certificate earned for "${cert.exam?.title || 'Exam'}"`,
      details: `Certificate #${cert.certificate_number}`,
      time: formatTimeAgo(date),
      datetime: cert.issued_at
    })
  })

  return activities.sort((a, b) => new Date(b.datetime).getTime() - new Date(a.datetime).getTime()).slice(0, 10)
})

const formatTimeAgo = (date: Date): string => {
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  const hours = Math.floor(diff / (1000 * 60 * 60))
  const minutes = Math.floor(diff / (1000 * 60))

  if (days > 0) return `${days} day${days > 1 ? 's' : ''} ago`
  if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''} ago`
  if (minutes > 0) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`
  return 'Just now'
}

const handleTakeExam = (examId?: number) => {
  if (examId) {
    router.push(`/exams/${examId}/take`)
  } else {
    router.push('/exams')
  }
}

const handleViewMaterials = () => {
  router.push('/materials')
}

const handleViewResults = () => {
  router.push('/results')
}

onMounted(async () => {
  try {
    await Promise.all([
      fetchAvailableExams(),
      fetchExamAttempts(),
      fetchMaterials(),
      fetchCertificates()
    ])
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  }
})
</script>
