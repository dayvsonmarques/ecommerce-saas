<template>
  <AdminLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
          <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              Detalhes do Produto
            </h2>
          </div>
          <div class="mt-4 flex md:mt-0 md:ml-4">
            <Link
              :href="route('admin.products.index')"
              class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Voltar
            </Link>
          </div>
        </div>

        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informações</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Visualize os detalhes do produto.</p>
          </div>
          <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Imagem</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                  <img
                    v-if="productImage(product)"
                    :src="productImage(product)"
                    :alt="product.name"
                    class="h-40 w-40 rounded-lg object-cover"
                    @error="onImgError($event, product)"
                  />
                  <span v-else class="text-gray-500">Sem imagem</span>
                </dd>
              </div>

              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ product.name }}</dd>
              </div>

              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Categoria</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ product.category?.name }}</dd>
              </div>

              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Preço</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">R$ {{ parseFloat(product.price).toFixed(2) }}</dd>
              </div>

              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Estoque</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ product.stock }}</dd>
              </div>

              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                  <span :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ product.is_active ? 'Ativo' : 'Inativo' }}
                  </span>
                </dd>
              </div>

              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 whitespace-pre-line">{{ product.description || '—' }}</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  product: Object
})

const placeholder = (product) => `https://picsum.photos/seed/product-${product.id || encodeURIComponent(product.name)}/320/320`
const productImage = (product) => product?.image || placeholder(product)
const onImgError = (e, product) => {
  if (!e?.target) return
  const current = e.target.getAttribute('src')
  const fallback = placeholder(product)
  if (current !== fallback) {
    e.target.setAttribute('src', fallback)
  }
}
</script>
