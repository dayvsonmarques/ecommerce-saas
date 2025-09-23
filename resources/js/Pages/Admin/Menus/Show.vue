<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes do Cardápio</h1>
        <div class="flex gap-2">
          <Link :href="route('admin.menus.edit', item.id)" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">Editar</Link>
          <Link :href="route('admin.menus.index')" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Voltar</Link>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-md p-6 space-y-4">
        <div>
          <div class="text-sm text-gray-500 dark:text-gray-400">Nome</div>
          <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.name }}</div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Início</div>
            <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.start_date }}</div>
          </div>
          <div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Fim</div>
            <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.end_date || 'sem fim' }}</div>
          </div>
        </div>
        <div>
          <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Produtos</div>
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="p in item.products" :key="p.id" class="text-sm text-gray-900 dark:text-gray-100">{{ p.name }}</li>
          </ul>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  item: Object,
})
</script>


