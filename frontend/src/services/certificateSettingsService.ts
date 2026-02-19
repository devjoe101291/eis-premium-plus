import api from './api'

export interface CertificateSettings {
  organizationName: string
  programName: string
  sampleRecipientName: string
  certificateText: string
  leftSignatureName: string
  leftSignaturePosition: string
  rightSignatureName: string
  rightSignaturePosition: string
  rightSignatureDataUrl?: string | null
  dateISO: string
}

export interface SaveCertificateSettingsPayload extends CertificateSettings {
  eSignatureDataUrl?: string | null
  removeSignature?: boolean
}

export interface CertificateSettingsResponse {
  success: boolean
  data: {
    id: number
    template_name: string
    content: CertificateSettings
    e_signature_url: string | null
    is_active: boolean
  }
}

export const certificateSettingsService = {
  async getSettings(): Promise<CertificateSettingsResponse> {
    const response = await api.get<CertificateSettingsResponse>('/certificate-settings')
    return response.data
  },

  async saveSettings(payload: SaveCertificateSettingsPayload): Promise<CertificateSettingsResponse> {
    const requestBody = {
      organizationName: payload.organizationName,
      programName: payload.programName,
      sampleRecipientName: payload.sampleRecipientName,
      certificateText: payload.certificateText,
      leftSignatureName: payload.leftSignatureName,
      leftSignaturePosition: payload.leftSignaturePosition,
      rightSignatureName: payload.rightSignatureName,
      rightSignaturePosition: payload.rightSignaturePosition,
      right_signature_data: payload.rightSignatureDataUrl || null,
      dateISO: payload.dateISO,
      e_signature_data: payload.eSignatureDataUrl || null,
      remove_signature: payload.removeSignature ? 1 : 0,
    }

    const response = await api.put<CertificateSettingsResponse>('/certificate-settings', requestBody)
    return response.data
  },
}







