<template>
  <AdminLayout>
    <div class="max-w-2xl mx-auto">
      <div class="mb-6">
        <Link
          :href="route('admin.categories.index')"
          class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
        >
          ← Voltar para Categorias
        </Link>
      </div>

      <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
            Criar Nova Categoria
          </h3>

          <form @submit.prevent="submit">
            <div class="space-y-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                  Nome da Categoria
                </label>
                <input
                  type="text"
                  name="name"
                  id="name"
                  v-model="form.name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.name }"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                  {{ form.errors.name }}
                </p>
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                  Descrição
                </label>
                <textarea
                  name="description"
                  id="description"
                  rows="3"
                  v-model="form.description"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.description }"
                ></textarea>
                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">
                  {{ form.errors.description }}
                </p>
              </div>

              <div class="flex items-center">
                <input
                  type="checkbox"
                  name="is_active"
                  id="is_active"
                  v-model="form.is_active"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                  Categoria ativa
                </label>
              </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
              <Link
                :href="route('admin.categories.index')"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
              >
                {{ form.processing ? 'Salvando...' : 'Salvar Categoria' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const form = useForm({
  name: '',
  description: '',
  is_active: true
})

const submit = () => {
  form.post(route('admin.categories.store'))
}
</script>
