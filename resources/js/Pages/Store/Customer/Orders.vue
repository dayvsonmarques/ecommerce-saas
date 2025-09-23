<template>
  <StoreLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Meus Pedidos</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Acompanhe o status dos seus pedidos</p>
      </div>

      <!-- Orders List -->
      <div v-if="orders.data.length > 0" class="space-y-6">
        <div v-for="order in orders.data" :key="order.id" class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Pedido #{{ order.id }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(order.created_at) }}</p>
              </div>
              <div class="flex items-center space-x-4">
                <span :class="getStatusClass(order.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ getStatusText(order.status) }}
                </span>
                <Link 
                  :href="route('store.customer.order', order.id)" 
                  class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                >
                  Ver detalhes
                </Link>
              </div>
            </div>
          </div>
          <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Itens</h4>
                <div class="space-y-2">
                  <div v-for="item in order.items.slice(0, 3)" :key="item.id" class="flex items-center space-x-3">
                    <img 
                      :src="item.product.image || `https://picsum.photos/40/40?random=${item.product.id}`" 
                      :alt="item.product.name"
                      class="h-8 w-8 object-cover rounded"
                      @error="handleImageError"
                    />
                    <div class="flex-1 min-w-0">
                      <p class="text-sm text-gray-900 dark:text-white truncate">{{ item.product.name }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">Qtd: {{ item.quantity }}</p>
                    </div>
                  </div>
                  <p v-if="order.items.length > 3" class="text-xs text-gray-500 dark:text-gray-400">
                    +{{ order.items.length - 3 }} mais item(s)
                  </p>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Endereço de Entrega</h4>
                <div class="text-sm text-gray-900 dark:text-white">
                  <p>{{ order.shipping_address.name }}</p>
                  <p>{{ order.shipping_address.street }}</p>
                  <p>{{ order.shipping_address.city }}, {{ order.shipping_address.state }}</p>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total</h4>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">R$ {{ formatPrice(order.total_amount) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ getPaymentMethodText(order.payment_method) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum pedido encontrado</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Você ainda não fez nenhum pedido.</p>
        <div class="mt-6">
          <Link 
            :href="route('store.products.index')" 
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Começar a Comprar
          </Link>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="orders.data.length > 0" class="mt-8">
        <nav class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link 
              v-if="orders.prev_page_url" 
              :href="orders.prev_page_url" 
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              Anterior
            </Link>
            <Link 
              v-if="orders.next_page_url" 
              :href="orders.next_page_url" 
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              Próximo
            </Link>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Mostrando {{ orders.from }} até {{ orders.to }} de {{ orders.total }} resultados
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <Link 
                  v-if="orders.prev_page_url" 
                  :href="orders.prev_page_url" 
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <span class="sr-only">Anterior</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>
                <Link 
                  v-if="orders.next_page_url" 
                  :href="orders.next_page_url" 
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <span class="sr-only">Próximo</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>
              </nav>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import StoreLayout from '../Layout.vue'

const props = defineProps({
  orders: Object,
})

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const getStatusText = (status) => {
  const statusMap = {
    pending: 'Pendente',
    processing: 'Processando',
    shipped: 'Enviado',
    delivered: 'Entregue',
    cancelled: 'Cancelado',
  }
  return statusMap[status] || status
}

const getStatusClass = (status) => {
  const classMap = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    processing: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    shipped: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    delivered: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
  }
  return classMap[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
}

const getPaymentMethodText = (method) => {
  const methodMap = {
    credit_card: 'Cartão de Crédito',
    debit_card: 'Cartão de Débito',
    pix: 'PIX',
    boleto: 'Boleto Bancário',
  }
  return methodMap[method] || method
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/40/40?random=${Math.random()}`
}
</script>
