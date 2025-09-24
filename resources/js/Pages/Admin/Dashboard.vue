<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">
          Visão geral do sistema de e-commerce
        </p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total de Produtos
                  </dt>
                  <dd class="text-lg font-medium text-gray-900">
                    {{ stats.total_products }}
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total de Categorias
                  </dt>
                  <dd class="text-lg font-medium text-gray-900">
                    {{ stats.total_categories }}
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total de Clientes
                  </dt>
                  <dd class="text-lg font-medium text-gray-900">
                    {{ stats.total_customers }}
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total de Pedidos
                  </dt>
                  <dd class="text-lg font-medium text-gray-900">
                    {{ stats.total_orders }}
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div v-if="stats && stats.charts" class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Orders by Month (Yearly) -->
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Pedidos por mês ({{ stats.charts.year }})</h3>
          <BarChart :data="stats.charts.ordersByMonth" />
        </div>

        <!-- Customers by Month (Yearly) -->
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Clientes registrados por mês ({{ stats.charts.year }})</h3>
          <BarChart :data="stats.charts.customersByMonth" color="#10b981" />
        </div>

        <!-- Customers by Region (Brazil) -->
        <div class="bg-white shadow rounded-lg p-6 lg:col-span-2">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Clientes por região (Brasil)</h3>
          <RegionBars :regions="stats.charts.customersByRegion" />
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Pedidos Recentes
          </h3>
          <div class="flow-root">
            <ul class="-my-5 divide-y divide-gray-200">
              <li v-for="order in stats.recent_orders" :key="order.id" class="py-4">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                      <span class="text-sm font-medium text-gray-600">#{{ order.id }}</span>
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                      {{ order.customer?.name || 'Cliente não encontrado' }}
                    </p>
                    <p class="text-sm text-gray-500">
                      R$ {{ parseFloat(order.total_amount).toFixed(2) }}
                    </p>
                  </div>
                  <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="getStatusColor(order.status)">
                      {{ getStatusText(order.status) }}
                    </span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from './Layout.vue'
import { computed, defineComponent } from 'vue'

const monthLabels = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']

defineProps({
  stats: Object
})

const getStatusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    pending: 'Pendente',
    processing: 'Processando',
    shipped: 'Enviado',
    delivered: 'Entregue',
    cancelled: 'Cancelado'
  }
  return texts[status] || status
}

// Simple bar chart component (SVG-based, no external deps)
const BarChart = defineComponent({
  props: { data: Array, color: { type: String, default: '#6366f1' } },
  setup(props){
    const max = computed(() => Math.max(1, ...(props.data || [0])))
    return { max }
  },
  template: `
    <div class="w-full overflow-x-auto">
      <svg :width="600" height="180" viewBox="0 0 600 180" class="min-w-full">
        <g>
          <text v-for="(m, i) in 12" :key="'lbl-'+i" :x="i*48 + 24" y="170" text-anchor="middle" class="fill-gray-500 text-[10px]">{{ ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'][i] }}</text>
          <rect v-for="(v,i) in (data || [])" :key="i" :x="i*48 + 12" :y="150 - (v/max)*130" width="24" :height="(v/max)*130" :fill="color" rx="4" />
        </g>
      </svg>
    </div>
  `
})

// Region bars (N, NE, CO, SE, S)
const RegionBars = defineComponent({
  props: { regions: Object },
  setup(props){
    const entries = computed(() => Object.entries(props.regions || {}))
    const max = computed(() => Math.max(1, ...entries.value.map(([,v]) => v)))
    const labels = { N: 'Norte', NE: 'Nordeste', CO: 'Centro-Oeste', SE: 'Sudeste', S: 'Sul' }
    return { entries, max, labels }
  },
  template: `
    <div class="space-y-3">
      <div v-for="([k,v]) in entries" :key="k">
        <div class="flex items-center justify-between mb-1">
          <span class="text-sm text-gray-600">{{ labels[k] || k }}</span>
          <span class="text-sm font-medium text-gray-900">{{ v }}</span>
        </div>
        <div class="h-3 bg-gray-100 rounded">
          <div class="h-3 bg-indigo-500 rounded" :style="{ width: ((v/max)*100)+'%' }"></div>
        </div>
      </div>
    </div>
  `
})
</script>
