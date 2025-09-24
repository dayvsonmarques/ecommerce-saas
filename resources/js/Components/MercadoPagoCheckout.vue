<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      Pagamento
    </h3>
    
    <div class="space-y-4">
      <!-- Métodos de Pagamento -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
          Escolha a forma de pagamento:
        </label>
        
        <div class="grid grid-cols-2 gap-3">
          <label
            v-for="metodo in metodosPagamento"
            :key="metodo.key"
            class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
            :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900': metodoSelecionado === metodo.key }"
          >
            <input
              type="radio"
              :value="metodo.key"
              v-model="metodoSelecionado"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
            />
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ metodo.nome }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ metodo.descricao }}
              </p>
            </div>
          </label>
        </div>
      </div>

      <!-- Informações do Pagador -->
      <div v-if="metodoSelecionado" class="space-y-4">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
          Dados para Pagamento:
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="payer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nome Completo *
            </label>
            <input
              id="payer_name"
              v-model="payerData.name"
              type="text"
              required
              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              placeholder="Seu nome completo"
            />
          </div>
          
          <div>
            <label for="payer_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email *
            </label>
            <input
              id="payer_email"
              v-model="payerData.email"
              type="email"
              required
              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              placeholder="seu@email.com"
            />
          </div>
          
          <div>
            <label for="payer_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Telefone
            </label>
            <input
              id="payer_phone"
              v-model="payerData.phone"
              type="tel"
              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              placeholder="(11) 99999-9999"
            />
          </div>
        </div>
      </div>

      <!-- Botão de Pagamento -->
      <div v-if="metodoSelecionado && dadosValidos" class="pt-4">
        <button
          @click="processarPagamento"
          :disabled="processando"
          class="w-full flex justify-center items-center px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
        >
          <span v-if="processando">Processando...</span>
          <span v-else>
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
            Pagar com Mercado Pago
          </span>
        </button>
      </div>

      <!-- Erro -->
      <div v-if="erro" class="p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ erro }}
        </p>
      </div>

      <!-- Informações de Segurança -->
      <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
        <p>🔒 Seus dados estão protegidos com criptografia SSL</p>
        <p>✅ Pagamento processado pelo Mercado Pago</p>
        <p>💳 Aceitamos cartões de crédito, débito, PIX e boleto</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { route } from 'ziggy-js'

const props = defineProps({
  orderId: {
    type: Number,
    required: true
  },
  items: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['pagamento-processado'])

const metodosPagamento = ref([])
const metodoSelecionado = ref('')
const processando = ref(false)
const erro = ref('')

const payerData = ref({
  name: '',
  email: '',
  phone: ''
})

const dadosValidos = computed(() => {
  return payerData.value.name.trim() !== '' && 
         payerData.value.email.trim() !== '' &&
         isValidEmail(payerData.value.email)
})

const isValidEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

const carregarMetodosPagamento = async () => {
  try {
    const response = await fetch(route('store.payment.methods'))
    const data = await response.json()
    
    if (data.success) {
      metodosPagamento.value = Object.entries(data.metodos).map(([key, value]) => ({
        key,
        ...value
      }))
    }
  } catch (error) {
    console.error('Erro ao carregar métodos de pagamento:', error)
  }
}

const processarPagamento = async () => {
  if (!dadosValidos.value) {
    erro.value = 'Por favor, preencha todos os campos obrigatórios'
    return
  }

  processando.value = true
  erro.value = ''

  try {
    const response = await fetch(route('store.payment.create-preference'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        order_id: props.orderId,
        items: props.items,
        payer: payerData.value
      })
    })

    const data = await response.json()

    if (data.success) {
      // Redirecionar para o Mercado Pago
      window.location.href = data.init_point
    } else {
      erro.value = data.error || 'Erro ao processar pagamento'
    }
  } catch (error) {
    erro.value = 'Erro de conexão. Tente novamente.'
  } finally {
    processando.value = false
  }
}

onMounted(() => {
  carregarMetodosPagamento()
})
</script>
