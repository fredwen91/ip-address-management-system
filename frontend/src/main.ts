import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import vuetify from "../plugins/vuetify";
import { useAuthStore } from '@/stores/auth'

const app = createApp(App)
app.use(createPinia())

const { fetchUser } = useAuthStore()
await fetchUser()

app.use(router)
app.use(vuetify)

app.mount('#app')
