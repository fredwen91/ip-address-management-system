export interface SortField {
  key: string
  order: 'asc' | 'desc'
}

export interface LoadItemsParams {
  page: number
  itemsPerPage: number
  sortBy: SortField[]
  search?: string
}

export interface FetchResponse<T> {
  data: T[]
  total: number
}
