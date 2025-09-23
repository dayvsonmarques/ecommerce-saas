<template>
  <div class="product-image-gallery">
    <!-- Main Image Display -->
    <div class="main-image-container relative">
      <div class="aspect-square bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
        <img
          :src="currentImage.image_url"
          :alt="currentImage.alt_text"
          class="w-full h-full object-cover cursor-zoom-in transition-transform duration-300 hover:scale-105"
          @click="openZoomModal"
          @error="handleImageError"
        />
      </div>
      
      <!-- Navigation Arrows -->
      <button
        v-if="images.length > 1"
        @click="previousImage"
        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 rounded-full p-2 shadow-lg transition-all duration-200"
      >
        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      
      <button
        v-if="images.length > 1"
        @click="nextImage"
        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 rounded-full p-2 shadow-lg transition-all duration-200"
      >
        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>

      <!-- Image Counter -->
      <div v-if="images.length > 1" class="absolute bottom-2 right-2 bg-black/50 text-white text-sm px-2 py-1 rounded">
        {{ currentImageIndex + 1 }} / {{ images.length }}
      </div>
    </div>

    <!-- Thumbnail Navigation -->
    <div v-if="images.length > 1" class="thumbnail-container mt-4">
      <div class="flex space-x-2 overflow-x-auto pb-2">
        <button
          v-for="(image, index) in images"
          :key="image.id"
          @click="selectImage(index)"
          :class="[
            'flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-200',
            currentImageIndex === index
              ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800'
              : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
          ]"
        >
          <img
            :src="image.image_url"
            :alt="image.alt_text"
            class="w-full h-full object-cover"
            @error="handleImageError"
          />
        </button>
      </div>
    </div>

    <!-- Zoom Modal -->
    <div
      v-if="showZoomModal"
      class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
      @click="closeZoomModal"
    >
      <div class="relative max-w-4xl max-h-full">
        <button
          @click="closeZoomModal"
          class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition-colors duration-200"
        >
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <div class="relative">
          <img
            :src="currentImage.image_url"
            :alt="currentImage.alt_text"
            class="max-w-full max-h-[80vh] object-contain rounded-lg"
            @error="handleImageError"
          />

          <!-- Zoom Navigation -->
          <button
            v-if="images.length > 1"
            @click.stop="previousImage"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          
          <button
            v-if="images.length > 1"
            @click.stop="nextImage"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>

        <!-- Zoom Thumbnails -->
        <div v-if="images.length > 1" class="flex justify-center space-x-2 mt-4">
          <button
            v-for="(image, index) in images"
            :key="image.id"
            @click.stop="selectImage(index)"
            :class="[
              'w-12 h-12 rounded-lg overflow-hidden border-2 transition-all duration-200',
              currentImageIndex === index
                ? 'border-white'
                : 'border-white/50 hover:border-white/80'
            ]"
          >
            <img
              :src="image.image_url"
              :alt="image.alt_text"
              class="w-full h-full object-cover"
              @error="handleImageError"
            />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  images: {
    type: Array,
    required: true,
    default: () => []
  }
})

const currentImageIndex = ref(0)
const showZoomModal = ref(false)

const currentImage = computed(() => {
  return props.images[currentImageIndex.value] || props.images[0] || {}
})

const selectImage = (index) => {
  currentImageIndex.value = index
}

const nextImage = () => {
  if (currentImageIndex.value < props.images.length - 1) {
    currentImageIndex.value++
  } else {
    currentImageIndex.value = 0
  }
}

const previousImage = () => {
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--
  } else {
    currentImageIndex.value = props.images.length - 1
  }
}

const openZoomModal = () => {
  showZoomModal.value = true
  document.body.style.overflow = 'hidden'
}

const closeZoomModal = () => {
  showZoomModal.value = false
  document.body.style.overflow = 'auto'
}

const handleImageError = (event) => {
  event.target.src = `https://picsum.photos/800/800?random=${Math.random()}`
}

// Keyboard navigation
const handleKeydown = (event) => {
  if (!showZoomModal.value) return
  
  switch (event.key) {
    case 'Escape':
      closeZoomModal()
      break
    case 'ArrowLeft':
      event.preventDefault()
      previousImage()
      break
    case 'ArrowRight':
      event.preventDefault()
      nextImage()
      break
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
  document.body.style.overflow = 'auto'
})
</script>

<style scoped>
.thumbnail-container::-webkit-scrollbar {
  height: 4px;
}

.thumbnail-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 2px;
}

.thumbnail-container::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

.thumbnail-container::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

.dark .thumbnail-container::-webkit-scrollbar-track {
  background: #374151;
}

.dark .thumbnail-container::-webkit-scrollbar-thumb {
  background: #6b7280;
}

.dark .thumbnail-container::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>
