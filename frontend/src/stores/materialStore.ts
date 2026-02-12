// src/stores/materialStore.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Material } from '@/config/types/material'
import { materialService } from '@/services/material.service'

export const useMaterialStore = defineStore('materials', () => {
  const materials = ref<Material[]>([])
  const totalMaterials = ref(0)

  const fetchMaterials = async () => {
    try {
      const data = await materialService.fetchAll()
      materials.value = data
      totalMaterials.value = materials.value.length
    } catch (error) {
      console.error('Error fetching materials:', error)
      throw error
    }
  }

  const fetchMaterialById = async (id: number) => {
    try {
      return await materialService.fetchById(id)
    } catch (error) {
      console.error('Error fetching material by id:', error)
      throw error
    }
  }

  const addMaterial = async (formData: FormData) => {
    try {
      const created = await materialService.create(formData)
      materials.value.unshift(created)
      totalMaterials.value = materials.value.length
      return created
    } catch (error) {
      console.error('Error adding material:', error)
      throw error
    }
  }

  const removeMaterial = async (id: number) => {
    try {
      await materialService.delete(id)
      materials.value = materials.value.filter((m) => m.id !== id)
      totalMaterials.value = materials.value.length
    } catch (error) {
      console.error('Error deleting material:', error)
      throw error
    }
  }

  return {
    materials,
    totalMaterials,
    fetchMaterials,
    fetchMaterialById,
    addMaterial,
    removeMaterial,
  }
})
