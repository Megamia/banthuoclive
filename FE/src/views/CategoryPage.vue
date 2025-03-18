<template>
  <!-- eslint-disable vue/no-v-model-argument -->

  <section class="w-full flex flex-col gap-[30px]">
    <CategorySlideComponent :path="category?.image?.path" />
    <a-flex vertical class="w-full">
      <a-flex
        class="mb-[1rem] border-t-[1px] border-b-[1px] border-[#dbe0f0] bg-[#f6f9ff] gap-[24px] w-full"
      >
        <a-flex class="p-[18px] px-[15px] border-b border-b-[#02b6ac]">
          <h1 class="text-[16px] font-bold text-[#02b6ac] capitalize">
            {{ category[0]?.name }}
          </h1>
        </a-flex>
      </a-flex>
      <a-form></a-form>
      <a-flex
        v-if="Object.keys(selectedFilter).length > 0"
        class="flex-wrap gap-2"
      >
        <a-flex
          v-for="(item, filterId) in selectedFilter"
          :key="filterId"
          class="p-1 px-3 bg-[#FDC400] rounded-md mb-[15px]"
        >
          <span class="font-normal">{{ item[0] }}</span>
        </a-flex>
      </a-flex>
      <a-flex class="mb-4">
        <section>
          <a-flex wrap="wrap" gap="small">
            <a-badge
              :count="Object.keys(selectedFilter).length"
              :offset="[-15, 0]"
            >
              <a-flex class="gap-1 items-center sort_item"
                ><PhLightFunnel class="text-[18px] font-medium" /><span
                  class="text-[14px]"
                  >Bộ lọc</span
                ></a-flex
              >
            </a-badge>
            <div v-for="filter in data" :key="filter.id">
              <a-dropdown :trigger="['click']" arrow>
                <a
                  @click.prevent
                  type="primary"
                  class="gap-1 items-center sort_item flex"
                >
                  {{ filter.name }}<AkChevronDownSmall />
                </a>
                <template #overlay>
                  <a-flex vertical class="gap-2 p-0">
                    <a-menu>
                      <a-flex class="flex-wrap gap-2 max-w-[400px]">
                        <div
                          v-for="(item, index) in filter.options"
                          :key="index"
                        >
                          <a-menu-item
                            v-if="filter.type === 'radiobutton'"
                            :key="item.label"
                            @click="selectOption(filter.name, item.label)"
                            class="border"
                          >
                            {{ item.label }}
                          </a-menu-item>
                          <a-menu-item
                            v-if="filter.type === 'range'"
                            :key="item.label"
                            @click="
                              rangeOption(
                                filter.name,
                                item.label,
                                item.min,
                                item.max
                              )
                            "
                            class="border"
                          >
                            {{ item.label }}
                          </a-menu-item>
                        </div>
                      </a-flex>
                    </a-menu>
                    <a-flex
                      class="w-full gap-3 py-2 px-6 bg-white z-10 justify-center"
                      ><a-button
                        class="text-red-500 border-red-500 px-8"
                        @click="clearOption(filter.name)"
                      >
                        <span class="font-bold">Bỏ chọn</span> </a-button
                      ><a-button
                        class="text-white px-5 bg-[#02b6ac]"
                        @click="applyFilter"
                      >
                        <span class="font-bold">Xem kết quả</span>
                      </a-button></a-flex
                    >
                  </a-flex>
                </template>
              </a-dropdown>
            </div>
            <div key="price">
              <a-dropdown :trigger="['click']" arrow>
                <a
                  @click.prevent
                  type="primary"
                  class="gap-2 items-center sort_item flex"
                >
                  <CdTag class="text-[17px]" />Giá
                </a>
                <template #overlay>
                  <a-flex vertical class="gap-2 p-0">
                    <a-menu>
                      <a-flex class="flex-wrap gap-2 max-w-[335px]">
                        <a-menu-item
                          v-for="item in price"
                          :key="item.label"
                          @click="
                            rangeOption('price', item.label, item.min, item.max)
                          "
                          class="border"
                        >
                          {{ item.label }}
                        </a-menu-item>
                      </a-flex>
                    </a-menu>
                    <a-flex
                      class="w-full gap-3 py-2 px-6 bg-white z-10 justify-center"
                      ><a-button
                        class="text-red-500 border-red-500 px-8"
                        @click="clearOption('price')"
                      >
                        <span class="font-bold">Bỏ chọn</span> </a-button
                      ><a-button
                        class="text-white px-5 bg-[#02b6ac]"
                        @click="applyFilter"
                      >
                        <span class="font-bold">Xem kết quả</span>
                      </a-button></a-flex
                    >
                  </a-flex>
                </template>
              </a-dropdown>
            </div>
          </a-flex>
        </section>
      </a-flex>
      <a-flex class="justify-between items-center mb-4">
        <a-flex
          ><span
            >Hiển thị tất cả {{ productCurrentData.length }} kết quả</span
          ></a-flex
        >
        <a-flex
          ><a-select
            ref="select"
            v-model:value="value1"
            style="width: 250px"
            :options="options1"
            @focus="focus"
            @change="handleChange"
          ></a-select>
        </a-flex>
      </a-flex>
      <a-flex horizontal class="flex-wrap gap-8 justify-center">
        <ProductItemComponent
          v-for="product in productCurrentData"
          :key="product.id"
          :product="product"
        />
        <!-- <ProductItemComponent
          v-for="product in productCurrentData"
          :key="product.id"
          :product="product"
        />
        <ProductItemComponent
          v-for="product in productCurrentData"
          :key="product.id"
          :product="product"
        />
        <ProductItemComponent
          v-for="product in productCurrentData"
          :key="product.id"
          :product="product"
        /> -->
      </a-flex>
    </a-flex>
  </section>

  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import CategorySlideComponent from "@/components/CategorySlideComponent.vue";
import ProductItemComponent from "@/components/ProductItemComponent.vue";
import { getDataFromIndexedDB } from "@/store/indexedDB";
import {
  PhLightFunnel,
  AkChevronDownSmall,
  CdTag,
} from "@kalimahapps/vue-icons";

import axios from "axios";
import { ref, onMounted, reactive, watch } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const category = ref("");
const selectedFilter = reactive({});
const productCurrentData = ref("");

const selectOption = (filterId, label) => {
  selectedFilter[filterId] = [label];
  // console.log(selectedFilter);
};

const rangeOption = (filterId, label, min, max) => {
  selectedFilter[filterId] = [label, min, max];
  // console.log(selectedFilter);
};

const clearOption = (filterId) => {
  delete selectedFilter[filterId];
  applyFilter();
};

const applyFilter = async () => {
  const dataProduct = await getDataFromIndexedDB("products");

  // Nếu không có danh mục nào được chọn, không lọc
  if (!category.value || category.value.length === 0) {
    return;
  }

  // Lọc sản phẩm theo danh mục đang chọn
  const categoryIds = category.value.map((cat) => cat.id);
  productCurrentData.value = dataProduct.filter((item) =>
    categoryIds.includes(item.category_id)
  );

  // Nếu không có bộ lọc nào, giữ nguyên danh sách sản phẩm
  if (Object.keys(selectedFilter).length === 0) {
    return;
  }

  // Áp dụng bộ lọc của danh mục hiện tại
  productCurrentData.value = productCurrentData.value.filter((product) => {
    return Object.keys(selectedFilter).every((filterId) => {
      const filterValue = selectedFilter[filterId];
      if (!filterValue) return true; // Nếu không có giá trị filter, giữ lại sản phẩm

      if (filterId === "price") {
        const productValue = product[filterId];
        const [label, min, max] = filterValue;

        const minVal = min ? parseInt(min) : Number.NEGATIVE_INFINITY;
        const maxVal = max ? parseInt(max) : Number.POSITIVE_INFINITY;

        return productValue >= minVal && productValue <= maxVal;
      } else {
        if (!Array.isArray(product.thongso)) return false;

        const thongsoItem = product.thongso.find(
          (item) => item.thuoc_tinh === filterId
        );
        if (!thongsoItem) return false;

        if (Array.isArray(filterValue) && filterValue.length === 3) {
          const [label, min, max] = filterValue;
          const productValue = parseInt(
            thongsoItem.gia_tri.replace(/[^0-9]/g, "") || 0
          );

          const minVal = min ? parseInt(min) : Number.NEGATIVE_INFINITY;
          const maxVal = max ? parseInt(max) : Number.POSITIVE_INFINITY;

          return productValue >= minVal && productValue <= maxVal;
        } else if (Array.isArray(filterValue) && filterValue.length === 1) {
          return thongsoItem.gia_tri === filterValue[0];
        }

        return true;
      }
    });
  });
};

// Dữ liệu bộ lọc chỉ lấy từ danh mục đang chọn
const data = ref([]);

onMounted(async () => {
  try {
    const { slug1, slug2 } = route.params;

    const dataProduct = await getDataFromIndexedDB("products");
    const dataCategory = await getDataFromIndexedDB("category");

    let selectedCategories = [];

    if (slug2) {
      selectedCategories = dataCategory.filter((item) => item.slug === slug2);
    } else {
      const parentCategory = dataCategory.find((item) => item.slug === slug1);
      if (parentCategory) {
        selectedCategories = [parentCategory];
        const childCategories = dataCategory.filter(
          (item) => item.parent_id === parentCategory.id
        );
        selectedCategories = selectedCategories.concat(childCategories);
      }
    }

    // Chỉ lấy bộ lọc của danh mục đang chọn
    category.value = selectedCategories;
    if (selectedCategories.length > 0) {
      data.value = selectedCategories[0].filters;
    }

    // Lọc sản phẩm thuộc danh mục hiện tại
    const categoryIds = selectedCategories.map((cat) => cat.id);
    productCurrentData.value = dataProduct.filter((item) =>
      categoryIds.includes(item.category_id)
    );
  } catch (error) {
    console.error("Error fetching category:", error);
  }
});

// Theo dõi sự thay đổi của danh mục để cập nhật bộ lọc
watch(category, (newCategory) => {
  if (newCategory.length > 0) {
    data.value = newCategory[0].filters; // Chỉ lấy bộ lọc của danh mục đầu tiên
  } else {
    data.value = [];
  }
});

// Theo dõi sự thay đổi của `selectedFilter` để cập nhật sản phẩm
watch(
  selectedFilter,
  () => {
    applyFilter();
  },
  { deep: true }
);

const price = ref([
  {
    label: "Dưới 5 triệu",
    min: "",
    max: "5000000",
  },
  {
    label: "Từ 5 - 7 triệu",
    min: "5000000",
    max: "7000000",
  },
  {
    label: "Từ 7 - 10 triệu",
    min: "7000000",
    max: "10000000",
  },
  {
    label: "Trên 10 triệu",
    min: "10000000",
    max: "",
  },
]);

const value1 = ref("default");
const options1 = ref([
  {
    value: "best-sale",
    label: "Bán chạy nhất",
  },
  {
    value: "best-rate",
    label: "Thứ tự theo điểm đánh giá",
  },
  {
    value: "default",
    label: "Thứ tự mặc định",
  },
  {
    value: "min-max",
    label: "Thứ tự theo giá: thấp đến cao",
  },
  {
    value: "max-min",
    label: "Thứ tự theo giá: cao đến thấp",
  },
]);

const focus = () => {
  console.log("focus");
};
const handleChange = (value) => {
  // console.log(`selected ${value}`);
  sortProducts(value); // Gọi hàm sắp xếp sản phẩm
};

const sortProducts = (order) => {
  switch (order) {
    case "min-max":
      productCurrentData.value.sort((a, b) => a.price - b.price);
      break;
    case "max-min":
      productCurrentData.value.sort((a, b) => b.price - a.price);
      break;
    case "best-sale":
      // Sắp xếp theo số lượng bán (giả sử có trường `sold_out`)
      productCurrentData.value.sort(
        (a, b) => (b.sold_out || 0) - (a.sold_out || 0)
      );

      break;
    case "best-rate":
      // Giả sử có trường `rating` cho điểm đánh giá
      productCurrentData.value.sort(
        (a, b) => (b.rating || 0) - (a.rating || 0)
      );
      break;
    default:
      // Sắp xếp mặc định (có thể sắp xếp theo `created_at` hoặc trả về danh sách gốc)
      productCurrentData.value.sort(
        (a, b) => new Date(a.created_at) - new Date(b.created_at)
      );
      break;
  }
};
</script>

<style scoped>
.sort_item {
  border-color: #e9e9e9;
  padding: 7px 16px;
  border-radius: 0.375rem /* 6px */;
  border-width: 1px;
}
.sort_item:hover {
  cursor: pointer;
  border-color: #02b6ac;
  color: #02b6ac;
  background: white;
}
</style>
