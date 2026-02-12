import { computed, ref } from 'vue';

export function usePagination<T>(items: () => T[], perPage = 10) {
  const page = ref(1);

  const paginated = computed(() => {
    const start = (page.value - 1) * perPage;
    return items().slice(start, start + perPage);
  });

  return { page, paginated };
}
