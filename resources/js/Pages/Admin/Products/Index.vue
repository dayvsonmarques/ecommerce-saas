<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-2xl font-bold text-gray-900">Produtos</h1>
          <p class="mt-2 text-sm text-gray-700">
            Gerencie todos os produtos do seu catálogo
          </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <Link
            :href="route('admin.products.create')"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
          >
            Adicionar Produto
          </Link>
        </div>
      </div>

      <!-- Filters -->
      <form @submit.prevent="submitFilters" class="bg-white shadow sm:rounded-md p-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="sm:col-span-2">
          <label for="f-name" class="block text-sm font-medium text-gray-700">Nome</label>
          <input id="f-name" v-model="form.name" type="text" placeholder="Buscar por nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        </div>
        <div>
          <label for="f-category" class="block text-sm font-medium text-gray-700">Categoria</label>
          <select id="f-category" v-model="form.category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="">Todas</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>
        <div>
          <label for="f-status" class="block text-sm font-medium text-gray-700">Status</label>
          <select id="f-status" v-model="form.is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="">Todos</option>
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
          </select>
        </div>
        <div class="sm:col-span-4 flex items-center gap-3">
          <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Filtrar</button>
          <button type="button" @click="resetFilters" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Limpar</button>
        </div>
      </form>

      <!-- Products Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="product in products.data" :key="product.id">
            <div class="px-4 py-4 flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                  <img
                    v-if="productImage(product)"
                    :src="productImage(product)"
                    :alt="product.name"
                    class="h-16 w-16 rounded-lg object-cover"
                    @error="onImgError($event, product)"
                  />
                  <div v-else class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-600">{{ product.name.charAt(0) }}</span>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                  <div class="text-sm text-gray-500">{{ product.category?.name }}</div>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-900">
                  R$ {{ parseFloat(product.price).toFixed(2) }}
                </div>
                <div class="text-sm text-gray-500">
                  Estoque: {{ product.stock }}
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ product.is_active ? 'Ativo' : 'Inativo' }}
                </span>
                <div class="flex items-center gap-2">
                  <button
                    :title="'Ver'"
                    @click="router.visit(route('admin.products.show', product.id))"
                    class="inline-flex items-center justify-center h-8 w-8 rounded hover:bg-gray-100 text-indigo-600"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z"/></svg>
                  </button>
                  <button
                    :title="'Editar'"
                    @click="editProduct(product.id)"
                    class="inline-flex items-center justify-center h-8 w-8 rounded hover:bg-gray-100 text-indigo-600"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Zm15.71-9.04a1 1 0 0 0 0-1.41l-1.51-1.51a1 1 0 0 0-1.41 0l-1.13 1.13 3.75 3.75 1.3-1.46Z"/></svg>
                  </button>
                  <button
                    :title="'Excluir'"
                    @click="deleteProduct(product.id)"
                    class="inline-flex items-center justify-center h-8 w-8 rounded hover:bg-gray-100 text-red-600"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M6 7h12v2H6V7Zm2 3h8l-1 10H9L8 10Zm3-7h2v2h-2V3Z"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Pagination -->
      <div v-if="products.links" class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <Link
            v-if="products.prev_page_url"
            :href="products.prev_page_url"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Anterior
          </Link>
          <Link
            v-if="products.next_page_url"
            :href="products.next_page_url"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Próximo
          </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Mostrando
              <span class="font-medium">{{ products.from }}</span>
              até
              <span class="font-medium">{{ products.to }}</span>
              de
              <span class="font-medium">{{ products.total }}</span>
              resultados
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <Link
                v-for="link in products.links"
                :key="link.label"
                :href="link.url"
                v-html="link.label"
                :class="[
                  link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]"
              />
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object,
})

const form = reactive({
  name: props.filters?.name || '',
  category_id: props.filters?.category_id || '',
  is_active: props.filters?.is_active ?? '',
})

const submitFilters = () => {
  router.get(route('admin.products.index'), {
    name: form.name || undefined,
    category_id: form.category_id || undefined,
    is_active: form.is_active !== '' ? form.is_active : undefined,
  }, { preserveState: true, replace: true })
}

const resetFilters = () => {
  form.name = ''
  form.category_id = ''
  form.is_active = ''
  submitFilters()
}

const placeholder = (product) => `https://picsum.photos/seed/product-${product.id || encodeURIComponent(product.name)}/160/160`
const productImage = (product) => product?.image || placeholder(product)
const onImgError = (e, product) => {
  if (!e?.target) return
  const current = e.target.getAttribute('src')
  const fallback = placeholder(product)
  if (current !== fallback) {
    e.target.setAttribute('src', fallback)
  }
}

const editProduct = (id) => {
  router.visit(route('admin.products.edit', id))
}

const deleteProduct = (id) => {
  if (confirm('Tem certeza que deseja excluir este produto?')) {
    router.delete(route('admin.products.destroy', id))
  }
}
</script>
