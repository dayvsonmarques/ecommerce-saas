<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      Calcular Frete
    </h3>
    
    <div class="space-y-4">
      <!-- CEP Input -->
      <div>
        <label for="cep" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          CEP de Destino
        </label>
        <div class="flex space-x-2">
          <input
            id="cep"
            v-model="cep"
            type="text"
            placeholder="00000-000"
            maxlength="9"
            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @input="formatCep"
            @blur="calcularFrete"
          />
          <button
            @click="calcularFrete"
            :disabled="loading || !cepValido"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
          >
            <span v-if="loading">Calculando...</span>
            <span v-else>Calcular</span>
          </button>
        </div>
        <div v-if="cepError" class="mt-1 text-sm text-red-600 dark:text-red-400">
          {{ cepError }}
        </div>
      </div>

      <!-- Opções de Frete -->
      <div v-if="opcoesFrete.length > 0" class="space-y-3">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
          Opções de Entrega:
        </h4>
        
        <div class="space-y-2">
          <label
            v-for="opcao in opcoesFrete"
            :key="opcao.codigo"
            class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
            :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900': opcaoSelecionada === opcao.codigo }"
          >
            <input
              type="radio"
              :value="opcao.codigo"
              v-model="opcaoSelecionada"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
            />
            <div class="ml-3 flex-1">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ opcao.nome }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ opcao.descricao }} - {{ opcao.prazo_estimado }}
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    R$ {{ formatPrice(opcao.valor) }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ opcao.prazo }} dia(s)
                  </p>
                </div>
              </div>
            </div>
          </label>
        </div>
      </div>

      <!-- Erro de Cálculo -->
      <div v-if="erroCalculo" class="p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ erroCalculo }}
        </p>
      </div>

      <!-- Informações Adicionais -->
      <div class="text-xs text-gray-500 dark:text-gray-400">
        <p>* O prazo de entrega é estimado e pode variar conforme a região.</p>
        <p>* O frete será recalculado no checkout com base no endereço completo.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { route } from 'ziggy-js'

const props = defineProps({
  items: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['frete-selecionado'])

const cep = ref('')
const loading = ref(false)
const opcoesFrete = ref([])
const opcaoSelecionada = ref('')
const erroCalculo = ref('')
const cepError = ref('')

const cepValido = computed(() => {
  const cepLimpo = cep.value.replace(/\D/g, '')
  return cepLimpo.length === 8
})

const formatCep = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length >= 5) {
    value = value.substring(0, 5) + '-' + value.substring(5, 8)
  }
  cep.value = value
}

const calcularFrete = async () => {
  if (!cepValido.value) {
    cepError.value = 'CEP inválido'
    return
  }

  cepError.value = ''
  erroCalculo.value = ''
  loading.value = true

  try {
    const response = await fetch(route('store.shipping.calculate'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        cep_destino: cep.value,
        items: props.items
      })
    })

    const data = await response.json()

    if (data.success) {
      opcoesFrete.value = data.opcoes
      if (data.opcoes.length > 0) {
        opcaoSelecionada.value = data.opcoes[0].codigo
        emit('frete-selecionado', data.opcoes[0])
      }
    } else {
      erroCalculo.value = data.error || 'Erro ao calcular frete'
    }
  } catch (error) {
    erroCalculo.value = 'Erro de conexão. Tente novamente.'
  } finally {
    loading.value = false
  }
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2).replace('.', ',')
}

// Watch para emitir mudanças na seleção
watch(opcaoSelecionada, (newValue) => {
  if (newValue) {
    const opcao = opcoesFrete.value.find(o => o.codigo === newValue)
    if (opcao) {
      emit('frete-selecionado', opcao)
    }
  }
})
</script>
