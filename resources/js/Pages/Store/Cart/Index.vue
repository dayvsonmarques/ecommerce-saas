<template>
  <StoreLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Carrinho de Compras</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Revise seus itens antes de finalizar a compra</p>
      </div>

      <div v-if="cart && cart.items && cart.items.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Itens no Carrinho</h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
              <div v-for="item in cart.items" :key="item.id" class="p-6">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <img 
                      :src="item.product.image || `https://picsum.photos/150/150?random=${item.product.id}`" 
                      :alt="item.product.name"
                      class="h-20 w-20 object-cover rounded-md"
                      @error="handleImageError"
                    />
                  </div>
                  <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                      <Link :href="route('store.products.show', item.product.id)" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                        {{ item.product.name }}
                      </Link>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                      {{ item.product.description }}
                    </p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                      R$ {{ formatPrice(item.price) }} cada
                    </p>
                  </div>
                  <div class="flex items-center space-x-4">
                    <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md">
                      <button 
                        @click="updateQuantity(item.id, item.quantity - 1)" 
                        class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        :disabled="item.quantity <= 1"
                      >
                        -
                      </button>
                      <span class="px-3 py-2 text-gray-900 dark:text-white">{{ item.quantity }}</span>
                      <button 
                        @click="updateQuantity(item.id, item.quantity + 1)" 
                        class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        :disabled="item.quantity >= item.product.stock"
                      >
                        +
                      </button>
                    </div>
                    <div class="text-right">
                      <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        R$ {{ formatPrice(item.price * item.quantity) }}
                      </p>
                    </div>
                    <button 
                      @click="removeItem(item.id)"
                      class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                    >
                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-6 flex justify-between">
            <Link 
              :href="route('store.products.index')" 
              class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
            >
              Continuar Comprando
            </Link>
            <button 
              @click="clearCart"
              class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 transition-colors"
            >
              Limpar Carrinho
            </button>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow sticky top-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo do Pedido</h2>
            </div>
            <div class="p-6 space-y-4">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(cart.total_amount) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Frete</span>
                <span class="text-gray-900 dark:text-white">R$ 0,00</span>
              </div>
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex justify-between text-lg font-semibold">
                  <span class="text-gray-900 dark:text-white">Total</span>
                  <span class="text-gray-900 dark:text-white">R$ {{ formatPrice(cart.total_amount) }}</span>
                </div>
              </div>
              <Link 
                :href="route('store.checkout.index')" 
                class="w-full bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 text-center block transition-colors"
              >
                Finalizar Compra
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty Cart -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Seu carrinho está vazio</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Adicione alguns produtos para começar a comprar.</p>
        <div class="mt-6">
          <Link 
            :href="route('store.products.index')" 
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Continuar Comprando
          </Link>
        </div>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import StoreLayout from '../Layout.vue'

const props = defineProps({
  cart: Object,
})

const updateQuantity = (itemId, newQuantity) => {
  if (newQuantity < 1) return
  
  router.put(route('store.cart.update', itemId), {
    quantity: newQuantity,
  }, {
    preserveState: true,
  })
}

const removeItem = (itemId) => {
  if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
    router.delete(route('store.cart.remove', itemId), {
      preserveState: true,
    })
  }
}

const clearCart = () => {
  if (confirm('Tem certeza que deseja limpar o carrinho?')) {
    router.delete(route('store.cart.clear'), {
      preserveState: true,
    })
  }
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/150/150?random=${Math.random()}`
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
