<template>
    <AdminLayout>
      <div class="text-gray-800 space-y-6">
        <!-- Welcome Text -->
        <div>
          <h2 class="text-xl font-semibold">Hello, Robby</h2>
          <h1 class="text-2xl font-bold">Welcome to Dashboard</h1>
        </div>
  
        <!-- Summary Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <SummaryCard color="orange" title="Daftar" count="5 Pegawai" icon="lucide:list" />
          <SummaryCard color="gray" title="Cuti" count="1 Pegawai" icon="lucide:calendar" />
          <SummaryCard color="black" title="Kehadiran" count="2 Pegawai" icon="lucide:check" />
        </div>
  
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Absensi Table -->
          <div class="col-span-2 bg-white p-4 rounded-xl shadow">
            <h3 class="font-semibold text-lg mb-4">Absensi</h3>
            <DataTable
              :headers="['No', 'Pegawai', '⏱ Jam Kerja', 'Status']"
              :rows="absensiRows"
            />
          </div>
  
          <!-- Pengajuan Cuti -->
          <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="font-semibold text-lg mb-4">Pengajuan Cuti</h3>
            <ul class="space-y-2">
              <CutiCard nama="Karyawan 3" tanggal="15 Desember 2024" color="gray" />
              <CutiCard nama="Karyawan 2" tanggal="18 Desember 2024" color="green" />
              <CutiCard nama="Karyawan 1" tanggal="12 Desember 2024" color="red" />
            </ul>
          </div>
        </div>
  
        <!-- Gaji Table -->
        <div class="bg-white p-4 rounded-xl shadow">
          <h3 class="font-semibold text-lg mb-4">Gaji</h3>
          <DataTable
            :headers="['No', 'Pegawai', '⏱ Jam Kerja', 'Gaji']"
            :rows="gajiRows"
          />
        </div>
  
        <!-- Footer bulan -->
        <div class="flex justify-end text-gray-600 mt-4">
          <span>Desember 2024</span>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  import SummaryCard from '@/Components/SummaryCard.vue'
  import DataTable from '@/Components/DataTable.vue'
  import CutiCard from '@/Components/CutiCard.vue'
  
  const absensiRows = [
    ['1', 'Karyawan 1', '08:23:00', 'Hadir'],
    ['2', 'Karyawan 2', '07:56:30', 'Kerja'],
    ['3', 'Karyawan 3', '-', 'Cuti'],
    ['4', 'Karyawan 4', '-', 'Tidak Hadir'],
  ]
  
  const gajiRows = [
    ['1', 'Karyawan 1', '08:23:00', 'Rp. 4.000.000'],
    ['2', 'Karyawan 2', '07:56:30', 'Rp. 3.500.000'],
    ['3', 'Karyawan 3', '-', '-'],
    ['4', 'Karyawan 4', '-', '-'],
  ]
  </script>
    