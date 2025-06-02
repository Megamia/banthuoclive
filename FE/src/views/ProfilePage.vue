<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div
    class="bg-white w-full flex flex-col md:gap-5 px-3 md:px-16 lg:px-28 md:flex-row text-[#161931]"
  >
    <aside class="py-4 md:w-1/3 lg:w-1/4 md:block">
      <div
        class="sticky flex flex-row max-md:justify-center md:flex-col gap-2 p-4 text-sm md:border-r max-md:border-b border-indigo-100 top-12"
      >
        <h2 class="hidden md:flex pl-3 max-md:mb-4 text-2xl font-semibold">
          Cài đặt
        </h2>
        <button
          href="#"
          class="flex items-center px-3 py-2.5 hover:text-indigo-900 rounded-full"
          :class="
            activePage == 0
              ? 'bg-slate-200 text-indigo-900 font-bold'
              : 'bg-white font-semibold'
          "
          @click="handleChangeActivePage(0)"
        >
          Thông tin tài khoản
        </button>
        <button
          href="#"
          class="flex items-center px-3 py-2.5 hover:text-indigo-900 rounded-full"
          @click="handleChangeActivePage(1)"
          :class="
            activePage == 1
              ? 'bg-slate-200 text-indigo-900 font-bold'
              : 'bg-white font-semibold'
          "
        >
          Đổi mật khẩu
        </button>
        <button
          href="#"
          class="flex items-center px-3 py-2.5 hover:text-indigo-900 rounded-full"
          @click="handleChangeActivePage(2)"
          :class="
            activePage == 2
              ? 'bg-slate-200 text-indigo-900 font-bold'
              : 'bg-white font-semibold'
          "
        >
          Đơn hàng của bạn
        </button>
      </div>
    </aside>
    <main class="w-full min-h-screen py-1 md:w-2/3 lg:w-3/4">
      <div class="p-2 md:p-4">
        <div
          v-if="activePage == 0"
          class="w-full px-6 pb-8 md:mt-4 sm:max-w-xl sm:rounded-lg"
        >
          <h2 class="pl-6 text-2xl font-bold sm:text-xl">
            Thông tin tài khoản
          </h2>
          <div class="grid max-w-2xl mx-auto">
            <form @submit.prevent="handleChangeInfo">
              <div class="items-center mt-4 md:mt-8 text-[#202142]">
                <div
                  class="flex flex-col items-center w-full mb-2 space-x-0 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 sm:mb-6"
                >
                  <div class="w-full">
                    <label
                      for="first_name"
                      class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                      >Tên</label
                    >
                    <input
                      type="text"
                      id="first_name"
                      class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                      placeholder="Nhập tên"
                      v-model="profile.first_name"
                      required
                      :disabled="!editMode"
                    />
                  </div>

                  <div class="w-full">
                    <label
                      for="last_name"
                      class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                      >Họ</label
                    >
                    <input
                      type="text"
                      id="last_name"
                      class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                      placeholder="Nhập họ"
                      v-model="profile.last_name"
                      :disabled="!editMode"
                    />
                  </div>
                </div>

                <div class="mb-2 sm:mb-6">
                  <label
                    for="email"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Email</label
                  >
                  <input
                    type="email"
                    id="email"
                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                    v-model="profile.email"
                    disabled
                  />
                </div>
                <div class="mb-2 sm:mb-6">
                  <label
                    for="phone"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Số điện thoại</label
                  >
                  <input
                    type="text"
                    id="phone"
                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                    v-model="profile.phone"
                    placeholder="Nhập số điện thoại"
                    :disabled="!editMode"
                  />
                </div>

                <div class="mb-2 sm:mb-6">
                  <label
                    for="province"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Tỉnh/Thành phố</label
                  >
                  <a-select
                    v-model:value="profile.province"
                    @change="onProvinceChange"
                    placeholder="Chọn Tỉnh/Thành phố"
                    id="province"
                    :bordered="false"
                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block py-1"
                  >
                    <a-select-option
                      v-for="province in provinces"
                      :key="province.ProvinceID"
                      :value="province.ProvinceID"
                      :disabled="!editMode"
                    >
                      {{ province.ProvinceName }}
                    </a-select-option>
                  </a-select>
                </div>
                <div class="mb-2 sm:mb-6">
                  <label
                    for="email"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Quận/Huyện</label
                  >
                  <a-select
                    v-model:value="profile.district"
                    @change="onDistrictChange"
                    placeholder="Chọn Quận/Huyện"
                    id="district"
                    :bordered="false"
                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block py-1"
                  >
                    <a-select-option
                      v-for="district in districts"
                      :key="district.DistrictID"
                      :value="district.DistrictID"
                      :disabled="!editMode"
                    >
                      {{ district.DistrictName }}
                    </a-select-option>
                  </a-select>
                </div>
                <div class="mb-2 sm:mb-6">
                  <label
                    for="subdistrict"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Xã/Phường/Thị trấn</label
                  >
                  <a-select
                    v-model:value="profile.subdistrict"
                    placeholder="Chọn Xã/Phường/Thị trấn"
                    id="subdistrict"
                    :bordered="false"
                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block py-1"
                  >
                    <a-select-option
                      v-for="ward in wards"
                      :key="ward.WardCode"
                      :value="ward.WardCode"
                      :disabled="!editMode"
                    >
                      {{ ward.WardName }}
                    </a-select-option>
                  </a-select>
                </div>
                <div class="mb-2 sm:mb-6">
                  <label
                    for="address"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Địa chỉ đường</label
                  >
                  <input
                    type="text"
                    id="address"
                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                    v-model="profile.address"
                    placeholder="Nhập địa chỉ đường"
                    :disabled="!editMode"
                  />
                </div>
              </div>
              <div
                class="flex flex-col md:flex-row md:justify-end gap-3 mt-[20px]"
              >
                <div v-if="editMode" class="flex justify-end">
                  <button
                    type="submit"
                    class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800"
                  >
                    Lưu
                  </button>
                </div>
                <div class="flex justify-end">
                  <button
                    v-if="editMode"
                    @click="handleEditInfo"
                    type="button"
                    class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800"
                  >
                    Thoát
                  </button>
                  <button
                    v-else
                    @click="handleEditInfo"
                    type="button"
                    class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800"
                  >
                    Cập nhật thông tin
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- Doi mat khau -->
        <div
          v-if="activePage == 1"
          class="w-full px-6 pb-8 md:mt-4 sm:max-w-xl sm:rounded-lg"
        >
          <h2 class="pl-6 text-2xl font-bold sm:text-xl">Đổi mật khẩu</h2>
          <div class="grid max-w-2xl mx-auto">
            <form @submit.prevent="handleChangePassword">
              <div class="items-center max-md:mt-4 md:mt-8 text-[#202142]">
                <input
                  type="text"
                  id="username"
                  name="username"
                  autocomplete="username"
                  style="display: none"
                />
                <div class="mb-2 sm:mb-6">
                  <label
                    for="old_password"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Mật khẩu cũ</label
                  >
                  <div class="relative">
                    <input
                      :type="passwordVisibility.old ? 'text' : 'password'"
                      id="old_password"
                      class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                      v-model="passwordForm.old_password"
                      placeholder="Nhập mật khẩu cũ..."
                      autocomplete="current-password"
                      required
                    />
                    <button
                      type="button"
                      @click="passwordVisibility.old = !passwordVisibility.old"
                      class="absolute inset-y-0 right-2 flex items-center text-indigo-600"
                    >
                      <CdEye
                        class="text-[20px]"
                        v-if="passwordVisibility.old"
                      />
                      <CdEyeClosed class="text-[20px]" v-else />
                    </button>
                  </div>
                </div>
                <div class="mb-2 sm:mb-6">
                  <label
                    for="new_password"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Mật khẩu mới</label
                  >
                  <div class="relative">
                    <input
                      :type="passwordVisibility.new ? 'text' : 'password'"
                      id="new_password"
                      class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                      v-model="passwordForm.new_password"
                      placeholder="Nhập mật khẩu mới..."
                      required
                      autocomplete="new-password"
                    />
                    <button
                      type="button"
                      @click="passwordVisibility.new = !passwordVisibility.new"
                      class="absolute inset-y-0 right-2 flex items-center text-indigo-600"
                    >
                      <CdEye
                        class="text-[20px]"
                        v-if="passwordVisibility.new"
                      />
                      <CdEyeClosed class="text-[20px]" v-else />
                    </button>
                  </div>
                </div>
                <div class="mb-2 sm:mb-6">
                  <label
                    for="confirm_password"
                    class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white"
                    >Xác nhận mật khẩu mới</label
                  >
                  <div class="relative">
                    <input
                      :type="passwordVisibility.confirm ? 'text' : 'password'"
                      id="confirm_password"
                      class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                      v-model="passwordForm.confirm_password"
                      placeholder="Nhập lại mật khẩu mới..."
                      required
                      autocomplete="new-password"
                    />
                    <button
                      type="button"
                      @click="
                        passwordVisibility.confirm = !passwordVisibility.confirm
                      "
                      class="absolute inset-y-0 right-2 flex items-center text-indigo-600"
                    >
                      <CdEye
                        class="text-[20px]"
                        v-if="passwordVisibility.confirm"
                      />
                      <CdEyeClosed class="text-[20px]" v-else />
                    </button>
                  </div>
                </div>
                <div class="h-[20px]">
                  <p v-if="errorMessage" class="text-red-600">
                    {{ errorMessage }}
                  </p>
                  <p v-if="successMessage" class="text-green-600">
                    {{ successMessage }}
                  </p>
                </div>
                <div class="flex justify-end">
                  <button
                    type="submit"
                    class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800"
                  >
                    Save
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div
          v-if="activePage == 2"
          class="w-full px-6 pb-8 md:mt-4 sm:max-w-xl sm:rounded-lg"
        >
          <a-flex vertical>
            <a-flex>
              <a-form
                :model="formState"
                @finish="handleFindOrder"
                class="flex flex-row gap-[30px]"
              >
                <a-form-item
                  label="Code"
                  name="GHN_Code"
                  :rules="[
                    { required: true, message: 'Code không được để trống!' },
                  ]"
                >
                  <a-input v-model:value="formState.GHN_Code" />
                </a-form-item>

                <a-form-item>
                  <a-button type="primary" html-type="submit"
                    >Tìm kiếm</a-button
                  >
                  <a-button @click="closeFind" v-if="isFind" class="ml-[10px]"
                    >Tắt</a-button
                  >
                </a-form-item>
              </a-form>
              <!-- <input
                placeholder="Mã"
                class="border border-black p-2"
                v-model="GHN_Code"
              />
              <button @click="handleFindOrder">Tìm kiếm</button> -->
            </a-flex>
            <a-flex vertical v-if="dataGHN && isFind">
              <p>Thông tin đơn hàng</p>
              <a-flex vertical>
                <span
                  >Người gửi: {{ dataGHN.from_name }} -
                  {{ dataGHN.from_phone }}</span
                >
                <span
                  >Người nhận:
                  {{ dataInfor ? dataInfor.name : "Chưa có thông tin" }} -
                  {{ dataInfor ? dataInfor.phone : "Chưa có thông tin" }}</span
                >
                <span>Tên sản phẩm: {{ dataGHN.content }}</span>
                <span
                  >Địa chỉ:
                  {{
                    dataInfor
                      ? dataInfor.address +
                        ", " +
                        dataInfor.ward +
                        ", " +
                        dataInfor.district +
                        ", " +
                        dataInfor.province
                      : "Đang tải..."
                  }}</span
                >
                <span
                  >Loại thanh toán:
                  {{
                    dataGHN.payment_type_ids
                      ? formatPaymentType(dataGHN.payment_type_ids[0])
                      : "Chưa có thông tin"
                  }}</span
                >
                <span>Tiền cod: {{ formatCurrency(dataGHN.cod_amount) }}</span>
                <span>Ghi chú: {{ dataGHN.required_note }}</span>
                <span
                  >Thời gian giao hàng dự kiến:
                  {{ formatDate(dataGHN.leadtime) }}
                </span>
                <span>
                  Trạng thái vận chuyển:
                  {{
                    dataInfor
                      ? formatShippingStatus(dataInfor.shipping_status)
                      : "Chưa có thông tin"
                  }}</span
                >
              </a-flex>
            </a-flex>
            <a-flex vertical v-else-if="!dataGHN">
              <span>Bạn chưa có đơn hàng</span>
            </a-flex>
            <a-flex v-if="!isFind" vertical class="gap-[10px]">
              <a-flex
                v-for="(dataOrderItem, index) in dataOrder"
                :key="dataOrderItem.id"
                vertical
                class="gap-[10px]"
              >
                <a-flex class="items-center">
                  <span>
                    Đơn hàng: {{ dataOrderItem.ghn_order_code }} -
                    {{
                      dataOrderItem ? dataOrderItem.name : "Chưa có thông tin"
                    }}
                  </span>

                  <a-button
                    type="link"
                    @click="toggleDetail(index, dataOrderItem.ghn_order_code)"
                  >
                    {{
                      selectedOrderIndex === index
                        ? "Ẩn chi tiết"
                        : "Xem chi tiết"
                    }}
                  </a-button>
                  <a-button
                    class="border border-[gray]"
                    type="link"
                    @click="
                      handleReceived(
                        index,
                        dataOrderItem.order_code,
                        dataOrderItem.id
                      )
                    "
                    :disabled="isReceived[index]"
                  >
                    Đã nhận được hàng
                  </a-button>
                </a-flex>
                <a-flex v-if="selectedOrderIndex === index" vertical>
                  <span
                    >Người gửi: {{ dataGHN.from_name }} -
                    {{ dataGHN.from_phone }}</span
                  >
                  <span>
                    Người nhận:
                    {{ dataGHN ? dataGHN.to_name : "Chưa có thông tin" }}
                    -
                    {{ dataGHN ? dataGHN.to_phone : "Chưa có thông tin" }}
                  </span>
                  <span>Tên sản phẩm: {{ dataGHN.content }}</span>
                  <span>
                    Địa chỉ:
                    {{
                      dataOrderItem
                        ? dataOrderItem.property.address +
                          ", " +
                          dataOrderItem.wardName +
                          ", " +
                          dataOrderItem.districtName +
                          ", " +
                          dataOrderItem.provinceName
                        : "Đang tải..."
                    }}
                  </span>

                  <span
                    >Loại thanh toán:
                    {{
                      dataGHN.payment_type_ids
                        ? formatPaymentType(dataGHN.payment_type_ids[0])
                        : "Chưa có thông tin"
                    }}</span
                  >
                  <span
                    >Tiền COD: {{ formatCurrency(dataGHN.cod_amount) }}</span
                  >
                  <span>Ghi chú: {{ dataGHN.required_note }}</span>
                  <span
                    >Thời gian giao hàng dự kiến:
                    {{ formatDate(dataGHN.leadtime) }}</span
                  >
                  <span>
                    Trạng thái vận chuyển:
                    {{ formatShippingStatus(dataOrderItem.status_id) }}
                  </span>
                </a-flex>
              </a-flex>
            </a-flex>
          </a-flex>
        </div>
      </div>
    </main>
  </div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import {
  onMounted,
  reactive,
  ref,
  resolveDirective,
  watch,
  watchEffect,
} from "vue";
import { useRouter } from "vue-router";
import { CdEye, CdEyeClosed } from "@kalimahapps/vue-icons";
import axios from "axios";
import { Modal } from "ant-design-vue";
const router = useRouter();

const provinces = ref([]);
const districts = ref([]);
const wards = ref([]);
const activePage = ref(0);
const editMode = ref(false);
const GHN_Code = ref("");
const formState = reactive({
  GHN_Code: "",
});
const dataGHN = ref("");
const dataInfor = ref(null);
const dataOrder = ref([]);
const isFind = ref(false);
const isReceived = ref([]);
const selectedOrderIndex = ref(null);

const toggleDetail = (index, code) => {
  if (selectedOrderIndex.value === index) {
    selectedOrderIndex.value = null;
  } else {
    selectedOrderIndex.value = index;
    find(code);
    isFind.value = false;
  }
};
const handleFindOrder = () => {
  if (!formState.GHN_Code.trim()) {
    alert("Vui lòng nhập mã đơn hàng!");
    return;
  }

  find(formState.GHN_Code);
  isFind.value = true;
};

const handleReceived = async (index, order_code, id) => {
  Modal.confirm({
    title: "Hành động này sẽ không thể hoàn tác!!",
    content: "Bạn có chắc chắn muốn xác nhận đã nhận đơn hàng?",
    async onOk() {
      try {
        const response = await axios.post(
          `${import.meta.env.VITE_APP_URL_API_ORDER}/updateStatus/${order_code}`
        );

        isReceived.value[index] = true;
        dataOrder.value[index].status_id = 2;

        Modal.success({
          title: "Xác nhận thành công!",
          content: "Đơn hàng đã được cập nhật là 'đã nhận'.",
        });

        await new Promise((resolve) => setTimeout(resolve, 1000));
      } catch (error) {
        console.error("Lỗi khi cập nhật trạng thái:", error);
        Modal.error({
          title: "Xác nhận thất bại!",
          content: "Đã xảy ra lỗi khi gửi yêu cầu tới server.",
        });
      }
    },
    onCancel() {
      Modal.info({
        title: "Đã huỷ xác nhận.",
      });
    },
  });
  // if (confirm("Hành động này sẽ không thể hoàn tác")) {
  //   alert("Xác nhận thành công");
  //   try {
  //     const response = await axios.post(
  //       `${import.meta.env.VITE_APP_URL_API_ORDER}/updateStatus/${order_code}`
  //     );
  //     isReceived.value[index] = true;
  //     dataOrder.value[index].status_id = 2;
  //   } catch (e) {
  //     console.log("Error: ", e);
  //   }
  // } else {
  //   alert("Xác nhận thất bại");
  // }
};

const find = async (code) => {
  try {
    const response = await axios.get(
      `https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail?order_code=${code}`,
      {
        headers: {
          Token: import.meta.env.VITE_GHN_API_KEY,
          "Content-Type": "application/json",
        },
      }
    );
    if (response.status === 200) {
      dataGHN.value = response.data.data;

      //Lấy địa chỉ theo GHN
      // if (dataGHN.value.to_district_id) {
      //   await getAddressInforGHN(
      //     dataGHN.value.to_district_id,
      //     dataGHN.value.to_ward_code
      //   );
      // } else {
      //   alert("Không có thông tin về quận/huyện.");
      // }
      //Lấy địa chỉ theo GHN

      //Lấy dữ liệu theo order_code
      await getDataOrder(dataGHN.value.client_order_code);
      //Lấy dữ liệu theo order_code
    } else {
      alert(`Không tìm thấy thông tin của đơn hàng với mã ${GHN_Code.value}`);
      return;
    }
  } catch (e) {
    console.error("Error:", e.response ? e.response.data : e.message);
  }
};

const closeFind = () => {
  isFind.value = false;
};

const formatDate = (value) => {
  const date = new Date(value);
  return date.toLocaleDateString("vi-VN");
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};
const formatPaymentType = (value) => {
  if (value === 1) {
    return "Thanh toán trước (online payment)";
  } else if (value === 2) {
    return "Thanh toán khi nhận hàng (COD)";
  } else {
    return "Hình thức thanh toán khác...";
  }
};
const formatShippingStatus = (value) => {
  if (value === 0) {
    return "Chưa giao";
  } else if (value === 1) {
    return "Đang giao hàng";
  } else if (value === 2) {
    // isReceived.value[index] = true;
    return "Giao hàng thành công";
  }
};

const getDataOrder = async (order_code) => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_ORDER}/order/${order_code}`
    );

    if (response.status === 200) {
      return (dataInfor.value = {
        name: response.data.property.name,
        phone: response.data.property.phone,
        address: response.data.property.address,
        ward: response.data.property.subdistrict,
        district: response.data.property.district,
        province: response.data.property.province,
        shipping_status: response.data.status_id,
      });
    }
  } catch (e) {
    console.log("Error: ", e);
  }
};

const getAllDataOrder = async (id) => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_ORDER}/allDataOrder/${id}`
    );
    if (response.status === 200) {
      dataOrder.value = response.data;
      isReceived.value = dataOrder.value.map((order) => order.status_id === 2);

      await loadNamesForOrders();
    }

    async function loadNamesForOrders() {
      const promises = dataOrder.value.map(async (order) => {
        order.provinceName = await fetchProvinceNameById(
          order.property.province
        );
        order.districtName = await fetchDistrictNameById(
          order.property.province,
          order.property.district
        );
        order.wardName = await fetchWardNameById(
          order.property.district,
          order.property.subdistrict
        );
      });

      await Promise.all(promises);
    }
  } catch (e) {
    console.log("Error: ", e);
  }
};

const fetchProvinceNameById = (id) => {
  const province = provinces.value.find((p) => p.ProvinceID == id);
  return province ? province.ProvinceName : "";
};

const fetchDistrictNameById = async (provinceId, districtId) => {
  if (!districtId) return "";
  await onProvinceChange(provinceId);

  const district = districts.value.find((d) => d.DistrictID == districtId);
  return district ? district.DistrictName : "";
};

const fetchWardNameById = async (districtId, wardCode) => {
  if (!wardCode) return "";
  await onDistrictChange(districtId);

  const ward = wards.value.find((w) => String(w.WardCode) === String(wardCode));
  return ward ? ward.WardName : "";
};

const profile = ref({
  first_name: "",
  last_name: "",
  email: "",
  phone: "",
  province: null,
  district: null,
  subdistrict: null,
  address: "",
});

const errorMessage = ref("");
const successMessage = ref("");

const passwordVisibility = ref({
  old: false,
  new: false,
  confirm: false,
});

const passwordForm = ref({
  old_password: "",
  new_password: "",
  confirm_password: "",
});

const handleEditInfo = () => {
  editMode.value = !editMode.value;
};

const handleChangeActivePage = (value) => {
  activePage.value = value;
};

const host = import.meta.env.VITE_APP_URL_API_GHN;

const fetchProvinces = async () => {
  try {
    const token = localStorage.getItem("vuex");
    console.log("token: ",token);
    
    if (!token) {
      console.warn("Token not found");
      return;
    }

    const response = await axios.get(`${host}/ghn/provinces`, {
      headers: {
        Token: token,
      },
    });

    provinces.value = response.data.data || [];
  } catch (error) {
    console.error("Failed to fetch GHN provinces:", error);
  }
};

const onProvinceChange = async (provinceCode) => {
  try {
    const token = localStorage.getItem("vuex");
    if (!token) {
      console.warn("Token not found");
      return;
    }
    if (provinceCode) {
      const response = await axios.get(
        `${host}/ghn/districts/${provinceCode}`,
        {
          headers: {
            Token: token,
          },
        }
      );
      districts.value = response.data.data || [];

      const currentDistrictExists = districts.value.some(
        (district) => district.ProvinceID === provinceCode
      );

      if (!currentDistrictExists) {
        profile.value.district = null;
        wards.value = [];
        profile.value.subdistrict = null;
      } else {
        await onDistrictChange(profile.value.district);
      }
    }
  } catch (error) {
    console.error("Failed to fetch districts:", error);
  }
};

const onDistrictChange = async (districtCode) => {
  try {
    const token = localStorage.getItem("vuex");
    if (!token) {
      console.warn("Token not found");
      return;
    }

    if (districtCode) {
      const response = await axios.get(`${host}/ghn/wards/${districtCode}`, {
        headers: {
          Token: token,
        },
      });

      wards.value = response.data.data || [];
      const currentWardExists = wards.value.some(
        (ward) => ward.DistrictID === districtCode
      );

      if (!currentWardExists) {
        profile.value.subdistrict = null;
      }
    }
  } catch (error) {
    console.error("Failed to fetch wards:", error);
  }
};

const fetchProfile = async () => {
  const storedUser = sessionStorage.getItem("user");
  if (storedUser) {
    const user = JSON.parse(storedUser);
    profile.value.first_name = user.first_name;
    profile.value.last_name = user.last_name;
    profile.value.email = user.email;
    profile.value.phone = user.additional_user?.phone || null;
    profile.value.province = user.additional_user?.province || null;
    profile.value.district = user.additional_user?.district || null;
    profile.value.subdistrict = user.additional_user?.subdistrict
      ? user.additional_user.subdistrict.toString()
      : null;
    await onProvinceChange(profile.value.province);
    profile.value.address = user.additional_user?.address || "";
    getAllDataOrder(user.id);
  }
};

const handleChangeInfo = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API_USER}/change-info`,
      profile.value,
      { withCredentials: true }
    );
    if (response.data) {
      alert("Cập nhật thông tin tài khoản thành công");
      handleEditInfo();
      sessionStorage.clear("user");
      sessionStorage.setItem("user", JSON.stringify(response.data));
    } else if (response.status == 205) {
      alert("Chưa đăng nhập");
      sessionStorage.clear("user");
      router.push("/login");
    }
  } catch (error) {
    if (error.response && error.response.status === 401) {
      alert("Bạn chưa đăng nhập. Đang chuyển hướng...");
      router.push("/login");
    } else {
      console.log(error);
    }
  }
};

const handleChangePassword = async () => {
  errorMessage.value = "";
  successMessage.value = "";
  if (passwordForm.value.new_password !== passwordForm.value.confirm_password) {
    errorMessage.value = "Mật khẩu mới không khớp.";
    return;
  }
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API_USER}/change-password`,
      {
        old_password: passwordForm.value.old_password,
        new_password: passwordForm.value.new_password,
      },
      { withCredentials: true }
    );
    if (response.data) {
      successMessage.value = response.data.message;
      passwordForm.value.old_password = "";
      passwordForm.value.new_password = "";
      passwordForm.value.confirm_password = "";
    } else if (response.status == 205) {
      alert("Chưa đăng nhập");
      sessionStorage.clear("user");
      router.push("/login");
    }
  } catch (error) {
    if (error.response?.status === 422) {
      errorMessage.value = error.response.data.error || "Lỗi dữ liệu nhập vào.";
      console.log(errorMessage);
    } else {
      errorMessage.value = "Có lỗi xảy ra! Vui lòng thử lại.";
      console.log(errorMessage);
    }
  }
};

onMounted(() => {
  fetchProvinces();
  fetchProfile();
});
</script>

<style scoped>
.text-red-600 {
  color: red;
}
.text-green-600 {
  color: green;
}
</style>
