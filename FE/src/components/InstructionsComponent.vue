<template>
  <div v-html="HDSD" class="description" />
</template>

<script setup>
import { getDataFromIndexedDB } from "@/store/indexedDB";
import { onMounted, ref } from "vue";

const props = defineProps(["product"]);
const dataProduct = ref([]);
const HDSD = ref("");
const fetchData = async () => {
  const data = await getDataFromIndexedDB("products");
  const foundProduct = data.find((item) => item.id === props.product);
  dataProduct.value = foundProduct ? [foundProduct] : [];
  HDSD.value =
    dataProduct.value[0]?.ingredients_and_instructions[0]?.instructions;
};

onMounted(() => fetchData());
</script>

<style>
.description ul {
  list-style: none;
  padding-inline-start: 40px;
}

.description ul li {
  position: relative;
  padding-left: 1.5em;
  margin-top: 10px;
}

.description ul li::before {
  content: "•";
  color: #747373;
  font-size: 1.5em;
  position: absolute;
  left: 10px;
  top: -5px;
}
.description h2 {
  font-size: 25px;
}
</style>
