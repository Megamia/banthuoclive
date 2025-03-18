<template>
  <a-flex vertical gap="30">
    <!-- {{ products }} -->
    {{ category }}
    <ul>
      <li v-for="item in parentCategories" :key="item">{{ item }}</li>
    </ul>
    <ul>
      <li v-for="item in chilCategories" :key="item">{{ item }}</li>
    </ul>
    <button @click="clear" class="bg-[red] text-white text-[30px] p-2">
      clear
    </button>
  </a-flex>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import {
  saveDataToIndexedDB,
  clearDataFromIndexedDB,
  getDataFromIndexedDB,
  getAllDataFromIndexedDB,
} from "@/store/indexedDB";

const fetchData1 = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_PRODUCT}/allProduct`
    );
    if (response.data.status === 1) {
      return response.data.allProduct;
    } else {
      return [];
    }
  } catch (e) {
    console.log("Error: ", e);
    return [];
  }
};

const fetchDataCategory = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/allCategory`
    );

    if (response.data.status === 1) {
      return response.data.allCategory;
    } else {
      return [];
    }
  } catch (e) {
    console.log("Error: ", e);
    return [];
  }
};
const fetchData2 = async () => {
  const localData = await getDataFromIndexedDB("products");
  const categoryData = await getDataFromIndexedDB("category");

  if (localData.length > 0) {
    return localData, categoryData;
  } else {
    const apiData = await fetchData1();
    const apiCategory = await fetchDataCategory();
    if (apiCategory.length > 0) {
      await saveDataToIndexedDB("category", apiCategory);
    }
    if (apiData.length > 0) {
      await saveDataToIndexedDB("products", apiData);
    }
  }
};

onMounted(() => {
  fetchData2();
});
</script>

<style scoped></style>
