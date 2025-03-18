<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex class="flex-1 a" vertical>
    <a-flex
      class="flex-1 justify-between pt-[50px] pb-[30px] gap-[24px] border-b-[1px] border-[#DCDFE8]"
    >
      <a-flex class="flex-2 justify-center items-center">
        <img
          src="https://livotec.com/wp-content/uploads/2024/03/livotec.png"
          class="h-[50px]"
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
          <h5 class="text-[16px] font-[600]">CÔNG TY TNHH LIVOTEC</h5>
          <a-flex class="flex gap-[10px]" vertical>
            <ul class="flex flex-col gap-[10px]">
              <li>
                Tầng 7, số 113 - 115 Đường Lê Duẩn, Phường Cửa Nam, Quận Hoàn
                Kiếm, thành phố Hà Nội
              </li>
              <li>
                Email:
                <span class="hover:text-[#02b6ac] cursor-pointer">
                  livotecvn@gmail.com
                </span>
              </li>
              <li>
                Điện thoại:
                <span class="hover:text-[#02b6ac] cursor-pointer">
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
                <span class="hover:text-[#02b6ac] cursor-pointer">
                  {{ data.name }}
                </span>
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

const dataFooter = ref([
  {
    id: 1,
    topic: "Danh mục",
    data: [
      {
        id: 1,
        name: "Máy lọc nước",
      },
      {
        id: 2,
        name: "Bếp từ",
      },
    ],
  },
  {
    id: 2,
    topic: "Tiện ích",
    data: [
      {
        id: 1,
        name: "Giới thiệu",
      },
      {
        id: 2,
        name: "Tin tức",
      },
      {
        id: 3,
        name: "Liên hệ",
      },
      {
        id: 4,
        name: "Hệ thống cửa hàng Livotec",
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
