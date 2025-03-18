<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-drawer
    v-model:open="open"
    root-class-name="root-class-name"
    placement="top"
    :closable="false"
    :maskClosable="false"
    class="justify-center flex min-h-[440px]"
  >
    <a-flex class="items-center flex-1 gap-[10px]">
      <a-flex
        class="flex-1 border-b border-[#ebebeb] focus-within:border-[#02b6ac]"
      >
        <a-input
          placeholder="Chúng tôi có thể giúp bạn tìm kiếm?"
          :bordered="false"
          class="px-0 py-[12px]"
          v-model:value="searchInput"
        >
          <template #suffix>
            <BxSearch
              class="cursor-pointer text-[24px] text-black hover:text-[#02b6ac]"
            />
          </template>
        </a-input>
      </a-flex>
      <ClCloseLg
        @click="closeSearch"
        class="cursor-pointer text-[20px] hover:text-[#02b6ac]"
      />
    </a-flex>

    <template v-if="!searchInput.trim()">
      <a-flex v-if="dataQuickFind.length" vertical>
        <a-flex class="pt-[10px] pl-[5px]" vertical>
          <h5 class="text-[1.25rem] font-medium">Tìm kiếm nhanh</h5>
          <ul class="flex flex-col gap-[10px] py-[10px]">
            <li
              v-for="item in dataQuickFind"
              :key="item.id"
              class="text-black cursor-pointer"
            >
              <a
                :href="`/product/${item.slug}`"
                class="hover:bg-white hover:text-black"
              >
                {{ item.name }}
              </a>
            </li>
          </ul>
        </a-flex>
      </a-flex>

      <a-flex v-if="dataMostFind.length" vertical>
        <a-flex class="pt-[10px] pl-[5px]" vertical>
          <h5 class="text-[1.25rem] font-medium">Từ khóa tìm kiếm nhiều</h5>
          <ul class="flex flex-col gap-[10px] py-[10px]">
            <li
              v-for="item in dataMostFind"
              :key="item.id"
              class="text-black cursor-pointer"
            >
              <a
                :href="`/product/${item.slug}`"
                class="hover:bg-white hover:text-black"
              >
                {{ item.name }}
              </a>
            </li>
          </ul>
        </a-flex>
      </a-flex>
    </template>

    <a-flex v-else vertical>
      <a-flex class="pt-[10px] pl-[5px]" vertical>
        <h5 class="text-[1.25rem] font-medium">Kết quả tìm kiếm</h5>
        <ul class="flex flex-col gap-[10px] py-[10px]">
          <li
            v-for="item in filteredData"
            :key="item.id"
            class="text-black cursor-pointer"
          >
            <a
              :href="`/product/${item.slug}`"
              class="hover:bg-white hover:text-black"
            >
              {{ item.name }}
            </a>
          </li>
        </ul>
      </a-flex>
    </a-flex>
  </a-drawer>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import { getDataFromIndexedDB } from "@/store/indexedDB";
import { ClCloseLg, BxSearch } from "@kalimahapps/vue-icons";

const emit = defineEmits(["closeSearch"]);
const open = ref(true);
const searchInput = ref("");
const dataQuickFind = ref([]);
const dataMostFind = ref([]);

const fetchData = async () => {
  try {
    const dataProduct = await getDataFromIndexedDB("products");

    if (Array.isArray(dataProduct) && dataProduct.length > 0) {
      dataQuickFind.value = dataProduct.slice(0, 4);

      dataMostFind.value = [...dataProduct].sort(
        (a, b) => b.sold_out - a.sold_out
      );
    } else {
      console.warn("Không có dữ liệu hợp lệ từ IndexedDB.");
    }
  } catch (e) {
    console.error("Lỗi khi lấy dữ liệu từ IndexedDB: ", e);
  }
};

const filteredData = computed(() => {
  const search = searchInput.value.trim().toLowerCase();

  if (!search) return [];

  return dataMostFind.value.filter((item) =>
    item.name?.toLowerCase().includes(search)
  );
});
const closeSearch = () => {
  open.value = false;
  setTimeout(() => {
    emit("closeSearch");
  }, 500);
};
onMounted(fetchData);
</script>
