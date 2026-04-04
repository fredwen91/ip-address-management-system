<template>
  <v-snackbar v-model="snackbar" location="top right" :color="color" :timeout="timeout">
    {{ message }}

    <template v-if="closable" #actions>
      <v-btn icon size="small" @click="snackbar = false">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface SnackbarPayload {
  message: string
  type?: string
  timeout?: number
  closable?: boolean
}

const snackbar = ref(false)
const closable = ref(true)
const color = ref<string>('')
const timeout = ref<number>(4000)
const message = ref<string>('')

function show(val: SnackbarPayload) {
  closable.value = val.closable ?? true
  color.value = val.type ?? ''
  timeout.value = val.timeout ?? 4000
  message.value = val.message
  snackbar.value = true
}

function hide() {
  snackbar.value = false
}

defineExpose({
  show,
  hide,
})
</script>
