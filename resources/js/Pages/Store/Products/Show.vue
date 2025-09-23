<template>
  <StoreLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Breadcrumb -->
      <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
          <li><Link :href="route('store.products.index')" class="hover:text-indigo-600 dark:hover:text-indigo-400">Produtos</Link></li>
          <li v-if="product.category">
            <span class="mx-2">/</span>
            <Link :href="route('store.products.index', { category: product.category.id })" class="hover:text-indigo-600 dark:hover:text-indigo-400">
              {{ product.category.name }}
            </Link>
          </li>
          <li><span class="mx-2">/</span><span class="text-gray-900 dark:text-white">{{ product.name }}</span></li>
        </ol>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="space-y-4">
          <div class="aspect-w-16 aspect-h-12 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
            <img 
              :src="product.image || `https://picsum.photos/600/600?random=${product.id}`" 
              :alt="product.name"
              class="w-full h-96 object-cover"
              @error="handleImageError"
            />
          </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ product.name }}</h1>
            <div class="flex items-center space-x-4">
              <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                R$ {{ formatPrice(product.price) }}
              </span>
              <span v-if="product.category" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-full text-sm">
                {{ product.category.name }}
              </span>
            </div>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Descrição</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ product.description }}</p>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Informações do Produto</h3>
            <dl class="grid grid-cols-1 gap-4">
              <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Preço</dt>
                <dd class="text-lg font-semibold text-gray-900 dark:text-white">R$ {{ formatPrice(product.price) }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estoque</dt>
                <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ product.stock }} unidades</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="text-lg font-semibold text-green-600 dark:text-green-400">
                  {{ product.is_active ? 'Disponível' : 'Indisponível' }}
                </dd>
              </div>
            </dl>
          </div>

          <!-- Add to Cart -->
          <div class="space-y-4">
            <div class="flex items-center space-x-4">
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade:</label>
              <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md">
                <button 
                  @click="decreaseQuantity" 
                  class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                  :disabled="quantity <= 1"
                >
                  -
                </button>
                <input 
                  v-model.number="quantity" 
                  type="number" 
                  min="1" 
                  :max="product.stock"
                  class="w-16 px-2 py-2 text-center border-0 bg-transparent text-gray-900 dark:text-white focus:ring-0"
                />
                <button 
                  @click="increaseQuantity" 
                  class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                  :disabled="quantity >= product.stock"
                >
                  +
                </button>
              </div>
            </div>

            <button 
              @click="addToCart"
              :disabled="!product.is_active || product.stock <= 0"
              class="w-full bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
            >
              {{ product.is_active && product.stock > 0 ? 'Adicionar ao Carrinho' : 'Produto Indisponível' }}
            </button>

            <div class="flex space-x-4">
              <Link 
                :href="route('store.cart.index')" 
                class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 text-center transition-colors"
              >
                Ver Carrinho
              </Link>
              <Link 
                :href="route('store.checkout.index')" 
                class="flex-1 bg-green-600 text-white py-3 px-6 rounded-md hover:bg-green-700 text-center transition-colors"
              >
                Comprar Agora
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <div v-if="relatedProducts.length > 0" class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Produtos Relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="relatedProduct in relatedProducts" :key="relatedProduct.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <Link :href="route('store.products.show', relatedProduct.id)" class="block">
              <div class="aspect-w-16 aspect-h-12 bg-gray-200 dark:bg-gray-700">
                <img 
                  :src="relatedProduct.image || `https://picsum.photos/400/300?random=${relatedProduct.id}`" 
                  :alt="relatedProduct.name"
                  class="w-full h-48 object-cover"
                  @error="handleImageError"
                />
              </div>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                  {{ relatedProduct.name }}
                </h3>
                <div class="flex items-center justify-between">
                  <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                    R$ {{ formatPrice(relatedProduct.price) }}
                  </span>
                </div>
              </div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import StoreLayout from '../Layout.vue'

const props = defineProps({
  product: Object,
  relatedProducts: Array,
})

const quantity = ref(1)

const increaseQuantity = () => {
  if (quantity.value < props.product.stock) {
    quantity.value++
  }
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const addToCart = () => {
  router.post(route('store.cart.add'), {
    product_id: props.product.id,
    quantity: quantity.value,
  }, {
    preserveState: true,
    onSuccess: () => {
      // Show success message
    }
  })
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/600/600?random=${Math.random()}`
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
