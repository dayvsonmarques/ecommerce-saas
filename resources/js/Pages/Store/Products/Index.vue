<template>
  <StoreLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Produtos</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Encontre os melhores produtos para você</p>
      </div>

      <!-- Filters -->
      <div class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
            <input 
              v-model="filters.search" 
              type="text" 
              placeholder="Nome do produto..." 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
            <select 
              v-model="filters.category" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="">Todas as categorias</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordenar por</label>
            <select 
              v-model="filters.sort" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="">Mais recentes</option>
              <option value="name_asc">Nome A-Z</option>
              <option value="name_desc">Nome Z-A</option>
              <option value="price_asc">Menor preço</option>
              <option value="price_desc">Maior preço</option>
            </select>
          </div>

          <div>
            <PriceRangeSlider
              :min="priceRange?.min_price || 0"
              :max="priceRange?.max_price || 1000"
              :step="0.01"
              v-model="priceRangeValue"
              @update:modelValue="updatePriceRange"
            />
          </div>
          
          <div class="flex items-end space-x-2">
            <button 
              type="submit" 
              class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              Filtrar
            </button>
            <button 
              type="button"
              @click="clearFilters"
              class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
              Limpar
            </button>
          </div>
        </form>
        
        <!-- Price Range Info -->
        <div v-if="priceRange" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Faixa de preços disponível: 
            <span class="font-medium text-indigo-600 dark:text-indigo-400">
              R$ {{ formatPrice(priceRange.min_price) }} - R$ {{ formatPrice(priceRange.max_price) }}
            </span>
          </p>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-if="products.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="product in products.data" :key="product.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <Link :href="route('store.products.show', product.id)" class="block">
            <div class="aspect-w-16 aspect-h-12 bg-gray-200 dark:bg-gray-700">
              <img 
                :src="product.image || `https://picsum.photos/400/300?random=${product.id}`" 
                :alt="product.name"
                class="w-full h-48 object-cover"
                @error="handleImageError"
              />
            </div>
            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                {{ product.name }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">
                {{ product.description }}
              </p>
              <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                  R$ {{ formatPrice(product.price) }}
                </span>
                <span v-if="product.category" class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">
                  {{ product.category.name }}
                </span>
              </div>
            </div>
          </Link>
          <div class="px-4 pb-4">
            <button 
              @click="addToCart(product.id)"
              class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
            >
              Adicionar ao Carrinho
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum produto encontrado</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tente ajustar os filtros de busca.</p>
      </div>

      <!-- Pagination -->
      <div v-if="products.data.length > 0" class="mt-8">
        <nav class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link 
              v-if="products.prev_page_url" 
              :href="products.prev_page_url" 
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              Anterior
            </Link>
            <Link 
              v-if="products.next_page_url" 
              :href="products.next_page_url" 
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              Próximo
            </Link>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Mostrando {{ products.from }} até {{ products.to }} de {{ products.total }} resultados
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <Link 
                  v-if="products.prev_page_url" 
                  :href="products.prev_page_url" 
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <span class="sr-only">Anterior</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>
                <Link 
                  v-if="products.next_page_url" 
                  :href="products.next_page_url" 
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
import { Link, router } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'
import StoreLayout from '../Layout.vue'
import PriceRangeSlider from '../../../Components/PriceRangeSlider.vue'
import { route } from 'ziggy-js'

const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object,
  priceRange: Object,
})

const filters = reactive({
  search: props.filters?.search || '',
  category: props.filters?.category || '',
  sort: props.filters?.sort || '',
  min_price: props.filters?.min_price || '',
  max_price: props.filters?.max_price || '',
})

// Price range slider value - initialize with actual product price range
const priceRangeValue = ref({
  min: props.filters?.min_price || props.priceRange?.min_price || 0,
  max: props.filters?.max_price || props.priceRange?.max_price || 1000,
})

// Initialize slider with actual product price range if no filters are applied
if (!props.filters?.min_price && !props.filters?.max_price && props.priceRange) {
  priceRangeValue.value = {
    min: props.priceRange.min_price,
    max: props.priceRange.max_price,
  }
}

const applyFilters = () => {
  router.visit(route('store.products.index'), {
    data: filters,
    preserveState: true,
    replace: true,
  })
}

const updatePriceRange = (value) => {
  filters.min_price = value.min
  filters.max_price = value.max
}

const clearFilters = () => {
  filters.search = ''
  filters.category = ''
  filters.sort = ''
  filters.min_price = ''
  filters.max_price = ''
  
  // Reset price range slider to full product range
  priceRangeValue.value = {
    min: props.priceRange?.min_price || 0,
    max: props.priceRange?.max_price || 1000,
  }
  
  router.visit(route('store.products.index'), {
    data: {},
    preserveState: true,
    replace: true,
  })
}

const addToCart = async (productId) => {
  // Show loading spinner
  window.dispatchEvent(new CustomEvent('show-loading'))
  
  try {
    const response = await fetch(route('store.cart.add'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: 1,
      }),
    });

    const data = await response.json();

    if (data.success) {
      // Emit event to update cart count in header
      window.dispatchEvent(new CustomEvent('cart-updated', {
        detail: data.cart
      }));
      
      // Show success message (you can implement a toast notification here)
      alert(data.message);
    } else {
      alert(data.error || 'Erro ao adicionar produto ao carrinho');
    }
  } catch (error) {
    console.error('Error adding to cart:', error);
    alert('Erro ao adicionar produto ao carrinho');
  } finally {
    // Hide loading spinner
    window.dispatchEvent(new CustomEvent('hide-loading'))
  }
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/400/300?random=${Math.random()}`
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
