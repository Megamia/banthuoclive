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
      </div>
    </aside>
    <main class="w-full min-h-screen py-1 md:w-2/3 lg:w-3/4">
      <div class="p-2 md:p-4">
        <!-- Thong tin nguoi dung -->
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
                      :key="province.code"
                      :value="province.code"
                      :disabled="!editMode"
                    >
                      {{ province.name }}
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
                      :key="district.code"
                      :value="district.code"
                      :disabled="!editMode"
                    >
                      {{ district.name }}
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
                      :key="ward.code"
                      :value="ward.code"
                      :disabled="!editMode"
                    >
                      {{ ward.name }}
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
      </div>
    </main>
  </div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { onMounted, ref, resolveDirective, watch } from "vue";
import { useRouter } from "vue-router";
import { CdEye, CdEyeClosed } from "@kalimahapps/vue-icons";
import axios from "axios";
const router = useRouter();

const provinces = ref([]);
const districts = ref([]);
const wards = ref([]);
const activePage = ref(0);
const editMode = ref(false);
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

const host = "https://provinces.open-api.vn/api/";

const fetchProvinces = async () => {
  try {
    const response = await axios.get(`${host}?depth=1`);
    provinces.value = response.data;
  } catch (error) {
    console.error("Failed to fetch provinces:", error);
  }
};

const onProvinceChange = async (provinceCode) => {
  try {
    if (provinceCode) {
      const response = await axios.get(`${host}p/${provinceCode}?depth=2`);
      districts.value = response.data.districts;
      if (
        districts.value.some(
          (district) => district.code === profile.value.district
        )
      ) {
      } else {
        profile.value.district = null;
        wards.value = [];
        profile.value.subdistrict = null;
      }
    }
  } catch (error) {
    console.error("Failed to fetch districts:", error);
  }
};

const onDistrictChange = async (districtCode) => {
  try {
    if (districtCode) {
      const response = await axios.get(`${host}d/${districtCode}?depth=2`);
      wards.value = response.data.wards;
      if (
        wards.value.some((wards) => wards.code === profile.value.subdistrict)
      ) {
      } else {
        profile.value.subdistrict = null;
      }
    }
  } catch (error) {
    console.error("Failed to fetch wards:", error);
  }
};

const fetchProfile = () => {
  const storedUser = sessionStorage.getItem("user");
  if (storedUser) {
    const user = JSON.parse(storedUser);
    profile.value.first_name = user.first_name;
    profile.value.last_name = user.last_name;
    profile.value.email = user.email;
    profile.value.phone = user.additional_user?.phone || null;
    profile.value.province = user.additional_user?.province || null;
    onProvinceChange(profile.value.province);
    profile.value.district = user.additional_user?.district || null;
    onDistrictChange(profile.value.district);
    profile.value.subdistrict = user.additional_user?.subdistrict || null;
    profile.value.address = user.additional_user?.address || "";
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
      // console.log("Bạn chưa đăng nhập. Đang chuyển hướng...");
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
  // console.log(profile.value);
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
