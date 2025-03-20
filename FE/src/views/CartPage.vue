<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div class="w-full flex flex-1 flex-col">
    <div class="w-full border-b-[1px] border-gray-300 py-3 mb-4">
      <span class="text-[#2268DE] font-bold">Giỏ hàng</span>
    </div>
    <div class="w-full flex items-center">
      <h1 class="text-[#2268DE] font-bold text-3xl">Giỏ hàng</h1>
    </div>
    <div class="product-comparison p-[10px] overflow-x-auto" v-if="haveData">
      <table>
        <thead>
          <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in specs" :key="index">
            <td>
              <a-flex class="items-center">
                <button
                  class="text-center text-2xl text-red-500 rounded-full p-[2px] hover:text-white hover:bg-red-500"
                  @click="deleteItem(item.id)"
                >
                  <CgClose class="text-[18px]" />
                </button>
              </a-flex>
            </td>
            <!-- <td>{{ item.img }}</td> -->
            <td>
              <a-flex class="">
                <img
                  src="https://livotec.com/wp-content/uploads/2024/11/Bep-tu-don-Livotec-E-smart-LIS-646-1-300x300.png"
                  class="w-[32px]"
                />
              </a-flex>
            </td>
            <td>
              <router-link
                :to="`/product/${item.slug}`"
                class="hover:bg-white text-[#0d6efd] capitalize"
              >
                {{ item.name }}
              </router-link>
            </td>
            <td>
              <span class="text-[16px] text-[#02b6ac] font-bold">
                {{ formatPrice(item.price) }}
              </span>
            </td>
            <td>
              <a-input-number
                v-model:value="item.quantity"
                @change="handleChangeQuantity()"
                :min="0"
                :max="item.stock"
                class="border-[#666] hover:border-[#666] rounded-none w-[60px] text-center"
              />
            </td>
            <td>
              <span class="text-[16px] text-[#02b6ac] font-bold">
                {{ formatPrice(item.price * item.quantity) }}
              </span>
            </td>
          </tr>
          <tr>
            <td class="text-right" colspan="6">
              <div class="float-left">
                <input
                  placeholder="Coupon code"
                  class="border-[1px] border-black mr-1 px-[6px] py-[5px] w-[80px]"
                />
                <button
                  class="text-nowrap rounded-[3px] font-bold text-[#515151] p-[6px] bg-[#e9e6ed]"
                >
                  Apply coupon
                </button>
              </div>
              <a-button
                :class="[
                  'rounded-[3px] flex float-right h-[100%] p-[6px] bg-[#e9e6ed] text-[#515151]',
                  changeQuantity ? '' : 'buttonUpdateCart',
                ]"
                @click="handleUpdateCart()"
                :disabled="changeQuantity"
              >
                <span class="font-bold"> Update cart </span>
              </a-button>
            </td>
          </tr>
        </tbody>
      </table>
      <div
        class="bg-[#3fb696] p-4 text-white text-center flex-1 cursor-pointer mt-[50px]"
        @click="handlePayment"
      >
        <span class="text-lg font-bold">Proceed to checkout</span>
      </div>
    </div>
    <a-flex v-else vertical class="my-[20px]" gap="3">
      <span class="font-semibold">Your cart is currently empty.</span>
      <p>
        <button
          @click="handleHome"
          class="justify-start flex bg-[#DCD7E3] px-4 py-[0.618em] rounded-[3px] font-bold text-[#515151]"
        >
          Return to shop
        </button>
      </p>
    </a-flex>
  </div>
</template>

<script setup>
import DefaultLayout from "./layouts/DefaultLayout.vue";
import { ref, onMounted, watch } from "vue";
import store from "@/store/store";
import { CgClose } from "@kalimahapps/vue-icons";
import { routeLocationKey, useRouter } from "vue-router";
import {
  deleteItemFromIndexedDB,
  getDataFromIndexedDB,
} from "@/store/indexedDB";

const router = useRouter();
const specs = ref([]);
const haveData = ref(false);
const changeQuantity = ref(true);

onMounted(() => {
  fetchData();
});

const fetchData = async () => {
  const dataStore = JSON.parse(
    JSON.stringify(store.getters["product/getDataStoreCart"])
  );
  const dataProduct = await getDataFromIndexedDB("products");

  const dataCart = dataProduct.filter((item) =>
    dataStore.some((cartItem) => cartItem.id === item.id)
  );
  if (dataCart && dataCart.length > 0) {
    specs.value = dataCart.map((item) => {
      const cartItem = dataStore.find((cart) => cart.id === item.id);
      return { ...item, quantity: cartItem ? cartItem.quantity : 1 };
    });
    haveData.value = true;
  } else {
    haveData.value = false;
  }
};
watch(
  () => store.getters["product/getDataStoreCart"],
  (newVal, oldVal) => {
    fetchData();
  },
  { deep: true }
);

const formatPrice = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const deleteItem = async (itemId) => {
  await deleteItemFromIndexedDB("cart", itemId);

  let updatedCart = store.getters["product/getDataStoreCart"].filter(
    (item) => item.id !== itemId
  );

  store.commit("product/setDataStoreCart", { dataStoreCart: updatedCart });

  await fetchData();
};

const handlePayment = () => {
  router.push("/payment");
};

const handleUpdateCart = () => {
  store.commit("product/setDataStoreCart", {
    dataStoreCart: specs.value,
  });
  changeQuantity.value = true;
  const dataWithNoQuantity = specs.value.filter((item) => item.quantity === 0);

  if (dataWithNoQuantity && dataWithNoQuantity.length > 0) {
    if (
      confirm(
        "Bạn chắc chắn muốn xóa các sản phẩm không mong muốn khỏi giỏ hảng?"
      )
    ) {
      dataWithNoQuantity.forEach((item) => {
        deleteItem(item.id);
      });
    } else {
      return;
    }
  }
};

const handleChangeQuantity = () => {
  changeQuantity.value = false;
};

const handleHome = () => {
  router.push("/");
};
</script>

<style scoped>
table {
  width: 100%;
  border: 1px solid #e5e5e5;
}

table thead th {
  text-align: start;
  border-bottom: 1px solid #e5e5e5;
  font-weight: 700;
  line-height: 1.5rem;
}
table th,
table td {
  min-width: 30px;
  padding: 0 10px;
}

table th {
  padding-block: 12px;
}
table tr td {
  padding-block: 12px;
  border-bottom: 1px solid #e5e5e5;
}
table tbody tr td {
  margin-inline: 10px;
}

.buttonUpdateCart:hover {
  background-color: #dcd7e3;
  text-decoration: none;
  background-image: none;
  color: #515151;
  border: 1px solid #d9d9d9;
}
</style>
