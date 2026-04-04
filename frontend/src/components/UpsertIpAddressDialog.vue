<script setup lang="ts">
import formRules from '@/helpers/formRules'
import { computed, reactive, ref, watch } from 'vue'
import type { IpAddressForm } from '@/types/ip-address'

const props = defineProps<{
  modelValue: boolean
  loading: boolean
  errors: Record<string, string[]>
  ipAddress: IpAddressForm | null
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'save', data: IpAddressForm): void
  (e: 'clear-error', field: string): void
}>()

const rules = reactive(formRules)
const formRef = ref()
const form = ref<IpAddressForm>({
  ip_address: '',
  label: '',
  comment: '',
  ...props.ipAddress,
})

const modelValue = computed({
  get: () => props.modelValue,
  set: (value: boolean) => emit('update:modelValue', value),
})

const close = () => emit('update:modelValue', false)

const onSubmit = async () => {
  const { valid } = await formRef.value!.validate()
  if (!valid) return
  emit('save', form.value)
}

watch(
  () => props.ipAddress,
  (ipAddress) => {
    if (ipAddress) {
      form.value = {
        ip_address: ipAddress.ip_address,
        label: ipAddress.label,
        comment: ipAddress.comment,
      }
    }
  },
  { immediate: true },
)
</script>

<template>
  <v-dialog v-model="modelValue" max-width="500">
    <v-card :disabled="loading" :loading="loading">
      <v-card-title class="d-flex align-center mx-2">
        <div class="me-auto">
          {{ ipAddress?.id ? 'Edit' : 'Create' }}
        </div>
        <v-btn icon variant="flat" size="small" @click="close">
          <v-icon> mdi-close </v-icon>
        </v-btn>
      </v-card-title>

      <v-divider></v-divider>

      <v-form ref="formRef" @submit.prevent="onSubmit">
        <v-card-text>
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="form.ip_address"
                label="IP Address"
                color="primary"
                variant="outlined"
                density="compact"
                hide-details="auto"
                :rules="[rules.required, rules.maximum(255)]"
                :error-messages="props.errors.ip_address"
                @update:model-value="emit('clear-error', 'ip_address')"
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="form.label"
                label="Label"
                color="primary"
                variant="outlined"
                density="compact"
                hide-details="auto"
                :rules="[rules.required, rules.maximum(255)]"
                :error-messages="props.errors.label"
                @update:model-value="emit('clear-error', 'label')"
              />
            </v-col>
            <v-col cols="12">
              <v-textarea
                v-model="form.comment"
                label="Comment"
                color="primary"
                variant="outlined"
                density="compact"
                hide-details="auto"
                rows="3"
                :rules="[rules.maximum(900)]"
                :error-messages="props.errors.comment"
                @update:model-value="emit('clear-error', 'comment')"
              />
            </v-col>
          </v-row>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <div class="ms-auto px-4 py-3">
            <v-btn variant="tonal" text="Cancel" @click="close" class="mr-2"></v-btn>
            <v-btn
              :color="ipAddress?.id ? 'primary' : 'success'"
              variant="flat"
              :text="ipAddress?.id ? 'Update' : 'Create'"
              type="submit"
              :loading="loading"
            ></v-btn>
          </div>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>
