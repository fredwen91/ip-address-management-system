import type { FetchResponse, LoadItemsParams } from '@/types/data-table'
import type { IpAddress, UpsertIpAddressResponse } from '@/types/ip-address'
import { ref } from 'vue'
import api from '@/services/api'
import { normalizeTableOptions } from '@/utils/table'

export function useIpAddresses() {
  const loadingIpAddresses = ref(false)
  const ipAddressErrors = ref<Record<string, string[]>>({})
  const ipAddresses = ref<IpAddress[]>([])
  const totalIpAddresses = ref(0)
  const loadingUpsertIpAddress = ref(false)
  const loadingDeleteIpAddress = ref(false)
  const page = ref(1)
  const itemsPerPage = ref(10)
  const lastOptions = ref<LoadItemsParams | null>(null)

  const fetchIpAddresses = async (options: LoadItemsParams) => {
    loadingIpAddresses.value = true
    lastOptions.value = options

    try {
      const res = await api.get<FetchResponse<IpAddress>>('ip_addresses', {
        params: normalizeTableOptions(options),
      })

      ipAddresses.value = res.data.data
      totalIpAddresses.value = res.data.total ?? 0
    } catch (err: any) {
      alert('The system encountered an error. Please refresh the page.')
    } finally {
      loadingIpAddresses.value = false
    }
  }

  const createIpAddress = async (payload: Partial<IpAddress>): Promise<boolean> => {
    loadingUpsertIpAddress.value = true
    ipAddressErrors.value = {}

    try {
      const { data } = await api.post<UpsertIpAddressResponse>('ip_addresses', payload)

      totalIpAddresses.value += 1
      if (page.value === 1) {
        ipAddresses.value.unshift(data.data)

        if (ipAddresses.value.length > itemsPerPage.value) {
          ipAddresses.value.pop()
        }
      }

      return true
    } catch (error: any) {
      if (error.response?.status === 422 && error.response.data?.errors) {
        ipAddressErrors.value = error.response.data.errors
      } else {
        alert('The system encountered an error. Please refresh the page.')
      }

      return false
    } finally {
      loadingUpsertIpAddress.value = false
    }
  }

  const updateIpAddress = async (id: number, payload: Partial<IpAddress>): Promise<boolean> => {
    loadingUpsertIpAddress.value = true
    ipAddressErrors.value = {}

    try {
      const { data } = await api.patch<UpsertIpAddressResponse>(`ip_addresses/${id}`, payload)

      ipAddresses.value = [data.data, ...ipAddresses.value.filter((p) => p.id !== data.data.id)]

      return true
    } catch (error: any) {
      if (error.response.status === 422) {
        ipAddressErrors.value = error.response.data.errors
      } else {
        alert('The system encountered an error. Please refresh the page.')
      }

      return false
    } finally {
      loadingUpsertIpAddress.value = false
    }
  }

  const deleteIpAddress = async (id: number): Promise<boolean> => {
    loadingDeleteIpAddress.value = true

    try {
      await api.delete(`ip_addresses/${id}`)

      ipAddresses.value = ipAddresses.value.filter((p) => p.id !== id)
      totalIpAddresses.value -= 1

      return true
    } catch {
      alert('The system encountered an error. Please refresh the page.')
      return false
    } finally {
      loadingDeleteIpAddress.value = false
    }
  }

  return {
    loadingIpAddresses,
    loadingUpsertIpAddress,
    loadingDeleteIpAddress,
    ipAddressErrors,
    ipAddresses,
    totalIpAddresses,
    page,
    itemsPerPage,
    fetchIpAddresses,
    createIpAddress,
    updateIpAddress,
    deleteIpAddress,
  }
}
