<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-drawer
    v-model:open="open"
    root-class-name="root-class-name"
    placement="left"
    :closable="false"
    :maskClosable="false"
  >
    <template #title>
      <a-flex class="justify-between flex-1 items-center">
        <span class="text-[18px] font-bold">MENU</span>
        <ClCloseLg @click="closeMenu" class="cursor-pointer" />
      </a-flex>
    </template>
    <a-flex> Content </a-flex>
  </a-drawer>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { ref, defineEmits } from "vue";
import { ClCloseLg } from "@kalimahapps/vue-icons";
const emit = defineEmits(["closeMenu"]);

const open = ref(true);

const closeMenu = () => {
  open.value = false;
  setTimeout(() => {
    emit("closeMenu");
  }, 200);
};
</script>
<style scoped></style>
