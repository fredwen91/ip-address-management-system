export interface AuditLogs {
  id: number
  action: string
  entity_type: string
  entity_id: string
  changes: number
  session_id: number
  created_at: string
  updated_at: string
}
