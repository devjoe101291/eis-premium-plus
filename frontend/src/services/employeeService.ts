import api from './api'

export interface Employee {
  id: number
  name: string
  first_name?: string
  last_name?: string
  username?: string
  email: string
  role: 'admin' | 'employee' | 'student'
  status: 'pending' | 'active' | 'inactive'
  title?: string
  phone?: string
  date_of_birth?: string
  gender?: 'male' | 'female' | 'other'
  address?: string
  street?: string
  city?: string
  state?: string
  zipcode?: string
  profile_picture?: string
  email_verified_at?: string
  created_at: string
  updated_at: string
}

export interface CreateEmployeeData {
  first_name: string
  last_name: string
  username: string
  email: string
  password: string
  password_confirmation?: string
  role?: 'admin' | 'employee' | 'student'
  status?: 'pending' | 'active' | 'inactive'
  title?: string
  phone?: string
  date_of_birth?: string
  gender?: 'male' | 'female' | 'other'
  address?: string
  street?: string
  city?: string
  state?: string
  zipcode?: string
}

export interface UpdateEmployeeData {
  name?: string
  first_name?: string
  last_name?: string
  username?: string
  email?: string
  role?: 'admin' | 'employee' | 'student'
  status?: 'pending' | 'active' | 'inactive'
  title?: string
  phone?: string
  date_of_birth?: string
  gender?: 'male' | 'female' | 'other'
  address?: string
  street?: string
  city?: string
  state?: string
  zipcode?: string
  profile_picture?: string
}

export interface EmployeeListResponse {
  data: Employee[]
  stats?: {
    total: number
    active: number
    inactive: number
    pending: number
  }
  total: number
  per_page: number
  current_page: number
  last_page: number
}

export const employeeService = {
  /**
   * Get all employees with pagination
   */
  async getEmployees(params?: {
    page?: number
    per_page?: number
    search?: string
    status?: string
    role?: string
    gender?: 'male' | 'female' | 'other'
  }): Promise<EmployeeListResponse> {
    const response = await api.get<EmployeeListResponse>('/users', { params })
    return response.data
  },

  /**
   * Get employee by ID
   */
  async getEmployee(id: number): Promise<Employee> {
    const response = await api.get<any>(`/users/${id}`)
    return response.data?.data || response.data
  },

  /**
   * Create new employee
   */
  async createEmployee(data: CreateEmployeeData): Promise<Employee> {
    const response = await api.post<any>('/users', data)
    return response.data?.data || response.data
  },

  /**
   * Update employee
   */
  async updateEmployee(id: number, data: UpdateEmployeeData): Promise<Employee> {
    const response = await api.put<any>(`/users/${id}`, data)
    return response.data?.data || response.data
  },

  /**
   * Update employee status
   */
  async updateEmployeeStatus(
    id: number,
    status: 'active' | 'inactive'
  ): Promise<Employee> {
    const response = await api.patch<Employee>(`/users/${id}/status`, { status })
    return response.data
  },

  /**
   * Delete employee
   */
  async deleteEmployee(id: number): Promise<void> {
    await api.delete(`/users/${id}`)
  }
}
