<template>
  <div>
    <a-flex
      class="justify-center bg-[#007BFF] sticky top-0 z-50 border-b-[1px] border-white"
    >
      <HeaderComponent />
    </a-flex>
    <main class="flex flex-col flex-1 items-center mb-[50px] container p-0">
      <slot></slot>
    </main>
    <a-flex class="justify-center bg-[#EAEBF4] bottom-0">
      <FooterComponent />
    </a-flex>
  </div>
</template>

<script setup>
import FooterComponent from "@/components/guarantee/FooterComponent.vue";
import HeaderComponent from "@/components/guarantee/HeaderComponent.vue";
</script>

<style>
</style>
