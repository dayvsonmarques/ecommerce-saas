<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-2xl font-bold text-gray-900">Clientes</h1>
          <p class="mt-2 text-sm text-gray-700">
            Gerencie todos os clientes cadastrados
          </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <Link
            :href="route('admin.customers.create')"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
          >
            Adicionar Cliente
          </Link>
        </div>
      </div>

      <!-- Customers Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="customer in customers.data" :key="customer.id">
            <div class="px-4 py-4 flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-600">{{ customer.name.charAt(0) }}</span>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
                  <div class="text-sm text-gray-500">{{ customer.email }}</div>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                  {{ customer.orders_count }} pedidos
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="customer.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ customer.is_active ? 'Ativo' : 'Inativo' }}
                </span>
                <div class="flex space-x-2">
                  <Link
                    :href="route('admin.customers.show', customer.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    Ver
                  </Link>
                  <Link
                    :href="route('admin.customers.edit', customer.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    Editar
                  </Link>
                  <button
                    @click="deleteCustomer(customer.id)"
                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                  >
                    Excluir
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Pagination -->
      <div v-if="customers.links" class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <Link
            v-if="customers.prev_page_url"
            :href="customers.prev_page_url"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Anterior
          </Link>
          <Link
            v-if="customers.next_page_url"
            :href="customers.next_page_url"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Próximo
          </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Mostrando
              <span class="font-medium">{{ customers.from }}</span>
              até
              <span class="font-medium">{{ customers.to }}</span>
              de
              <span class="font-medium">{{ customers.total }}</span>
              resultados
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <Link
                v-for="link in customers.links"
                :key="link.label"
                :href="link.url"
                v-html="link.label"
                :class="[
                  link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]"
              />
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

defineProps({
  customers: Object
})

const deleteCustomer = (id) => {
  if (confirm('Tem certeza que deseja excluir este cliente?')) {
    router.delete(route('admin.customers.destroy', id))
  }
}
</script>
