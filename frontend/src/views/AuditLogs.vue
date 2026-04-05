<script setup lang="ts">
import { useAuditLogs } from '@/composable/auditLogs'
import { ref } from 'vue'
import type { DataTableHeader } from 'vuetify'
import formatDate from '@/helpers/formatDate'

const { loadingAuditLogs, auditLogs, totalAuditLogs, page, itemsPerPage, fetchAuditLogs } =
  useAuditLogs()

const search = ref('')
const itemsPerPageOptions = ref([10, 25, 50, 100])
const headers = ref<DataTableHeader[]>([
  { title: 'Action', key: 'action', align: 'start' },
  { title: 'Entity Type', key: 'entity_type', align: 'start' },
  { title: 'Entity ID', key: 'entity_id', align: 'start' },
  { title: 'Data', key: 'changes', align: 'start' },
  { title: 'Date Created', key: 'created_at', align: 'start' },
  { title: 'Date Updated', key: 'updated_at', align: 'start' },
])

const dateFormatted = (date: string) => formatDate(date)
</script>

<template>
  <v-container>
    <h3 class="mb-2">{{ $route.meta.title }}</h3>

    <v-card class="mb-5">
      <v-card-text>
        <v-data-table-server
          v-model:page="page"
          :items-per-page="itemsPerPage"
          :items-per-page-options="itemsPerPageOptions"
          :headers="headers"
          :items="auditLogs"
          :items-length="totalAuditLogs"
          :loading="loadingAuditLogs"
          :search="search"
          item-value="id"
          @update:options="fetchAuditLogs"
          density="comfortable"
        >
          <template v-slot:[`item.created_at`]="{ item }">
            <span>{{ dateFormatted(item.created_at) }}</span>
          </template>
          <template v-slot:[`item.updated_at`]="{ item }">
            <span>{{ dateFormatted(item.updated_at) }}</span>
          </template>
        </v-data-table-server>
      </v-card-text>
    </v-card>
  </v-container>
</template>
