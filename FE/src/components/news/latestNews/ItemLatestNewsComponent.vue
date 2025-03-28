<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex vertical class="flex-1 justify-center items-center">
    <a-flex class="py-5 mb-1">
      <span class="text-[28px] text-[#2268DE] font-medium"> Tin mới nhất </span>
    </a-flex>
    <a-flex
      class="w-[1086px] justify-center mb-[20px] border-b-[1px] border-[#dee2e6] tabList"
    >
      <a-tabs v-model:activeKey="activeKey" @change="changeData" type="card">
        <a-tab-pane v-for="item in dataTab" :key="item.slug" :tab="item.name" />
      </a-tabs>
    </a-flex>
    <a-flex vertical class="w-full justify-center items-center gap-[50px]">
      <a-flex class="flex-1 flex-wrap justify-center gap-[20px]">
        <a-flex
          v-for="item in paginatedData"
          :key="item.id"
          class="w-[100%] max-w-[350px]"
        >
          <a-flex vertical class="w-full">
            <img
              :src="item.image_url || defaultImage"
              class="w-[100%] h-[270px] object-cover"
            />
            <a-flex vertical class="w-[100%] p-[15px] gap-[10px] bg-[#ededed]">
              <a-flex class="text-[#6d6d6d] items-center gap-[3px] h-[20px]">
                <AkClock />
                {{
                  formatDate(item.published_at)
                    ? formatDate(item.published_at)
                    : "No data"
                }}
              </a-flex>

              <span
                class="contentHtmlSpan2Text text-black font-normal leading-[24px] min-h-[51.5px]"
              >
                {{ item.title }}
              </span>
              <span
                class="contentHtmlSpan3Text text-[16px] text-black font-normal h-[100%] min-h-[72px]"
              >
                {{ truncateText(item.summary) }}
              </span>
              <a
                :href="`/detailNews/${item.slug}`"
                class="text-[#0d6efd] hover:bg-[#ededed] hover:text-[#2268DE]"
              >
                Đọc tiếp</a
              >
            </a-flex>
          </a-flex>
        </a-flex>
      </a-flex>

      <a-pagination
        v-model:current="currentPage"
        :total="data.length"
        :page-size="pageSize"
        @change="onPageChange"
        show-less-items
      />
    </a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>
<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { ref, computed, onMounted } from "vue";
import { AkClock } from "@kalimahapps/vue-icons";

const data = ref([]);
const currentPage = ref(1);
const pageSize = 6;
const defaultImage =
  "https://livotec.com/wp-content/uploads/2024/11/Binh-tam-nong-lanh-%E2%80%93-Giai-phap-hoan-hao-cho-ngoi-nha-hien-dai.jpg";

const slugsToFilter = ["boc-phot", "tin-tuc"];
const activeKey = ref(slugsToFilter[0]);

const fetchData = async (value) => {
  if (!value || value == undefined) {
    value = activeKey.value;
  }
  console.log(value);
  
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_POST}/hotNews/${value}`
    );
    data.value = response.data.data;
    console.log(data.value);
    
  } catch (e) {
    console.log(e);
  }
};

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  const end = start + pageSize;
  return data.value.slice(start, end);
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

const onPageChange = (page) => {
  currentPage.value = page;
};

onMounted(() => {
  fetchDataTabItem();
  fetchData();
});

const dataTab = ref([]);

const changeData = async (slug) => {
  activeKey.value = slug;
  await fetchData(slug);
  return (currentPage.value = 1);
};

const fetchDataTabItem = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_POST}/allPostCategory`
    );
    dataTab.value = response.data.data;
    filterData(dataTab.value);
  } catch (e) {
    console.log(e);
  }
};

const categoryOrder = {
  "kien-thuc": 1,
  "tin-tuc": 2,
};

const filterData = (data) => {
  dataTab.value = data
    .filter((item) => slugsToFilter.includes(item.slug))
    .map((item) => ({
      ...item,
      order: categoryOrder[item.slug] || 0,
    }))
    .sort((a, b) => a.order - b.order);
  if (dataTab.value.length <= 0) {
    console.log("Không có data");
  }
};
</script>
<style scoped>
.contentHtmlSpan2Text {
  font-size: calc(1.275rem + 0.3vw);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  max-width: 300px;
}

.contentHtmlSpan3Text {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  max-width: 300px;
}
:deep(.tabList .ant-tabs-nav) {
  margin: 0;
}

:deep(.tabList .ant-tabs-tab) {
  border: 0;
  color: black !important;
  background-color: white;
}

:deep(.tabList .ant-tabs-tab-active) {
  background-color: #2268DE;
  transition: all 0.5s ease-in-out;
}

:deep(.tabList .ant-tabs-tab-active .ant-tabs-tab-btn) {
  color: white !important;
}
</style>
