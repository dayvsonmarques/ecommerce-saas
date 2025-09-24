<template>
  <StoreLayout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center">
        <!-- Pending Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 dark:bg-yellow-900 mb-6">
          <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
          Pagamento em Análise
        </h1>

        <!-- Description -->
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
          Seu pagamento está sendo processado. Você receberá uma confirmação por email assim que o pagamento for aprovado.
        </p>

        <!-- Status Info -->
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-8">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <div class="text-left">
              <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                Status do Pagamento
              </h3>
              <p class="text-sm text-blue-700 dark:text-blue-300">
                Aguardando confirmação do banco
              </p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link
            :href="route('store.customer.orders')"
            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Ver Meus Pedidos
          </Link>
          
          <Link
            :href="route('store.products.index')"
            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Continuar Comprando
          </Link>
        </div>

        <!-- Information -->
        <div class="mt-12 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            O que acontece agora?
          </h3>
          <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400 text-left">
            <div class="flex items-start">
              <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">1</span>
              <p>Seu pagamento está sendo verificado pelo banco</p>
            </div>
            <div class="flex items-start">
              <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">2</span>
              <p>Você receberá um email de confirmação em breve</p>
            </div>
            <div class="flex items-start">
              <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">3</span>
              <p>Seu pedido será processado e enviado</p>
            </div>
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
