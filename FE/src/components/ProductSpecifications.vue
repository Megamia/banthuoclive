<template>
  <a-descriptions layout="horizontal" bordered size="small" :column="1">
    <a-descriptions-item :style="{ border: '1px solid black' }"
      ><template #label>
        <span class="font-bold"> THUỘC TÍNH </span>
      </template>
      <span class="font-bold"> GIÁ TRỊ </span>
    </a-descriptions-item>
    <a-descriptions-item
      :style="{ border: '1px solid black' }"
      v-for="(item, index) in items"
      :key="index"
      :label="item.thuoc_tinh"
    >
      {{ item.gia_tri }}
    </a-descriptions-item>
  </a-descriptions>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    required: true, // Đảm bảo props `items` được truyền vào
  },
});
</script>

<style scoped></style>
