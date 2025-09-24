<template>
  <AdminLayout>
    <div class="p-6">
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Configurações - Mercado Pago</h1>

      <form @submit.prevent="submit" class="max-w-2xl space-y-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Public Key</label>
          <input v-model="form.public_key" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          <p v-if="form.errors.public_key" class="mt-1 text-sm text-red-600">{{ form.errors.public_key }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Access Token</label>
          <input v-model="form.access_token" type="password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          <p v-if="form.errors.access_token" class="mt-1 text-sm text-red-600">{{ form.errors.access_token }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Integrator ID (opcional)</label>
          <input v-model="form.integrator_id" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div class="flex items-center space-x-2">
          <input id="sandbox" v-model="form.sandbox" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
          <label for="sandbox" class="text-sm text-gray-700 dark:text-gray-300">Usar ambiente Sandbox</label>
        </div>

        <div class="pt-2">
          <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400">Salvar</button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  config: Object,
})

const form = useForm({
  public_key: props.config?.public_key || '',
  access_token: props.config?.access_token || '',
  integrator_id: props.config?.integrator_id || '',
  sandbox: !!props.config?.sandbox,
})

const submit = () => {
  form.post(route('admin.settings.mercadopago.update'))
}
</script>


