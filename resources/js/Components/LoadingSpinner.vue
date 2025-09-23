<template>
  <div
    v-if="isLoading"
    class="loading-spinner fixed inset-0 bg-black/20 backdrop-blur-sm z-40 flex items-center justify-center"
  >
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
      <div class="flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-2 border-indigo-500 border-t-transparent"></div>
        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ message }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  message: {
    type: String,
    default: 'Carregando...'
  }
})

const isLoading = ref(false)

const show = () => {
  isLoading.value = true
}

const hide = () => {
  isLoading.value = false
}

// Expose methods for parent components
defineExpose({
  show,
  hide
})

// Global loading state management
let globalLoadingCount = 0

const showGlobalLoading = () => {
  globalLoadingCount++
  if (globalLoadingCount === 1) {
    isLoading.value = true
  }
}

const hideGlobalLoading = () => {
  globalLoadingCount = Math.max(0, globalLoadingCount - 1)
  if (globalLoadingCount === 0) {
    isLoading.value = false
  }
}

// Listen for global loading events
onMounted(() => {
  window.addEventListener('show-loading', showGlobalLoading)
  window.addEventListener('hide-loading', hideGlobalLoading)
})

onUnmounted(() => {
  window.removeEventListener('show-loading', showGlobalLoading)
  window.removeEventListener('hide-loading', hideGlobalLoading)
})
</script>

<style scoped>
.loading-spinner {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
