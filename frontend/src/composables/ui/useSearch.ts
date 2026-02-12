import { ref, computed } from 'vue';

export function useSearch<T>(
  items: () => T[],
  key: keyof T
) {
  const query = ref('');

  const filtered = computed(() =>
    items().filter(item =>
      String(item[key])
        .toLowerCase()
        .includes(query.value.toLowerCase())
    )
  );

  return { query, filtered };
}
