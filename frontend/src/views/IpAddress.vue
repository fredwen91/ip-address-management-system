<script setup lang="ts">
import { useIpAddresses } from '@/composable/useIpAddress'
import type { IpAddress, IpAddressForm } from '@/types/ip-address'
import { ref } from 'vue'
import type { DataTableHeader } from 'vuetify'
import formatDate from '@/helpers/formatDate'
import Snackbar from '@/components/Snackbar.vue'
import UpsertIpAddressDialog from '@/components/UpsertIpAddressDialog.vue'

const {
  loadingIpAddresses,
  loadingUpsertIpAddress,
  loadingDeleteIpAddress,
  ipAddressError,
  ipAddressErrors,
  ipAddresses,
  totalIpAddresses,
  page,
  itemsPerPage,
  fetchIpAddresses,
  createIpAddress,
  updateIpAddress,
  deleteIpAddress,
} = useIpAddresses()

const search = ref('')
const itemsPerPageOptions = ref([10, 25, 50, 100])
const headers = ref<DataTableHeader[]>([
  { title: 'Action', key: 'actions', align: 'center', sortable: false },
  { title: 'IP Address', key: 'ip_address', align: 'start' },
  { title: 'Label', key: 'label', align: 'start' },
  { title: 'Comment', key: 'comment', align: 'start' },
  { title: 'Date Created', key: 'created_at', align: 'start' },
  { title: 'Date Updated', key: 'updated_at', align: 'start' },
])
const selectedIpAddress = ref<IpAddress | null>(null)
const dialogOpen = ref(false)
const confirmDeleteDialog = ref(false)
const resMessage = ref('')
const snackbarRef = ref<InstanceType<typeof Snackbar> | null>(null)

const dateFormatted = (date: string) => formatDate(date)

const openDialog = (ipAddress: IpAddress) => {
  if (ipAddressErrors.value) ipAddressErrors.value = {}

  if (ipAddress) selectedIpAddress.value = ipAddress
  dialogOpen.value = true
}

const confirmDelete = (ipAddress: IpAddress) => {
  selectedIpAddress.value = ipAddress
  confirmDeleteDialog.value = true
}

const saveIpAddress = async (data: IpAddressForm) => {
  resMessage.value = ''

  if (selectedIpAddress.value?.id) {
    const res = await updateIpAddress(selectedIpAddress.value.id, data)
    if (res) {
      resMessage.value = 'Updated successfully'
    }
  } else {
    const res = await createIpAddress(data)
    if (res) {
      resMessage.value = 'Created successfully.'
    }
  }

  if (resMessage.value) {
    dialogOpen.value = false

    snackbarRef.value?.show({
      message: resMessage.value,
      type: 'success',
      timeout: 3000,
    })
  }
}

const clearError = (field: string) => {
  delete ipAddressErrors.value[field]
}

const deleteConfirmed = async () => {
  if (!selectedIpAddress.value) return
  const res = await deleteIpAddress(selectedIpAddress.value.id)

  if (res) {
    confirmDeleteDialog.value = false
    selectedIpAddress.value = null

    snackbarRef.value?.show({
      message: 'Deleted successfully',
      type: 'success',
      timeout: 3000,
    })
  }
}
</script>

<template>
  <v-container>
    <h3 class="mb-2">{{ $route.meta.title }}</h3>

    <v-card class="mb-5">
      <v-card-title class="d-flex align-center pb-4">
        <v-tooltip text="Create" location="right">
          <template v-slot:activator="{ props }">
            <v-btn
              v-bind="props"
              :disabled="loadingIpAddresses"
              density="compact"
              icon="mdi-plus"
              color="success"
              @click="openDialog"
            ></v-btn>
          </template>
        </v-tooltip>
      </v-card-title>

      <v-divider></v-divider>

      <v-card-text>
        <v-data-table-server
          v-model:page="page"
          :items-per-page="itemsPerPage"
          :items-per-page-options="itemsPerPageOptions"
          :headers="headers"
          :items="ipAddresses"
          :items-length="totalIpAddresses"
          :loading="loadingIpAddresses"
          :search="search"
          item-value="id"
          @update:options="fetchIpAddresses"
          density="comfortable"
        >
          <template v-slot:[`item.actions`]="{ item }">
            <v-tooltip text="Edit" location="top">
              <template v-slot:activator="{ props }">
                <v-btn
                  v-bind="props"
                  variant="flat"
                  icon
                  @click="openDialog(item)"
                  density="compact"
                  class="mr-1"
                >
                  <v-icon size="small" color="primary"> mdi-pencil </v-icon>
                </v-btn>
              </template>
            </v-tooltip>

            <v-tooltip text="Delete" location="top">
              <template v-slot:activator="{ props }">
                <v-btn
                  v-bind="props"
                  variant="flat"
                  icon
                  @click="confirmDelete(item)"
                  density="compact"
                  class="mr-1"
                >
                  <v-icon size="small" color="error"> mdi-delete </v-icon>
                </v-btn>
              </template>
            </v-tooltip>
          </template>
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

  <UpsertIpAddressDialog
    v-model="dialogOpen"
    :loading="loadingUpsertIpAddress"
    :errors="ipAddressErrors"
    :ipAddress="selectedIpAddress"
    @save="saveIpAddress"
    @clear-error="clearError"
  />

  <v-dialog v-model="confirmDeleteDialog" max-width="400">
    <v-card :disabled="loadingDeleteIpAddress" :loading="loadingDeleteIpAddress">
      <v-card-title class="text-h6">Confirm Delete</v-card-title>
      <v-card-text>
        Are you sure you want to delete <strong>{{ selectedIpAddress?.ip_address }}</strong
        >?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn variant="tonal" @click="confirmDeleteDialog = false">Cancel</v-btn>
        <v-btn color="red" variant="flat" @click="deleteConfirmed" :loading="loadingDeleteIpAddress"
          >Delete</v-btn
        >
      </v-card-actions>
    </v-card>
  </v-dialog>

  <Snackbar ref="snackbarRef" />
</template>
