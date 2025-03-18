<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex vertical class="w-full gap-[50px]">
    <a-flex vertical class="w-full justify-center">
      <span class="text-[30px] text-[#02b6ac] mb-2 font-semibold text-center"
        >Tin nổi bật</span
      >
      <a-flex horizontal v-if="dataFake.length > 0" class="gap-[20px]">
        <a-flex vertical class="flex-1 gap-[10px]">
          <a-flex>
            <a
              :href="`detailNews/${dataFake[0].slug}`"
              class="hover:bg-white p-0"
            >
              <img
                src="https://livotec.com/wp-content/uploads/2025/02/Bep-Tu-don-Livotec-E-smart-%E2%80%93-Tiet-kiem-thoi-gian-va-dien-nang-100.jpg"
              />
            </a>
          </a-flex>
          <a-flex vertical class="gap-[10px]">
            <a-flex
              class="text-[#6d6d6d] text-start items-center gap-[3px] h-[20px]"
            >
              <AkClock />
              {{
                formatDate(dataFake[0].published_at)
                  ? formatDate(dataFake[0].published_at)
                  : "No data"
              }}
            </a-flex>
            <a
              :href="`detailNews/${dataFake[0].slug}`"
              class="hover:bg-white p-0"
            >
              <span
                class="text-[calc(1.275rem+0.3vw)] text-black text-start font-normal leading-[24px] min-h-[51.5px] hover:text-[#02B6AC] transition-all duration-300 whitespace-nowrap"
              >
                {{ dataFake[0].title }}
              </span>
            </a>
            <span
              class="contentHtmlSpan3Text text-[16px] text-start text-black font-medium h-[72px]"
            >
              {{ truncateText(dataFake[0].summary) }}
            </span>
          </a-flex>
        </a-flex>
        <a-flex vertical class="flex-1 gap-[20px]">
          <a-flex
            v-for="item in dataFake.slice(1, 3)"
            :key="item.id"
            class="gap-[20px]"
          >
            <a-flex>
              <a :href="`detailNews/${item.slug}`" class="hover:bg-white p-0">
                <img
                  :src="
                    item.featured_images?.path ||
                    'https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty-300x240.jpg'
                  "
                  class="h-[190px]"
                />
              </a>
            </a-flex>
            <a-flex vertical class="flex-1 gap-[5px]">
              <a-flex
                class="text-[#6d6d6d] text-start items-center gap-[3px] h-[20px]"
              >
                <AkClock />
                {{
                  formatDate(item.published_at)
                    ? formatDate(item.published_at)
                    : "No data"
                }}
              </a-flex>
              <a :href="`detailNews/${item.slug}`" class="hover:bg-white p-0">
                <span
                  class="contentHtmlSpan2Text text-black text-start font-normal leading-[24px] min-h-[51.5px]"
                >
                  {{ item.title }}
                </span>
              </a>
              <span
                class="contentHtmlSpan3Text text-[16px] text-start text-black font-medium h-[72px]"
              >
                {{ truncateText(item.summary) }}
              </span>
            </a-flex>
          </a-flex>
        </a-flex>
      </a-flex>
    </a-flex>
    <a-flex class="w-full justify-center items-center gap-[50px] relative">
      <button
        v-if="data.length > 4"
        class="absolute flex w-[30px] h-[30px] rounded-[50%] text-black items-center justify-center top-[50%] translate-y-[-50%] left-[-50px] bg-[#F3F3F3] border-[1px] border-[#b4b6b5]"
        @click="prevSlide"
      >
        <BsArrowLeft class="font-black" />
      </button>
      <a-flex class="max-w-[100%]">
        <swiper
          :slidesPerView="data.length > 0 ? Math.min(data.length, 4) : 1"
          :spaceBetween="30"
          :modules="modules"
          :loop="true"
          @swiper="onSwiper"
          :breakpoints="breakpoints"
          :navigation="false"
          class="swiperProduct"
        >
          <swiper-slide v-for="item in data" :key="item.id" class="w-[350px]">
            <a-flex
              vertical
              class="bg-[#EDEDED] rounded-lg flex-1 h-[500px] w-[300px]"
            >
              <!-- <a-flex
            v-for="item in data"
            :key="item.id"
            class="w-[100%] max-w-[350px]"
          > -->
              <a-flex vertical class="w-full">
                <img
                  :src="item.image_url || defaultImage"
                  class="w-[100%] h-[270px] object-cover"
                />
                <a-flex
                  vertical
                  class="w-[100%] p-[15px] gap-[10px] bg-[#ededed]"
                >
                  <a-flex
                    class="text-[#6d6d6d] text-start items-center gap-[3px] h-[20px]"
                  >
                    <AkClock />
                    {{
                      formatDate(item.published_at)
                        ? formatDate(item.published_at)
                        : "No data"
                    }}
                  </a-flex>

                  <span
                    class="contentHtmlSpan2Text text-black text-start font-normal leading-[24px] h-[51.5px]"
                  >
                    {{ item.title }}
                  </span>
                  <span
                    class="contentHtmlSpan3Text text-[16px] text-start text-black font-normal h-[72px]"
                  >
                    {{ truncateText(item.summary) }}
                  </span>
                  <a
                    :href="`/detailNews/${item.slug}`"
                    class="text-[#0d6efd] hover:bg-[#ededed] hover:text-[#02B6AC] text-start"
                  >
                    Đọc tiếp</a
                  >
                </a-flex>
              </a-flex>
              <!-- </a-flex> -->
            </a-flex>
          </swiper-slide>
        </swiper>
      </a-flex>
      <button
        v-if="data.length > 4"
        class="absolute flex w-[30px] h-[30px] rounded-[50%] text-black items-center justify-center top-[50%] translate-y-[-50%] right-[-50px] bg-[#F3F3F3] border-[1px] border-[#b4b6b5]"
        @click="nextSlide"
      >
        <BsArrowRight class="font-black" />
      </button>
    </a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { onMounted, ref } from "vue";
import { AkClock } from "@kalimahapps/vue-icons";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper";
import "swiper/css";
import "swiper/css/navigation";
import "./NavProductComponent.css";
import { BsArrowLeft, BsArrowRight } from "@kalimahapps/vue-icons";

const modules = [Navigation];

const data = ref([]);
const dataFake = ref([]);
const defaultImage =
  "https://livotec.com/wp-content/uploads/2024/11/Binh-tam-nong-lanh-%E2%80%93-Giai-phap-hoan-hao-cho-ngoi-nha-hien-dai.jpg";

const fetchData = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_POST}/allPost`
    );
    data.value = response.data.data;
  } catch (e) {
    console.log("Error: ", e);
  }
};
const fetchData2 = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_POST}/hotNews/tin-tuc`
    );
    dataFake.value = response.data.data;
  } catch (e) {
    console.log(e);
  }
};

onMounted(() => {
  fetchData();
  fetchData2();
});
const truncateText = (summary) => {
  if (!summary) return "";

  const tempElement = document.createElement("div");
  tempElement.innerHTML = summary;
  tempElement.querySelectorAll("img").forEach((img) => img.remove());

  return tempElement.textContent.trim().split("\n")[0];
};

const formatDate = (date) => {
  return date ? dayjs(date).format("DD/MM/YYYY") : "";
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
.contentHtmlSpan2Text {
  font-size: calc(1.275rem + 0.3vw);
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  max-width: 100%;
}

.contentHtmlSpan3Text {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  max-width: 100%;
}
</style>
