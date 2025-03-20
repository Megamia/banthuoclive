<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { useRoute } from "vue-router";
import { saveDataToIndexedDB, getDataFromIndexedDB } from "./store/indexedDB";
import axios from "axios";
import echo from "@/utils/echo";
import layouts from "./views/layouts";

const products = ref([]);
const messages = ref([]);
const echoChannel = echo.channel("products");

const route = useRoute();
const layout = computed(() => layouts[route.meta.layout] || layouts.default);

let timeoutId;
let dataUpdateInterval;

const handleToken = () => {
  const token = localStorage.getItem("token");
  const tokenTimestamp = localStorage.getItem("tokenTimestamp");
  const currentTime = Date.now();
  const oneHour = 3600000;

  if (!token) {
    localStorage.removeItem("tokenTimestamp");
    return;
  }

  const timeElapsed = tokenTimestamp ? currentTime - tokenTimestamp : 0;
  const remainingTime = oneHour - timeElapsed;

  if (remainingTime <= 0) {
    localStorage.removeItem("token");
    localStorage.removeItem("tokenTimestamp");
  } else {
    timeoutId = setTimeout(() => {
      localStorage.removeItem("token");
      localStorage.removeItem("tokenTimestamp");
    }, remainingTime);
  }
};

const fetchData = async (url) => {
  try {
    const response = await axios.get(url);
    return response.data.status === 1
      ? response.data.allProduct || response.data.allCategory
      : [];
  } catch (e) {
    console.error("Error fetching data:", e);
    return [];
  }
};

// Kiểm tra sự thay đổi dữ liệu
const hasDataChanged = (localData, apiData) => {
  if (localData.length !== apiData.length) return true;

  const localMap = new Map(localData.map((item) => [item.id, item]));

  return apiData.some((apiItem) => {
    const localItem = localMap.get(apiItem.id);
    if (!localItem) {
      // console.log(`New item added:`, apiItem);
      return true;
    }

    const changes = {};
    Object.keys(apiItem).forEach((key) => {
      if (JSON.stringify(localItem[key]) !== JSON.stringify(apiItem[key])) {
        changes[key] = { old: localItem[key], new: apiItem[key] };
      }
    });

    if (Object.keys(changes).length > 0) {
      // console.log(`Item ID ${apiItem.id} changed:`, changes);
      return true;
    }

    return false;
  });
};

const updateProductListInState = async (apiProducts) => {
  products.value = apiProducts;

  await saveDataToIndexedDB("products", apiProducts);
};

const updateDataIfNeeded = async () => {
  try {
    const [localProducts, localCategories] = await Promise.all([
      getDataFromIndexedDB("products"),
      getDataFromIndexedDB("category"),
    ]);

    const [apiProducts, apiCategories] = await Promise.all([
      fetchData(`${import.meta.env.VITE_APP_URL_API_PRODUCT}/allProduct`),
      fetchData(`${import.meta.env.VITE_APP_URL_API_CATEGORY}/allCategory`),
    ]);

    const categoriesWithFilters = apiCategories.map((cat) => ({
      ...cat,
      filters: cat.filters || [],
    }));

    const isProductChanged = hasDataChanged(localProducts, apiProducts);
    const isCategoryChanged = hasDataChanged(
      localCategories,
      categoriesWithFilters
    );

    if (isProductChanged) await saveDataToIndexedDB("products", apiProducts);
    if (isCategoryChanged)
      await saveDataToIndexedDB("category", categoriesWithFilters);

    if (isProductChanged || isCategoryChanged) {
      localStorage.setItem("lastUpdated", Date.now());
    }

    if (isProductChanged) {
      await updateProductListInState(apiProducts);
    }
  } catch (error) {
    console.error("Error updating data:", error);
  }
};

onMounted(() => {
  handleToken();
  updateDataIfNeeded();

  dataUpdateInterval = setInterval(updateDataIfNeeded, 1 * 60 * 100);

  echoChannel.listen("ProductUpdated", (event) => {
    // console.log("ProductUpdated event:", event.product);

    messages.value.push(event.product);
    updateProductListInState([event.product]);
  });
});

onUnmounted(() => {
  clearTimeout(timeoutId);
  clearInterval(dataUpdateInterval);
  echoChannel.stopListening("ProductUpdated");
});
</script>

<template>
  <a-config-provider :theme="{ token: { colorPrimary: '#2267DF' } }">
    <component :is="layout">
      <router-view />
    </component>
  </a-config-provider>
</template>

<style scoped></style>
