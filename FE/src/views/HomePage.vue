<template>
  <a-flex class="flex-1 w-[100%] flex-col gap-[100px]">
    <HomeHeroSlideComponent />
    <NavProductComponent />
    <ProductComponent :categorySlug="'may-loc-nuoc'" />
    <ProductComponent :categorySlug="'bep-tu'" />
  </a-flex>
</template>

<script setup>
import HomeHeroSlideComponent from "@/components/HomeHeroSlideComponent.vue";
import NavProductComponent from "@/components/NavProductComponent.vue";
import ProductComponent from "@/components/ProductComponent.vue";
</script>

<style scoped></style>
