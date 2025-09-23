<template>
  <StoreLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pedido #{{ order.id }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ formatDate(order.created_at) }}</p>
          </div>
          <span :class="getStatusClass(order.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
            {{ getStatusText(order.status) }}
          </span>
        </div>
      </div>

      <!-- Order Details -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Itens do Pedido</h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
              <div v-for="item in order.items" :key="item.id" class="p-6">
                <div class="flex items-center space-x-4">
                  <img 
                    :src="item.product.image || `https://picsum.photos/80/80?random=${item.product.id}`" 
                    :alt="item.product.name"
                    class="h-16 w-16 object-cover rounded"
                    @error="handleImageError"
                  />
                  <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                      <Link :href="route('store.products.show', item.product.id)" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                        {{ item.product.name }}
                      </Link>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                      {{ item.product.description }}
                    </p>
                    <div class="mt-2 flex items-center space-x-4">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Quantidade: {{ item.quantity }}</span>
                      <span class="text-sm text-gray-500 dark:text-gray-400">Preço unitário: R$ {{ formatPrice(item.price) }}</span>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                      R$ {{ formatPrice(item.price * item.quantity) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="space-y-6">
            <!-- Order Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Pedido</h3>
              <dl class="space-y-3">
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número do Pedido</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">#{{ order.id }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data do Pedido</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">{{ formatDate(order.created_at) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                  <dd>
                    <span :class="getStatusClass(order.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ getStatusText(order.status) }}
                    </span>
                  </dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Método de Pagamento</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">{{ getPaymentMethodText(order.payment_method) }}</dd>
                </div>
              </dl>
            </div>

            <!-- Totals -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo do Pedido</h3>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                  <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(order.total_amount) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Frete</span>
                  <span class="text-gray-900 dark:text-white">R$ 0,00</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                  <div class="flex justify-between text-lg font-semibold">
                    <span class="text-gray-900 dark:text-white">Total</span>
                    <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(order.total_amount) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço de Entrega</h3>
              <div class="text-sm text-gray-600 dark:text-gray-400">
                <p class="font-medium text-gray-900 dark:text-white">{{ order.shipping_address.name }}</p>
                <p>{{ order.shipping_address.street }}</p>
                <p>{{ order.shipping_address.city }}, {{ order.shipping_address.state }} {{ order.shipping_address.zip }}</p>
                <p>{{ order.shipping_address.country }}</p>
              </div>
            </div>

            <!-- Billing Address -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço de Cobrança</h3>
              <div class="text-sm text-gray-600 dark:text-gray-400">
                <p class="font-medium text-gray-900 dark:text-white">{{ order.billing_address.name }}</p>
                <p>{{ order.billing_address.street }}</p>
                <p>{{ order.billing_address.city }}, {{ order.billing_address.state }} {{ order.billing_address.zip }}</p>
                <p>{{ order.billing_address.country }}</p>
              </div>
            </div>

            <!-- Notes -->
            <div v-if="order.notes" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.notes }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-8 flex justify-center">
        <Link 
          :href="route('store.customer.orders')" 
          class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
        >
          Voltar para Meus Pedidos
        </Link>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
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
  event.target.src = `https://picsum.photos/80/80?random=${Math.random()}`
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
