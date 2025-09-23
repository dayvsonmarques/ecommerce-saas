<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-2xl font-bold text-gray-900">Categorias</h1>
          <p class="mt-2 text-sm text-gray-700">
            Gerencie as categorias de produtos
          </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <Link
            :href="route('admin.categories.create')"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
          >
            Adicionar Categoria
          </Link>
        </div>
      </div>

      <!-- Categories Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="category in categories.data" :key="category.id">
            <div class="px-4 py-4 flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-indigo-600">{{ category.name.charAt(0) }}</span>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                  <div class="text-sm text-gray-500">{{ category.products_count }} produtos</div>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ category.is_active ? 'Ativa' : 'Inativa' }}
                </span>
                <div class="flex space-x-2">
                  <Link
                    :href="route('admin.categories.show', category.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    Ver
                  </Link>
                  <Link
                    :href="route('admin.categories.edit', category.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    Editar
                  </Link>
                  <button
                    @click="deleteCategory(category.id)"
                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                  >
                    Excluir
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Pagination -->
      <div v-if="categories.links" class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <Link
            v-if="categories.prev_page_url"
            :href="categories.prev_page_url"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Anterior
          </Link>
          <Link
            v-if="categories.next_page_url"
            :href="categories.next_page_url"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Próximo
          </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Mostrando
              <span class="font-medium">{{ categories.from }}</span>
              até
              <span class="font-medium">{{ categories.to }}</span>
              de
              <span class="font-medium">{{ categories.total }}</span>
              resultados
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <Link
                v-for="link in categories.links"
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
import AdminLayout from '../Layout.vue'

defineProps({
  categories: Object
})

const deleteCategory = (id) => {
  if (confirm('Tem certeza que deseja excluir esta categoria?')) {
    router.delete(route('admin.categories.destroy', id))
  }
}
</script>
