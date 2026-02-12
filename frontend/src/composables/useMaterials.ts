// src/composables/useMaterials.ts
import { storeToRefs } from 'pinia'
import { useMaterialStore } from '@/stores/materialStore'

export function useMaterials() {
  const store = useMaterialStore()
  const { materials, totalMaterials } = storeToRefs(store)

  return {
    materials,
    totalMaterials,
    fetchMaterials: store.fetchMaterials,
    fetchMaterialById: store.fetchMaterialById,
    addMaterial: store.addMaterial,
    removeMaterial: store.removeMaterial,
  }
}
