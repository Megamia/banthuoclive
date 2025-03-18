<template>
  <swiper
    class="mySlide max-w-[100%] w-full"
  >
    <swiper-slide>
      <img
        :src="path"
        class="cursor-pointer"
      />
    </swiper-slide>
  </swiper>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { defineProps } from "vue";
import "swiper/css";
import "swiper/css/pagination";

defineProps({
  path: String
})
</script>

<style scoped>
.mySlide::v-deep(.swiper-pagination) {
  width: auto;
  display: flex;
  gap: 15px;
  border-radius: 15px;
  background: #f5f1f1;
  padding: 12px 15px;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2;
}
.mySlide::v-deep(.swiper-pagination-bullet) {
  background-color: #706a6a;
  opacity: 1;
}

.mySlide::v-deep(.swiper-pagination-bullet-active),
.mySlide::v-deep(.swiper-pagination-bullet):hover {
  background-color: #5893ed;
}
</style>
