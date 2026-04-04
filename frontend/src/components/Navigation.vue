<script setup lang="ts">
import { ref } from 'vue'
import LogoutDialog from '@/components/LogoutDialog.vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const { logout } = useAuthStore()
const router = useRouter()

const drawer = ref(true)
const showLogout = ref(false)

const onLogout = async () => {
  showLogout.value = true

  await logout()
  router.push({ name: 'login' })
  showLogout.value = false
}
</script>

<template>
  <v-navigation-drawer v-model="drawer">
    <template v-slot:prepend>
      <v-list-item lines="two" title="Practical Test" subtitle="TECHLINT Digital Solutions"></v-list-item>
    </template>

    <v-divider></v-divider>

    <v-list nav>
      <v-list-item
        prepend-icon="mdi-ip"
        title="IP Address"
        value="ip address"
        color="primary"
        :to="{ name: 'ipAddress' }"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-ip"
        title="Test"
        value="test"
        color="primary"
        :to="{ name: 'test' }"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-logout"
        title="Logout"
        value="logout"
        @click="onLogout"
      ></v-list-item>
    </v-list>
  </v-navigation-drawer>

  <v-app-bar :elevation="2">
    <template v-slot:prepend>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
    </template>
  </v-app-bar>

  <LogoutDialog v-model="showLogout" />
</template>
