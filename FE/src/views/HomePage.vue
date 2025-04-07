<template>
  <a-flex class="flex-1 w-[100%] flex-col gap-[100px]">
    <HomeHeroSlideComponent />
    <NavProductComponent />
    <ProductComponent :categorySlug="'thuc-pham-chuc-nang'" />
    <ProductComponent :categorySlug="'duoc-my-pham'" />
    <ProductComponent :categorySlug="'cham-soc-ca-nhan'" />
    <ProductComponent :categorySlug="'thiet-bi-y-te'" />
    <a-flex class="border-[1px] justify-center items-center p-[10px]">
      <ImageRecognition />
    </a-flex>
    <a-flex class="flex-1 overflow-hidden">
      <MapComponent class="w-[100%]" />
    </a-flex>
  </a-flex>
</template>

<script setup>
import HomeHeroSlideComponent from "@/components/HomeHeroSlideComponent.vue";
import NavProductComponent from "@/components/NavProductComponent.vue";
import ProductComponent from "@/components/ProductComponent.vue";
import MapComponent from "@/components/MapComponent.vue";
import ImageRecognition from "@/components/ImageRecognition.vue";
</script>

<style scoped></style>
