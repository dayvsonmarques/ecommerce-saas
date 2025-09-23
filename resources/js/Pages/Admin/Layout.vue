<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar -->
    <div class="flex">
      <!-- Off-canvas for mobile -->
      <div class="lg:hidden" v-if="sidebarOpen">
        <div class="fixed inset-0 z-40 flex">
          <div class="fixed inset-0 bg-gray-900/80" @click="sidebarOpen = false" />
          <div class="relative mr-16 flex w-full max-w-xs flex-1">
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
              <button @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                <span class="sr-only">Fechar sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <Sidebar class="z-50" @navigate="sidebarOpen = false" />
          </div>
        </div>
      </div>

      <!-- Static sidebar for desktop -->
      <div class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:inset-y-0">
        <Sidebar />
      </div>

      <!-- Main content area -->
      <div class="lg:pl-72 flex flex-1 flex-col">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white/60 dark:bg-gray-800/60 backdrop-blur border-b border-gray-200 dark:border-gray-700">
          <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
              <div class="flex items-center gap-2">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true" class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                  <span class="sr-only">Abrir menu</span>
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </button>
                <Link :href="route('admin.dashboard')" class="flex items-center gap-2">
                  <span class="inline-flex h-8 w-8 items-center justify-center rounded bg-indigo-600 text-white font-bold">A</span>
                  <span class="text-lg font-semibold text-gray-900 dark:text-white">Admin</span>
                </Link>
              </div>

              <div class="flex items-center gap-3">
                <!-- Dark mode toggle -->
                <button @click="darkMode = !darkMode" class="inline-flex items-center justify-center h-9 w-9 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                  <span class="sr-only">Alternar tema</span>
                  <svg v-if="!darkMode" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0V4a1 1 0 0 1 1-1Zm0 14a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm8-5a1 1 0 0 1 1 1h-2a1 1 0 1 1 0-2h2a1 1 0 0 1-1 1ZM5 12a1 1 0 0 1-1 1H2a1 1 0 1 1 0-2h2a1 1 0 0 1 1 1Zm11.657 6.657a1 1 0 0 1 0 1.414l-1.414 1.414a1 1 0 1 1-1.414-1.414l1.414-1.414a1 1 0 0 1 1.414 0ZM6.757 6.757a1 1 0 0 1 0-1.414L8.17 3.93A1 1 0 1 1 9.586 5.34L8.172 6.757A1 1 0 0 1 6.757 6.757Zm10.486-1.414a1 1 0 0 1 1.414 0L20.07 6.757a1 1 0 0 1-1.414 1.414L16.83 6.757a1 1 0 0 1 0-1.414ZM6.757 17.243a1 1 0 0 1 1.414 0L9.586 18.66a1 1 0 0 1-1.414 1.414L6.757 18.657a1 1 0 0 1 0-1.414Z"/></svg>
                  <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
                </button>

                <!-- Dashboard link -->
                <Link :href="route('dashboard')" class="hidden sm:inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                  Voltar ao Dashboard
                </Link>

                <!-- Logout -->
                <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                  Sair
                </Link>
              </div>
            </div>
          </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'

const sidebarOpen = ref(false)
const darkMode = ref(false)

onMounted(() => {
  try {
    const stored = localStorage.getItem('theme')
    if (stored === 'dark') {
      darkMode.value = true
    } else if (stored === 'light') {
      darkMode.value = false
    } else {
      darkMode.value = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    // apply at startup
    const root = document.documentElement
    if (darkMode.value) {
      root.classList.add('dark')
    } else {
      root.classList.remove('dark')
    }
  } catch (e) {
    darkMode.value = false
  }
})

watch(darkMode, (isDark) => {
  try {
    localStorage.setItem('theme', isDark ? 'dark' : 'light')
  } catch (e) {}
  const root = document.documentElement
  if (isDark) {
    root.classList.add('dark')
  } else {
    root.classList.remove('dark')
  }
})
</script>

<script>
export default {
  components: {
    Sidebar: {
      emits: ['navigate'],
      components: { Link: (await import('@inertiajs/vue3')).Link },
      template: `
      <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-gray-800 px-6 pb-4 border-r border-gray-200 dark:border-gray-700 w-72">
        <div class="flex h-16 shrink-0 items-center">
          <a href="#" class="flex items-center gap-2">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded bg-indigo-600 text-white font-bold">A</span>
            <span class="text-base font-semibold text-gray-900 dark:text-white">Admin Panel</span>
          </a>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-1">
            <li>
              <Link :href="$page.props.ziggy ? route('admin.dashboard') : '/admin'"
                :class="[$page.url === '/admin' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/20 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700', 'group flex gap-x-3 rounded-md p-2 text-sm font-medium']"
                @click="$emit('navigate')">
                <span class="material-icons-outlined text-gray-400 group-hover:text-gray-500">space_dashboard</span>
                Dashboard
              </Link>
            </li>
            <li>
              <Link :href="route('admin.products.index')"
                :class="[$page.url.startsWith('/admin/products') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/20 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700', 'group flex gap-x-3 rounded-md p-2 text-sm font-medium']"
                @click="$emit('navigate')">
                <span class="material-icons-outlined text-gray-400 group-hover:text-gray-500">inventory_2</span>
                Produtos
              </Link>
            </li>
            <li>
              <Link :href="route('admin.categories.index')"
                :class="[$page.url.startsWith('/admin/categories') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/20 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700', 'group flex gap-x-3 rounded-md p-2 text-sm font-medium']"
                @click="$emit('navigate')">
                <span class="material-icons-outlined text-gray-400 group-hover:text-gray-500">category</span>
                Categorias
              </Link>
            </li>
            <li>
              <Link :href="route('admin.customers.index')"
                :class="[$page.url.startsWith('/admin/customers') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/20 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700', 'group flex gap-x-3 rounded-md p-2 text-sm font-medium']"
                @click="$emit('navigate')">
                <span class="material-icons-outlined text-gray-400 group-hover:text-gray-500">people</span>
                Clientes
              </Link>
            </li>
            <li>
              <Link :href="route('admin.orders.index')"
                :class="[$page.url.startsWith('/admin/orders') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/20 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700', 'group flex gap-x-3 rounded-md p-2 text-sm font-medium']"
                @click="$emit('navigate')">
                <span class="material-icons-outlined text-gray-400 group-hover:text-gray-500">shopping_cart</span>
                Pedidos
              </Link>
            </li>
          </ul>
        </nav>
      </div>
      `
    }
  }
}
</script>

<style>
/***** Optional: Material Icons (outlined) if available via CDN *****/
.material-icons-outlined { font-family: 'Material Icons Outlined','Material Icons', sans-serif; font-weight: normal; font-style: normal; font-size: 20px; line-height: 1; letter-spacing: normal; text-transform: none; display: inline-block; white-space: nowrap; word-wrap: normal; direction: ltr; -webkit-font-feature-settings: 'liga'; -webkit-font-smoothing: antialiased; }
</style>
