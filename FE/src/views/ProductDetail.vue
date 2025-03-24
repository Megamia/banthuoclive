<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <section v-if="product" class="w-full">
    <main :key="product.id">
      <div class="py-2 border-b-2">
        <a-breadcrumb>
          <template #separator><span>&gt;</span></template>
          <a-breadcrumb-item
            ><RouterLink to="/" class="text-[15px] font-semibold"
              >Trang chủ</RouterLink
            ></a-breadcrumb-item
          >
          <a-breadcrumb-item v-if="product.category?.parent != null"
            ><RouterLink
              :to="`/category/${product.category.parent?.slug}`"
              class="text-[15px] font-semibold"
              >{{ product.category.parent?.name }}</RouterLink
            ></a-breadcrumb-item
          >
          <a-breadcrumb-item
            ><RouterLink
              :to="
                product.category.parent?.slug
                  ? `/category/${product.category.parent.slug}/${product.category.slug}`
                  : `/category/${product.category.slug}`
              "
              class="text-[15px] font-semibold"
            >
              {{ product.category.name }}
            </RouterLink></a-breadcrumb-item
          >
          <a-breadcrumb-item
            ><span class="text-[15px] font-semibold text-[#2267DF]">{{
              product.name
            }}</span></a-breadcrumb-item
          >
        </a-breadcrumb>
      </div>
      <div class="w-full flex flex-wrap gap-2 max-lg:flex-col">
        <div class="flex-1 flex flex-col">
          <div class="flex-1 flex flex-col my-3 border rounded-md">
            <a-image-preview-group>
              <div class="w-full">
                <a-image
                  :src="
                    activeImage ||
                    'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'
                  "
                  class="lg:max-w-[500px] max-w-[400px]"
                  alt="Product Image"
                />
              </div>
              <div class="flex-wrap flex justify-evenly">
                <a-image
                  v-for="item in product.gallery"
                  :key="item.id"
                  :src="item.path"
                  :width="120"
                  @click="setActiveImage(item.path)"
                  alt="Product Image"
                  :class="{
                    'gallery-image ring-2 ring-[#2268DE] scale-100 rounded-sm my-2':
                      item.path === activeImage,
                    'gallery-image rounded-sm border my-2':
                      item.path !== activeImage,
                  }"
                />
              </div>
            </a-image-preview-group>
          </div>
          <div class="flex flex-1 justify-evenly items-center">
            <div class="flex flex-col justify-center items-center gap-2">
              <MdLocalShipping class="w-[60px] h-[60px] text-[#2268DE] cursor-default" />
              <span class="font-medium text-sm"
                >Miễn phí vận chuyển toàn quốc</span
              >
            </div>
            <div class="flex flex-col justify-center items-center gap-2">
              <AnFilledSetting class="w-[60px] h-[60px] text-[#2268DE] cursor-default" />
              <span class="font-medium text-sm"
                >Miễn phí lắp đặt toàn quốc</span
              >
            </div>
          </div>
        </div>
        <div class="flex-1 max-lg:w-full p-2 flex gap-2 flex-col">
          <h1
            class="text-[#2268DE] max-md:text-center text-[30px] font-bold capitalize"
          >
            {{ product.name }}
          </h1>
          <a-flex class="items-center gap-[10px]">
            <a-rate
              :value="5"
              class="text-[25px] max-lg:text-center text-[#2268DE]"
            />
            <span class="text-[#a2a2a2] mt-[5px] text-[15px]"
              >(0 đánh giá)</span
            >
          </a-flex>
          <div class="flex items-center gap-3 max-lg:justify-center">
            <span> Chia sẻ sản phẩm: </span>
            <div v-for="item in imgSocialMedia" :key="item.id">
              <div class="items-center justify-center flex">
                <img :src="item.path" class="w-8 h-8 cursor-pointer" />
              </div>
            </div>
          </div>
          <div class="border-spacing-1 border-gray-400 border mx-10"></div>
          <div v-html="product.description" class="description"></div>
          <div
            class="text-[#EF0B00] font-bold text-[45px] flex gap-2 items-center w-full justify-center my-5"
          >
            {{ formattedPrice }}
            <span class="font-bold text-[35px] mb-1">₫</span>
          </div>
          <div class="flex flex-col gap-2">
            <div
              class="w-full bg-[#2268DE] py-2 flex justify-center items-center gap-2 rounded-md hover:opacity-80 cursor-pointer"
            >
              <span class="text-[#FFF833] font-bold text-[18px]"
                >Gọi Đặt Mua: 1800 2298</span
              >
              <span class="text-white text-[15px] font-medium"
                >( 7:30 - 21:00 )</span
              >
            </div>
            <div class="relative">
              <a-flex gap="15">
                <div
                  class="flex-1 bg-[#EF0B00] py-2 flex justify-center items-center rounded-md hover:opacity-80 cursor-pointer"
                  @click="handleAddToCart(product)"
                >
                  <span class="text-white text-[15px] font-medium"
                    >Mua Ngay</span
                  >
                </div>
                <div
                  class="flex-1 bg-[#2268DE] py-2 flex justify-center items-center rounded-md hover:opacity-80 cursor-pointer"
                  @click="addToComparison(product)"
                >
                  <span class="text-white text-[15px] font-medium"
                    >So Sánh</span
                  >
                </div>
              </a-flex>
              <div
                v-if="compare"
                class="bg-[#f0fffb] absolute z-30 top-[44px] mt-[5px] pb-[5px] rounded-[5px] right-0 border-[#ededed] border-[1px] flex flex-col w-[calc(50%-10px)] items-center"
              >
                <span class="font-medium"> Thêm so sánh thành công </span>
                <a
                  href="/compareProducts"
                  class="hover:bg-[#f0fffb] text-[#4bc4a3] text-[14px] leading-[16px]"
                >
                  Xem so sánh sản phẩm
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="my-4">
        <a-tabs v-model:activeKey="activeKey" size="large">
          <a-tab-pane key="1" tab="Bài viết liên quan">
            <ProductPosts v-if="product.post != null" :items="product.post" />
          </a-tab-pane>
          <a-tab-pane key="2" tab="Thông số sản phẩm" force-render>
            <ProductSpecifications
              v-if="product.thongso != null"
              :items="product.thongso"
            />
          </a-tab-pane>
          <a-tab-pane key="3" tab="Thành phần">
            <UseIngredientsComponent :product="product.id" />
          </a-tab-pane>
          <a-tab-pane key="4" tab="Hướng dẫn sử dụng">
            <InstructionsComponent :product="product.id" />
          </a-tab-pane>
        </a-tabs>
      </div>
    </main>
  </section>
  <div v-else>Không có sản phẩm</div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { MdLocalShipping, AnFilledSetting } from "@kalimahapps/vue-icons";
import { onMounted, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import ProductSpecifications from "@/components/ProductSpecifications.vue";
import ProductPosts from "@/components/ProductPosts.vue";
import UseIngredientsComponent from "../components/UseIngredientsComponent.vue";
import store from "@/store/store";
import { getDataFromIndexedDB } from "@/store/indexedDB";
import InstructionsComponent from "@/components/InstructionsComponent.vue";
const route = useRoute();
const router = useRouter();
const product = ref(null);
const formattedPrice = ref(null);
const activeImage = ref(null);
const activeKey = ref("1");
const compare = ref(false);

const fetchData = async () => {
  try {
    const { slug } = route.params;

    const allProductData = await getDataFromIndexedDB("products");
    const detailProduct = allProductData.filter((item) => item.slug === slug);

    if (detailProduct.length === 0) {
      alert("Không tìm thấy sản phẩm!");
      return;
    }

    product.value = detailProduct[0];

    formattedPrice.value = new Intl.NumberFormat("de-DE").format(
      product.value?.price ?? 0
    );

    if (product.value?.gallery?.length > 0) {
      activeImage.value = product.value.gallery[0].path;
    }
  } catch (error) {
    console.error("Error fetching product:", error);
  }
};
onMounted(() => {
  fetchData(route.params.slug);
});

watch(
  () => route.params.slug,
  (newSlug) => {
    if (newSlug) {
      fetchData(newSlug);
    }
  }
);

const setActiveImage = (path) => {
  activeImage.value = path;
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

  // console.log("Giỏ hàng sau khi cập nhật:", updatedCart);
};

const addToComparison = (product) => {
  if (!product || !product.id) {
    alert("Thêm thất bại: Sản phẩm không hợp lệ");
    return;
  }

  const currentProducts = store.getters["product/getDataStoreProducts"] ?? [];

  const differentProduct = currentProducts.find(
    (item) => item.category?.name !== product.category?.name
  );

  if (differentProduct) {
    alert("Sản phẩm bạn chọn không cùng chuyên mục");
    return;
  }

  const existProduct = currentProducts.some((item) => item.id === product.id);
  if (existProduct) {
    alert("Sản phẩm đã tồn tại trong danh sách so sánh");
    compare.value = true;
    return;
  }

  const updatedProducts = [...currentProducts, product];
  store.commit("product/setDataStoreProducts", {
    dataStoreProducts: updatedProducts,
  });

  compare.value = true;
};

const imgSocialMedia = ref([
  {
    id: 1,
    name: "FaceBook",
    path: "https://livotec.com/wp-content/themes/livotec/icon/fb.svg",
  },
  {
    id: 2,
    name: "Linkedin",
    path: "https://livotec.com/wp-content/themes/livotec/icon/in.svg",
  },
  {
    id: 3,
    name: "Zalo",
    path: "https://livotec.com/wp-content/themes/livotec/icon/zalo.svg",
  },
]);
</script>

<style>
.description ul {
  list-style: none;
  padding: 0;
}

.description ul li {
  position: relative;
  padding-left: 1.5em;
  margin-top: 10px;
}

.description ul li::before {
  content: "•";
  color: #2268de;
  font-size: 1.5em;
  position: absolute;
  left: 0;
  top: -5px;
}

.gallery-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
}
</style>
<style scoped>
:deep(.ant-image) {
  display: flex;
  justify-content: center;
}
</style>
