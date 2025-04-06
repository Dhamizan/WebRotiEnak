<template>
    <div :class="['p-4 rounded-xl text-white shadow text-center', bgColor]">
      <h4 class="font-semibold text-md">{{ title }}</h4>
      <p class="text-lg font-bold">{{ count }}</p>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: String,
    count: String,
    color: String,
  })
  
  const bgColor = {
    orange: 'bg-orange-500',
    gray: 'bg-gray-500',
    black: 'bg-black',
  }[color || 'gray']
  </script>
  