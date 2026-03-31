import { defineStore } from 'pinia'
import api from '@/services/api'
import type { User } from '@/types/user'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    accessToken: null as string | null,
    loading: false,
    error: null as string | null,
  }),

  getters: {
    isAuthenticated(state) {
      return state.accessToken && state.user
    },
  },

  actions: {
    async login(credentials: { email: string; password: string }) {
      this.loading = true
      this.error = null

      try {
        const { data } = await api.post('auth/login', credentials)

        localStorage.setItem('accessToken', data.access_token)
        localStorage.setItem('refreshToken', data.refresh_token)
        await this.fetchUser()

        return true
      } catch (error: any) {
        if (error.response.status === 401) {
          this.error = error.response.data.message
        } else {
          alert('The system encountered an error. Please refresh the page.')
        }
      } finally {
        this.loading = false
      }
    },

    async fetchUser() {
      const accessToken = localStorage.getItem('accessToken')
      if (!accessToken) return

      this.accessToken = accessToken
      try {
        const { data } = await api.get('auth/me')
        this.user = data
      } catch {
        this.logout()
      }
    },

    async logout() {
      try {
        const refreshToken = localStorage.getItem('refreshToken')

        await api.post('auth/logout', { refresh_token: refreshToken })

        this.user = null
        this.accessToken = null
        localStorage.removeItem('accessToken')
        localStorage.removeItem('refreshToken')
      } catch (error) {
        alert('The system encountered an error. Please refresh the page.')
      }
    },
  },
})
