import type { AuditLogs } from '@/types/audit-logs'
import type { FetchResponse, LoadItemsParams } from '@/types/data-table'
import { ref } from 'vue'
import api from '@/services/api'
import { normalizeTableOptions } from '@/utils/table'

export function useAuditLogs() {
  const loadingAuditLogs = ref(false)
  const auditLogs = ref<AuditLogs[]>([])
  const totalAuditLogs = ref(0)
  const page = ref(1)
  const itemsPerPage = ref(10)
  const lastOptions = ref<LoadItemsParams | null>(null)

  const fetchAuditLogs = async (options: LoadItemsParams) => {
    loadingAuditLogs.value = true
    lastOptions.value = options

    try {
      const res = await api.get<FetchResponse<AuditLogs>>('audit_logs', {
        params: normalizeTableOptions(options),
      })

      auditLogs.value = res.data.data
      totalAuditLogs.value = res.data.total ?? 0
    } catch (err: any) {
      alert('The system encountered an error. Please refresh the page.')
    } finally {
      loadingAuditLogs.value = false
    }
  }

  return {
    loadingAuditLogs,
    auditLogs,
    totalAuditLogs,
    page,
    itemsPerPage,
    fetchAuditLogs,
  }
}
