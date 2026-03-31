<script setup lang="ts">
import Navigation from './components/Navigation.vue'
import { useAuthStore } from '@/stores/auth'
const auth = useAuthStore()
</script>

<template>
  <v-app>
    <Navigation v-if="auth.isAuthenticated && auth.accessToken" />
    <v-main>
      <RouterView />
    </v-main>
  </v-app>
</template>

<style scoped></style>
