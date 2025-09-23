<template>
  <AdminLayout>
    <div class="max-w-2xl mx-auto space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes da Reserva</h1>
        <div class="flex gap-2">
          <Link :href="route('admin.reservations.edit', item.id)" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">Editar</Link>
          <Link :href="route('admin.reservations.index')" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Voltar</Link>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-md p-6 space-y-4">
        <div>
          <div class="text-sm text-gray-500 dark:text-gray-400">Data</div>
          <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.date }}</div>
        </div>
        <div>
          <div class="text-sm text-gray-500 dark:text-gray-400">Horário</div>
          <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.start_time }} - {{ item.end_time }}</div>
        </div>
        <div>
          <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
          <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.status === 'available' ? 'Disponível' : 'Reservado' }}</div>
        </div>
        <div v-if="item.customer_name">
          <div class="text-sm text-gray-500 dark:text-gray-400">Cliente</div>
          <div class="text-base font-medium text-gray-900 dark:text-white">{{ item.customer_name }} ({{ item.customer_email }})</div>
        </div>
        <div v-if="item.notes">
          <div class="text-sm text-gray-500 dark:text-gray-400">Observações</div>
          <div class="text-base font-medium text-gray-900 dark:text-white whitespace-pre-line">{{ item.notes }}</div>
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


