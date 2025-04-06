<template>
    <li :class="['p-2 rounded text-white', bgColor]">
      <div class="flex justify-between items-center">
        <span>{{ nama }}</span>
        <span class="text-sm">{{ tanggal }}</span>
      </div>
    </li>
  </template>
  
  <script setup>
  defineProps({
    nama: String,
    tanggal: String,
    color: String,
  })
  
  const bgColor = {
    red: 'bg-red-500',
    green: 'bg-green-500',
    gray: 'bg-gray-400',
  }[color || 'gray']
  </script>
  