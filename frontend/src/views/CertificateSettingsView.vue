<template>
  <div class="space-y-6 sm:space-y-8 px-6 sm:px-8 py-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-tr from-primary to-secondary dark:from-primary-light dark:to-secondary-light bg-clip-text text-transparent">
          Certificate Settings
        </h1>
        <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70 mt-2">
          Customize your certificate template and preview it in real time.
        </p>
      </div>

      <div class="flex flex-wrap gap-3">
        <button
          @click="saveSettings"
          :disabled="isSaving || isLoading"
          class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold bg-primary text-white hover:opacity-95 transition dark:bg-primary-dark disabled:opacity-60 disabled:cursor-not-allowed"
        >
          <Save class="h-4 w-4" />
          <span>{{ isSaving ? 'Saving...' : 'Save Settings' }}</span>
        </button>

        <button
          @click="resetToDefaults"
          :disabled="isLoading"
          class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold bg-primary text-white hover:opacity-95 transition dark:bg-primary-dark disabled:opacity-60 disabled:cursor-not-allowed"
        >
          <RotateCcw class="h-4 w-4" />
          Reset to Defaults
        </button>

      </div>
    </div>

    <div class="space-y-6">
      <section>
        <div class="card relative z-10 border border-gray-200/50 dark:border-gray-700/50 shadow-sm overflow-visible">
          <div class="card-body">
            <div class="flex items-center gap-3 mb-5">
              <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary/10 dark:bg-primary-dark/10">
                <Eye class="w-5 h-5 text-primary dark:text-primary-dark" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-primary-text dark:text-primary-dark-text">Certificate Preview</h2>
                <p class="text-sm text-secondary-text/70 dark:text-secondary-dark-text/70">
                  Edit directly on the certificate.
                </p>
              </div>
            </div>

            <div class="rounded-2xl border border-gray-200/60 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/30 p-5 shadow-inner">
              <div class="mx-auto w-full max-w-[980px] aspect-[1.414/1] rounded-xl shadow-lg overflow-hidden certificate-bg">
                <div class="relative h-full p-3">
                  <div class="absolute inset-3 border-2 border-primary/60 dark:border-primary-dark/60"></div>
                  <div class="absolute inset-[18px] border border-secondary/70 dark:border-secondary-dark/70"></div>
                  <div class="absolute inset-[26px] border border-primary/25 dark:border-primary-dark/25"></div>

                  <div class="relative h-full px-10 sm:px-12 py-10 flex flex-col items-center text-center">
                    <input
                      v-model="organizationName"
                      type="text"
                      placeholder="Organization name"
                      class="w-full bg-transparent text-center text-4xl font-extrabold tracking-wide text-primary-text dark:text-primary-dark-text focus:outline-none"
                    />

                    <p class="mt-4 text-3xl font-serif text-primary-text/90 dark:text-primary-dark-text/90">
                      Certificate of Completion
                    </p>

                    <input
                      v-model="programName"
                      type="text"
                      placeholder="Program name"
                      class="mt-7 w-full bg-transparent text-center text-lg text-secondary-text/80 dark:text-secondary-dark-text/80 focus:outline-none"
                    />

                    <input
                      v-model="sampleRecipientName"
                      type="text"
                      placeholder="Recipient name"
                      class="mt-8 w-full bg-transparent text-center text-4xl font-extrabold text-primary-text dark:text-primary-dark-text focus:outline-none"
                    />

                    <div class="mt-3 h-px w-[62%] bg-primary/40 dark:bg-primary-dark/40"></div>

                    <textarea
                      v-model="certificateText"
                      rows="3"
                      placeholder="Certificate message"
                      class="mt-7 w-full max-w-[72%] bg-transparent text-center text-base leading-7 text-secondary-text dark:text-secondary-dark-text focus:outline-none resize-none"
                    ></textarea>

                    <div class="mt-auto w-full px-6 sm:px-10 pb-2">
                      <div class="grid grid-cols-2 gap-8 sm:gap-10 items-end">
                        <div class="text-center relative group">
                          <button
                            v-if="signaturePreviewUrl || signatureDataUrl"
                            @click="clearLeftSignature"
                            title="Clear and redraw left signature"
                            class="absolute -top-5 right-[20%] text-xs font-semibold text-danger hover:text-danger-hover transition opacity-70 hover:opacity-100"
                          >
                            Clear
                          </button>
                          <div class="h-14 flex items-end justify-center mb-2">
                            <canvas
                              ref="signatureCanvas"
                              class="mx-auto h-12 w-[60%] border-b border-primary/40 dark:border-primary-dark/40"
                              style="touch-action: none;"
                              @pointerdown="startDrawing"
                              @pointermove="drawSignature"
                              @pointerup="stopDrawing"
                              @pointerleave="stopDrawing"
                              @pointercancel="stopDrawing"
                            ></canvas>
                          </div>
                          <input
                            v-model="leftSignatureName"
                            type="text"
                            placeholder="Signer name"
                            class="w-full bg-transparent text-center font-semibold text-primary-text dark:text-primary-dark-text focus:outline-none"
                          />
                          <input
                            v-model="leftSignaturePosition"
                            type="text"
                            placeholder="Position"
                            class="mt-1 w-full bg-transparent text-center text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 focus:outline-none"
                          />
                        </div>

                        <div class="text-center relative group">
                          <button
                            v-if="rightSignatureDataUrl"
                            @click="clearRightSignature"
                            title="Clear and redraw right signature"
                            class="absolute -top-5 right-[20%] text-xs font-semibold text-danger hover:text-danger-hover transition opacity-70 hover:opacity-100"
                          >
                            Clear
                          </button>
                          <div class="h-14 flex items-end justify-center mb-2">
                            <canvas
                              ref="rightSignatureCanvas"
                              class="mx-auto h-12 w-[60%] border-b border-primary/40 dark:border-primary-dark/40"
                              style="touch-action: none;"
                              @pointerdown="startRightDrawing"
                              @pointermove="drawRightSignature"
                              @pointerup="stopRightDrawing"
                              @pointerleave="stopRightDrawing"
                              @pointercancel="stopRightDrawing"
                            ></canvas>
                          </div>
                          <input
                            v-model="rightSignatureName"
                            type="text"
                            placeholder="Signer name"
                            class="w-full bg-transparent text-center font-semibold text-primary-text dark:text-primary-dark-text focus:outline-none"
                          />
                          <input
                            v-model="rightSignaturePosition"
                            type="text"
                            placeholder="Position"
                            class="mt-1 w-full bg-transparent text-center text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 focus:outline-none"
                          />
                        </div>
                      </div>

                      <div class="mt-8 flex items-center justify-center gap-2 text-sm text-secondary-text/80 dark:text-secondary-dark-text/80">
                        <span>Date:</span>
                        <input
                          v-model="dateISO"
                          type="date"
                          class="bg-transparent text-sm text-secondary-text/80 dark:text-secondary-dark-text/80 focus:outline-none"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { Eye, RotateCcw, Save } from 'lucide-vue-next'
import { useTitle } from '@/composables/ui/useTitle'
import { useToast } from '@/composables/ui/useToast'
import {
  certificateSettingsService,
  type CertificateSettings,
  type SaveCertificateSettingsPayload,
} from '@/services/certificateSettingsService'

const appTitle = computed(() => process.env.VUE_APP_TITLE || 'SP Team Template')
useTitle(`${appTitle.value} - Certificate Settings`)

const { success: showSuccess, error: showError } = useToast()

const defaults: CertificateSettings = {
  organizationName: 'Proweaver, Inc',
  programName: 'Online Examination Program',
  sampleRecipientName: 'John Doe',
  certificateText:
    'This certificate is awarded to this individual for the successful completion of the online examination program.',
  leftSignatureName: 'Test Admin',
  leftSignaturePosition: 'AdminTest',
  rightSignatureName: '',
  rightSignaturePosition: '',
  rightSignatureDataUrl: '',
  dateISO: new Date().toISOString().slice(0, 10),
}

const isLoading = ref(true)
const isSaving = ref(false)

const organizationName = ref(defaults.organizationName)
const programName = ref(defaults.programName)
const sampleRecipientName = ref(defaults.sampleRecipientName)
const certificateText = ref(defaults.certificateText)
const leftSignatureName = ref(defaults.leftSignatureName)
const leftSignaturePosition = ref(defaults.leftSignaturePosition)
const rightSignatureName = ref(defaults.rightSignatureName)
const rightSignaturePosition = ref(defaults.rightSignaturePosition)
const dateISO = ref(defaults.dateISO)

const signaturePreviewUrl = ref<string | null>(null)
const signatureDataUrl = ref<string | null>(null)
const rightSignatureDataUrl = ref<string | null>(null)
const shouldRemoveSignature = ref(false)
const signatureCanvas = ref<HTMLCanvasElement | null>(null)
const rightSignatureCanvas = ref<HTMLCanvasElement | null>(null)
const isDrawing = ref(false)
const isRightDrawing = ref(false)
let signatureContext: CanvasRenderingContext2D | null = null
let rightSignatureContext: CanvasRenderingContext2D | null = null
let lastPoint: { x: number; y: number } | null = null
let rightLastPoint: { x: number; y: number } | null = null


const applyContent = (content?: Partial<CertificateSettings>) => {
  const source = content || {}
  organizationName.value = source.organizationName ?? defaults.organizationName
  programName.value = source.programName ?? defaults.programName
  sampleRecipientName.value = source.sampleRecipientName ?? defaults.sampleRecipientName
  certificateText.value = source.certificateText ?? defaults.certificateText
  leftSignatureName.value = source.leftSignatureName ?? defaults.leftSignatureName
  leftSignaturePosition.value = source.leftSignaturePosition ?? defaults.leftSignaturePosition
  rightSignatureName.value = source.rightSignatureName ?? defaults.rightSignatureName
  rightSignaturePosition.value = source.rightSignaturePosition ?? defaults.rightSignaturePosition
  rightSignatureDataUrl.value = source.rightSignatureDataUrl ?? defaults.rightSignatureDataUrl
  const today = new Date().toISOString().slice(0, 10)
  // Always reflect the current date dynamically on load
  dateISO.value = today
}

const initializeSignatureCanvas = () => {
  const canvas = signatureCanvas.value
  if (!canvas) return

  signatureContext = canvas.getContext('2d')
  if (!signatureContext) return

  resizeSignatureCanvas()
}

const clearCanvasOnly = () => {
  const canvas = signatureCanvas.value
  if (!canvas || !signatureContext) return

  signatureContext.save()
  signatureContext.setTransform(1, 0, 0, 1, 0, 0)
  signatureContext.clearRect(0, 0, canvas.width, canvas.height)
  signatureContext.restore()
}

const resizeSignatureCanvas = () => {
  const canvas = signatureCanvas.value
  if (!canvas || !signatureContext) return

  const existingSignature = signaturePreviewUrl.value
  const rect = canvas.getBoundingClientRect()
  const ratio = window.devicePixelRatio || 1
  canvas.width = Math.max(1, Math.floor(rect.width * ratio))
  canvas.height = Math.max(1, Math.floor(rect.height * ratio))
  signatureContext.setTransform(ratio, 0, 0, ratio, 0, 0)
  signatureContext.lineWidth = 2
  signatureContext.lineCap = 'round'
  signatureContext.strokeStyle = '#1f2937'

  if (existingSignature) {
    renderSignatureToCanvas(existingSignature)
  } else {
    clearCanvasOnly()
  }
}

const renderSignatureToCanvas = (dataUrl: string) => {
  const canvas = signatureCanvas.value
  if (!canvas || !signatureContext) return

  const img = new Image()
  img.crossOrigin = 'anonymous'
  img.onload = () => {
    const ratio = window.devicePixelRatio || 1
    const width = canvas.width / ratio
    const height = canvas.height / ratio
    clearCanvasOnly()
    signatureContext?.drawImage(img, 0, 0, width, height)
  }
  img.src = dataUrl
}
const initializeRightSignatureCanvas = () => {
  const canvas = rightSignatureCanvas.value
  if (!canvas) return

  rightSignatureContext = canvas.getContext('2d')
  if (!rightSignatureContext) return

  resizeRightSignatureCanvas()
}

const clearRightCanvasOnly = () => {
  const canvas = rightSignatureCanvas.value
  if (!canvas || !rightSignatureContext) return

  rightSignatureContext.save()
  rightSignatureContext.setTransform(1, 0, 0, 1, 0, 0)
  rightSignatureContext.clearRect(0, 0, canvas.width, canvas.height)
  rightSignatureContext.restore()
}

const resizeRightSignatureCanvas = () => {
  const canvas = rightSignatureCanvas.value
  if (!canvas || !rightSignatureContext) return

  const existingSignature = rightSignatureDataUrl.value
  const rect = canvas.getBoundingClientRect()
  const ratio = window.devicePixelRatio || 1
  canvas.width = Math.max(1, Math.floor(rect.width * ratio))
  canvas.height = Math.max(1, Math.floor(rect.height * ratio))
  rightSignatureContext.setTransform(ratio, 0, 0, ratio, 0, 0)
  rightSignatureContext.lineWidth = 2
  rightSignatureContext.lineCap = 'round'
  rightSignatureContext.strokeStyle = '#1f2937'

  if (existingSignature) {
    renderRightSignatureToCanvas(existingSignature)
  } else {
    clearRightCanvasOnly()
  }
}

const renderRightSignatureToCanvas = (dataUrl: string) => {
  const canvas = rightSignatureCanvas.value
  if (!canvas || !rightSignatureContext) return

  const img = new Image()
  img.crossOrigin = 'anonymous'
  img.onload = () => {
    const ratio = window.devicePixelRatio || 1
    const width = canvas.width / ratio
    const height = canvas.height / ratio
    clearRightCanvasOnly()
    rightSignatureContext?.drawImage(img, 0, 0, width, height)
  }
  img.src = dataUrl
}

const getRightCanvasPoint = (event: PointerEvent) => {
  const canvas = rightSignatureCanvas.value
  if (!canvas) return null
  const rect = canvas.getBoundingClientRect()
  return {
    x: event.clientX - rect.left,
    y: event.clientY - rect.top,
  }
}

const startRightDrawing = (event: PointerEvent) => {
  if (!rightSignatureContext) return
  const point = getRightCanvasPoint(event)
  if (!point) return

  isRightDrawing.value = true
  rightLastPoint = point
  rightSignatureContext.beginPath()
  rightSignatureContext.moveTo(point.x, point.y)
}

const drawRightSignature = (event: PointerEvent) => {
  if (!isRightDrawing.value || !rightSignatureContext) return
  const point = getRightCanvasPoint(event)
  if (!point || !rightLastPoint) return

  rightSignatureContext.lineTo(point.x, point.y)
  rightSignatureContext.stroke()
  rightLastPoint = point
}

const stopRightDrawing = () => {
  if (!isRightDrawing.value || !rightSignatureContext) return
  isRightDrawing.value = false
  rightLastPoint = null
  rightSignatureContext.closePath()

  const canvas = rightSignatureCanvas.value
  if (!canvas) return

  const dataUrl = canvas.toDataURL('image/png')
  rightSignatureDataUrl.value = dataUrl
}

const clearRightSignature = () => {
  clearRightCanvasOnly()
  rightSignatureDataUrl.value = null
}

const getCanvasPoint = (event: PointerEvent) => {
  const canvas = signatureCanvas.value
  if (!canvas) return null
  const rect = canvas.getBoundingClientRect()
  return {
    x: event.clientX - rect.left,
    y: event.clientY - rect.top,
  }
}

const startDrawing = (event: PointerEvent) => {
  if (!signatureContext) return
  const point = getCanvasPoint(event)
  if (!point) return

  isDrawing.value = true
  lastPoint = point
  signatureContext.beginPath()
  signatureContext.moveTo(point.x, point.y)
}

const drawSignature = (event: PointerEvent) => {
  if (!isDrawing.value || !signatureContext) return
  const point = getCanvasPoint(event)
  if (!point || !lastPoint) return

  signatureContext.lineTo(point.x, point.y)
  signatureContext.stroke()
  lastPoint = point
}

const stopDrawing = () => {
  if (!isDrawing.value || !signatureContext) return
  isDrawing.value = false
  lastPoint = null
  signatureContext.closePath()

  const canvas = signatureCanvas.value
  if (!canvas) return

  const dataUrl = canvas.toDataURL('image/png')
  signaturePreviewUrl.value = dataUrl
  signatureDataUrl.value = dataUrl
  shouldRemoveSignature.value = false
}

const clearLeftSignature = () => {
  clearCanvasOnly()
  signaturePreviewUrl.value = null
  signatureDataUrl.value = null
  shouldRemoveSignature.value = true
}

const loadSettings = async () => {
  isLoading.value = true
  try {
    const response = await certificateSettingsService.getSettings()
    applyContent(response.data?.content)
    const signatureUrl = response.data?.e_signature_url || null
    signaturePreviewUrl.value = signatureUrl
    signatureDataUrl.value = signatureUrl?.startsWith('data:') ? signatureUrl : null
    shouldRemoveSignature.value = false
    if (signatureUrl) {
      renderSignatureToCanvas(signatureUrl)
    } else {
      clearCanvasOnly()
    }
    rightSignatureDataUrl.value = response.data?.content?.rightSignatureDataUrl || null
    if (rightSignatureDataUrl.value) {
      renderRightSignatureToCanvas(rightSignatureDataUrl.value)
    } else {
      clearRightCanvasOnly()
    }
  } catch (err: any) {
    showError(err?.response?.data?.message || 'Failed to load certificate settings', 'Error')
    applyContent(defaults)
  } finally {
    isLoading.value = false
  }
}

const saveSettings = async () => {
  isSaving.value = true
  try {
    const payload: SaveCertificateSettingsPayload = {
      organizationName: organizationName.value.trim() || defaults.organizationName,
      programName: programName.value.trim() || defaults.programName,
      sampleRecipientName: sampleRecipientName.value.trim() || defaults.sampleRecipientName,
      certificateText: certificateText.value.trim() || defaults.certificateText,
      leftSignatureName: leftSignatureName.value.trim(),
      leftSignaturePosition: leftSignaturePosition.value.trim(),
      rightSignatureName: rightSignatureName.value.trim(),
      rightSignaturePosition: rightSignaturePosition.value.trim(),
      rightSignatureDataUrl: rightSignatureDataUrl.value,
      dateISO: dateISO.value || defaults.dateISO,
      eSignatureDataUrl: signatureDataUrl.value,
      removeSignature: shouldRemoveSignature.value,
    }

    const response = await certificateSettingsService.saveSettings(payload)
    applyContent(response.data?.content)
    const signatureUrl = response.data?.e_signature_url || null
    signaturePreviewUrl.value = signatureUrl
    signatureDataUrl.value = signatureUrl?.startsWith('data:') ? signatureUrl : null
    shouldRemoveSignature.value = false
    if (signatureUrl) {
      renderSignatureToCanvas(signatureUrl)
    } else {
      clearCanvasOnly()
    }
    rightSignatureDataUrl.value = response.data?.content?.rightSignatureDataUrl || null
    if (rightSignatureDataUrl.value) {
      renderRightSignatureToCanvas(rightSignatureDataUrl.value)
    } else {
      clearRightCanvasOnly()
    }
    showSuccess('Certificate settings saved.', 'Success')
  } catch (err: any) {
    showError(err?.response?.data?.message || 'Failed to save certificate settings', 'Error')
  } finally {
    isSaving.value = false
  }
}
const resetToDefaults = () => {
  applyContent(defaults)
  clearLeftSignature()
  clearRightSignature()
}

onMounted(async () => {
  await nextTick()
  initializeSignatureCanvas()
  initializeRightSignatureCanvas()
  window.addEventListener('resize', resizeSignatureCanvas)
  window.addEventListener('resize', resizeRightSignatureCanvas)
  await loadSettings()
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', resizeSignatureCanvas)
  window.removeEventListener('resize', resizeRightSignatureCanvas)
})
</script>

<style scoped>
.certificate-bg {
  background-image: url('@/assets/certificate-logo.png');
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
}
</style>






























