<template>
  <AdminLayout>
    <div class="max-w-2xl mx-auto space-y-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Slot/Reserva</h1>
      <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 shadow sm:rounded-md p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data</label>
            <input v-model="form.date" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
            <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">{{ form.errors.date }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Início</label>
            <input v-model="form.start_time" type="time" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
            <p v-if="form.errors.start_time" class="mt-1 text-sm text-red-600">{{ form.errors.start_time }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fim</label>
            <input v-model="form.end_time" type="time" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
            <p v-if="form.errors.end_time" class="mt-1 text-sm text-red-600">{{ form.errors.end_time }}</p>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
              <option value="available">Disponível</option>
              <option value="booked">Reservado</option>
            </select>
            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
          </div>
          <div class="flex items-center gap-2"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" v-if="form.status === 'booked'">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
            <input v-model="form.customer_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
            <p v-if="form.errors.customer_name" class="mt-1 text-sm text-red-600">{{ form.errors.customer_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input v-model="form.customer_email" type="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
            <p v-if="form.errors.customer_email" class="mt-1 text-sm text-red-600">{{ form.errors.customer_email }}</p>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
          <textarea v-model="form.notes" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" rows="3"></textarea>
          <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</p>
        </div>
        <div class="flex justify-end gap-2">
          <Link :href="route('admin.reservations.index')" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Cancelar</Link>
          <button type="submit" :disabled="form.processing" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Salvar</button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  item: Object,
})

const form = useForm({
  date: props.item.date,
  start_time: props.item.start_time,
  end_time: props.item.end_time,
  status: props.item.status,
  customer_name: props.item.customer_name,
  customer_email: props.item.customer_email,
  notes: props.item.notes,
})

const submit = () => {
  form.put(route('admin.reservations.update', props.item.id))
}
</script>


