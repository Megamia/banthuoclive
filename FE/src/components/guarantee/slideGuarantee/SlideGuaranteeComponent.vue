<template>
  <a-flex
    vertical
    class="justify-center items-center flex-1 gap-[20px] w-full max-w-[100%]"
  >
    <a-flex class="w-full">
      <ul class="flex flex-1 flex-row justify-center gap-[30px]">
        <li
          v-for="item in data"
          :key="item.id"
          class="text-black text-[22px] font-semibold whitespace-nowrap cursor-pointer liActive"
          :class="{
            liActived: liActive === item.slug,
          }"
          @click="changeLiActive(item.slug)"
        >
          {{ item.name }}
        </li>
      </ul>
    </a-flex>
    <a-flex class="flex-1 w-[100%] justify-center">
      <a-flex
        v-if="haveData && dataSlide.length > 0"
        class="max-w-[100%] w-full flex-1 py-[30px] justify-center relative"
      >
        <button
          v-if="dataSlide.length > 4"
          class="absolute flex w-[30px] h-[30px] rounded-[50%] text-black items-center justify-center top-[50%] left-[50px] bg-[#F3F3F3] border-[1px] border-[#b4b6b5]"
          @click="prevSlide"
        >
          <BsArrowLeft class="font-black" />
        </button>
        <swiper
          :slidesPerView="
            dataSlide.length > 0 ? Math.min(dataSlide.length, 4) : 1
          "
          :modules="modules"
          @swiper="onSwiper"
          :breakpoints="breakpoints"
          :navigation="false"
          class="swiperProduct"
        >
          <swiper-slide
            v-for="itemChil in dataSlide"
            :key="itemChil.id"
            class="w-[350px]"
          >
            <a-flex
              vertical
              class="rounded-[26px] min-w-[100%] w-[400px]  border border-[#2268DE] border-t-[8px] bg-white shadow-md shadow-black/20 p-5 flex flex-col items-center justify-center ml-[7px] m-2"
            >
              <a-flex vertical align="center" class="flex-1">
                <div class="w-full relative py-[20px] justify-center flex">
                  <img
                    :src="
                      itemChil.image?.path ||
                      'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'
                    "
                    class="justify-center items-center min-w-[100%] max-w-[300px] w-[100%] h-[300px]  max-h-full object-cover"
                  />
                </div>
                <a-flex class="px-[10px] w-[70%]">
                  <a-flex
                    vertical
                    class="flex-1 max-w-[100%] text-[16px] font-normal text-black"
                  >
                    <p class="truncate max-w-[180px]">
                      {{ itemChil.name ? itemChil.name : "Chưa có tên" }}
                    </p>
                    <p class="truncate">
                      {{ itemChil.price ? itemChil.price : "Chưa có giá" }}
                    </p>
                  </a-flex>
                </a-flex>
              </a-flex>
            </a-flex>
          </swiper-slide>
        </swiper>
        <button
          v-if="dataSlide.length > 4"
          class="absolute flex w-[30px] h-[30px] rounded-[50%] text-black items-center justify-center top-[50%] right-[50px] bg-[#F3F3F3] border-[1px] border-[#b4b6b5]"
          @click="nextSlide"
        >
          <BsArrowRight class="font-black" />
        </button>
      </a-flex>
      <div v-else class="py-[30px]">Không có dữ liệu để hiển thị</div>
    </a-flex>
  </a-flex>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref, watchEffect } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "./NavProductComponent.css";
import { Navigation } from "swiper";
import { BsArrowLeft, BsArrowRight } from "@kalimahapps/vue-icons";
const modules = [Navigation];
const haveData = ref(false);

const data = ref([]);
const fetchData = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/allCategoryParent`
    );

    data.value = response.data.allCategoryParent.slice(0, 4);

    if (data.value.length > 0) {
      liActive.value = data.value[0].slug;
      fetchDataSldie(liActive.value);
    }
  } catch (e) {
    console.log("Error: ", e);
  }
};

onMounted(() => {
  fetchData();
});
const liActive = ref("");

const changeLiActive = (value) => {
  liActive.value = value;
  fetchDataSldie(liActive.value);
};

const dataSlide = ref([]);
const fetchDataSldie = async (slug) => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_PRODUCT}/navProducts/${slug}`
    );
    if (response.data) {
      dataSlide.value = response.data.products;
      haveData.value = true;
    } else {
      dataSlide.value = [];
      haveData.value = false;
    }
  } catch (e) {
    console.error("Error fetching data:", e);
    dataSlide.value = [];
    haveData.value = false;
  }
};
const swiperInstance = ref(null);

const onSwiper = (swiper) => {
  swiperInstance.value = swiper;
};

const prevSlide = () => {
  if (swiperInstance.value) swiperInstance.value.slidePrev();
};

const nextSlide = () => {
  if (swiperInstance.value) swiperInstance.value.slideNext();
};

const breakpoints = {
  0: {
    slidesPerView: 1,
  },
  580: {
    slidesPerView: 2,
  },
  876: {
    slidesPerView: 3,
  },
  1200: {
    slidesPerView: 4,
  },
};
</script>

<style scoped>
.liActived {
  text-decoration: underline solid;
  text-decoration-thickness: 2px;
  text-underline-offset: 3px;
  color: #2268DE;
}
.liActive:hover {
  transition: all 0.3s ease-in-out;
  text-decoration: underline solid;
  text-decoration-thickness: 2px;
  text-underline-offset: 3px;
  color: #2268DE;
}
</style>
