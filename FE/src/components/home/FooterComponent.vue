<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex class="flex-1 a" vertical>
    <a-flex
      class="flex-1 justify-between pt-[50px] pb-[30px] gap-[24px] border-b-[1px] border-[#DCDFE8]"
    >
      <a-flex class="flex-2 justify-center items-center">
        <img
          src="/medical-drug-grade-manufacturing-on-blue-background-3d-rendering_9827483-removebg-preview-removebg-preview.png"
          class="h-[100px]"
        />
      </a-flex>
      <a-flex vertical class="flex-2 justify-end gap-[12px]">
        <!-- Cần UPDATE -->
        <a-form :model="formState" name="basic" layout="vertical">
          <a-form-item
            label="Đăng ký nhận bản tin"
            name="register"
            :rules="[
              {
                required: true,
                message: 'Vui lòng nhập dữ liệu cho trường này.',
              },
            ]"
          >
            <a-input-search
              v-model:value="formState.register"
              placeholder="Nhập văn bản tìm kiếm"
              enter-button="Gửi"
              size="large"
              @search="onSearch"
            />
          </a-form-item>
        </a-form>

        <a-flex class="border-[1px] border-[#ffb900] px-8 py-4" v-if="a">
          Có một hoặc nhiều mục nhập có lỗi. Vui lòng kiểm tra và thử lại.
        </a-flex>
        <!-- Cần UPDATE -->
      </a-flex>
    </a-flex>
    <a-flex class="flex-1 py-[35px] gap-6">
      <a-flex class="flex-1">
        <a-flex vertical gap="16">
          <h5 class="text-[16px] font-[600]">CÔNG TY TNHH LIVE</h5>
          <a-flex class="flex gap-[10px]" vertical>
            <ul class="flex flex-col gap-[10px]">
              <li>
                Tầng 7, số 113 - 115 Đường Lê Duẩn, Phường Cửa Nam, Quận Hoàn
                Kiếm, thành phố Hà Nội
              </li>
              <li>
                Email:
                <span class="hover:text-[#2268DE] cursor-pointer">
                  livevn@gmail.com
                </span>
              </li>
              <li>
                Điện thoại:
                <span class="hover:text-[#2268DE] cursor-pointer">
                  1800 2298</span
                >
              </li>
            </ul>
          </a-flex>
        </a-flex>
      </a-flex>
      <a-flex
        v-for="item in dataFooter"
        :key="item.id"
        vertical
        class="flex-3 justify-between"
      >
        <a-flex vertical gap="16">
          <h5 class="text-[16px] font-[600]">{{ item.topic }}</h5>
          <a-flex class="flex gap-[10px]" vertical>
            <ul v-for="data in item.data" :key="data.id">
              <li>
                <a
                  :href="
                    item.id == 1 ? `/category/${data.href}` : `/${data.href}`
                  "
                  class="hover:text-[#2268DE] cursor-pointer hover:bg-[#EAEBF4] p-0 text-black"
                >
                  {{ data.name }}
                </a>
              </li>
            </ul>
          </a-flex>
        </a-flex>
      </a-flex>
      <a-flex class="w-[45%]" vertical>
        <a-flex vertical gap="16">
          <h5 class="text-[16px] font-[600]">Hỗ trợ thanh toán</h5>
          <a-flex class="flex gap-[10px]" vertical>
            <a-flex class="flex-wrap">
              <img
                src="https://livotec.com/wp-content/themes/livotec/assets/app/images/icons/png/visa.png"
                class="hoverImg"
              />

              <img
                src="https://livotec.com/wp-content/themes/livotec/assets/app/images/icons/png/credit.png"
                class="hoverImg"
              />

              <img
                src="https://livotec.com/wp-content/themes/livotec/assets/app/images/icons/png/jcb.png"
                class="hoverImg"
              />

              <img
                src="https://livotec.com/wp-content/themes/livotec/assets/app/images/icons/png/vnpay.png"
                class="hoverImg"
              />
            </a-flex>
          </a-flex>
        </a-flex>
        <a-flex vertical gap="16">
          <h5 class="text-[16px] font-[600]">Đối tác vận chuyển</h5>
          <a-flex class="flex gap-[10px]" vertical>
            <a-flex class="flex-wrap">
              <img
                src="https://livotec.com/wp-content/themes/livotec/assets/app/images/icons/png/viettelpost.jpg"
                class="w-[80px] hoverImg"
              />
            </a-flex>
          </a-flex>
        </a-flex>
      </a-flex>
    </a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { message } from "ant-design-vue";
import "./footer.css";
import { ref } from "vue";

const register = ref("");
const formState = ref({
  register: "",
});

const a = ref(false);

const onSearch = () => {
  if (register.value.trim() == "") {
    alert("Chưa nhập");
    return;
  } else {
    alert("Đã nhập: ", register.value);
  }
};
const toSlug = (name) => {
  return name
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/đ/g, "d")
    .replace(/[^a-z0-9\s-]/g, "")
    .replace(/\s+/g, "-")
    .replace(/-+/g, "-")
    .trim();
};
const dataFooter = ref([
  {
    id: 1,
    topic: "Danh mục",
    data: [
      {
        id: 1,
        name: "Thực phẩm chức năng",
        href: `${toSlug("Thực phẩm chức năng")}`,
      },
      {
        id: 2,
        name: "Thiết bị y tế",
        href: `${toSlug("Thiết bị y tế")}`,
      },
      {
        id: 3,
        name: "Dược mỹ phẩm",
        href: `${toSlug("Dược mỹ phẩm")}`,
      },
    ],
  },
  {
    id: 2,
    topic: "Tiện ích",
    data: [
      {
        id: 1,
        name: "Tin tức",
        href: "news",
      },
      {
        id: 2,
        name: "Giới thiệu",
        href: "about",
      },
      {
        id: 3,
        name: "Bảo hành",
        href: "guaranteeHome",
      },
      {
        id: 4,
        name: "Thư viện",
      },
    ],
  },
]);
</script>

<style scoped>
.hoverImg:hover {
  transform: translateY(-4px);
  animation-duration: 200ms;
  transition: 0.15s ease;
}
</style>
