<template>
  <swiper
    :pagination="{
      clickable: true,
    }"
    :modules="modules"
    class="mySlide max-w-[100%]"
  >
    <swiper-slide>
      <img
        src="https://media-cdn-v2.laodong.vn/storage/newsportal/2022/6/9/1054566/Thuc-Pham-Chuc-Nang.jpg"
      />
    </swiper-slide>
    <swiper-slide>
      <img
        src="https://cdyhn.vn/wp-content/uploads/2022/01/thuc-pham-chuc-nang-va-thuoc-1.jpg"
      />
    </swiper-slide>
    <swiper-slide>
      <img
        src="https://images.baodantoc.vn/uploads/2021/Th%C3%A1ng_12/Ng%C3%A0y_25/NGOC/1.jpg"
      /> </swiper-slide
    ><swiper-slide>
      <img
        src="https://soyt.vinhphuc.gov.vn/noidung/PublishingImages/T2.2023/thuoc-chua-benh_2023169824.jpg"
      /> </swiper-slide
    ><swiper-slide>
      <img
        src="https://www.vinmec.com/static/uploads/20211106_132340_139604_vitamin_tot_cho_phu_max_1800x1800_jpg_6d6667a55e.jpg"
      /> </swiper-slide
    ><swiper-slide>
      <img
        src="https://suckhoedoisong.qltns.mediacdn.vn/324455921873985536/2024/3/29/photo-1711704199580-17117042002282045391232.jpeg"
      />
    </swiper-slide>
  </swiper>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";

import { Pagination } from "swiper";
import "swiper/css";
import "swiper/css/pagination";

const modules = [Pagination];
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

.mySlide img {
  cursor: pointer;
  width: 100%;
  height: 550px;
}
</style>
