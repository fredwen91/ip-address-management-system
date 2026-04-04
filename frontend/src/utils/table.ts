import type { LoadItemsParams } from '@/types/data-table'

export function normalizeTableOptions(options: LoadItemsParams) {
  const sort = options.sortBy?.[0]

  return {
    page: options.page,
    itemsPerPage: options.itemsPerPage,
    sortKey: sort?.key ?? 'updated_at',
    sortOrder: sort?.order ?? 'desc',
    search: options.search ?? null,
  }
}
