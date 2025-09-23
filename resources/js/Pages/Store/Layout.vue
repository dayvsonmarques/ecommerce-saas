<template>
  <div class="min-h-screen bg-white dark:bg-gray-900">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center">
            <Link :href="route('store.products.index')" class="flex items-center gap-2">
              <span class="inline-flex h-9 w-9 items-center justify-center rounded bg-indigo-600 text-white font-bold">S</span>
              <span class="text-lg font-semibold text-gray-900 dark:text-white">Minha Loja</span>
            </Link>
          </div>

          <!-- Navigation -->
          <nav class="hidden md:flex items-center space-x-8">
            <Link :href="route('store.products.index')" class="text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Produtos</Link>
            <Link :href="route('store.products.index')" class="text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Categorias</Link>
            <Link :href="route('store.products.index')" class="text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Ofertas</Link>
          </nav>

          <!-- Right side -->
          <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="hidden sm:block">
              <div class="relative">
                <input 
                  type="text" 
                  placeholder="Buscar produtos..." 
                  class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  v-model="searchQuery"
                  @keyup.enter="performSearch"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Cart -->
            <Link :href="route('store.cart.index')" class="relative p-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors duration-200">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
              </svg>
              <span v-if="cartItemsCount > 0" class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium animate-pulse">
                {{ cartItemsCount > 99 ? '99+' : cartItemsCount }}
              </span>
            </Link>

            <!-- User Menu -->
            <div v-if="$page.props.auth?.user" class="relative">
              <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-white text-sm font-semibold">
                  {{ userInitials }}
                </span>
                <span class="hidden sm:block">{{ $page.props.auth.user.name }}</span>
              </button>
              
              <div v-if="userMenuOpen" class="absolute right-0 mt-2 w-48 rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black/5 z-40">
                <div class="py-1">
                  <Link :href="route('store.customer.dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Minha Conta</Link>
                  <Link :href="route('store.customer.orders')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Meus Pedidos</Link>
                  <Link :href="route('store.customer.profile')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Perfil</Link>
                  <div class="border-t border-gray-100 dark:border-gray-700"></div>
                  <Link :href="route('logout')" method="post" as="button" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Sair</Link>
                </div>
              </div>
            </div>

            <!-- Login/Register -->
            <div v-else class="flex items-center space-x-2">
              <Link :href="route('login')" class="text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Entrar</Link>
              <Link :href="route('register')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Cadastrar</Link>
            </div>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-200 dark:border-gray-700 py-4">
          <div class="space-y-2">
            <Link :href="route('store.products.index')" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Produtos</Link>
            <Link :href="route('store.products.index')" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Categorias</Link>
            <Link :href="route('store.products.index')" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Ofertas</Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Minha Loja</h3>
            <p class="text-gray-600 dark:text-gray-400">Sua loja online com os melhores produtos e preços.</p>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Links Úteis</h4>
            <ul class="space-y-2">
              <li><Link :href="route('store.products.index')" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Produtos</Link></li>
              <li><Link :href="route('store.products.index')" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Categorias</Link></li>
              <li><Link :href="route('store.products.index')" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Ofertas</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Suporte</h4>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Central de Ajuda</a></li>
              <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Contato</a></li>
              <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">FAQ</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Conta</h4>
            <ul class="space-y-2">
              <li v-if="$page.props.auth?.user">
                <Link :href="route('store.customer.dashboard')" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Minha Conta</Link>
              </li>
              <li v-else>
                <Link :href="route('login')" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Entrar</Link>
              </li>
              <li v-if="!$page.props.auth?.user">
                <Link :href="route('register')" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Cadastrar</Link>
              </li>
            </ul>
          </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 text-center text-gray-600 dark:text-gray-400">
          <p>&copy; {{ new Date().getFullYear() }} Minha Loja. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import { route } from 'ziggy-js'

const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)
const searchQuery = ref('')

const page = usePage()
const userInitials = computed(() => {
  const name = page.props.auth?.user?.name || ''
  const parts = name.split(' ').filter(Boolean)
  return (parts[0]?.[0] || 'U').toUpperCase() + (parts[1]?.[0] || '').toUpperCase()
})

const cartItemsCount = ref(0)
const cartTotal = ref(0)

const performSearch = () => {
  if (searchQuery.value.trim()) {
    router.visit(route('store.products.index', { search: searchQuery.value }))
  }
}

const fetchCartInfo = async () => {
  try {
    const response = await fetch(route('store.cart.info'))
    const data = await response.json()
    cartItemsCount.value = data.items_count || 0
    cartTotal.value = data.total_amount || 0
  } catch (error) {
    console.error('Error fetching cart info:', error)
  }
}

// Close menus when clicking outside
onMounted(() => {
  // Fetch initial cart info
  fetchCartInfo()
  
  // Listen for cart updates
  window.addEventListener('cart-updated', (event) => {
    cartItemsCount.value = event.detail.items_count || 0
    cartTotal.value = event.detail.total_amount || 0
  })
  
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      userMenuOpen.value = false
      mobileMenuOpen.value = false
    }
  })
})
</script>
