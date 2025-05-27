<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useAuth } from '@/Composables/useAuth';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import axios from 'axios';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const map = ref(null);
const geraiList = ref([]);
const selectedJenis = ref('Semua Gerai');
const selectedWilayah = ref('Semua Wilayah');
const selectedJumlah = ref('Semua Pegawai');

let markerLayerGroup = null; // nanti diinisialisasi di initMap

async function getWilayahFromCoordinates(lat, lon) {
  try {
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
    const data = await response.json();
    return data.address.city || data.address.town || data.address.county || 'Tidak Diketahui';
  } catch (error) {
    console.error('Gagal reverse geocoding:', error);
    return 'Tidak Diketahui';
  }
}

onMounted(async () => {
  const auth = await useAuth('admin');
  const user = ref(null);

  if (auth && auth.props?.auth?.user) {
    user.value = auth.props.auth.user;
  }

  initMap();
  await getGeraiList();
  addLegend();
});

async function getGeraiList() {
  try {
    const response = await axios.get('/api/gerai', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        Accept: 'application/json',
      },
    });

    const data = response.data.data || [];

    for (const gerai of data) {
      if (gerai.lat && gerai.long) {
        gerai.wilayah = await getWilayahFromCoordinates(gerai.lat, gerai.long);
      } else {
        gerai.wilayah = 'Tidak Diketahui';
      }
    }
    geraiList.value = data;
    updateMarkers();
  } catch (error) {
    console.error('Gagal mengambil data gerai:', error);
  }
}

const wilayahList = computed(() => {
  return ['Semua Wilayah', ...new Set(geraiList.value.map(g => g.wilayah))];
});

function initMap() {
  const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
  });

  const satelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: '&copy; Esri, Maxar',
  });

  const grayscale = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; Stadia Maps, OpenMapTiles & OpenStreetMap',
  });

  const baseLayers = {
    'OpenStreetMap': osm,
    'Satelit': satelit,
    'Grayscale': grayscale,
  };

  map.value = L.map('map', {
    center: [-6.369028, 106.895912],
    zoom: 10,
    layers: [osm],
  });

  const layerControl = L.control.layers(baseLayers, null, {
    collapsed: false
  }).addTo(map.value);

  // Inisialisasi markerLayerGroup dan tambahkan ke map setelah map dibuat
  markerLayerGroup = L.layerGroup().addTo(map.value);

  // Tambah judul di layer control
  const container = layerControl.getContainer();
  const title = L.DomUtil.create('div', 'layers-control-title', container);
  title.innerHTML = '<strong>Base Map</strong>';
  title.style.padding = '5px 10px';
  title.style.borderBottom = '1px solid #ccc';
  title.style.backgroundColor = '#fff';
  container.insertBefore(title, container.firstChild);
}

function addLegend() {
  const legend = L.control({ position: 'bottomright' });
  legend.onAdd = function () {
    const div = L.DomUtil.create('div', 'info legend bg-white p-2 rounded shadow text-sm');
    const jenisSet = [...new Set(geraiList.value.map((g) => g.jenis_gerai))];
    div.innerHTML = `<strong>Legenda:</strong><br>`;
    jenisSet.forEach((jenis) => {
      const color = getColor(jenis);
      div.innerHTML += `<i style="background:${color}; width: 10px; height: 10px; display: inline-block; margin-right:5px;"></i> ${jenis}<br>`;
    });
    return div;
  };
  legend.addTo(map.value);
}

function getColor(jenis) {
  const colors = {
    'gerai': 'brown',
    'stan': 'blue',
  };
  return colors[jenis] || 'gray';
}

function updateMarkers() {
  if (!markerLayerGroup) return;

  markerLayerGroup.clearLayers();

  geraiList.value.forEach((gerai) => {
    const lat = parseFloat(gerai.lat);
    const long = parseFloat(gerai.long);
    const wilayah = gerai.wilayah;

    if (
      !isNaN(lat) && !isNaN(long) &&
      (selectedJenis.value === 'Semua Gerai' || gerai.jenis_gerai === selectedJenis.value) &&
      (selectedWilayah.value === 'Semua Wilayah' || wilayah === selectedWilayah.value) &&
      (
        selectedJumlah.value === 'Semua Pegawai' ||
        (selectedJumlah.value === '< Kurang dari 2' && gerai.jumlah_pegawai < 2) ||
        (selectedJumlah.value === '2 Sampai 4' && gerai.jumlah_pegawai >= 2 && gerai.jumlah_pegawai <= 4) ||
        (selectedJumlah.value === '> Lebih Dari 4' && gerai.jumlah_pegawai > 4)
      )
    ) {
      const popupContent = `
        <strong>${gerai.gerai}</strong><br>
        Kota/Kabupaten: ${gerai.alamat}<br>
        Pegawai: ${gerai.jumlah_pegawai}<br>
        Jenis: ${gerai.jenis_gerai}
      `;

      const marker = L.marker([lat, long], {
        icon: L.divIcon({
          className: 'custom-icon',
          html: `<div style="background-color:${getColor(gerai.jenis_gerai)}; width:12px; height:12px; border-radius:50%"></div>`,
        }),
      });

      marker.bindPopup(popupContent).addTo(markerLayerGroup);
    }
  });
}

watch(selectedJenis, updateMarkers);
watch(selectedWilayah, updateMarkers);
watch(selectedJumlah, updateMarkers);

function exportGeoJSON() {
  const geojson = {
    type: 'FeatureCollection',
    features: geraiList.value.map((gerai) => ({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [parseFloat(gerai.long), parseFloat(gerai.lat)],
      },
      properties: {
        gerai: gerai.gerai,
        alamat: gerai.alamat,
        jumlah_pegawai: gerai.jumlah_pegawai,
        jenis_gerai: gerai.jenis_gerai,
      },
    })),
  };

  const blob = new Blob([JSON.stringify(geojson, null, 2)], { type: 'application/json' });
  const url = URL.createObjectURL(blob);

  const link = document.createElement('a');
  link.href = url;
  link.download = 'gerai.geojson';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
</script>
<template>
  <AdminLayout>
    <div class="py-2 space-y-4">
      <div class="flex items-center justify-between">
        <div class="flex gap-2">
          <select v-model="selectedJenis" class="w-full md:w-[200px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
            <option>Semua Gerai</option>
            <option v-for="jenis in [...new Set(geraiList.map(g => g.jenis_gerai))]" :key="jenis">
              {{ jenis }}
            </option>
          </select>
          <select v-model="selectedWilayah" class="w-full md:w-[200px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
            <option v-for="wilayah in wilayahList" :key="wilayah">
              {{ wilayah }}
            </option>
          </select>
          <select v-model="selectedJumlah" class="w-full md:w-[200px] border border-gray-300 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
            <option>Semua Pegawai</option>
            <option>&lt; Kurang dari 2</option>
            <option>2 Sampai 4</option>
            <option>&gt; Lebih Dari 4</option>
          </select>
        </div>
        <button @click="exportGeoJSON" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm shadow transition">
          Export Data
        </button>
      </div>
      <div id="map" class="w-full h-full rounded-lg shadow"></div>
    </div>
  </AdminLayout>
</template>
<style scoped>
#map {
  height: 500px;
}

.sidebar {
  z-index: 1001;
  position: relative;
}

.leaflet-container {
  z-index: 0 !important;
}
</style>