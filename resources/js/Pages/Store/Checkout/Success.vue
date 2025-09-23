<template>
  <StoreLayout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Success Message -->
      <div class="text-center mb-8">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 mb-4">
          <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Pedido Realizado com Sucesso!</h1>
        <p class="text-gray-600 dark:text-gray-400">Obrigado pela sua compra. Você receberá um email de confirmação em breve.</p>
      </div>

      <!-- Order Details -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalhes do Pedido</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Número do Pedido</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">#{{ order.id }}</p>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Data do Pedido</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatDate(order.created_at) }}</p>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
              {{ getStatusText(order.status) }}
            </span>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">R$ {{ formatPrice(order.total_amount) }}</p>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Itens do Pedido</h2>
        <div class="space-y-4">
          <div v-for="item in order.items" :key="item.id" class="flex items-center space-x-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <img 
              :src="item.product.image || `https://picsum.photos/80/80?random=${item.product.id}`" 
              :alt="item.product.name"
              class="h-16 w-16 object-cover rounded"
              @error="handleImageError"
            />
            <div class="flex-1">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ item.product.name }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">Quantidade: {{ item.quantity }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Preço unitário: R$ {{ formatPrice(item.price) }}</p>
            </div>
            <div class="text-right">
              <p class="text-lg font-semibold text-gray-900 dark:text-white">R$ {{ formatPrice(item.price * item.quantity) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Shipping Address -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço de Entrega</h2>
        <div class="text-gray-600 dark:text-gray-400">
          <p class="font-medium text-gray-900 dark:text-white">{{ order.shipping_address.name }}</p>
          <p>{{ order.shipping_address.street }}</p>
          <p>{{ order.shipping_address.city }}, {{ order.shipping_address.state }} {{ order.shipping_address.zip }}</p>
          <p>{{ order.shipping_address.country }}</p>
        </div>
      </div>

      <!-- Payment Information -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações de Pagamento</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Método de Pagamento</h3>
            <p class="text-gray-900 dark:text-white">{{ getPaymentMethodText(order.payment_method) }}</p>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Pago</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">R$ {{ formatPrice(order.total_amount) }}</p>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <Link 
          :href="route('store.products.index')" 
          class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 text-center transition-colors"
        >
          Continuar Comprando
        </Link>
        <Link 
          :href="route('store.customer.orders')" 
          class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 text-center transition-colors"
        >
          Ver Meus Pedidos
        </Link>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import StoreLayout from '../Layout.vue'

const props = defineProps({
  order: Object,
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
  event.target.src = `https://picsum.photos/80/80?random=${Math.random()}`
}
</script>
