<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex vertical align="center" class="w-full">
    <h1 class="text-[24px] font-semibold text-[#2268DE] text-center">
      Live - Vì một cuộc sống tươi đẹp hơn
    </h1>
    <a-flex class="max-w-[100%]">
      <a-tabs
        v-model:data="activeKey"
        class="nav max-w-[100%]"
        @change="changeData"
      >
        <a-tab-pane
          v-for="item in validateCategory"
          :key="item.slug"
          :tab="item.name"
          class="flex gap-[10px]"
        >
        </a-tab-pane>
      </a-tabs>
    </a-flex>

    <a-flex
      v-if="haveData && dataChil.length > 0"
      class="max-w-[100%] w-full px-[100px] py-[30px] relative content"
    >
      <button
        v-if="dataChil.length > 4"
        class="absolute flex w-[30px] h-[30px] rounded-[50%] text-black items-center justify-center top-[50%] left-[50px] bg-[#F3F3F3] border-[1px] border-[#b4b6b5] buttonSlide"
        @click="prevSlide"
      >
        <BsArrowLeft class="font-black" />
      </button>
      <swiper
        :spaceBetween="30"
        :modules="modules"
        @swiper="onSwiper"
        :navigation="false"
        :breakpoints="{
          0: { slidesPerView: 1 },
          480: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          992: { slidesPerView: 3 },
          1200: { slidesPerView: 4 },
        }"
      >
        <swiper-slide v-for="itemChil in dataChil" :key="itemChil.id">
          <a-flex
            vertical
            class="bg-[#F3F4F6] rounded-lg pb-[20px] w-full flex-1"
          >
            <a-flex vertical align="center" class="flex-1 itemsContent">
              <div class="w-full relative py-[20px] justify-center flex">
                <img
                  :src="
                    itemChil?.image?.path ||
                    'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'
                  "
                  class="justify-center items-center w-[300px] h-[300px] max-w-full max-h-full object-cover"
                />
                <div
                  class="absolute bg-[#e20008] top-[20px] right-0 rounded-l-md label z-10 title"
                >
                  <span class="text-[20px] text-white">Mới nhất</span>
                </div>
                <div
                  class="absolute cursor-pointer h-[100%] top-0 w-[100%] bg-gradient-to-r from-black/50 to-black/50 text-white p-2 description-nav rounded-t-lg z-20"
                >
                  <div
                    v-html="
                      itemChil.description
                        ? itemChil.description
                        : 'Chưa có mô tả'
                    "
                    class="max-h-[90%] overflow-y-scroll text-left test"
                  />
                </div>
              </div>
              <a-flex class="px-[10px] w-[70%]">
                <a-flex gap="12" vertical class="flex-1 max-w-[100%]">
                  <a
                    :href="`/product/${itemChil.slug}`"
                    class="text-[16px] text-center font-bold w-[100%] hover:bg-[#F3F4F6] hover:text-[#2268DE] cursor-pointer text-ellipsis overflow-hidden whitespace-nowrap"
                  >
                    {{ itemChil.name ? itemChil.name : "Chưa có tên" }}
                  </a>
                  <span
                    class="text-[16px] text-center font-bold text-[#2268DE] cursor-pointer"
                  >
                    {{ formatCurrency(itemChil.price) }}
                  </span>
                  <a-flex vertical class="gap-[10px] text-[16px]">
                    <button
                      class="flex-1 font-bold px-[12px] py-[10px] rounded-[9999px] text-white hover:bg-[#CC020B] bg-[linear-gradient(270deg,_#e20008_0%,_rgba(226,_0,_8,_0.7)_100%,_rgba(226,_0,_8,_0.68)_100%)] shadow-[#ff0000] shadow-sm"
                      @click="handleAddToCart(itemChil)"
                    >
                      Mua ngay
                    </button>

                    <button
                      class="flex-1 font-sans border-[1px] border-[#4fa8e7] px-[12px] py-[10px] rounded-full text-white bg-[#2268DE] hover:bg-[linear-gradient(270deg,_#ccf7fb_2.05%,_#fff_100%)] hover:text-[#424242]"
                      @click="handleProductDetail(itemChil.slug)"
                    >
                      Chi tiết
                    </button>
                  </a-flex>
                </a-flex>
              </a-flex>
            </a-flex>
          </a-flex>
        </swiper-slide>
      </swiper>
      <button
        v-if="dataChil.length > 4"
        class="absolute flex w-[30px] h-[30px] rounded-[50%] text-black items-center justify-center top-[50%] right-[50px] bg-[#F3F3F3] border-[1px] border-[#b4b6b5] buttonSlide"
        @click="nextSlide"
      >
        <BsArrowRight class="font-black" />
      </button>
    </a-flex>
    <div v-else>Không có dữ liệu để hiển thị</div>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import {
  ref,
  onMounted,
} from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "./NavProductComponent.css";
import { Navigation } from "swiper";
import { useRouter } from "vue-router";
import { BsArrowLeft, BsArrowRight } from "@kalimahapps/vue-icons";
import store from "@/store/store";
import { getDataFromIndexedDB } from "@/store/indexedDB";

const modules = [Navigation];
const slugsToFilter = [
  "thuc-pham-chuc-nang",
  "duoc-my-pham",
  "thuoc",
  "thiet-bi-y-te",
  "cham-soc-ca-nhan",
];

const data = ref([]);
const activeKey = ref(null);
const haveData = ref(false);
const router = useRouter();
const dataChil = ref([]);
const validateCategory = ref([]);

const categoryOrder = {
  "thuc-pham-chuc-nang": 1,
  "duoc-my-pham": 2,
  "thuoc": 3,
  "thiet-bi-y-te": 4,
  "cham-soc-ca-nhan": 5,
};

const fetchDataCategory = async () => {
  try {
    let categoryData = await getDataFromIndexedDB("category");
    const parent = categoryData.filter((item) => !item.parent_id);
    data.value = parent;

    filterData(data.value);

    if (validateCategory.value.length > 0) {
      activeKey.value = validateCategory.value[0].slug;
      await fetchData(activeKey.value);
    } else {
      activeKey.value = null;
      dataChil.value = [];
      haveData.value = false;
    }
  } catch (e) {
    console.log("Error: ", e);
  }
};

const filterData = (data) => {
  validateCategory.value = data
    .filter((item) => slugsToFilter.includes(item.slug))
    .map((item) => ({
      ...item,
      order: categoryOrder[item.slug] || 0,
    }))
    .sort((a, b) => a.order - b.order);
};

onMounted(() => fetchDataCategory());

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const handleAddToCart = async (data) => {
  const currentCart = store.getters["product/getDataStoreCart"] || [];

  let itemExists = false;
  const updatedCart = currentCart.map((item) => {
    if (item.id === data.id) {
      itemExists = true;
      return { id: item.id, quantity: (item.quantity || 1) + 1 };
    }
    return item;
  });

  if (!itemExists) {
    updatedCart.push({ id: data.id, quantity: 1 });
  }

  store.commit("product/setDataStoreCart", {
    dataStoreCart: updatedCart,
  });
};

const handleProductDetail = (items) => {
  router.push(`/product/${items}`);
};

const changeData = async (slug) => {
  activeKey.value = slug;
  await fetchData(slug);
};

const fetchData = async (slug) => {
  try {
    let categoryData = await getDataFromIndexedDB("category");
    let productData = await getDataFromIndexedDB("products");

    const parent = categoryData.find((item) => item.slug === slug);
    if (!parent) {
      alert("Không tìm thấy danh mục");
      return;
    }

    const categoryIds = [
      parent.id,
      ...categoryData
        .filter((item) => item.parent_id === parent.id)
        .map((item) => item.id),
    ];

    const filteredProducts = productData.filter((product) =>
      categoryIds.includes(product.category_id)
    );
    if (filteredProducts.length > 0) {
      dataChil.value = filteredProducts;
      haveData.value = true;
    } else {
      dataChil.value = [];
      haveData.value = false;
    }
  } catch (e) {
    console.log("Error: ", e);
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

</script>

<style scoped>
.nav::v-deep(.ant-tabs-tab) {
  margin: 0;
  padding-inline: 0.5rem;
  padding-inline: 16px;
}

.nav::v-deep(.ant-tabs-nav .ant-tabs-tab-btn) {
  font-size: 18px;
  font-weight: 500;
}
:deep(.ant-tabs-nav .ant-tabs-tab-btn::first-letter) {
  text-transform: uppercase;
}
@media only screen and (max-width: 480px) {
  .content {
    height: auto;
    padding-inline: 0;
  }
  .itemsContent {
    width: 100%;
  }
  .title,
  .buttonSlide {
    display: none;
  }
  img {
    width: 200px;
    height: 200px;
  }
}
</style>

<style>
.test {
  padding: 10px 20px;
}

.test ul li::before {
  content: "•";
  margin-right: 0.5rem;
  position: absolute;
  left: 0;
  top: 0;
}
.test ul li {
  position: relative;
  padding-left: 10px;
  color: white;
}
.test::-webkit-scrollbar {
  width: 5px;
}

.test::-webkit-scrollbar-track {
  background: #9b9b9c;
  border-radius: 4px;
}
.test::-webkit-scrollbar-thumb {
  background: #f0f0f0;
  border-radius: 4px;
}

.test::-webkit-scrollbar-thumb:hover {
  background: #f0f0f0;
}
</style>
