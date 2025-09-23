<template>
  <div class="price-range-slider">
    <div class="mb-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Faixa de Preço
      </label>
      <div class="text-sm text-gray-600 dark:text-gray-400">
        R$ {{ formatPrice(minValue) }} - R$ {{ formatPrice(maxValue) }}
      </div>
    </div>
    
    <div class="relative">
      <!-- Track -->
      <div 
        class="absolute top-1/2 transform -translate-y-1/2 w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-full"
        @click="handleTrackClick"
      ></div>
      
      <!-- Active Range -->
      <div 
        class="absolute top-1/2 transform -translate-y-1/2 h-2 bg-indigo-500 rounded-full"
        :style="{
          left: `${minPercentage}%`,
          width: `${maxPercentage - minPercentage}%`
        }"
      ></div>
      
      <!-- Min Handle -->
      <div 
        class="absolute top-1/2 transform -translate-y-1/2 w-5 h-5 bg-white dark:bg-gray-800 border-2 border-indigo-500 rounded-full cursor-pointer shadow-lg hover:shadow-xl transition-shadow"
        :style="{ left: `${minPercentage}%`, marginLeft: '-10px' }"
        @mousedown="startDrag('min', $event)"
        @touchstart="startDrag('min', $event)"
      >
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-indigo-500 rounded-full"></div>
      </div>
      
      <!-- Max Handle -->
      <div 
        class="absolute top-1/2 transform -translate-y-1/2 w-5 h-5 bg-white dark:bg-gray-800 border-2 border-indigo-500 rounded-full cursor-pointer shadow-lg hover:shadow-xl transition-shadow"
        :style="{ left: `${maxPercentage}%`, marginLeft: '-10px' }"
        @mousedown="startDrag('max', $event)"
        @touchstart="startDrag('max', $event)"
      >
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-indigo-500 rounded-full"></div>
      </div>
    </div>
    
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  min: {
    type: Number,
    default: 0
  },
  max: {
    type: Number,
    default: 1000
  },
  step: {
    type: Number,
    default: 0.01
  },
  modelValue: {
    type: Object,
    default: () => ({ min: 0, max: 1000 })
  }
})

const emit = defineEmits(['update:modelValue'])

const minValue = ref(props.modelValue.min || props.min)
const maxValue = ref(props.modelValue.max || props.max)
const isDragging = ref(false)
const dragType = ref(null)
const startX = ref(0)
const startMinValue = ref(0)
const startMaxValue = ref(0)

const minPercentage = computed(() => {
  return ((minValue.value - props.min) / (props.max - props.min)) * 100
})

const maxPercentage = computed(() => {
  return ((maxValue.value - props.min) / (props.max - props.min)) * 100
})

const formatPrice = (value) => {
  return new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value)
}


const handleTrackClick = (event) => {
  if (isDragging.value) return
  
  const rect = event.currentTarget.getBoundingClientRect()
  const clickX = event.clientX - rect.left
  const clickPercentage = (clickX / rect.width) * 100
  const clickValue = props.min + (clickPercentage / 100) * (props.max - props.min)
  
  // Determine which handle to move based on which is closer
  const minDistance = Math.abs(clickValue - minValue.value)
  const maxDistance = Math.abs(clickValue - maxValue.value)
  
  if (minDistance < maxDistance) {
    minValue.value = Math.round(clickValue / props.step) * props.step
    if (minValue.value > maxValue.value) {
      minValue.value = maxValue.value
    }
  } else {
    maxValue.value = Math.round(clickValue / props.step) * props.step
    if (maxValue.value < minValue.value) {
      maxValue.value = minValue.value
    }
  }
  
  // Clamp values to range and emit update
  minValue.value = Math.max(props.min, Math.min(props.max, minValue.value))
  maxValue.value = Math.max(props.min, Math.min(props.max, maxValue.value))
  
  emit('update:modelValue', { min: minValue.value, max: maxValue.value })
}

const startDrag = (type, event) => {
  isDragging.value = true
  dragType.value = type
  startX.value = event.type === 'mousedown' ? event.clientX : event.touches[0].clientX
  startMinValue.value = minValue.value
  startMaxValue.value = maxValue.value
  
  event.preventDefault()
}

const handleDrag = (event) => {
  if (!isDragging.value) return
  
  const currentX = event.type === 'mousemove' ? event.clientX : event.touches[0].clientX
  const deltaX = currentX - startX.value
  const deltaPercentage = (deltaX / 300) * 100 // 300px is approximate slider width
  const deltaValue = (deltaPercentage / 100) * (props.max - props.min)
  
  if (dragType.value === 'min') {
    const newValue = startMinValue.value + deltaValue
    minValue.value = Math.max(props.min, Math.min(maxValue.value, Math.round(newValue / props.step) * props.step))
  } else {
    const newValue = startMaxValue.value + deltaValue
    maxValue.value = Math.min(props.max, Math.max(minValue.value, Math.round(newValue / props.step) * props.step))
  }
  
  // Emit update during drag
  emit('update:modelValue', { min: minValue.value, max: maxValue.value })
}

const stopDrag = () => {
  isDragging.value = false
  dragType.value = null
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue) {
    minValue.value = newValue.min || props.min
    maxValue.value = newValue.max || props.max
  }
}, { deep: true })

// Watch for min/max prop changes (when product range changes)
watch(() => [props.min, props.max], ([newMin, newMax]) => {
  // If current values are outside the new range, adjust them
  if (minValue.value < newMin) minValue.value = newMin
  if (maxValue.value > newMax) maxValue.value = newMax
  if (minValue.value > newMax) minValue.value = newMax
  if (maxValue.value < newMin) maxValue.value = newMin
})

// Initialize values
onMounted(() => {
  minValue.value = props.modelValue.min || props.min
  maxValue.value = props.modelValue.max || props.max
})

// Add event listeners
onMounted(() => {
  document.addEventListener('mousemove', handleDrag)
  document.addEventListener('mouseup', stopDrag)
  document.addEventListener('touchmove', handleDrag)
  document.addEventListener('touchend', stopDrag)
})

onUnmounted(() => {
  document.removeEventListener('mousemove', handleDrag)
  document.removeEventListener('mouseup', stopDrag)
  document.removeEventListener('touchmove', handleDrag)
  document.removeEventListener('touchend', stopDrag)
})
</script>

<style scoped>
.price-range-slider {
  user-select: none;
}
</style>
