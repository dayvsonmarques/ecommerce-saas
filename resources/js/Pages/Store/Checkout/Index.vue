<template>
  <StoreLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Finalizar Compra</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Complete suas informações para finalizar o pedido</p>
      </div>

      <form @submit.prevent="submitOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Shipping Address -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço de Entrega</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome Completo</label>
                <input 
                  v-model="form.shipping_address.name" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</label>
                <input 
                  v-model="form.shipping_address.street" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cidade</label>
                <input 
                  v-model="form.shipping_address.city" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                <input 
                  v-model="form.shipping_address.state" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CEP</label>
                <input 
                  v-model="form.shipping_address.zip" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">País</label>
                <input 
                  v-model="form.shipping_address.country" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
            </div>
          </div>

          <!-- Billing Address -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Endereço de Cobrança</h2>
              <label class="flex items-center">
                <input 
                  v-model="sameAsShipping" 
                  type="checkbox" 
                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mesmo endereço de entrega</span>
              </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome Completo</label>
                <input 
                  v-model="form.billing_address.name" 
                  type="text" 
                  required
                  :disabled="sameAsShipping"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-600"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</label>
                <input 
                  v-model="form.billing_address.street" 
                  type="text" 
                  required
                  :disabled="sameAsShipping"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-600"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cidade</label>
                <input 
                  v-model="form.billing_address.city" 
                  type="text" 
                  required
                  :disabled="sameAsShipping"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-600"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                <input 
                  v-model="form.billing_address.state" 
                  type="text" 
                  required
                  :disabled="sameAsShipping"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-600"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CEP</label>
                <input 
                  v-model="form.billing_address.zip" 
                  type="text" 
                  required
                  :disabled="sameAsShipping"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-600"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">País</label>
                <input 
                  v-model="form.billing_address.country" 
                  type="text" 
                  required
                  :disabled="sameAsShipping"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-600"
                />
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Método de Pagamento</h2>
            <div class="space-y-3">
              <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                <input 
                  v-model="form.payment_method" 
                  type="radio" 
                  value="credit_card" 
                  class="text-indigo-600 focus:ring-indigo-500"
                />
                <span class="ml-3 text-gray-700 dark:text-gray-300">Cartão de Crédito</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                <input 
                  v-model="form.payment_method" 
                  type="radio" 
                  value="debit_card" 
                  class="text-indigo-600 focus:ring-indigo-500"
                />
                <span class="ml-3 text-gray-700 dark:text-gray-300">Cartão de Débito</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                <input 
                  v-model="form.payment_method" 
                  type="radio" 
                  value="pix" 
                  class="text-indigo-600 focus:ring-indigo-500"
                />
                <span class="ml-3 text-gray-700 dark:text-gray-300">PIX</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                <input 
                  v-model="form.payment_method" 
                  type="radio" 
                  value="boleto" 
                  class="text-indigo-600 focus:ring-indigo-500"
                />
                <span class="ml-3 text-gray-700 dark:text-gray-300">Boleto Bancário</span>
              </label>
            </div>
          </div>

          <!-- Notes -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações (Opcional)</h2>
            <textarea 
              v-model="form.notes" 
              rows="4" 
              placeholder="Alguma observação especial para o seu pedido?"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            ></textarea>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow sticky top-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo do Pedido</h2>
            </div>
            <div class="p-6">
              <!-- Cart Items -->
              <div class="space-y-4 mb-6">
                <div v-for="item in cart.items" :key="item.id" class="flex items-center space-x-3">
                  <img 
                    :src="item.product.image || `https://picsum.photos/50/50?random=${item.product.id}`" 
                    :alt="item.product.name"
                    class="h-12 w-12 object-cover rounded"
                    @error="handleImageError"
                  />
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ item.product.name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Qtd: {{ item.quantity }}</p>
                  </div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">R$ {{ formatPrice(item.price * item.quantity) }}</p>
                </div>
              </div>

              <!-- Totals -->
              <div class="space-y-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                  <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(cart.total_amount) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Frete</span>
                  <span class="text-gray-900 dark:text-white">R$ 0,00</span>
                </div>
                <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-700 pt-2">
                  <span class="text-gray-900 dark:text-white">Total</span>
                  <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(cart.total_amount) }}</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit" 
                :disabled="form.processing"
                class="w-full mt-6 bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
              >
                {{ form.processing ? 'Processando...' : 'Finalizar Pedido' }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import StoreLayout from '../Layout.vue'

const props = defineProps({
  cart: Object,
})

const sameAsShipping = ref(false)

const form = useForm({
  shipping_address: {
    name: '',
    street: '',
    city: '',
    state: '',
    zip: '',
    country: 'Brasil',
  },
  billing_address: {
    name: '',
    street: '',
    city: '',
    state: '',
    zip: '',
    country: 'Brasil',
  },
  payment_method: 'credit_card',
  notes: '',
})

// Watch for same as shipping checkbox
watch(sameAsShipping, (value) => {
  if (value) {
    form.billing_address = { ...form.shipping_address }
  }
})

const submitOrder = () => {
  form.post(route('store.checkout.store'), {
    onSuccess: () => {
      // Redirect to success page
    }
  })
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/50/50?random=${Math.random()}`
}
</script>
