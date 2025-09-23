<template>
  <AdminLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
          <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              Editar Produto
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

        <div class="mt-6">
          <form @submit.prevent="submit" class="space-y-6">
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
              <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                  <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Informações do Produto
                  </h3>
                  <p class="mt-1 text-sm text-gray-500">
                    Atualize as informações do produto.
                  </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <label for="name" class="block text-sm font-medium text-gray-700">
                        Nome
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

                    <div class="col-span-6">
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

                    <div class="col-span-6">
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

                    <div class="col-span-6 sm:col-span-3">
                      <label for="price" class="block text-sm font-medium text-gray-700">
                        Preço
                      </label>
                      <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input
                          type="number"
                          name="price"
                          id="price"
                          step="0.01"
                          min="0"
                          v-model="form.price"
                          class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                          :class="{ 'border-red-300': form.errors.price }"
                        />
                      </div>
                      <p v-if="form.errors.price" class="mt-2 text-sm text-red-600">
                        {{ form.errors.price }}
                      </p>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                      <label for="stock" class="block text-sm font-medium text-gray-700">
                        Estoque
                      </label>
                      <input
                        type="number"
                        name="stock"
                        id="stock"
                        min="0"
                        v-model="form.stock"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        :class="{ 'border-red-300': form.errors.stock }"
                      />
                      <p v-if="form.errors.stock" class="mt-2 text-sm text-red-600">
                        {{ form.errors.stock }}
                      </p>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
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
                        <option
                          v-for="category in categories"
                          :key="category.id"
                          :value="category.id"
                        >
                          {{ category.name }}
                        </option>
                      </select>
                      <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">
                        {{ form.errors.category_id }}
                      </p>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
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
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end">
              <Link
                :href="route('admin.products.index')"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
              >
                {{ form.processing ? 'Salvando...' : 'Salvar' }}
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
  product: Object,
  categories: Array
})

const form = useForm({
  name: props.product.name,
  description: props.product.description,
  image: props.product.image,
  price: props.product.price,
  stock: props.product.stock,
  category_id: props.product.category_id,
  is_active: props.product.is_active
})

const submit = () => {
  form.put(route('admin.products.update', props.product.id))
}
</script>
