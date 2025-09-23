<template>
  <AdminLayout>
    <div class="max-w-2xl mx-auto">
      <div class="mb-6">
        <Link
          :href="route('admin.products.index')"
          class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
        >
          ← Voltar para Produtos
        </Link>
      </div>

      <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
            Criar Novo Produto
          </h3>

          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">
                  Nome do Produto
                </label>
                <input
                  type="text"
                  name="name"
                  id="name"
                  v-model="form.name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.name }"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                  {{ form.errors.name }}
                </p>
              </div>

              <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">
                  Descrição
                </label>
                <textarea
                  name="description"
                  id="description"
                  rows="3"
                  v-model="form.description"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.description }"
                ></textarea>
                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">
                  {{ form.errors.description }}
                </p>
              </div>

              <div class="sm:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700">
                  URL da Imagem
                </label>
                <input
                  type="url"
                  name="image"
                  id="image"
                  v-model="form.image"
                  placeholder="https://exemplo.com/imagem.jpg"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.image }"
                />
                <p v-if="form.errors.image" class="mt-2 text-sm text-red-600">
                  {{ form.errors.image }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                  Cole a URL da imagem do produto
                </p>
              </div>

              <div>
                <label for="price" class="block text-sm font-medium text-gray-700">
                  Preço
                </label>
                <input
                  type="number"
                  step="0.01"
                  name="price"
                  id="price"
                  v-model="form.price"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.price }"
                />
                <p v-if="form.errors.price" class="mt-2 text-sm text-red-600">
                  {{ form.errors.price }}
                </p>
              </div>

              <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">
                  Estoque
                </label>
                <input
                  type="number"
                  name="stock"
                  id="stock"
                  v-model="form.stock"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.stock }"
                />
                <p v-if="form.errors.stock" class="mt-2 text-sm text-red-600">
                  {{ form.errors.stock }}
                </p>
              </div>

              <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">
                  Categoria
                </label>
                <select
                  name="category_id"
                  id="category_id"
                  v-model="form.category_id"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': form.errors.category_id }"
                >
                  <option value="">Selecione uma categoria</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">
                  {{ form.errors.category_id }}
                </p>
              </div>

              <div class="flex items-center">
                <input
                  type="checkbox"
                  name="is_active"
                  id="is_active"
                  v-model="form.is_active"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                  Produto ativo
                </label>
              </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
              <Link
                :href="route('admin.products.index')"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
              >
                {{ form.processing ? 'Salvando...' : 'Salvar Produto' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  categories: Array
})

const form = useForm({
  name: '',
  description: '',
  image: '',
  price: '',
  stock: '',
  category_id: '',
  is_active: true
})

const submit = () => {
  form.post(route('admin.products.store'))
}
</script>
