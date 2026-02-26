<template>
  <Teleport to="body">
    <transition name="fade">
      <div
        v-if="modelValue"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 overflow-y-auto"
      >
        <!-- Overlay -->
        <div
          class="absolute inset-0 bg-black/50 backdrop-blur-sm"
          @click="close"
        />

        <!-- Modal -->
        <div
          class="relative bg-white dark:bg-slate-800
                 rounded-xl sm:rounded-2xl
                 w-full max-w-3xl
                 shadow-2xl
                 my-4 sm:my-8
                 max-h-[95vh] sm:max-h-[90vh]
                 flex flex-col
                 overflow-hidden"
        >
          <!-- Header -->
          <div
            class="px-4 sm:px-6 py-4 sm:py-5
                   border-b border-slate-200 dark:border-slate-700
                   bg-gradient-to-r from-primary/5 to-secondary/5
                   flex-shrink-0"
          >
            <div class="flex items-center justify-between gap-3">
              <h3
                class="text-lg sm:text-xl font-bold
                       bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent"
              >
                {{ title }}
              </h3>

              <button
                type="button"
                class="p-2 rounded-lg text-slate-400 hover:text-slate-600
                       dark:hover:text-slate-200
                       hover:bg-slate-100 dark:hover:bg-slate-700
                       transition-all"
                @click="close"
                aria-label="Close"
                title="Close"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Body -->
          <div class="px-4 sm:px-6 py-4 sm:py-6 overflow-y-auto flex-1">
            <slot />
          </div>

          <!-- Footer -->
          <div
            class="px-4 sm:px-6 py-3 sm:py-4
                   border-t border-slate-200 dark:border-slate-700
                   bg-slate-50 dark:bg-slate-800/50
                   flex-shrink-0"
          >
            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-2 sm:gap-3">
              <slot name="footer">
                <button
                  type="button"
                  class="px-4 sm:px-5 py-2.5 text-sm font-semibold
                         text-slate-700 dark:text-slate-300
                         bg-white dark:bg-slate-700
                         border border-slate-300 dark:border-slate-600
                         rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600
                         transition-all shadow-sm"
                  @click="close"
                >
                  Cancel
                </button>
              </slot>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </Teleport>
</template>


<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Modal',

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: '',
    },
  },

  emits: ['update:modelValue'],

  setup(_, { emit }) {
    const close = () => emit('update:modelValue', false);

    return { close };
  },
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
