<template>
  <AdminLayout>
    <div class="space-y-6">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Usuários</h1>
          <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Gerencie os usuários e seus papéis (grupos).</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <Link :href="route('admin.users.create')" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Adicionar Usuário</Link>
        </div>
      </div>

      <form @submit.prevent="applyFilters" class="bg-white dark:bg-gray-800 shadow sm:rounded-md p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
          <input v-model="filterForm.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input v-model="filterForm.email" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
          <select v-model="filterForm.role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
            <option value="">Todos</option>
            <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
          </select>
        </div>
        <div class="md:col-span-3 flex justify-end gap-2">
          <button type="button" @click="resetFilters" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Limpar</button>
          <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Aplicar</button>
        </div>
      </form>

      <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
          <li v-for="u in users.data" :key="u.id" class="px-4 py-4 flex items-center justify-between">
            <div>
              <div class="text-sm font-medium text-gray-900 dark:text-white">{{ u.name }}</div>
              <div class="text-sm text-gray-500 dark:text-gray-400">{{ u.email }}</div>
              <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="badgeClass(u.role)">{{ u.role }}</span>
            </div>
            <div class="flex items-center gap-2">
              <button :title="'Ver'" @click="router.visit(route('admin.users.show', u.id))" class="inline-flex items-center justify-center h-8 w-8 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-indigo-600 dark:text-indigo-300">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z"/></svg>
              </button>
              <button :title="'Editar'" @click="router.visit(route('admin.users.edit', u.id))" class="inline-flex items-center justify-center h-8 w-8 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-indigo-600 dark:text-indigo-300">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Zm15.71-9.04a1 1 0 0 0 0-1.41l-1.51-1.51a1 1 0 0 0-1.41 0l-1.13 1.13 3.75 3.75 1.3-1.46Z"/></svg>
              </button>
              <button :title="'Excluir'" @click="destroy(u.id)" class="inline-flex items-center justify-center h-8 w-8 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-300">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M6 7h12v2H6V7Zm2 3h8l-1 10H9L8 10Zm3-7h2v2h-2V3Z"/></svg>
              </button>
            </div>
          </li>
        </ul>
      </div>

      <div v-if="users.links" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700 dark:text-gray-300">
              Mostrando <span class="font-medium">{{ users.from }}</span> até <span class="font-medium">{{ users.to }}</span> de <span class="font-medium">{{ users.total }}</span> resultados
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <Link v-for="link in users.links" :key="link.label" :href="link.url" v-html="link.label" :class="[link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 dark:bg-indigo-600/20 dark:border-indigo-500 dark:text-indigo-300' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600','relative inline-flex items-center px-4 py-2 border text-sm font-medium']" />
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
  
</template>

<script setup>
import { Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
})

const filterForm = useForm({
  name: props.filters?.name || '',
  email: props.filters?.email || '',
  role: props.filters?.role || '',
})

const applyFilters = () => {
  filterForm.get(route('admin.users.index'), { preserveState: true, replace: true })
}

const resetFilters = () => {
  filterForm.name = ''
  filterForm.email = ''
  filterForm.role = ''
  applyFilters()
}

const destroy = (id) => {
  if (confirm('Tem certeza que deseja excluir este usuário?')) {
    router.delete(route('admin.users.destroy', id))
  }
}

const badgeClass = (role) => {
  if (role === 'Admin') return 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
  if (role === 'Owner') return 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200'
  if (role === 'Seller') return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
  return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
}
</script>


