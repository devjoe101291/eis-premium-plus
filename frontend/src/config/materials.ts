export type MaterialStatus = 'active' | 'inactive'

export interface Material {
  id: number;
  name: string;
  description: string;
  source_type: 'file' | 'url';
  source: string;
}

export interface MaterialsResponse {
  data: Material[]
}
