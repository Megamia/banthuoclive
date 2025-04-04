<template>
  <div class="w-full mb-[3rem]">
    <div class="w-full border-b-[1px] border-gray-300 py-3">
      <span class="text-[#2268DE] font-bold">Thanh toán</span>
    </div>
    <div>
      <h1 class="text-3xl text-[#2268DE] font-bold my-3">Thanh toán</h1>
      <p class="font-medium">Cảm ơn bạn. Đơn hàng của bạn đã được nhận.</p>
      <div v-if="IsorderCode" class="flex flex-col gap-4">
        <div class="flex flex-col gap-5 md:flex-row items-center my-4 mx-8">
          <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
            <span class="text-[10px] font-bold">SỐ ĐẶT HÀNG:</span>
            <span class="font-bold">{{ OrderData.id }}</span>
          </div>
          <div class="separator hidden md:flex"></div>
          <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
            <span class="text-[10px] font-bold">MÃ ĐƠN HÀNG GHN:</span>
            <span class="font-bold">{{ OrderData.ghn_order_code }}</span>
          </div>
          <div class="separator hidden md:flex"></div>
          <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
            <span class="text-[10px] font-bold">NGÀY:</span>
            <span class="font-bold">{{
              formatDate(OrderData.created_at)
            }}</span>
          </div>
          <div class="separator hidden md:flex"></div>
          <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
            <span class="text-[10px] font-bold">TOTAL:</span>
            <span class="font-bold text-[#2268DE]">{{
              formatCurrency(OrderData.price)
            }}</span>
          </div>
          <div class="separator hidden md:flex"></div>
          <div class="flex flex-col items-center md:items-start">
            <span class="text-[10px] font-bold"> PHƯƠNG THỨC THANH TOÁN:</span>
            <span class="font-bold"
              >{{
                OrderData.property?.paymenttype == 1
                  ? "Chuyển khoản ngân hàng"
                  : "Tiền mặt"
              }}
            </span>
          </div>
        </div>
        <h2 class="text-2xl font-medium">Chi tiết ngân hàng của chúng tôi</h2>
        <div class="mb-4">
          <p class="font-bold">{{ bankUsername }}:</p>
          <div class="flex flex-col md:flex-row items-center my-1 mx-8 gap-5">
            <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
              <span class="text-[10px] font-bold">Ngân hàng:</span>
              <span class="font-bold">{{ bankName }}</span>
            </div>
            <div class="separator hidden md:flex"></div>
            <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
              <span class="text-[10px] font-bold uppercase">
                Số tài khoản:</span
              >
              <span class="font-bold">{{ bankNumber }}</span>
            </div>
          </div>
        </div>
        <h2 class="text-2xl font-medium">Chi tiết đặt hàng</h2>
        <a-flex class="w-full mb-[20px]"
          ><a-table
            :columns="columns"
            :data-source="data"
            :pagination="false"
            bordered
            class="w-full"
          >
            <template #bodyCell="{ column, record }">
              <template v-if="column.key === 'name'">
                <a-flex gap="5" class="items-center">
                  <a
                    class="a-product"
                    :href="`/product/${record.product.slug}`"
                    >{{ record.product.name }}</a
                  ><AkXSmall />
                  <span class="font-bold">{{ record.quantity }}</span>
                </a-flex>
              </template>
              <template v-else-if="column.key === 'subtotal'">
                <span class="text-[#2268DE] text-[16px] font-bold">{{
                  formatCurrency(record.price)
                }}</span>
              </template>
            </template>
            <template #summary>
              <a-table-summary-row>
                <a-table-summary-cell
                  ><span class="font-semibold"
                    >Tổng:</span
                  ></a-table-summary-cell
                >
                <a-table-summary-cell>
                  <span class="text-[#2268DE] text-[16px] font-bold">{{
                    formatCurrency(OrderData.price)
                  }}</span>
                </a-table-summary-cell>
              </a-table-summary-row>
              <a-table-summary-row>
                <a-table-summary-cell
                  ><span class="font-semibold"
                    >Vận chuyển:</span
                  ></a-table-summary-cell
                >
                <a-table-summary-cell>
                  <span class="font-semibold">Miễn phí vận chuyển</span>
                </a-table-summary-cell>
              </a-table-summary-row>
              <a-table-summary-row>
                <a-table-summary-cell
                  ><span class="font-semibold"
                    >Phương thức thanh toán:</span
                  ></a-table-summary-cell
                >
                <a-table-summary-cell>
                  <span class="font-semibold">{{
                    OrderData.property?.paymenttype == 1
                      ? "Chuyển khoản ngân hàng"
                      : "Tiền mặt"
                  }}</span>
                </a-table-summary-cell>
              </a-table-summary-row>
              <a-table-summary-row>
                <a-table-summary-cell
                  ><span class="font-semibold"
                    >Tổng:</span
                  ></a-table-summary-cell
                >
                <a-table-summary-cell>
                  <span class="text-[#2268DE] text-[16px] font-bold">{{
                    formatCurrency(OrderData.price)
                  }}</span>
                </a-table-summary-cell>
              </a-table-summary-row>
            </template>
          </a-table>
        </a-flex>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { AkXSmall } from "@kalimahapps/vue-icons";
import axios from "axios";
const bankName = ref(import.meta.env.VITE_BANK_NAME);
const bankNumber = ref(import.meta.env.VITE_BANK_NUMBER);
const bankUsername = ref(import.meta.env.VITE_BANK_USERNAME);
const OrderData = ref([]);
const data = ref([]);
const route = useRoute();
const IsorderCode = ref(false);

const columns = ref([
  {
    key: "name",
    title: "Sản phẩm",
    dataIndex: "name",
  },
  {
    key: "subtotal",
    title: "Tổng",
    dataIndex: "subtotal",
  },
]);

const fetchData = async () => {
  try {
    const order_code = route.params.slug;
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_ORDER}/order/${order_code}`
    );
    if (response.data) {
      IsorderCode.value = true;
      OrderData.value = response.data;
      data.value = response.data.orderdetail;
    }
  } catch (error) {
    console.error("Error fetching order:", error);
  }
};

onMounted(() => {
  fetchData();
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString("vi-VN", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};
</script>

<style scoped>
.separator {
  width: 1px;
  height: 32px;
  border-left: 1px dashed gray;
}
.a-product {
  color: #2268de;
  font-weight: bold;
}
.a-product:hover {
  color: #0024d9;
  font-weight: bold;
  background-color: transparent;
}
</style>
