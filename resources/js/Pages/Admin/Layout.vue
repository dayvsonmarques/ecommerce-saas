<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <!-- Mobile menu button -->
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            <div class="flex-shrink-0 flex items-center ml-2 md:ml-0">
              <Link :href="route('admin.dashboard')" class="text-xl font-bold text-gray-900">
                Admin Panel
              </Link>
            </div>
          </div>
          <div class="flex items-center">
            <Link
              :href="route('dashboard')"
              class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
            >
              Voltar ao Dashboard
            </Link>
            <Link
              :href="route('logout')"
              method="post"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium ml-4"
            >
              Sair
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile menu overlay -->
    <div v-if="mobileMenuOpen" class="md:hidden fixed inset-0 z-50 flex">
      <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="mobileMenuOpen = false"></div>
      <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
        <div class="absolute top-0 right-0 -mr-12 pt-2">
          <button
            @click="mobileMenuOpen = false"
            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
          >
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
          <div class="flex-shrink-0 flex items-center px-4">
            <Link :href="route('admin.dashboard')" class="text-xl font-bold text-gray-900" @click="mobileMenuOpen = false">
              Admin Panel
            </Link>
          </div>
          <nav class="mt-5 px-2 space-y-1">
            <Link
              :href="route('admin.dashboard')"
              @click="mobileMenuOpen = false"
              :class="[
                $page.url === '/admin' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                'group flex items-center px-2 py-2 text-base font-medium rounded-md'
              ]"
            >
              <span class="truncate">Dashboard</span>
            </Link>
            <Link
              :href="route('admin.products.index')"
              @click="mobileMenuOpen = false"
              :class="[
                $page.url.startsWith('/admin/products') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                'group flex items-center px-2 py-2 text-base font-medium rounded-md'
              ]"
            >
              <span class="truncate">Produtos</span>
            </Link>
            <Link
              :href="route('admin.categories.index')"
              @click="mobileMenuOpen = false"
              :class="[
                $page.url.startsWith('/admin/categories') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                'group flex items-center px-2 py-2 text-base font-medium rounded-md'
              ]"
            >
              <span class="truncate">Categorias</span>
            </Link>
            <Link
              :href="route('admin.customers.index')"
              @click="mobileMenuOpen = false"
              :class="[
                $page.url.startsWith('/admin/customers') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                'group flex items-center px-2 py-2 text-base font-medium rounded-md'
              ]"
            >
              <span class="truncate">Clientes</span>
            </Link>
            <Link
              :href="route('admin.orders.index')"
              @click="mobileMenuOpen = false"
              :class="[
                $page.url.startsWith('/admin/orders') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                'group flex items-center px-2 py-2 text-base font-medium rounded-md'
              ]"
            >
              <span class="truncate">Pedidos</span>
            </Link>
          </nav>
        </div>
      </div>
    </div>

    <!-- Sidebar + Page Content -->
    <div class="flex">
      <!-- Desktop Sidebar -->
      <aside class="hidden md:block w-64 bg-white border-r border-gray-200 min-h-[calc(100vh-4rem)] sticky top-16">
        <nav class="p-4 space-y-1">
          <Link
            :href="route('admin.dashboard')"
            :class="[
              $page.url === '/admin' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md'
            ]"
          >
            <span class="truncate">Dashboard</span>
          </Link>
          <Link
            :href="route('admin.products.index')"
            :class="[
              $page.url.startsWith('/admin/products') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md'
            ]"
          >
            <span class="truncate">Produtos</span>
          </Link>
          <Link
            :href="route('admin.categories.index')"
            :class="[
              $page.url.startsWith('/admin/categories') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md'
            ]"
          >
            <span class="truncate">Categorias</span>
          </Link>
          <Link
            :href="route('admin.customers.index')"
            :class="[
              $page.url.startsWith('/admin/customers') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md'
            ]"
          >
            <span class="truncate">Clientes</span>
          </Link>
          <Link
            :href="route('admin.orders.index')"
            :class="[
              $page.url.startsWith('/admin/orders') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md'
            ]"
          >
            <span class="truncate">Pedidos</span>
          </Link>
        </nav>
      </aside>

      <!-- Content -->
      <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
        <div class="py-6">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const mobileMenuOpen = ref(false)
</script>
