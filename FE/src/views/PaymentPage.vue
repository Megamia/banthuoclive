<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div class="w-full mb-[3rem]">
    <div class="w-full border-b-[1px] border-gray-300 py-3">
      <span class="text-[#2268DE] font-bold">Thanh toán</span>
    </div>
    <div>
      <h1 class="text-3xl text-[#2268DE] font-bold my-3">Thanh toán</h1>
      <div class="w-full">
        <div class="text-[14px] font-medium">
          Có phiếu giảm giá?
          <a class="text-blue-500 font-medium cursor-pointer hover:bg-white"
            >Nhấp vào đây để nhập mã của bạn</a
          >
        </div>
        <a-form
          ref="formRef"
          :model="formState"
          :rules="rules"
          layout="vertical"
        >
          <a-flex class="w-full gap-10 content">
            <a-flex vertical class="flex-wrap w-full flex-1">
              <h3 class="text-xl font-medium">Billing details</h3>
              <a-form-item ref="name" name="name" class="w-full">
                <template #label>
                  <span class="text-base">Họ và tên</span>
                </template>
                <a-input
                  v-model:value="formState.name"
                  placeholder="Nhập họ và tên"
                />
              </a-form-item>
              <a-flex class="w-full gap-8">
                <a-form-item ref="phone" name="phone" class="w-full">
                  <template #label>
                    <span class="text-base">Số điện thoại</span>
                  </template>
                  <a-input
                    v-model:value="formState.phone"
                    placeholder="Nhập số điện thoại"
                  />
                </a-form-item>
                <a-form-item ref="email" name="email" class="w-full">
                  <template #label>
                    <span class="text-base">Địa chỉ Email</span>
                  </template>
                  <a-input
                    v-model:value="formState.email"
                    placeholder="Nhập địa chỉ email"
                  />
                </a-form-item>
              </a-flex>
              <a-flex class="w-full gap-8">
                <a-form-item name="province" class="w-full">
                  <template #label>
                    <span class="text-base whitespace-nowrap"
                      >Tỉnh/Thành phố</span
                    >
                  </template>
                  <a-select
                    v-model:value="LocateState.province"
                    @change="onProvinceChange"
                    placeholder="Chọn Tỉnh/Thành phố"
                  >
                    <a-select-option
                      v-for="province in provinces"
                      :key="province.code"
                      :value="province.code"
                    >
                      {{ province.name }}
                    </a-select-option>
                  </a-select>
                </a-form-item>
                <a-form-item name="district" class="w-full">
                  <template #label>
                    <span class="text-base">Quận/Huyện</span>
                  </template>
                  <a-select
                    v-model:value="LocateState.district"
                    @change="onDistrictChange"
                    placeholder="Chọn Quận/Huyện"
                  >
                    <a-select-option
                      v-for="district in districts"
                      :key="district.code"
                      :value="district.code"
                    >
                      {{ district.name }}
                    </a-select-option>
                  </a-select>
                </a-form-item>
              </a-flex>
              <a-flex class="w-full gap-8">
                <a-form-item name="subdistrict" class="w-full">
                  <template #label>
                    <span class="text-base whitespace-nowrap"
                      >Xã/Phường/Thị trấn</span
                    >
                  </template>
                  <a-select
                    v-model:value="LocateState.subdistrict"
                    placeholder="Chọn Xã/Phường/Thị trấn"
                  >
                    <a-select-option
                      v-for="ward in wards"
                      :key="ward.code"
                      :value="ward.code"
                    >
                      {{ ward.name }}
                    </a-select-option>
                  </a-select>
                </a-form-item>
                <a-form-item ref="address" name="address" class="w-full">
                  <template #label>
                    <span class="text-base whitespace-nowrap"
                      >Địa chỉ đường</span
                    >
                  </template>
                  <a-input
                    v-model:value="formState.address"
                    placeholder="Nhập địa chỉ cụ thể"
                  />
                </a-form-item>
              </a-flex>
            </a-flex>
            <a-flex vertical class="w-full flex-1">
              <h3>
                <a-checkbox v-model:checked="formState.differentaddresschecked">
                  <span class="text-xl font-medium flex gap-2 text-center">
                    Gửi đến một địa chỉ khác?</span
                  >
                </a-checkbox>
              </h3>
              <a-flex v-if="formState.differentaddresschecked" vertical>
                <a-flex class="w-full gap-8">
                  <a-form-item ref="diffname" name="diffname" class="w-full">
                    <template #label>
                      <span class="text-base">Tên đầy đủ của người nhận</span>
                    </template>
                    <a-input
                      v-model:value="formState.diffname"
                      placeholder="Nhập họ và tên"
                    />
                  </a-form-item>
                  <a-form-item ref="diffphone" name="diffphone" class="w-full">
                    <template #label>
                      <span class="text-base"
                        >Số điện thoại người nhận (không bắt buộc)</span
                      >
                    </template>
                    <a-input
                      v-model:value="formState.diffphone"
                      placeholder="Nhập số điện thoại"
                    />
                  </a-form-item>
                </a-flex>
                <a-flex class="w-full gap-8">
                  <a-form-item name="diffprovince" class="w-full">
                    <template #label>
                      <span class="text-base whitespace-nowrap"
                        >Tỉnh/Thành phố</span
                      >
                    </template>
                    <a-select
                      v-model:value="LocateState.diffprovince"
                      @change="onProvinceChange"
                      placeholder="Chọn Tỉnh/Thành phố"
                    >
                      <a-select-option
                        v-for="province in diffprovinces"
                        :key="province.code"
                        :value="province.code"
                      >
                        {{ province.name }}
                      </a-select-option>
                    </a-select>
                  </a-form-item>
                  <a-form-item name="diffdistrict" class="w-full">
                    <template #label>
                      <span class="text-base">Quận/Huyện</span>
                    </template>
                    <a-select
                      v-model:value="LocateState.diffdistrict"
                      @change="onDistrictChange"
                      placeholder="Chọn Quận/Huyện"
                    >
                      <a-select-option
                        v-for="district in diffdistricts"
                        :key="district.code"
                        :value="district.code"
                      >
                        {{ district.name }}
                      </a-select-option>
                    </a-select>
                  </a-form-item>
                </a-flex>
                <a-flex class="w-full gap-8">
                  <a-form-item name="diffsubdistrict" class="w-full">
                    <template #label>
                      <span class="text-base whitespace-nowrap"
                        >Xã/Phường/Thị trấn</span
                      >
                    </template>
                    <a-select
                      v-model:value="LocateState.diffsubdistrict"
                      placeholder="Chọn Xã/Phường/Thị trấn"
                    >
                      <a-select-option
                        v-for="ward in diffwards"
                        :key="ward.code"
                        :value="ward.code"
                      >
                        {{ ward.name }}
                      </a-select-option>
                    </a-select>
                  </a-form-item>
                  <a-form-item
                    ref="diffaddress"
                    name="diffaddress"
                    class="w-full"
                  >
                    <template #label>
                      <span class="text-base whitespace-nowrap"
                        >Địa chỉ đường</span
                      >
                    </template>
                    <a-input
                      v-model:value="formState.diffaddress"
                      placeholder="Nhập địa chỉ cụ thể"
                    />
                  </a-form-item>
                </a-flex>
              </a-flex>
              <a-form-item name="notes">
                <template #label>
                  <span class="text-base">Ghi chú đơn hàng (tùy chọn)</span>
                </template>
                <a-textarea
                  v-model:value="formState.notes"
                  placeholder="Ghi chú về đơn hàng của bạn, ví dụ: ghi chú đặc biệt về việc giao hàng."
                />
              </a-form-item>
            </a-flex>
          </a-flex>
          <h3 class="text-black text-xl font-medium my-[20px]">
            Đơn hàng của bạn
          </h3>
          <a-flex class="w-full mb-[20px]"
            ><a-table
              :columns="columns"
              :data-source="dataTable"
              :pagination="false"
              bordered
              class="w-full"
            >
              <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'name'">
                  <a-flex gap="5" class="items-center">
                    <a class="a-product" :href="`/product/${record.slug}`">{{
                      record.name
                    }}</a
                    ><AkXSmall />
                    <span class="font-bold">{{ record.quantity }}</span>
                  </a-flex>
                </template>
                <template v-else-if="column.key === 'subtotal'">
                  <span class="text-[#2267DF] text-[16px] font-bold">{{
                    formatCurrency(record.price * record.quantity)
                  }}</span>
                </template>
              </template>
              <template #summary>
                <a-table-summary-row>
                  <a-table-summary-cell
                    ><span class="font-semibold"
                      >Tổng tiền</span
                    ></a-table-summary-cell
                  >
                  <a-table-summary-cell>
                    <span class="text-[#2267DF] text-[16px] font-bold">{{
                      formatCurrency(totals.subtotal)
                    }}</span>
                  </a-table-summary-cell>
                </a-table-summary-row>
                <a-table-summary-row>
                  <a-table-summary-cell
                    ><span class="font-semibold"
                      >Vận chuyển</span
                    ></a-table-summary-cell
                  >
                  <a-table-summary-cell>
                    <span class="font-semibold">Miễn phí vận chuyển</span>
                  </a-table-summary-cell>
                </a-table-summary-row>
                <a-table-summary-row>
                  <a-table-summary-cell
                    ><span class="font-semibold"
                      >Tổng tiền</span
                    ></a-table-summary-cell
                  >
                  <a-table-summary-cell>
                    <span class="text-[#2267DF] text-[16px] font-bold">{{
                      formatCurrency(totals.subtotal)
                    }}</span>
                  </a-table-summary-cell>
                </a-table-summary-row>
              </template>
            </a-table>
          </a-flex>
          <a-flex vertical class="w-full bg-[#E9E6ED] rounded-md">
            <a-form-item name="paymenttype" :rules="rules.paymenttype">
              <a-flex class="w-full p-[1em] border-b-[1px] border-[#cfc8d8]">
                <a-radio-group
                  v-model:value="formState.paymenttype"
                  class="flex flex-1 flex-col"
                >
                  <a-radio :style="radioStyle" :value="1"
                    >Chuyển khoản ngân hàng</a-radio
                  >
                  <a-card
                    v-if="formState.paymenttype === 1"
                    class="bg-[#DCD7E3] w-full"
                  >
                    <p>
                      Thực hiện thanh toán vào ngay tài khoản ngân hàng của
                      chúng tôi. Vui lòng sử dụng Mã đơn hàng của bạn trong phần
                      Nội dung thanh toán. Đơn hàng sẽ đươc giao sau khi tiền đã
                      chuyển.
                    </p>
                  </a-card>
                  <a-radio :style="radioStyle" :value="2"
                    >Trả tiền mặt khi nhận hàng</a-radio
                  >
                  <a-card
                    v-if="formState.paymenttype === 2"
                    class="bg-[#DCD7E3] w-full"
                  >
                    <p>Trả tiền mặt khi giao hàng</p>
                  </a-card>
                </a-radio-group>
              </a-flex>
            </a-form-item>
            <a-flex vertical class="w-full p-[1em]">
              <a-form-item
                ><p>
                  Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn đặt hàng,
                  hỗ trợ trải nghiệm của bạn trên trang web này và cho các mục
                  đích khác được mô tả trong phần của chúng tôi.<a
                    class="text-blue-500"
                    >chính sách bảo mật.</a
                  >
                </p>
              </a-form-item>
              <a-form-item name="terms" :rules="rules.terms">
                <a-checkbox v-model:checked="formState.terms">
                  Tôi đã đọc và đồng ý với trang web
                  <a class="text-blue-500 cursor-pointer hover:bg-[#E9E6ED]"
                    >điều khoản và điều kiện</a
                  >
                  <span class="text-red-500">*</span>
                </a-checkbox>
              </a-form-item>
              <a-flex class="justify-end">
                <a-button
                  type="primary"
                  @click="onSubmit"
                  v-if="!PayPalButtonRef"
                  >Đặt hàng</a-button
                >
                <PayPalButton
                  v-if="PayPalButtonRef"
                  :amount="totals.subtotal"
                  @payment-success="handlePaymentSuccess"
                />
              </a-flex>
            </a-flex>
          </a-flex>
        </a-form>
      </div>
    </div>
  </div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from "vue";
import axios from "axios";
import { AkXSmall } from "@kalimahapps/vue-icons";
import store from "@/store/store";
import PayPalButton from "@/components/paypal/PayPalButton.vue";
import { useRouter } from "vue-router";
import { getDataFromIndexedDB } from "@/store/indexedDB";
import { Modal } from "ant-design-vue";

const router = useRouter();
const PayPalButtonRef = ref(false);

const dataTable = ref([]);

const formRef = ref();
const formState = reactive({
  user_id: null,
  name: "",
  phone: "",
  email: "",
  province: "",
  district: "",
  subdistrict: "",
  address: "",
  diffname: "",
  diffphone: "",
  diffprovince: "",
  diffdistrict: "",
  diffsubdistrict: "",
  diffaddress: "",
  notes: "",
  terms: false,
  paymenttype: 1,
  differentaddresschecked: false,
  items: [],
});

const LocateState = reactive({
  province: null,
  district: null,
  subdistrict: null,
  diffprovince: null,
  diffdistrict: null,
  diffsubdistrict: null,
});

const fetchDataTable = async () => {
  try {
    const dataStore = JSON.parse(
      JSON.stringify(store.getters["product/getDataStoreCart"])
    );
    const dataProduct = await getDataFromIndexedDB("products");

    const dataCart = dataProduct.filter((item) =>
      dataStore.some((cartItem) => cartItem.id === item.id)
    );
    if (dataCart && dataCart.length > 0) {
      dataTable.value = dataCart.map((item) => {
        const cartItem = dataStore.find((cart) => cart.id === item.id);
        return { ...item, quantity: cartItem ? cartItem.quantity : 1 };
      });

      formState.items = dataTable.value.map((item) => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.price,
      }));
    }
  } catch (e) {
    console.log("Error: ", e);
  }
};

const checkUser = () => {
  const user = JSON.parse(sessionStorage.getItem("user"));
  if (user) {
    formState.user_id = user.id;
    formState.name = `${user.last_name || ""} ${user.first_name || ""}`.trim();
    formState.phone = user.additional_user?.phone;
    formState.email = user.email;
    LocateState.province = user.additional_user?.province;
    onProvinceChange();
    LocateState.district = user.additional_user?.district;
    onDistrictChange();
    LocateState.subdistrict = user.additional_user?.subdistrict;
    formState.address = user.additional_user?.address;
  }
};

const provinces = ref([]);
const districts = ref([]);
const wards = ref([]);
const diffprovinces = ref([]);
const diffdistricts = ref([]);
const diffwards = ref([]);

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const columns = ref([
  {
    key: "name",
    title: "Sản phẩm",
    dataIndex: "name",
  },
  {
    key: "subtotal",
    title: "Tổng tiền",
    dataIndex: "subtotal",
  },
]);

const totals = computed(() => {
  let subtotal = 0;
  dataTable.value.forEach(({ price, quantity }) => {
    subtotal += price * quantity;
  });
  return {
    subtotal,
  };
});

const radioStyle = reactive({
  display: "flex",
  height: "30px",
  lineHeight: "30px",
});

const host = "https://provinces.open-api.vn/api/";

const fetchProvinces = async () => {
  try {
    const response = await axios.get(`${host}?depth=1`);
    provinces.value = response.data;
    diffprovinces.value = response.data;
  } catch (error) {
    console.error("Failed to fetch provinces:", error);
  }
};

const onProvinceChange = async () => {
  const provinceCode = LocateState.province;
  const diffprovinceCode = LocateState.diffprovince;

  try {
    if (provinceCode) {
      const response = await axios.get(`${host}p/${provinceCode}?depth=2`);
      districts.value = response.data.districts;
      if (
        districts.value.some(
          (districts) => districts.code === LocateState.district
        )
      ) {
      } else {
        LocateState.district = null;
        wards.value = [];
        LocateState.subdistrict = null;
      }
    }

    if (diffprovinceCode) {
      const response = await axios.get(`${host}p/${diffprovinceCode}?depth=2`);
      diffdistricts.value = response.data.districts;
      if (
        diffdistricts.value.some(
          (diffdistricts) => diffdistricts.code === LocateState.diffdistrict
        )
      ) {
      } else {
        LocateState.diffdistrict = null;
        diffwards.value = [];
        LocateState.diffsubdistrict = null;
      }
    }
  } catch (error) {
    console.error("Failed to fetch districts:", error);
  }
};

const onDistrictChange = async () => {
  const districtCode = LocateState.district;
  const diffdistrictCode = LocateState.diffdistrict;

  try {
    if (districtCode) {
      const response = await axios.get(`${host}d/${districtCode}?depth=2`);
      wards.value = response.data.wards;
      if (wards.value.some((wards) => wards.code === LocateState.subdistrict)) {
      } else {
        LocateState.subdistrict = null;
      }
    }

    if (diffdistrictCode) {
      const response = await axios.get(`${host}d/${diffdistrictCode}?depth=2`);
      diffwards.value = response.data.wards;
      if (
        diffwards.value.some(
          (diffwards) => diffwards.code === LocateState.diffsubdistrict
        )
      ) {
      } else {
        LocateState.diffsubdistrict = null;
      }
    }
  } catch (error) {
    console.error("Failed to fetch wards:", error);
  }
};

function handleProvinceChange(newProvinceCode) {
  const selectedProvince = provinces.value.find(
    (p) => p.code === newProvinceCode
  );
  if (selectedProvince) {
    formState.province = selectedProvince.name;
  }
}

function handleDistrictChange(newDistrictCode) {
  const selectedDistrict = districts.value.find(
    (d) => d.code === newDistrictCode
  );
  if (selectedDistrict) {
    formState.district = selectedDistrict.name;
  }
}

function handleSubdistrictChange(newSubdistrictCode) {
  const selectedSubdistrict = wards.value.find(
    (w) => w.code === newSubdistrictCode
  );
  if (selectedSubdistrict) {
    formState.subdistrict = selectedSubdistrict.name;
  }
}
function handleDiffProvinceChange(newDiffProvinceCode) {
  const selectedDiffProvince = provinces.value.find(
    (p) => p.code === newDiffProvinceCode
  );
  formState.diffprovince = selectedDiffProvince
    ? selectedDiffProvince.name
    : "";
}

function handleDiffDistrictChange(newDiffDistrictCode) {
  const selectedDiffDistrict = diffdistricts.value.find(
    (d) => d.code === newDiffDistrictCode
  );
  formState.diffdistrict = selectedDiffDistrict
    ? selectedDiffDistrict.name
    : "";
}

function handleDiffSubdistrictChange(newDiffSubdistrictCode) {
  const selectedDiffSubdistrict = diffwards.value.find(
    (w) => w.code === newDiffSubdistrictCode
  );
  formState.diffsubdistrict = selectedDiffSubdistrict
    ? selectedDiffSubdistrict.name
    : "";
}

watch(() => LocateState.province, handleProvinceChange);
watch(() => LocateState.district, handleDistrictChange);
watch(() => LocateState.subdistrict, handleSubdistrictChange);
watch(() => LocateState.diffprovince, handleDiffProvinceChange);
watch(() => LocateState.diffdistrict, handleDiffDistrictChange);
watch(() => LocateState.diffsubdistrict, handleDiffSubdistrictChange);

const rules = {
  name: [
    { required: true, message: "Vui lòng nhập họ và tên", trigger: "submit" },
  ],
  diffname: [
    {
      required: true,
      message: "Vui lòng nhập họ và tên người nhận",
      trigger: "submit",
    },
  ],
  email: [
    {
      required: true,
      message: "Vui lòng nhập địa chỉ email",
      trigger: "submit",
    },
    {
      pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
      message: "Địa chỉ email không hợp lệ",
      trigger: "submit",
    },
  ],
  phone: [
    {
      required: true,
      message: "Vui lòng nhập số điện thoại",
      trigger: "submit",
    },
    {
      pattern: /^0\d{9,10}$/,
      message: "Số điện thoại không hợp lệ",
      trigger: "blur",
    },
  ],
  diffphone: [
    {
      pattern: /^0\d{9,10}$/,
      message: "Số điện thoại không hợp lệ",
      trigger: "blur",
    },
  ],
  province: [
    {
      required: true,
      message: "Vui lòng chọn tỉnh/thành phố",
      trigger: "submit",
    },
  ],
  district: [
    { required: true, message: "Vui lòng chọn quận/huyện", trigger: "submit" },
  ],
  subdistrict: [
    {
      required: true,
      message: "Vui lòng chọn xã/phường/thị trấn",
      trigger: "submit",
    },
  ],
  address: [
    {
      required: true,
      message: "Vui lòng nhập địa chỉ cụ thể",
      trigger: "submit",
    },
  ],
  diffprovince: [
    {
      required: true,
      message: "Vui lòng chọn tỉnh/thành phố",
      trigger: "submit",
    },
  ],
  diffdistrict: [
    { required: true, message: "Vui lòng chọn quận/huyện", trigger: "submit" },
  ],
  diffsubdistrict: [
    {
      required: true,
      message: "Vui lòng chọn xã/phường/thị trấn",
      trigger: "submit",
    },
  ],
  diffaddress: [
    {
      required: true,
      message: "Vui lòng nhập địa chỉ cụ thể",
      trigger: "submit",
    },
  ],
  terms: [
    {
      validator: (_, value) =>
        value
          ? Promise.resolve()
          : Promise.reject("Vui lòng chấp nhận các điều khoản và điều kiện."),
      trigger: "submit",
    },
  ],
};

const onSubmit = async () => {
  if (!formState.province) {
    handleProvinceChange(LocateState.province);
  }
  if (!formState.district) {
    handleDistrictChange(LocateState.district);
  }
  if (!formState.subdistrict) {
    handleSubdistrictChange(LocateState.subdistrict);
  }
  try {
    await formRef.value.validate();
    if (formState.paymenttype == 1) {
      PayPalButtonRef.value = true;
    }
    if (formState.paymenttype == 2) {
      PayPalButtonRef.value = false;
      const response = await axios.post(
        `${import.meta.env.VITE_APP_URL_API_ORDER}/createOrder`,
        formState
      );
      store.dispatch("product/clearDataStoreCart");
      alert("Order created successfully");

      router.push(`/payment/order-received/${response.data.order_code}`);
    }
  } catch (error) {
    if (error.errors) {
      console.error("Form validation failed:", error.errors);
    } else {
      console.error("Failed to create order:", error);
    }
  }
};

const handlePaymentSuccess = async (orderID) => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API_ORDER}/createOrder`,
      JSON.parse(JSON.stringify(formState))
    );

    store.dispatch("product/clearDataStoreCart");
    Modal.success({
      title: "Thanh toán đơn hàng thành công!",
    });
    console.log(response);

    router.push(`/payment/order-received/${response.data.order_code}`);
  } catch (error) {
    console.error("Error adding order to database:", error);
  }
};

onMounted(() => {
  fetchProvinces();
  fetchDataTable();
  checkUser();
});
</script>

<style scoped>
.a-product {
  color: #2267df;
  font-weight: bold;
}
.a-product:hover {
  color: #0024d9;
  font-weight: bold;
  background-color: transparent;
}

@media only screen and (max-width: 480px) {
  .content {
    flex-direction: column;
  }
}
</style>
