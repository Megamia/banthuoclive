<template>
  <a-flex vertical class="px-[100px] max-w-[100%] content">
    <img
      :src="
        pathImg ||
        'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'
      "
      class="w-[100%] bg-red-500 mb-5"
      v-if="pathImg"
    />
    <a-flex
      justify="center"
      vertical
      class="gap-[40px] justify-between max-w-[100%]"
    >
      <a-flex vertical>
        <span
          class="text-[28px] text-[#02b6ac] font-bold uppercase text-center"
          >{{ nameCategory ? nameCategory : props.categorySlug }}</span
        >
        <a-flex class="max-w-[100%] justify-center">
          <a-tabs class="nav max-w-[100%]" @change="changeData"
            ><a-tab-pane
              v-for="item in categoryChil"
              :key="item.id"
              :tab="item.name"
              class="flex gap-[10px]"
            ></a-tab-pane
          ></a-tabs>
        </a-flex>
      </a-flex>
      <a-flex
        horizontal
        class="max-w-[100%] overflow-hidden justify-center gap-[30px]"
        v-if="dataChil.length > 0"
      >
        <a-flex
          v-for="itemChil in displayedItems"
          :key="itemChil.id"
          class="max-w-[100%] min-w-[100px]"
        >
          <a-flex vertical class="bg-[#F3F4F6] rounded-lg pb-[20px] w-full">
            <a-flex vertical align="center" class="flex-1">
              <div class="w-full relative pt-[20px] justify-center flex">
                <img
                  class="w-[300px] h-[300px] max-w-none"
                  :src="
                    itemChil.image?.path ||
                    'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'
                  "
                />
                <div
                  v-if="itemChil.sold_out >= 10"
                  class="absolute bg-[#ffdc37] top-[20px] right-0 rounded-l-md z-10 bestseller"
                >
                  <span class="text-black"
                    >Bán chạy <br />
                    nhất</span
                  >
                </div>
                <div
                  v-else
                  class="absolute bg-[#e20008] top-[20px] right-0 rounded-l-md label z-10 new"
                >
                  <span class="font-bold text-white">Mới nhất</span>
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
              <a-flex class="px-[10px] w-[70%] text-center max-w-[200px]">
                <a-flex gap="12" vertical class="flex-1 w-[100%]">
                  <a
                    :href="`/product/${itemChil.slug}`"
                    class="text-[16px] font-bold max-w-[100%] hover:bg-[#F3F4F6] hover:text-[#02B6AC] cursor-pointer text-ellipsis overflow-hidden whitespace-nowrap"
                  >
                    {{ itemChil.name ? itemChil.name : "Chưa có tên" }}
                  </a>
                  <span class="text-[16px] font-bold text-[#02B6AC]">
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
                      class="flex-1 font-sans border-[1px] border-[#4fa8e7] px-[12px] py-[10px] rounded-full text-white bg-[#02b6ac] hover:bg-[linear-gradient(270deg,_#ccf7fb_2.05%,_#fff_100%)] hover:text-[#424242]"
                      @click="handleProductDetail(itemChil.slug)"
                    >
                      Chi tiết
                    </button>
                  </a-flex>
                </a-flex>
              </a-flex>
            </a-flex>
          </a-flex>
        </a-flex>
      </a-flex>
      <a-flex v-else class="flex-1 justify-center mt-[-40px]"
        >Không có dữ liệu để hiển thị</a-flex
      >
    </a-flex>
  </a-flex>
</template>

<script setup>
import { onMounted, ref, defineProps, onUnmounted, computed } from "vue";
import { useRouter } from "vue-router";
import "./ProductComponent.css";
import { getDataFromIndexedDB } from "@/store/indexedDB";
import store from "@/store/store";

const router = useRouter();
const dataChil = ref([]);
const categoryChil = ref([]);
const productData = ref([]);
const props = defineProps({
  categorySlug: [String, String],
});
const nameCategory = ref("");
const pathImg = ref("");

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const fetchData = async () => {
  try {
    const [allCategoryData, allProductData] = await Promise.all([
      getDataFromIndexedDB("category"),
      getDataFromIndexedDB("products"),
    ]);

    const parentCategory = allCategoryData.find(
      (item) => item.slug === props.categorySlug
    );
    if (!parentCategory) return;

    nameCategory.value = parentCategory.name || "";

    categoryChil.value = allCategoryData.filter(
      (item) => item.parent_id === parentCategory.id
    );

    const categoryIds = [
      parentCategory.id,
      ...categoryChil.value.map((item) => item.id),
    ];

    productData.value = allProductData.filter((product) =>
      categoryIds.includes(product.category_id)
    );

    if (categoryChil.value.length > 0) {
      fillterData(categoryChil.value[0].id);
    } else {
      fillterData(parentCategory.id);
    }
  } catch (error) {
    console.error("Lỗi khi lấy dữ liệu:", error);
  }
};

const fillterData = (id) => {
  dataChil.value = productData.value.filter(
    (product) => Number(product.category_id) === Number(id)
  );
};

const changeData = (id) => {
  fillterData(id);
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

const handleProductDetail = (items) => {
  router.push(`/product/${items}`);
};

onMounted(() => fetchData());

const screenWidth = ref(window.innerWidth);
const maxItems = computed(() => {
  if (screenWidth.value < 720) return 1;
  if (screenWidth.value < 992) return 2;
  if (screenWidth.value < 1200) return 3;
  if (screenWidth.value < 1400) return 4;
  return 4;
});

const updateScreenWidth = () => {
  screenWidth.value = window.innerWidth;
};

onMounted(() => {
  window.addEventListener("resize", updateScreenWidth);
});

onUnmounted(() => {
  window.removeEventListener("resize", updateScreenWidth);
});

const displayedItems = computed(() => dataChil.value.slice(0, maxItems.value));
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
.bestseller span::after {
  content: "";
  display: block;
  width: 0;
  height: 0;
  border-right: 10px solid #e3c849;
  border-bottom: 10px solid transparent;
  position: absolute;
  bottom: -10px;
  right: 0;
}

.bestseller span {
  text-align: center;
  font-size: 14px;
  line-height: 13px;
  font-weight: 700;
  min-height: 38px;
  justify-content: center;
  display: flex;
  padding: 7px 10px;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
  position: relative;
}
:deep(.ant-tabs-nav .ant-tabs-tab-btn::first-letter) {
  text-transform: uppercase;
}
@media only screen and (max-width: 480px) {
  .content {
    height: auto;
    padding-inline: 0;
  }
}
</style>
