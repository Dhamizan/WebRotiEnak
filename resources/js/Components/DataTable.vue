<template>
    <table class="w-full text-left text-sm">
      <thead class="text-gray-500">
        <tr>
          <th v-for="header in headers" :key="header" class="py-2">{{ header }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, i) in rows" :key="i" class="border-t">
          <td v-for="(cell, j) in row" :key="j" class="py-2">{{ cell }}</td>
        </tr>
      </tbody>
    </table>
  </template>
  
  <script setup>
  defineProps({
    headers: Array,
    rows: Array,
  })
  </script>
  