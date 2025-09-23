<template>
  <AdminLayout>
    <div class="max-w-2xl mx-auto space-y-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Grupo</h1>
      <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 shadow sm:rounded-md p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
          <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
          <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
          <input v-model="form.description" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
          <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
        </div>
        <div class="flex items-center gap-2">
          <input id="active" type="checkbox" v-model="form.is_active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
          <label for="active" class="text-sm text-gray-700 dark:text-gray-300">Ativo</label>
        </div>
        <div class="flex justify-end gap-2">
          <Link :href="route('admin.groups.index')" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Cancelar</Link>
          <button type="submit" :disabled="form.processing" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Salvar</button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const form = useForm({
  name: '',
  description: '',
  is_active: true,
})

const submit = () => {
  form.post(route('admin.groups.store'))
}
</script>


