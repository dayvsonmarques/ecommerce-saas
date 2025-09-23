<template>
  <div
    v-if="isLoading"
    class="loading-bar fixed top-0 left-0 w-full h-1 z-50"
  >
    <div 
      class="loading-bar-progress h-full bg-gradient-to-r from-indigo-500 to-purple-600 shadow-lg"
      :style="{ width: progress + '%' }"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

const isLoading = ref(false)
const progress = ref(0)
let progressInterval = null
let startTime = null

const startLoading = () => {
  isLoading.value = true
  progress.value = 0
  startTime = Date.now()
  
  // Simulate realistic progress
  progressInterval = setInterval(() => {
    const elapsed = Date.now() - startTime
    const baseProgress = Math.min(elapsed / 1000 * 30, 70) // 30% per second, max 70%
    const randomVariation = Math.random() * 5
    progress.value = Math.min(baseProgress + randomVariation, 90)
  }, 50)
}

const finishLoading = () => {
  if (progressInterval) {
    clearInterval(progressInterval)
  }
  
  // Complete the progress bar
  progress.value = 100
  
  // Hide after a short delay
  setTimeout(() => {
    isLoading.value = false
    progress.value = 0
  }, 300)
}

onMounted(() => {
  // Listen to Inertia navigation events
  router.on('start', startLoading)
  router.on('finish', finishLoading)
  router.on('error', finishLoading)
})

onUnmounted(() => {
  // Clean up event listeners
  router.off('start', startLoading)
  router.off('finish', finishLoading)
  router.off('error', finishLoading)
  
  if (progressInterval) {
    clearInterval(progressInterval)
  }
})
</script>

<style scoped>
.loading-bar {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(4px);
}

.loading-bar-progress {
  width: 0%;
  animation: loading 0.3s ease-out;
  transition: width 0.3s ease-out;
  box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
}

@keyframes loading {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}

/* Dark mode support */
.dark .loading-bar {
  background: rgba(0, 0, 0, 0.2);
}

.dark .loading-bar-progress {
  box-shadow: 0 0 10px rgba(99, 102, 241, 0.7);
}

/* Smooth transitions */
.loading-bar-progress {
  transition: width 0.2s ease-out;
}

/* Pulse effect for better visibility */
.loading-bar-progress::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}
</style>
