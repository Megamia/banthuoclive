<template>
  <div>
    <a-descriptions layout="horizontal" bordered size="small" :column="1">
      <a-descriptions-item :style="{ border: '1px solid black' }">
        <template #label>
          <span class="font-bold"> THÀNH PHẦN </span>
        </template>
        <span class="font-bold"> GIÁ TRỊ </span>
      </a-descriptions-item>

      <a-descriptions-item
        :style="{ border: '1px solid black' }"
        v-for="(item, index) in filteredIngredients"
        :key="index"
        :label="item.thuoc_tinh"
      >
        {{ item.gia_tri }}
      </a-descriptions-item>
    </a-descriptions>
  </div>
</template>

<script setup>
import { getDataFromIndexedDB } from "@/store/indexedDB";
import { ref, computed, onMounted } from "vue";

const props = defineProps(["product"]);
const dataProduct = ref([]);

const fetchData = async () => {
  const data = await getDataFromIndexedDB("products");
  const foundProduct = data.find((item) => item.id === props.product);
  dataProduct.value = foundProduct ? [foundProduct] : [];
};

onMounted(() => fetchData());

const filteredIngredients = computed(() => {
  if (!dataProduct.value.length) return [];

  const product = dataProduct.value[0];
  if (!product.ingredients_and_instructions) return [];

  const ingredients = product.ingredients_and_instructions[0]?.ingredients;

  return Array.isArray(ingredients) ? ingredients : [];
});
</script>

<style scoped></style>
