<template>
  <div class="w-full min-h-screen py-8 px-4
           bg-slate-50 dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950">
    <div class="mx-auto max-w-7xl">
      <!-- Header -->
<header class="rounded-2xl mb-8 shadow-xl
         bg-gradient-to-r from-primary to-secondary text-white">
  <div class="px-4 sm:px-8 py-6">

    <!-- ✅ Header Skeleton -->
    <div v-if="isPageLoading" class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <Skeleton class="h-10 w-10 rounded-lg bg-white/20" />
          <Skeleton class="h-8 w-48 bg-white/20" />
        </div>
        <Skeleton class="h-5 w-64 ml-14 bg-white/20" />
      </div>

      <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
        <Skeleton class="h-12 w-full sm:w-36 rounded-xl bg-white/20" />
        <Skeleton class="h-12 w-full sm:w-28 rounded-xl bg-white/20" />
      </div>
    </div>

    <!-- ✅ Existing Header -->
    <div v-else class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <button type="button" @click="goBack" class="bg-white/20 backdrop-blur-sm text-white p-2 rounded-lg font-semibold
                   hover:bg-white/30 transition border border-white/20 hover:border-white/40
                   flex items-center justify-center" title="Back">
            <span class="text-lg leading-none"><LeftIcon /></span>
          </button>

          <h1 class="text-2xl sm:text-3xl font-bold">
            {{ isEditing ? 'Edit Exam' : 'Exam Creator' }}
          </h1>
        </div>

        <p class="text-white/80 text-base sm:text-lg ml-14">
          {{ isEditing ? 'Update your exam' : 'Create exam with ease' }}
        </p>
      </div>

      <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
        <button type="button" @click="saveExam" :disabled="isSaving" class="w-full sm:w-auto bg-white/20 backdrop-blur-sm text-white px-4 sm:px-6 py-2.5 sm:py-3
                 rounded-xl font-semibold hover:bg-white/30 transition border border-white/20 hover:border-white/40
                 flex items-center justify-center disabled:opacity-60 disabled:cursor-not-allowed">
          <span class="text-sm sm:text-base">
            {{ isSaving ? 'Saving...' : 'Save Exam' }}
          </span>
        </button>

        <button type="button" @click="previewExam" class="w-full sm:w-auto bg-white text-slate-900 px-4 sm:px-6 py-2.5 sm:py-3
                 rounded-xl font-semibold hover:bg-slate-50 transition shadow-lg hover:shadow-xl
                 flex items-center justify-center">
          <span class="text-sm sm:text-base">Preview</span>
        </button>
      </div>
    </div>

  </div>
</header>

      <!-- ✅ Main Layout Skeleton -->
<div v-if="isPageLoading" class="grid grid-cols-1 xl:grid-cols-3 gap-8">
  <!-- LEFT skeleton -->
  <aside class="xl:col-span-1">
    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-4 sm:p-6
              border border-white/50 dark:border-slate-700/50 sticky top-4 sm:top-8">
      <div class="flex items-center gap-3 mb-6">
        <Skeleton class="h-10 w-10 rounded-xl" />
        <Skeleton class="h-6 w-40" />
      </div>

      <div class="space-y-5">
        <div>
          <Skeleton class="h-4 w-24 mb-2" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>

        <div>
          <Skeleton class="h-4 w-16 mb-2" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>

        <div>
          <Skeleton class="h-4 w-24 mb-2" />
          <div class="flex gap-4">
            <Skeleton class="h-5 w-24 rounded" />
            <Skeleton class="h-5 w-24 rounded" />
          </div>
        </div>

        <div>
          <Skeleton class="h-4 w-44 mb-2" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>

        <div>
          <Skeleton class="h-4 w-36 mb-2" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>

        <div>
          <Skeleton class="h-4 w-24 mb-2" />
          <Skeleton class="h-28 w-full rounded-xl" />
        </div>

        <div class="mt-6 rounded-xl p-4 border
                    bg-gradient-to-r from-primary/5 to-secondary/5
                    border-slate-200/60 dark:border-slate-700/60">
          <Skeleton class="h-5 w-32 mb-4" />
          <div class="space-y-3">
            <div class="flex justify-between">
              <Skeleton class="h-4 w-28" />
              <Skeleton class="h-4 w-10" />
            </div>
            <div class="flex justify-between">
              <Skeleton class="h-4 w-24" />
              <Skeleton class="h-4 w-10" />
            </div>
            <div class="flex justify-between">
              <Skeleton class="h-4 w-16" />
              <Skeleton class="h-4 w-16" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- RIGHT skeleton -->
  <main class="xl:col-span-2">
    <!-- Action cards skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-6
                border border-white/50 dark:border-slate-700/50">
        <div class="text-center">
          <Skeleton class="h-16 w-16 rounded-full mx-auto mb-4" />
          <Skeleton class="h-6 w-44 mx-auto mb-2" />
          <Skeleton class="h-4 w-52 mx-auto mb-4" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>
      </div>

      <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-6
                border border-white/50 dark:border-slate-700/50">
        <div class="text-center">
          <Skeleton class="h-16 w-16 rounded-full mx-auto mb-4" />
          <Skeleton class="h-6 w-52 mx-auto mb-2" />
          <Skeleton class="h-4 w-56 mx-auto mb-4" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>
      </div>
    </div>

<!-- Question cards skeleton (COUNT RESPONSIVE) -->
<div class="space-y-6">
  <div
    v-for="i in skeletonQuestionCount"
    :key="i"
    class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden
           border border-white/50 dark:border-slate-700/50"
  >
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60
              bg-gradient-to-r from-primary/5 to-secondary/5">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <Skeleton class="h-8 w-8 rounded-lg" />
          <Skeleton class="h-5 w-32" />
          <Skeleton class="h-6 w-20 rounded-full" />
        </div>
        <div class="flex items-center gap-2">
          <Skeleton class="h-9 w-9 rounded-xl" />
          <Skeleton class="h-9 w-9 rounded-xl" />
          <Skeleton class="h-9 w-9 rounded-xl" />
          <Skeleton class="h-9 w-9 rounded-xl" />
        </div>
      </div>
    </div>

    <div class="p-6 space-y-5">
      <div>
        <Skeleton class="h-4 w-28 mb-2" />
        <Skeleton class="h-12 w-full rounded-xl" />
      </div>

      <div>
        <Skeleton class="h-4 w-20 mb-2" />
        <Skeleton class="h-24 w-full rounded-xl" />
      </div>

      <div class="flex items-center gap-3">
        <div class="w-32">
          <Skeleton class="h-4 w-16 mb-2" />
          <Skeleton class="h-12 w-full rounded-xl" />
        </div>
        <Skeleton class="h-4 w-40 mt-7" />
      </div>

      <div>
        <div class="flex items-center justify-between mb-3">
          <Skeleton class="h-4 w-20" />
          <Skeleton class="h-3 w-28" />
        </div>

        <div class="space-y-3">
          <div
            v-for="k in 3"
            :key="k"
            class="flex items-center gap-3 p-3 rounded-xl border
                   border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/30"
          >
            <Skeleton class="h-10 w-10 rounded-lg" />
            <Skeleton class="h-10 flex-1 rounded-lg" />
            <Skeleton class="h-5 w-5 rounded" />
            <Skeleton class="h-9 w-9 rounded-lg" />
          </div>
        </div>

        <Skeleton class="h-5 w-24 mt-4" />
      </div>
    </div>
  </div>
</div>

  </main>
</div>

<!-- ✅ Existing Main Layout (unchanged) -->
<div v-else class="grid grid-cols-1 xl:grid-cols-3 gap-8">
  <!-- ... KEEP EVERYTHING YOU ALREADY HAVE HERE ... -->
        <!-- LEFT: Exam Settings -->
        <aside class="xl:col-span-1">
          <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-4 sm:p-6
                   border border-white/50 dark:border-slate-700/50 sticky top-4 sm:top-8">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white
                       bg-gradient-to-r from-primary to-secondary">
                <span class="text-lg"><SettingsIcon /> </span>
              </div>
              <h2 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100">
                Exam Settings
              </h2>
            </div>

            <!-- Title -->
            <div class="mb-5">
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Exam Title
              </label>
              <input v-model="exam.title" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-primary/50" placeholder="Enter exam title" />
            </div>

            <!-- Topic -->
            <div class="mb-5">
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Topic
              </label>
              <input :value="topicName" type="text" readonly class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
         bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
         focus:outline-none focus:ring-2 focus:ring-primary/50" />

              <!-- <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                Linked Topic ID: <b>{{ exam.topic_id ?? '—' }}</b>
              </p> -->

            </div>

            <!-- Status -->
            <div class="mb-5">
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Exam Status
              </label>
              <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="exam.status" type="radio" value="active" class="h-4 w-4" />
                  <span class="text-slate-700 dark:text-slate-200">Active</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="exam.status" type="radio" value="inactive" class="h-4 w-4" />
                  <span class="text-slate-700 dark:text-slate-200">Inactive</span>
                </label>
              </div>
            </div>

            <!-- Time Limit -->
            <div class="mb-5">
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Time Limit (minutes)
              </label>
              <input v-model.number="exam.time_limit" type="number" min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-primary/50" />
            </div>

            <!-- Passing Score -->
            <div class="mb-5">
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Passing Score (%)
              </label>
              <div class="relative">
                <input v-model.number="exam.passing_score" type="number" min="0" max="100" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                         bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                         focus:outline-none focus:ring-2 focus:ring-primary/50" />
                <!-- <span class="absolute right-[19em] top-1/2 -translate-y-1/2 text-slate-400">%</span> -->
              </div>
            </div>

            <!-- Instructions -->
            <div class="mb-2">
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Instructions
              </label>
              <textarea v-model="exam.description" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-secondary/50 resize-none"
                placeholder="Enter exam instructions..." />
            </div>

            <!-- Summary -->
            <div class="mt-6 rounded-xl p-4 border
                     bg-gradient-to-r from-primary/5 to-secondary/5
                     border-slate-200/60 dark:border-slate-700/60
                     dark:bg-gradient-to-r dark:from-primary/10 dark:to-secondary/10">
              <h3 class="font-semibold text-slate-700 dark:text-slate-200 mb-4">
                Exam Summary
              </h3>

              <div class="space-y-2 text-sm">
                <div class="flex justify-between border-b border-slate-200/60 dark:border-slate-700/60 pb-2">
                  <span class="text-slate-600 dark:text-slate-400">Total Questions</span>
                  <span class="font-bold text-slate-800 dark:text-slate-100">{{ safeQuestions.length }}</span>
                </div>

                <div class="flex justify-between border-b border-slate-200/60 dark:border-slate-700/60 pb-2">
                  <span class="text-slate-600 dark:text-slate-400">Total Points</span>
                  <span class="font-bold text-slate-800 dark:text-slate-100">{{ totalPoints }}</span>
                </div>

                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-400">Status</span>
                  <span class="font-bold capitalize" :class="exam.is_active
                    ? 'text-emerald-600 dark:text-emerald-400'
                    : 'text-rose-600 dark:text-rose-400'">
                    {{ exam.is_active ? 'active' : 'inactive' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </aside>

        <!-- RIGHT: Questions -->
        <main class="xl:col-span-2">
          <!-- Quick actions -->
          <div v-if="safeQuestions.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-6
                     border border-white/50 dark:border-slate-700/50 hover:shadow-xl transition">
              <div class="text-center">
                <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-white
                         bg-gradient-to-r from-primary to-secondary">
                  <span class="text-2xl">＋</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Add Single Question</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">Perfect for adding one question</p>
                <button type="button" @click="addQuestion" class="w-full py-3 rounded-xl font-semibold text-white
                         bg-gradient-to-r from-primary to-secondary
                         hover:opacity-90 transition shadow-md">
                  Add Question
                </button>
              </div>
            </div>

            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-6
                     border border-white/50 dark:border-slate-700/50 hover:shadow-xl transition">
              <div class="text-center">
                <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-white
                         bg-gradient-to-r from-primary to-secondary">
                  <span class="text-2xl">≡</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Add Multiple Questions</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">Quickly add multiple questions</p>
                <button type="button" @click="showAddMultipleModal = true" class="w-full py-3 rounded-xl font-semibold text-white
                         bg-gradient-to-r from-primary to-secondary
                         hover:opacity-90 transition shadow-md">
                  Add Multiple
                </button>
              </div>
            </div>
          </div>

          <!-- No questions state -->
          <div v-if="safeQuestions.length === 0" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-10
                   border border-white/50 dark:border-slate-700/50 text-center">
            <div class="w-24 h-24 rounded-full mx-auto mb-6 flex items-center justify-center
                     bg-gradient-to-r from-primary/10 to-secondary/10">
              <span class="text-3xl">📝</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-700 dark:text-slate-200 mb-2">No Questions Yet</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Start building your exam by adding questions</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
              <button type="button" @click="addQuestion" class="px-6 py-4 rounded-xl font-semibold text-white
                       bg-gradient-to-r from-primary to-secondary
                       hover:opacity-90 transition shadow-md">
                Add Single Question
              </button>

              <button type="button" @click="showAddMultipleModal = true" class="px-6 py-4 rounded-xl font-semibold text-white
                       bg-gradient-to-r from-primary to-secondary
                       hover:opacity-90 transition shadow-md">
                Add Multiple Questions
              </button>
            </div>
          </div>

          <!-- Questions list -->
          <div class="space-y-6">
            <div v-for="(q, index) in safeQuestions" :key="q.id" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden
                     border border-white/50 dark:border-slate-700/50">
              <!-- Question header -->
              <div class="px-4 sm:px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60
                       bg-gradient-to-r from-primary/5 to-secondary/5
                       dark:from-primary/10 dark:to-secondary/10">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg text-white flex items-center justify-center font-bold text-sm
                             bg-gradient-to-r from-primary to-secondary">
                      {{ index + 1 }}
                    </div>

                    <h3 class="font-bold text-slate-800 dark:text-slate-100">
                      Question {{ index + 1 }}
                    </h3>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize
                             bg-primary/10 text-primary dark:bg-primary/20 dark:text-slate-100">
                      {{ q.type }}
                    </span>
                  </div>

                  <div class="flex items-center gap-2">
                    <button type="button" class="w-9 h-9 rounded-xl border border-slate-200/70 dark:border-slate-700/60
                             bg-white/60 dark:bg-slate-900/30 hover:bg-white/80 dark:hover:bg-slate-900/40 transition flex items-center justify-center"
                      :disabled="index === 0" @click="moveQuestion(index, 'up')" title="Move up">
                      <UpIcon class="w-[14px] relative top-[5px]" />
                    </button>

                    <button type="button" class="w-9 h-9 rounded-xl border border-slate-200/70 dark:border-slate-700/60
                             bg-white/60 dark:bg-slate-900/30 hover:bg-white/80 dark:hover:bg-slate-900/40 transition flex items-center justify-center"
                      :disabled="index === safeQuestions.length - 1" @click="moveQuestion(index, 'down')"
                      title="Move down">
                      <DownIcon class="w-[14px] relative top-[5px]" />
                    </button>

                    <button type="button" class="w-9 h-9 rounded-xl border border-slate-200/70 dark:border-slate-700/60
                             bg-white/60 dark:bg-slate-900/30 hover:bg-white/80 dark:hover:bg-slate-900/40 transition flex items-center justify-center"
                      @click="duplicateQuestion(index)" title="Duplicate">
                      <CopyIcon class="w-[14px] relative top-[5px]"/>
                    </button>

                    <button type="button" class="w-9 h-9 rounded-xl border border-slate-200/70 dark:border-slate-700/60
                             bg-white/60 dark:bg-slate-900/30 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition flex items-center justify-center"
                      @click="deleteQuestion(index)" title="Delete">
                      <DeleteIcon class="w-[14px] relative top-[5px]" />
                    </button>
                  </div>
                </div>
              </div>

              <!-- Question body -->
              <div class="p-6 space-y-5">
                <div>
                  <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                    Question Type
                  </label>
                  <select v-model="q.type" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                           bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-primary/50">
                    <option value="multiple">Multiple Choice (Single Answer)</option>
                    <option value="multiple-answer">Multiple Answer</option>
                    <option value="true-false">True/False</option>
                    <option value="short">Short Answer</option>
                  </select>
                </div>

                <div>
                  <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                    Question
                  </label>
                  <textarea v-model="q.text" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                           bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-primary/50 resize-none"
                    placeholder="Enter your question..." />
                </div>

                <div class="flex items-center gap-3">
                  <div class="w-32">
                    <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                      Points
                    </label>
                    <input v-model.number="q.points" type="number" min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                             bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                             focus:outline-none focus:ring-2 focus:ring-primary/50" />
                  </div>
                  <p class="text-sm text-slate-500 dark:text-slate-400 mt-7">
                    points for this question
                  </p>
                </div>

                <!-- Multiple choice options -->
                <div v-if="q.type === 'multiple' || q.type === 'multiple-answer'">
                  <div class="flex items-center justify-between mb-3">
                    <label class="block text-slate-700 dark:text-slate-200 font-semibold">
                      Options
                    </label>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                      {{ q.type === 'multiple' ? 'Select one correct' : 'Select all correct' }}
                    </span>
                  </div>

                  <div class="space-y-3">
                    <div v-for="(opt, optIndex) in q.options" :key="optIndex" class="flex items-center gap-3 p-3 rounded-xl border
                             border-slate-200 dark:border-slate-700
                             bg-white/60 dark:bg-slate-900/30">
                      <span class="w-10 h-10 rounded-lg text-white font-semibold flex items-center justify-center
                               bg-gradient-to-r from-primary to-secondary">
                        {{ opt.letter }}
                      </span>

                      <input v-model="opt.text" type="text" class="flex-1 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700
                               bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                               focus:outline-none focus:ring-2 focus:ring-primary/50" placeholder="Option text" />

                      <input v-if="q.type === 'multiple-answer'" v-model="opt.isCorrect" type="checkbox" class="h-5 w-5"
                        title="Correct" />

                      <input v-else type="radio" :name="`correct-${q.id}`" :checked="opt.isCorrect"
                        @change="handleRadioChange(index, optIndex)" class="h-5 w-5" title="Correct" />

                      <button type="button"
                        class="w-9 h-9 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10 transition"
                        :disabled="q.options.length <= 2" @click="removeOption(index, optIndex)" title="Remove option">
                        ✕
                      </button>
                    </div>
                  </div>

                  <button type="button"
                    class="mt-4 font-semibold text-primary hover:opacity-80 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="addOption(index)" :disabled="q.options.length >= 6">
                    + Add Option
                  </button>
                </div>

                <!-- True/False -->
                <div v-if="q.type === 'true-false'" class="space-y-2">
                  <label class="block text-slate-700 dark:text-slate-200 font-semibold">
                    Correct Answer
                  </label>
                  <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input v-model="q.correctAnswer" type="radio" :name="`tf-${q.id}`" value="true" />
                      <span class="text-slate-700 dark:text-slate-200">True</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input v-model="q.correctAnswer" type="radio" :name="`tf-${q.id}`" value="false" />
                      <span class="text-slate-700 dark:text-slate-200">False</span>
                    </label>
                  </div>
                </div>

                <!-- Short answer -->
                <div v-if="q.type === 'short'" class="space-y-4">
                  <div>
                    <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                      Expected Answer
                    </label>
                    <input v-model="q.expectedAnswer" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                             bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                             focus:outline-none focus:ring-2 focus:ring-primary/50"
                      placeholder="Enter expected answer" />
                  </div>

                  <div>
                    <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                      Keywords (optional)
                    </label>
                    <input v-model="q.keywords" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                             bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                             focus:outline-none focus:ring-2 focus:ring-primary/50"
                      placeholder="Comma-separated keywords" />
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                      If provided, answer can be accepted if it contains any keyword.
                    </p>
                  </div>

                  <label class="flex items-center gap-2 cursor-pointer">
                    <input v-model="q.caseSensitive" type="checkbox" class="h-4 w-4" />
                    <span class="text-slate-700 dark:text-slate-200 font-medium">Case Sensitive</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </main>
</div>


      <!-- Add Multiple Modal -->
      <div v-if="showAddMultipleModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
          <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Add Multiple Questions</h3>
            <button type="button" class="w-9 h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700/40 transition"
              @click="showAddMultipleModal = false" title="Close">
              ✕
            </button>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Number of Questions
              </label>
              <input v-model.number="multipleQuestionsCount" type="number" min="1" max="50" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-primary/50" />
            </div>

            <div>
              <label class="block text-slate-700 dark:text-slate-200 font-semibold mb-2">
                Default Question Type
              </label>
              <select v-model="defaultQuestionType" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-white/70 dark:bg-slate-900/40 text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-primary/50">
                <option value="multiple">Multiple Choice</option>
                <option value="multiple-answer">Multiple Answer</option>
                <option value="true-false">True/False</option>
                <option value="short">Short Answer</option>
              </select>
            </div>

            <div class="flex gap-3 pt-2">
              <button type="button" class="flex-1 px-4 py-3 rounded-xl font-semibold
                       border border-slate-200 dark:border-slate-700
                       bg-slate-50 dark:bg-slate-700/30
                       text-slate-700 dark:text-slate-200
                       hover:bg-slate-100 dark:hover:bg-slate-700/50 transition" @click="showAddMultipleModal = false">
                Cancel
              </button>

              <button type="button" class="flex-1 px-4 py-3 rounded-xl font-semibold text-white
                       bg-gradient-to-r from-primary to-secondary hover:opacity-90 transition"
                @click="addMultipleQuestions">
                Add
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <Modal v-model="isPreviewOpen" title="" class="w-[30em]">
  <div class="text-center space-y-4">
    <!-- Icon -->
    <div class="flex justify-center">
      <div class="w-16 h-16 rounded-full border-2 border-sky-400 flex items-center justify-center text-sky-500 text-3xl">
        i
      </div>
    </div>

    <h2 class="text-xl font-bold text-slate-800">Exam Preview</h2>

    <div class="text-left text-sm space-y-1 text-slate-700">
      <p><b>Title:</b> {{ exam.title }}</p>
      <p><b>Total Questions:</b> {{ safeQuestions.length }}</p>
      <p><b>Total Points:</b> {{ totalPoints }}</p>
      <p><b>Time Limit:</b> {{ exam.time_limit }} minutes</p>
      <p><b>Passing Score:</b> {{ exam.passing_score }}%</p>
    </div>

    <p class="text-xs text-slate-500">
      Note: Correct answers are hidden for security.
    </p>
  </div>

  <template #footer>
    <div class="flex justify-center">
      <button
        type="button"
        class="px-6 py-2 rounded-xl bg-primary text-white font-semibold"
        @click="isPreviewOpen = false">
        Close
      </button>
    </div>
  </template>
</Modal>

</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/services/api'
import { useTitle } from '@/composables/ui/useTitle'
import { useToast } from '@/composables/ui/useToast'
import UpIcon from '@/components/icons/UpIcon.vue'
import LeftIcon from '@/components/icons/LeftIcon.vue'
import DownIcon from '@/components/icons/DownIcon.vue'
import CopyIcon from '@/components/icons/CopyIcon.vue'
import DeleteIcon from '@/components/icons/DeleteIcon.vue'
import Modal from '@/components/layout/Modal.vue'
import type { ExamUI, Question, QuestionType } from '@/config/types/exam'
import Skeleton from '@/components/ui/Skeleton.vue'
import SettingsIcon from '@/components/icons/SettingsIcon.vue'

const router = useRouter()
const route = useRoute()
const { error: showError, success: showSuccess } = useToast()

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
const { setTitle } = useTitle(`${appTitle.value} - Exam Creator`)

// /exams/:id/edit
const examId = computed(() => {
  const raw = route.params.id
  const n = Number(raw)
  return Number.isFinite(n) && n > 0 ? n : null
})
const isEditing = computed(() => examId.value !== null)

watch(
  isEditing,
  (editing) => {
    setTitle(`${appTitle.value} - ${editing ? 'Edit Exam' : 'Exam Creator'}`)
  },
  { immediate: true }
)

// ✅ init from query (?topic_id=)
const queryTopicId = computed(() => {
  const raw = route.query.topic_id
  const n = Number(raw)
  return Number.isFinite(n) && n > 0 ? n : null
})

// ✅ ALWAYS initialize with questions: []
const exam = ref<ExamUI>({
  title: '',
  description: '',
  topic_id: queryTopicId.value,
  status: 'active',
  is_active: true,
  time_limit: 60,
  passing_score: 70,
  category_id: null,
  exam_type: null,
  passing_criteria_type: null,
  questions: [],
})

// Keep status + is_active in sync
watch(
  () => exam.value.status,
  (v) => {
    exam.value.is_active = v === 'active'
  },
  { immediate: true }
)
watch(
  () => exam.value.is_active,
  (v) => {
    exam.value.status = v ? 'active' : 'inactive'
  }
)

const isSaving = ref(false)
const isLoadingExam = ref(false)

// ✅ Loader ONLY when editing an existing exam
const isPageLoading = computed(() => isEditing.value && isLoadingExam.value)

// Questions modal
const showAddMultipleModal = ref(false)
const multipleQuestionsCount = ref(5)
const defaultQuestionType = ref<QuestionType>('multiple')

// ✅ defensive fallback
const safeQuestions = computed(() => exam.value.questions ?? [])

// ✅ Remember last known questions count so skeleton can match count during loading
const lastKnownQuestionCount = ref<number>(0)

// ✅ keep last known count updated whenever user changes questions
watch(
  () => safeQuestions.value.length,
  (len) => {
    if (len > 0) lastKnownQuestionCount.value = len
  },
  { immediate: true }
)

// ✅ Dynamic skeleton count
const skeletonQuestionCount = computed(() => {
  const current = safeQuestions.value.length
  const base = current > 0 ? current : (lastKnownQuestionCount.value || 3)
  return Math.max(1, Math.min(10, base))
})

// totals
const totalPoints = computed(() => {
  return safeQuestions.value.reduce((sum, q) => sum + (Number(q.points) || 0), 0)
})

const goBack = () => router.back()

const createQuestion = (type: QuestionType): Question => {
  const id = Date.now() + Math.floor(Math.random() * 1000)
  return {
    id,
    type,
    text: '',
    points: 1,
    options: [
      { letter: 'A', text: '', isCorrect: true },
      { letter: 'B', text: '', isCorrect: false },
      { letter: 'C', text: '', isCorrect: false },
    ],
    correctAnswer: 'true',
    expectedAnswer: '',
    keywords: '',
    caseSensitive: false,
  }
}

const addQuestion = () => {
  safeQuestions.value.push(createQuestion(defaultQuestionType.value))
}

const addMultipleQuestions = () => {
  const n = Math.max(1, Math.min(50, Number(multipleQuestionsCount.value) || 1))
  const questionsToAdd: Question[] = []
  for (let i = 0; i < n; i++) questionsToAdd.push(createQuestion(defaultQuestionType.value))
  exam.value.questions = [...safeQuestions.value, ...questionsToAdd]
  showAddMultipleModal.value = false
}

const moveQuestion = (index: number, dir: 'up' | 'down') => {
  const list = [...safeQuestions.value]
  if (dir === 'up' && index > 0) ;[list[index - 1], list[index]] = [list[index], list[index - 1]]
  if (dir === 'down' && index < list.length - 1) ;[list[index + 1], list[index]] = [list[index], list[index + 1]]
  exam.value.questions = list
}

const duplicateQuestion = (index: number) => {
  const list = [...safeQuestions.value]
  const original = list[index]
  const copy: Question = JSON.parse(JSON.stringify(original))
  copy.id = Date.now() + Math.floor(Math.random() * 1000)
  list.splice(index + 1, 0, copy)
  exam.value.questions = list
}

const deleteQuestion = (index: number) => {
  const list = [...safeQuestions.value]
  list.splice(index, 1)
  exam.value.questions = list
}

const addOption = (questionIndex: number) => {
  const list = [...safeQuestions.value]
  const q = list[questionIndex]
  if (!q) return
  if (q.options.length >= 6) return

  const letters = ['A', 'B', 'C', 'D', 'E', 'F']
  q.options.push({
    letter: letters[q.options.length] ?? 'X',
    text: '',
    isCorrect: false,
  })
  exam.value.questions = list
}

const removeOption = (questionIndex: number, optionIndex: number) => {
  const list = [...safeQuestions.value]
  const q = list[questionIndex]
  if (!q) return
  if (q.options.length <= 2) return

  q.options.splice(optionIndex, 1)

  const letters = ['A', 'B', 'C', 'D', 'E', 'F']
  q.options.forEach((o, i) => (o.letter = letters[i] ?? 'X'))

  exam.value.questions = list
}

const handleRadioChange = (questionIndex: number, optionIndex: number) => {
  const list = [...safeQuestions.value]
  const q = list[questionIndex]
  if (!q) return

  q.options.forEach((o, i) => (o.isCorrect = i === optionIndex))
  exam.value.questions = list
}

// ---------- Backend integration ----------

const validateBeforeSave = (): string | null => {
  if (!exam.value.title.trim()) return 'Exam title is required.'
  if (!exam.value.topic_id || exam.value.topic_id <= 0) return 'Topic is required.'

  for (let i = 0; i < safeQuestions.value.length; i++) {
    const q = safeQuestions.value[i]
    const n = i + 1

    if (!q.text?.trim()) return `Question ${n} text is required.`
    if (!Number.isFinite(q.points) || q.points <= 0) return `Question ${n} points must be at least 1.`

    if (q.type === 'multiple' || q.type === 'multiple-answer') {
      if (!Array.isArray(q.options) || q.options.length < 2) return `Question ${n} must have at least 2 options.`
      if (q.options.some((o) => !o.text?.trim())) return `Question ${n} has empty option text.`

      const correctCount = q.options.filter((o) => !!o.isCorrect).length
      if (q.type === 'multiple' && correctCount !== 1) return `Question ${n} must have exactly 1 correct option.`
      if (q.type === 'multiple-answer' && correctCount < 1) return `Question ${n} must have at least 1 correct option.`
    }

    if (q.type === 'short' && !q.expectedAnswer?.trim()) return `Question ${n} expected answer is required.`
  }

  return null
}

const toBackendJson = () => {
  const question_json = safeQuestions.value.map((q) => {
    const base: any = { id: q.id, type: q.type, text: q.text, points: Number(q.points) || 0 }

    if (q.type === 'multiple' || q.type === 'multiple-answer') {
      base.options = (q.options || []).map((o, idx) => ({
        letter: o.letter || String.fromCharCode(65 + idx),
        text: o.text,
      }))
    }

    if (q.type === 'short') {
      base.caseSensitive = !!q.caseSensitive
      base.keywords = q.keywords || ''
    }

    return base
  })

  const answers_json = safeQuestions.value.map((q) => {
    if (q.type === 'multiple') {
      const correct = (q.options || []).find((o) => o.isCorrect)
      return { question_id: q.id, type: q.type, answer: correct?.letter ?? null }
    }

    if (q.type === 'multiple-answer') {
      const correctLetters = (q.options || []).filter((o) => o.isCorrect).map((o) => o.letter)
      return { question_id: q.id, type: q.type, answer: correctLetters }
    }

    if (q.type === 'true-false') {
      return { question_id: q.id, type: q.type, answer: q.correctAnswer }
    }

    return {
      question_id: q.id,
      type: q.type,
      answer: { expected: q.expectedAnswer, keywords: q.keywords, caseSensitive: !!q.caseSensitive },
    }
  })

  return { question_json, answers_json }
}

const buildPayloadForController = () => {
  const { question_json, answers_json } = toBackendJson()

  return {
    topic_id: exam.value.topic_id,
    title: exam.value.title.trim(),
    instructions: exam.value.description?.trim() || null,
    passing_rate: Number(exam.value.passing_score) || 0,
    time_limit: Number(exam.value.time_limit) || 0,
    is_active: !!exam.value.is_active,
    question_json,
    answers_json,
  }
}

const saveExam = async () => {
  const validationError = validateBeforeSave()
  if (validationError) return showError(validationError)

  isSaving.value = true
  try {
    const payload = buildPayloadForController()

    if (isEditing.value && examId.value) {
      await api.put(`/exams/${examId.value}`, payload)
      showSuccess('Exam updated successfully.')
      router.push({ name: 'topic-details', params: { id: exam.value.topic_id } })
      return
    }

    const res = await api.post('/exams', payload)
    showSuccess('Exam created successfully.')

    const newId = res?.data?.data?.id
    if (newId) router.push({ name: 'exam-edit', params: { id: newId } })
    else router.push({ name: 'topic-details', params: { id: exam.value.topic_id } })
  } catch (e: any) {
    console.error('saveExam failed:', e)
    showError(e?.response?.data?.message ?? e?.message ?? 'Failed to save exam.')
  } finally {
    isSaving.value = false
  }
}

const loadExam = async () => {
  if (!examId.value) return

  lastKnownQuestionCount.value = safeQuestions.value.length || lastKnownQuestionCount.value || 3
  isLoadingExam.value = true

  try {
    const res = await api.get(`/exams/${examId.value}`)
    const data = res?.data?.data ?? res?.data

    const questionJson = Array.isArray(data?.question_json) ? data.question_json : []
    const answersJson = Array.isArray(data?.answers_json) ? data.answers_json : []

    if (questionJson.length > 0) lastKnownQuestionCount.value = questionJson.length

    const hydratedQuestions: Question[] = (questionJson as any[]).map((raw, idx) => {
      const id = Number(raw?.id) || Date.now() + idx
      const type: QuestionType =
        raw?.type === 'multiple' || raw?.type === 'multiple-answer' || raw?.type === 'true-false' || raw?.type === 'short'
          ? raw.type
          : 'multiple'

      const base: Question = {
        id,
        type,
        text: String(raw?.text ?? ''),
        points: Number(raw?.points ?? 1) || 1,
        options: [],
        correctAnswer: 'true',
        expectedAnswer: '',
        keywords: '',
        caseSensitive: false,
      }

      if (type === 'multiple' || type === 'multiple-answer') {
        const opts = Array.isArray(raw?.options) ? raw.options : []
        const letters = ['A', 'B', 'C', 'D', 'E', 'F']
        base.options = (opts.length ? opts : [{}, {}, {}]).slice(0, 6).map((o: any, i: number) => ({
          letter: String(o?.letter ?? letters[i] ?? 'X'),
          text: String(o?.text ?? ''),
          isCorrect: false,
        }))
      }

      if (type === 'short') {
        base.keywords = String(raw?.keywords ?? '')
        base.caseSensitive = !!raw?.caseSensitive
      }

      const a = (answersJson as any[]).find((x) => Number(x?.question_id) === id)
      if (type === 'multiple' && a) {
        const correctLetter = String(a?.answer ?? '')
        base.options.forEach((o) => (o.isCorrect = o.letter === correctLetter))
      }
      if (type === 'multiple-answer' && a) {
        const correctLetters = Array.isArray(a?.answer) ? a.answer.map(String) : []
        base.options.forEach((o) => (o.isCorrect = correctLetters.includes(o.letter)))
      }
      if (type === 'true-false' && a) {
        base.correctAnswer = a?.answer === 'false' ? 'false' : 'true'
      }
      if (type === 'short' && a?.answer) {
        base.expectedAnswer = String(a.answer?.expected ?? '')
        base.keywords = String(a.answer?.keywords ?? base.keywords ?? '')
        base.caseSensitive = !!(a.answer?.caseSensitive ?? base.caseSensitive)
      }

      return base
    })

    if (hydratedQuestions.length > 0) lastKnownQuestionCount.value = hydratedQuestions.length

    exam.value = {
      ...exam.value,
      title: data?.title ?? '',
      description: data?.instructions ?? data?.description ?? '',
      topic_id: (() => {
        const raw = data?.fk_topic_id ?? data?.topic_id ?? exam.value.topic_id ?? null
        const n = Number(raw)
        return Number.isFinite(n) && n > 0 ? n : null
      })(),
      is_active: typeof data?.is_active === 'boolean' ? data.is_active : Number(data?.status ?? 0) === 0,
      status: Number(data?.status ?? 0) === 0 ? 'active' : 'inactive',
      time_limit: Number(data?.time_limit ?? 0),
      passing_score: Number(data?.passing_rate ?? data?.passing_score ?? 0),
      category_id: data?.category_id ?? null,
      exam_type: data?.exam_type ?? null,
      passing_criteria_type: data?.passing_criteria_type ?? null,
      questions: hydratedQuestions.length ? hydratedQuestions : [],
    }
  } catch (e: any) {
    console.error('loadExam failed:', e)
    showError(e?.response?.data?.message ?? 'Failed to load exam.')
  } finally {
    isLoadingExam.value = false
  }
}

const isPreviewOpen = ref(false)
const previewExam = () => {
  if (safeQuestions.value.length === 0) return showError('Add at least one question before previewing.')
  isPreviewOpen.value = true
}

const topicName = ref<string>('')

const fetchTopicName = async (topicId: number) => {
  try {
    const res = await api.get(`/topics/${topicId}`)
    const data = res?.data?.data ?? res?.data
    topicName.value = String(data?.title ?? data?.name ?? '')
  } catch (e) {
    console.error('fetchTopicName failed:', e)
    topicName.value = ''
  }
}

watch(
  () => exam.value.topic_id,
  (tid) => {
    const id = Number(tid)
    if (Number.isFinite(id) && id > 0) fetchTopicName(id)
    else topicName.value = ''
  },
  { immediate: true }
)

// ✅ ONLY load when editing
onMounted(() => {
  if (isEditing.value) loadExam()
})
</script>


