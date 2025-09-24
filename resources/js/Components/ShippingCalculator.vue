<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Calcular Frete</h2>
    
    <!-- CEP Input -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        CEP de Destino
      </label>
      <div class="flex space-x-2">
        <input
          v-model="cep"
          type="text"
          placeholder="00000-000"
          maxlength="9"
          @input="formatCep"
          class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          :class="{ 'border-red-500': cepError }"
        />
        <button
          @click="calcularFrete"
          :disabled="!cep || cep.length !== 9 || loading"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
        >
          <span v-if="loading">Calculando...</span>
          <span v-else>Calcular</span>
        </button>
      </div>
      <p v-if="cepError" class="mt-1 text-sm text-red-600">{{ cepError }}</p>
    </div>

    <!-- Endereço encontrado -->
    <div v-if="endereco" class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-md">
      <p class="text-sm text-green-800 dark:text-green-200">
        <strong>{{ endereco.logradouro }}</strong><br>
        {{ endereco.bairro }} - {{ endereco.localidade }}/{{ endereco.uf }}
      </p>
    </div>

    <!-- Opções de Frete -->
    <div v-if="opcoesFrete.length > 0" class="space-y-3">
      <h3 class="text-md font-medium text-gray-900 dark:text-white">Opções de Entrega:</h3>
      
      <div
        v-for="opcao in opcoesFrete"
        :key="opcao.codigo"
        class="border border-gray-200 dark:border-gray-600 rounded-md p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20': opcaoSelecionada?.codigo === opcao.codigo }"
        @click="selecionarFrete(opcao)"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <input
              type="radio"
              :value="opcao.codigo"
              v-model="opcaoSelecionada"
              :checked="opcaoSelecionada?.codigo === opcao.codigo"
              class="text-indigo-600 focus:ring-indigo-500"
            />
            <div>
              <h4 class="font-medium text-gray-900 dark:text-white">{{ opcao.nome }}</h4>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ opcao.descricao }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-500">{{ opcao.prazo_estimado }}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="font-semibold text-gray-900 dark:text-white">
              R$ {{ formatPrice(opcao.valor) }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ opcao.prazo }} dias úteis
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Erro -->
    <div v-if="erro" class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-md">
      <p class="text-sm text-red-800 dark:text-red-200">{{ erro }}</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mt-4 flex items-center justify-center">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
      <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Calculando frete...</span>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { route } from 'ziggy-js'

const props = defineProps({
  cartItems: Array,
  modelValue: Object // { cep: string, opcaoSelecionada: object }
})

const emit = defineEmits(['update:modelValue', 'frete-selecionado'])

const cep = ref(props.modelValue?.cep || '')
const opcoesFrete = ref([])
const opcaoSelecionada = ref(props.modelValue?.opcaoSelecionada || null)
const loading = ref(false)
const erro = ref(null)
const cepError = ref(null)
const endereco = ref(null)

// Formatar CEP
const formatCep = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length >= 5) {
    value = value.substring(0, 5) + '-' + value.substring(5, 8)
  }
  cep.value = value
  cepError.value = null
}

// Calcular frete
const calcularFrete = async () => {
  if (!cep.value || cep.value.length !== 9) {
    cepError.value = 'CEP deve ter 8 dígitos'
    return
  }

  loading.value = true
  erro.value = null
  opcoesFrete.value = []

  try {
    // Primeiro validar o CEP
    const cepResponse = await fetch(route('shipping.validate-cep'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        cep: cep.value
      })
    })

    const cepData = await cepResponse.json()

    if (!cepData.success) {
      cepError.value = cepData.error || 'CEP inválido'
      return
    }

    endereco.value = cepData.endereco

    // Preparar itens do carrinho
    const items = props.cartItems.map(item => ({
      id: item.product.id,
      quantity: item.quantity
    }))

    // Calcular frete
    const response = await fetch(route('shipping.calculate'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        cep_destino: cep.value,
        items: items
      })
    })

    const data = await response.json()

    if (data.success) {
      opcoesFrete.value = data.opcoes
      if (data.opcoes.length > 0) {
        // Selecionar automaticamente a primeira opção
        opcaoSelecionada.value = data.opcoes[0]
        emit('frete-selecionado', data.opcoes[0])
      }
    } else {
      erro.value = data.error || 'Erro ao calcular frete'
    }

  } catch (error) {
    erro.value = 'Erro de conexão. Tente novamente.'
    console.error('Erro ao calcular frete:', error)
  } finally {
    loading.value = false
  }
}

// Selecionar opção de frete
const selecionarFrete = (opcao) => {
  opcaoSelecionada.value = opcao
  emit('frete-selecionado', opcao)
}

// Formatar preço
const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

// Watch para atualizar o modelValue
watch([cep, opcaoSelecionada], () => {
  emit('update:modelValue', {
    cep: cep.value,
    opcaoSelecionada: opcaoSelecionada.value
  })
}, { deep: true })
</script>