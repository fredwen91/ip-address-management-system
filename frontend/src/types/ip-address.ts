export interface IpAddress {
  id: number
  ip_address: string
  label: string
  comment: string
  user_id: number
  created_at: string
  updated_at: string
}

export interface IpAddressForm {
  id?: number
  ip_address: string
  label: string
  comment: string
}

export interface UpsertIpAddressResponse {
  message: string
  data: {
    id: number
    ip_address: string
    label: string
    comment: string
    user_id: number
    created_at: string
    updated_at: string
  }
}
