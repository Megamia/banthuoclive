<template>
  <a-flex class="py-[30px]">
    <a-flex> </a-flex>
    <a-flex class="gap-[30px]">
      <a-flex vertical class="flex-1 pl-[125px] gap-[10px]">
        <a-flex vertical class="border-b-[1px] border-[#ededed]">
          <a-flex>
            <span
              class="text-[31px] text-[#02b6ac] mb-[10px] font-semibold leading-[35px]"
            >
              {{ post.title }}
            </span>
          </a-flex>
          <a-flex class="gap-[10px] mb-[15px]">
            <a-flex class="mr-[20px]">
              <span class="text-[15px] text-[#776677]">
                Tác giả: {{ post.user?.first_name }}
              </span>
            </a-flex>
            <a-flex class="gap-[5px] mr-[20px] text-[15px] text-[#776677]">
              Chuyên mục:
              <span
                v-for="item in post.categories"
                :key="item.id"
                class="text-[#0d6efd]"
              >
                {{ item.name }},
              </span>
            </a-flex>
            <a-flex>
              <span class="text-[15px] text-[#776677]">
                Ngày đăng: {{ formatDate(post.published_at) }}
              </span>
            </a-flex>
          </a-flex>
        </a-flex>
        <div v-html="post.content_html" class="a"></div>
      </a-flex>
      <a-flex class="flex-2 justify-end w-[500px] pr-[125px] h-[100vh]">
        <img
          src="https://livotec.com/wp-content/uploads/2024/05/Bao-hanh_livotec-Mobile.jpg.webp"
        />
      </a-flex>
    </a-flex>
  </a-flex>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import dayjs from "dayjs";

const post = ref("");
const route = useRoute();
const slug = route.params.slug;
onMounted(() => {
  fetchData();
});
const fetchData = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_POST}/post/${slug}`
    );
    post.value = response.data;
  } catch (e) {
    console.log(e);
  }
};

const formatDate = (date) => {
  return date ? dayjs(date).format("DD/MM/YYYY") : "";
};
</script>

<style scoped>
.a::v-deep(a) {
  color: #33cccc;
  font-size: 12pt;
}
.a::v-deep(a:hover) {
  background-color: white;
}
.a::v-deep(p) {
  color: black;
  font-size: 12pt;
}
.a::v-deep(strong em) {
  display: flex;
  color: black;
  font-size: 16px;
  font-weight: 500;
  line-height: 26px;
  margin-bottom: 20px;
}
</style>
