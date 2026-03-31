<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import formRules from '@/helpers/formRules'

const auth = useAuthStore()
const router = useRouter()

const rules = reactive(formRules)
const formRef = ref()
const email = ref('')
const password = ref('')
const showPassword = ref(false)

const submit = async () => {
  const { valid } = await formRef.value!.validate()
  if (!valid) return

  const success = await auth.login({
    email: email.value,
    password: password.value,
  })

  if (success) {
    router.push({ name: 'ipAddress' })
  }
}
</script>

<template>
  <div class="login-container">
    <v-card max-width="400" :disabled="auth.loading">
      <v-card-text>
        <h1 class="mb-8">Login</h1>
        <v-alert color="error" variant="tonal" density="comfortable" class="mb-5" v-if="auth.error">
          {{ auth.error }}
        </v-alert>

        <v-form ref="formRef" @submit.prevent="submit" validate-on="submit">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="email"
                label="Email"
                color="primary"
                variant="outlined"
                density="compact"
                hide-details="auto"
                autocomplete="email"
                :rules="[rules.required, rules.validEmail]"
              ></v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field
                v-model="password"
                label="Password"
                color="primary"
                variant="outlined"
                density="compact"
                hide-details="auto"
                :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showPassword ? 'text' : 'password'"
                @click:append-inner="showPassword = !showPassword"
                autocomplete="current-password"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>

            <v-col cols="12">
              <v-btn type="submit" color="primary" class="mb-5" :loading="auth.loading" block>
                Login
              </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 50px;
  margin-bottom: 50px;
}

.v-card {
  width: 400px;
  padding: 10px;
}
</style>
