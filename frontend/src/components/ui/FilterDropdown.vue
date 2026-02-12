<template>
  <div class="relative">
    <button
      type="button"
      @click="toggleDropdown"
      class="btn btn-secondary flex items-center space-x-2"
      :class="{ 'bg-primary dark:bg-primary-dark': isOpen }"
    >
      <span>{{ selectedLabel || label }}</span>
      <svg
        class="h-4 w-4 transition-transform duration-200"
        :class="{ 'rotate-180': isOpen }"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>

    <div
      v-if="isOpen"
      v-click-outside="closeDropdown"
      class="absolute z-10 mt-2 w-48 rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
    >
      <div class="py-1">
        <button
          v-for="option in options"
          :key="option.value"
          type="button"
          @click="selectOption(option)"
          class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
          :class="{
            'bg-primary-bg dark:bg-primary-dark-bg text-primary dark:text-primary-dark': option.value === modelValue
          }"
        >
          {{ option.label }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useClickOutside } from '@/composables/ui/useClickOutside'

interface FilterOption {
  value: string
  label: string
}

const props = defineProps<{
  modelValue: string
  label: string
  options: FilterOption[]
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const isOpen = ref(false)

const selectedLabel = computed(() => {
  const option = props.options.find(opt => opt.value === props.modelValue)
  return option?.label
})

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const selectOption = (option: FilterOption) => {
  emit('update:modelValue', option.value)
  isOpen.value = false
}

const { handleClickOutside } = useClickOutside(isOpen)
const closeDropdown = () => {
  isOpen.value = false
}
</script>
