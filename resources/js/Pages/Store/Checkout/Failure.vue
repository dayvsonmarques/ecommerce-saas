<template>
  <StoreLayout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center">
        <!-- Error Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900 mb-6">
          <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
          Pagamento Não Processado
        </h1>

        <!-- Description -->
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
          Ocorreu um problema ao processar seu pagamento. Por favor, tente novamente ou entre em contato conosco se o problema persistir.
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link
            :href="route('store.checkout.index')"
            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Tentar Novamente
          </Link>
          
          <Link
            :href="route('store.products.index')"
            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Continuar Comprando
          </Link>
        </div>

        <!-- Help -->
        <div class="mt-12 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Precisa de Ajuda?
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Se você continuar enfrentando problemas, entre em contato conosco:
          </p>
          <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
            <p>📧 Email: suporte@loja.com</p>
            <p>📞 Telefone: (11) 99999-9999</p>
            <p>💬 Chat: Disponível 24/7</p>
          </div>
        </div>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import StoreLayout from '../Layout.vue'
</script>
