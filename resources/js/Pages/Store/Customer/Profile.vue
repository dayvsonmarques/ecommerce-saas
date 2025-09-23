<template>
  <StoreLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Gerencie suas informações pessoais</p>
      </div>

      <form @submit.prevent="submitForm" class="space-y-8">
        <!-- Personal Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Pessoais</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome Completo</label>
              <input 
                v-model="form.name" 
                type="text" 
                required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <div v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ form.errors.name }}
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
              <input 
                v-model="form.email" 
                type="email" 
                required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <div v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ form.errors.email }}
              </div>
            </div>
          </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações da Conta</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Membro desde</label>
              <input 
                :value="formatDate(user.created_at)" 
                type="text" 
                disabled
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Última atualização</label>
              <input 
                :value="formatDate(user.updated_at)" 
                type="text" 
                disabled
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400"
              />
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
          <Link 
            :href="route('store.customer.dashboard')" 
            class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
          >
            Cancelar
          </Link>
          <button 
            type="submit" 
            :disabled="form.processing"
            class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
          >
            {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
          </button>
        </div>
      </form>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import StoreLayout from '../Layout.vue'

const props = defineProps({
  user: Object,
})

const form = useForm({
  name: props.user.name,
  email: props.user.email,
})

const submitForm = () => {
  form.put(route('store.customer.profile.update'), {
    onSuccess: () => {
      // Success message will be handled by the backend
    }
  })
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  })
}
</script>
