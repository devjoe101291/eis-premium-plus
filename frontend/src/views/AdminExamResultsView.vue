<template>
    <div class="space-y-6 sm:space-y-8 px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1
                    class="text-3xl sm:text-4xl font-bold bg-gradient-to-tr from-primary to-secondary dark:from-primary-light dark:to-secondary-light bg-clip-text text-transparent">
                    Exam Results
                </h1>
                <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-2">
                    Manage and organize exam results by learning topic.
                </p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="card relative z-20 border border-gray-200/50 dark:border-gray-700/50 shadow-sm overflow-visible">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <SearchBar v-model="searchQuery" placeholder="Search by employee, exam, or module..."
                            @update:modelValue="handleSearch" />
                    </div>

                    <div class="flex w-full sm:w-auto gap-3">
                        <FilterDropdown v-model="statusFilter" label="Result" :options="statusOptions"
                            @update:modelValue="handleFilter" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
            <div class="card-body">
                <div class="flex flex-col items-center justify-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 dark:bg-primary-dark/10 mb-4">
                        <Loader2 class="w-6 h-6 text-primary dark:text-primary-dark animate-spin" />
                    </div>
                    <p class="text-base font-medium text-secondary-text dark:text-secondary-dark-text">
                        Loading exam results...
                    </p>
                </div>
            </div>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
            <div class="card-body">
                <p class="text-sm text-danger dark:text-danger-dark">
                    {{ error }}
                </p>
            </div>
        </div>

        <!-- Exam Results Table -->
        <ExamResultTable v-else :examResults="examResults" @view="handleView" />

        <!-- Pagination -->
        <div v-if="!loading && !error && total > 0"
            class="card border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Pagination Info -->
                    <div class="text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 order-2 sm:order-1">
                        Showing
                        <span class="font-semibold text-primary-text dark:text-primary-dark-text">
                            {{ (currentPage - 1) * perPage + 1 }}
                        </span>
                        to
                        <span class="font-semibold text-primary-text dark:text-primary-dark-text">
                            {{ Math.min(currentPage * perPage, total) }}
                        </span>
                        of
                        <span class="font-semibold text-primary-text dark:text-primary-dark-text">
                            {{ total }}
                        </span>
                        <span class="hidden xs:inline">results</span>
                    </div>

                    <!-- Pagination Controls -->
                    <div
                        class="flex items-center space-x-2 order-1 sm:order-2 w-full sm:w-auto justify-center sm:justify-end">
                        <!-- Previous Button -->
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                       bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-primary-text dark:text-primary-dark-text
                       hover:bg-primary/5 dark:hover:bg-primary-dark/5 hover:border-primary dark:hover:border-primary-dark"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-sm'">
                            <span class="hidden sm:inline">Previous</span>
                            <ChevronLeft class="h-5 w-5 sm:hidden" />
                        </button>

                        <!-- Page Numbers (Desktop) -->
                        <div v-if="totalPages > 1" class="hidden md:flex items-center space-x-1">
                            <template v-for="page in visiblePages" :key="page">
                                <button v-if="page !== '...'" @click="goToPage(page as number)"
                                    class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 min-w-[2.5rem]"
                                    :class="page === currentPage
                                        ? 'bg-primary text-white dark:bg-primary-dark dark:text-white shadow-md'
                                        : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-primary-text dark:text-primary-dark-text hover:bg-primary/5 dark:hover:bg-primary-dark/5 hover:border-primary dark:hover:border-primary-dark'">
                                    {{ page }}
                                </button>
                                <span v-else
                                    class="px-2 text-secondary-text/50 dark:text-secondary-dark-text/50">...</span>
                            </template>
                        </div>

                        <!-- Mobile Page Indicator -->
                        <div
                            class="md:hidden px-3 py-2 text-sm font-medium text-primary-text dark:text-primary-dark-text bg-primary/10 dark:bg-primary-dark/10 rounded-lg">
                            {{ currentPage }} / {{ totalPages || 1 }}
                        </div>

                        <!-- Next Button -->
                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                            class="px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                       bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-primary-text dark:text-primary-dark-text
                       hover:bg-primary/5 dark:hover:bg-primary-dark/5 hover:border-primary dark:hover:border-primary-dark"
                            :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-sm'">
                            <span class="hidden sm:inline">Next</span>
                            <ChevronRight class="h-5 w-5 sm:hidden" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Loader2, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { useTitle } from '@/composables/ui/useTitle'
import { useToast } from '@/composables/ui/useToast'
import { useAdminExamResults } from '@/composables/useAdminExamResults'
import SearchBar from '@/components/ui/SearchBar.vue'
import FilterDropdown from '@/components/ui/FilterDropdown.vue'
import ExamResultTable from '@/components/exam-results/ExamResultTable.vue'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Exam Results`)

const { error: showError } = useToast()

const examResultsState = useAdminExamResults()

const {
    examResults,
    loading,
    error,
    total,
    currentPage,
    perPage,
    totalPages,
    fetchExamResults,
} = examResultsState

const searchQuery = ref('')
const statusFilter = ref<'all' | 'passed' | 'failed' | 'pending'>('all')

const statusOptions = [
    { value: 'all', label: 'All Results' },
    { value: 'passed', label: 'Passed' },
    { value: 'failed', label: 'Failed' },
    { value: 'pending', label: 'Pending' },
]

const handleSearch = async () => {
    try {
        await fetchExamResults({
            page: 1,
            search: searchQuery.value || undefined,
            status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
        })
    } catch (err: any) {
        showError(err?.response?.data?.message || 'Failed to search exam results', 'Error')
    }
}

const handleFilter = async () => {
    try {
        await fetchExamResults({
            page: 1,
            search: searchQuery.value || undefined,
            status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
        })
    } catch (err: any) {
        showError(err?.response?.data?.message || 'Failed to filter exam results', 'Error')
    }
}

/**
 * NOTE:
 * Your ExamResult does NOT have a single "id".
 * So your row/card should emit (employeeId, examId) OR you just route to a detail page.
 */
const handleView = (employeeId: number, examId: number) => {
    // Example route:
    // router.push({ name: 'AdminExamResultDetail', params: { employeeId, examId } })
    console.log('View exam result:', { employeeId, examId })
}

const goToPage = async (page: number) => {
    try {
        await fetchExamResults({ page })
    } catch (err: any) {
        showError(err?.response?.data?.message || 'Failed to change page', 'Error')
    }
}

const visiblePages = computed(() => {
    const pages: (number | string)[] = []
    const totalP = totalPages.value
    const current = currentPage.value

    if (totalP <= 1) return totalP === 1 ? [1] : []

    if (totalP <= 7) {
        for (let i = 1; i <= totalP; i++) pages.push(i)
    } else {
        pages.push(1)
        if (current <= 3) {
            for (let i = 2; i <= 4; i++) pages.push(i)
            pages.push('...')
            pages.push(totalP)
        } else if (current >= totalP - 2) {
            pages.push('...')
            for (let i = totalP - 3; i <= totalP; i++) pages.push(i)
        } else {
            pages.push('...')
            for (let i = current - 1; i <= current + 1; i++) pages.push(i)
            pages.push('...')
            pages.push(totalP)
        }
    }

    return pages
})

onMounted(async () => {
    try {
        await fetchExamResults()
    } catch (err) {
        console.error('Failed to load exam results:', err)
    }
})
</script>