<template>
  <StoreLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Finalizar Compra</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Complete suas informações para finalizar o pedido</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mesmo endereço de entrega</span>
              </label>
            </div>
            
            <div v-if="!sameAsShipping" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome Completo</label>
                <input 
                  v-model="form.billing_address.name" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</label>
                <input 
                  v-model="form.billing_address.street" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cidade</label>
                <input 
                  v-model="form.billing_address.city" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                <input 
                  v-model="form.billing_address.state" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CEP</label>
                <input 
                  v-model="form.billing_address.zip" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">País</label>
                <input 
                  v-model="form.billing_address.country" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>
            </div>
          </div>

          <!-- Shipping Calculator -->
          <ShippingCalculator 
            :cart-items="cartItems" 
            @frete-selecionado="onFreteSelecionado"
          />

          <!-- Payment -->
          <MercadoPagoCheckout 
            v-if="orderCreated"
            :order-id="orderCreated.id"
            :items="cartItems"
            @pagamento-processado="onPagamentoProcessado"
          />
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo do Pedido</h2>
            
            <!-- Cart Items -->
            <div class="space-y-3 mb-6">
              <div v-for="item in cartItems" :key="item.id" class="flex items-center space-x-3">
                <img 
                  :src="item.product.image || `https://picsum.photos/60/60?random=${item.product.id}`" 
                  :alt="item.product.name"
                  class="h-12 w-12 object-cover rounded"
                />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ item.product.name }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    Qtd: {{ item.quantity }}
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    R$ {{ formatPrice(item.price * item.quantity) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(subtotal) }}</span>
              </div>
              
              <div v-if="freteSelecionado" class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Frete ({{ freteSelecionado.nome }})</span>
                <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(freteSelecionado.valor) }}</span>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                <div class="flex justify-between text-lg font-semibold">
                  <span class="text-gray-900 dark:text-white">Total</span>
                  <span class="text-gray-900 dark:text-white">
                    R$ {{ formatPrice(totalComFrete) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Create Order Button -->
            <button
              v-if="!orderCreated && canCreateOrder"
              @click="createOrder"
              :disabled="creatingOrder"
              class="w-full mt-6 bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
            >
              <span v-if="creatingOrder">Criando Pedido...</span>
              <span v-else>Criar Pedido</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import StoreLayout from '../Layout.vue'
import ShippingCalculator from '../../../Components/ShippingCalculator.vue'
import MercadoPagoCheckout from '../../../Components/MercadoPagoCheckout.vue'

const props = defineProps({
  cartItems: Array
})

const sameAsShipping = ref(true)
const freteSelecionado = ref(null)
const orderCreated = ref(null)
const creatingOrder = ref(false)

const form = useForm({
  shipping_address: {
    name: '',
    street: '',
    city: '',
    state: '',
    zip: '',
    country: 'Brasil'
  },
  billing_address: {
    name: '',
    street: '',
    city: '',
    state: '',
    zip: '',
    country: 'Brasil'
  },
  notes: ''
})

const subtotal = computed(() => {
  return props.cartItems.reduce((total, item) => {
    return total + (item.price * item.quantity)
  }, 0)
})

const totalComFrete = computed(() => {
  const frete = freteSelecionado.value ? freteSelecionado.value.valor : 0
  return subtotal.value + frete
})

const canCreateOrder = computed(() => {
  return form.shipping_address.name && 
         form.shipping_address.street && 
         form.shipping_address.city && 
         form.shipping_address.state && 
         form.shipping_address.zip &&
         freteSelecionado.value
})

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const onFreteSelecionado = (frete) => {
  freteSelecionado.value = frete
}

const onPagamentoProcessado = (resultado) => {
  console.log('Pagamento processado:', resultado)
}

const createOrder = async () => {
  if (!canCreateOrder.value) return

  creatingOrder.value = true

  try {
    const response = await fetch(route('store.checkout.store'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        shipping_address: form.shipping_address,
        billing_address: sameAsShipping.value ? form.shipping_address : form.billing_address,
        payment_method: 'mercadopago',
        notes: form.notes,
        shipping_cost: freteSelecionado.value.valor,
        shipping_method: freteSelecionado.value.nome
      })
    })

    const data = await response.json()

    if (data.success) {
      orderCreated.value = data.order
    } else {
      alert('Erro ao criar pedido: ' + (data.error || 'Erro desconhecido'))
    }
  } catch (error) {
    alert('Erro de conexão. Tente novamente.')
  } finally {
    creatingOrder.value = false
  }
}

// Watch para sincronizar endereços
watch(sameAsShipping, (newValue) => {
  if (newValue) {
    form.billing_address = { ...form.shipping_address }
  }
})
</script>