<template>
  <div>
    <LoadingBar />
    <LoadingSpinner />
    <slot />
  </div>
</template>

<script setup>
import LoadingBar from './LoadingBar.vue'
import LoadingSpinner from './LoadingSpinner.vue'
</script>
