<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Pedido #{{ order.id }}</h1>
          <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
            Atualize as informações do pedido
          </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
          <Link
            :href="route('admin.orders.show', order.id)"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Ver Detalhes
          </Link>
          <Link
            :href="route('admin.orders.index')"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Voltar à Lista
          </Link>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main Form -->
          <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
              <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações do Pedido</h2>
              </div>
              <div class="p-6 space-y-6">
                <!-- Status -->
                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status do Pedido
                  </label>
                  <select
                    id="status"
                    v-model="form.status"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option value="pending">Pendente</option>
                    <option value="processing">Processando</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregue</option>
                    <option value="cancelled">Cancelado</option>
                  </select>
                  <div v-if="form.errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.status }}
                  </div>
                </div>

                <!-- Total Amount -->
                <div>
                  <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Valor Total (R$)
                  </label>
                  <input
                    type="number"
                    id="total_amount"
                    v-model="form.total_amount"
                    step="0.01"
                    min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="0.00"
                  />
                  <div v-if="form.errors.total_amount" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.total_amount }}
                  </div>
                </div>

                <!-- Payment Method -->
                <div>
                  <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Método de Pagamento
                  </label>
                  <select
                    id="payment_method"
                    v-model="form.payment_method"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option value="credit_card">Cartão de Crédito</option>
                    <option value="debit_card">Cartão de Débito</option>
                    <option value="pix">PIX</option>
                    <option value="boleto">Boleto Bancário</option>
                  </select>
                  <div v-if="form.errors.payment_method" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.payment_method }}
                  </div>
                </div>

                <!-- Notes -->
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Observações
                  </label>
                  <textarea
                    id="notes"
                    v-model="form.notes"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Observações sobre o pedido..."
                  ></textarea>
                  <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.notes }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="space-y-6">
              <!-- Order Info -->
              <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo do Pedido</h3>
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
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ order.customer?.name || 'N/A' }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Itens</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ order.items?.length || 0 }} item(s)</dd>
                  </div>
                </dl>
              </div>

              <!-- Order Items -->
              <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Itens do Pedido</h3>
                <div class="space-y-3">
                  <div v-for="item in order.items" :key="item.id" class="flex items-center space-x-3">
                    <img 
                      :src="item.product.image || `https://picsum.photos/40/40?random=${item.product.id}`" 
                      :alt="item.product.name"
                      class="h-10 w-10 object-cover rounded"
                      @error="handleImageError"
                    />
                    <div class="flex-1 min-w-0">
                      <p class="text-sm text-gray-900 dark:text-white truncate">{{ item.product.name }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">Qtd: {{ item.quantity }}</p>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-medium text-gray-900 dark:text-white">
                        R$ {{ formatPrice(item.price * item.quantity) }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
          <Link
            :href="route('admin.orders.index')"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
          >
            <span v-if="form.processing">Salvando...</span>
            <span v-else>Salvar Alterações</span>
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  order: Object,
})

const form = useForm({
  status: props.order.status,
  total_amount: props.order.total_amount,
  payment_method: props.order.payment_method,
  notes: props.order.notes || '',
})

const submit = () => {
  form.put(route('admin.orders.update', props.order.id))
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const month = (date.getMonth() + 1).toString().padStart(2, '0')
  const year = date.getFullYear()
  const hours = date.getHours().toString().padStart(2, '0')
  const minutes = date.getMinutes().toString().padStart(2, '0')
  
  return `${day}-${month}-${year} ${hours}:${minutes}`
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/40/40?random=${Math.random()}`
}
</script>
