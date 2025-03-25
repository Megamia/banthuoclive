<template>
  <div class="app">
    <button @click="relocateUser">Vị trí hiện tại</button>
    <button @click="getUserLocation">Tìm nhà thuốc gần đây</button>

    <div id="map" ref="mapContainer" class="map"></div>
    <div v-if="loading" class="loading">Đang tải...</div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";

const mapContainer = ref(null);
const loading = ref(false);
const error = ref(null);
let map = null;
let platform = null;
let userMarker = null;
let markers = [];
const HERE_API_KEY = "iu6ELc4bhBvcXDbWVBlhwo_qdiy--KNCHr3IrskEptA";

const loadMap = async () => {
  await nextTick();
  platform = new H.service.Platform({ apikey: HERE_API_KEY });
  const defaultLayers = platform.createDefaultLayers();

  map = new H.Map(mapContainer.value, defaultLayers.vector.normal.map, {
    center: { lat: 21.0285, lng: 105.8542 },
    zoom: 12,
  });

  new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
  H.ui.UI.createDefault(map, defaultLayers);

  setTimeout(() => map.getViewPort().resize(), 200);
};

const relocateUser = async () => {
  loading.value = true;
  error.value = null;

  if (!navigator.geolocation) {
    error.value = "Trình duyệt không hỗ trợ định vị.";
    loading.value = false;
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const { latitude, longitude } = position.coords;
      initUserMarker(latitude, longitude);
    },
    (err) => {
      error.value = "Không thể lấy vị trí: " + err.message;
      loading.value = false;
    },
    { enableHighAccuracy: true }
  );
};

const getUserLocation = async () => {
  loading.value = true;
  error.value = null;

  if (!navigator.geolocation) {
    error.value = "Trình duyệt không hỗ trợ định vị.";
    loading.value = false;
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const { latitude, longitude } = position.coords;
      searchPharmacies(latitude, longitude);
    },
    (err) => {
      error.value = "Không thể lấy vị trí: " + err.message;
      loading.value = false;
    },
    { enableHighAccuracy: true }
  );
};

const initUserMarker = (lat, lon) => {
  if (!map) return;

  if (userMarker) {
    map.removeObject(userMarker);
  }

  const userIcon = new H.map.Icon(
    "https://cdn-icons-png.flaticon.com/512/4781/4781517.png",
    { size: { w: 32, h: 32 } }
  );
  userMarker = new H.map.Marker({ lat, lng: lon }, { icon: userIcon });
  userMarker.addEventListener("tap", () => {
    alert(`Vị trí của bạn:\nKinh độ: ${lon}\nVĩ độ: ${lat}`);
  });
  userMarker.addEventListener("pointerenter", () => {
    map.getElement().style.cursor = "pointer";
  });

  userMarker.addEventListener("pointerleave", () => {
    map.getElement().style.cursor = "default";
  });
  map.addObject(userMarker);
  map.setCenter({ lat, lng: lon });
  loading.value = false;
};

const searchPharmacies = async (lat, lon) => {
  const url = `https://discover.search.hereapi.com/v1/discover?at=${lat},${lon}&q=pharmacy&limit=10&apikey=${HERE_API_KEY}`;
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error("Lỗi tải dữ liệu từ API");
    const data = await response.json();

    markers.forEach((marker) => map.removeObject(marker));
    markers = [];

    if (!data.items || data.items.length === 0) {
      error.value = "❌ Không tìm thấy nhà thuốc nào!";
      return;
    }

    data.items.forEach((pharmacy) => {
      const pharmacyIcon = new H.map.Icon(
        "https://cdn-icons-png.freepik.com/512/1596/1596389.png",
        { size: { w: 32, h: 32 } }
      );
      const marker = new H.map.Marker(
        { lat: pharmacy.position.lat, lng: pharmacy.position.lng },
        { icon: pharmacyIcon }
      );

      marker.addEventListener("tap", () => {
        alert(
          `Tên: ${pharmacy.title}\nĐịa chỉ: ${pharmacy.address.label}\nKhoảng cách: ${pharmacy.distance}m`
        );
      });
      marker.addEventListener("pointerenter", () => {
        map.getElement().style.cursor = "pointer";
      });

      marker.addEventListener("pointerleave", () => {
        map.getElement().style.cursor = "default";
      });
      markers.push(marker);
      map.addObject(marker);
    });

    loading.value = false;
  } catch (err) {
    error.value = "Không thể tải dữ liệu từ API.";
    console.error("Lỗi API:", err);
    loading.value = false;
  }
};

onMounted(() => {
  loadMap();
});
</script>

<style scoped>
#map {
  width: 100vw;
  height: 500px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.app {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 8px;
  text-align: center;
}

button {
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
  border: none;
  background: #007bff;
  color: white;
  border-radius: 5px;
  margin: 5px;
}

.error {
  color: red;
  font-size: 14px;
}

.loading {
  color: #007bff;
  font-size: 16px;
  font-weight: bold;
}
</style>
