<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex class="flex-1 relative justify-between p-[20px] gap-[30px] a">
    <a-flex class="justify-center items-center">
      <router-link to="/">
        <img
          src="https://livotec.com/wp-content/uploads/2024/08/logo-livotec.png"
        />
      </router-link>
    </a-flex>
    <a-flex class="w-[650px] hidden-mobie" vertical>
      <a-input
        placeholder="Chúng tôi có thể giúp bạn tìm kiếm?"
        @focus="searchInputHover = true"
        @blur="handleBlur"
        v-model:value="searchInput"
      >
        <template #suffix>
          <BxSearch />
        </template>
      </a-input>
      <a-flex
        v-if="searchInputHover"
        @mouseenter="searchInputHover = true"
        @mouseleave="handleMouseLeave"
        class="max-h-[400px] overflow-hidden overflow-y-scroll rounded-[30px] w-[66%] absolute bg-white top-[50px] z-40 p-[20px]"
      >
        <a-flex v-if="filteredData.length" vertical>
          <a-flex
            class="pt-[10px] pl-[5px]"
            v-for="item in filteredData"
            :key="item.id"
            vertical
          >
            <h5 class="text-[1.25rem] font-medium">{{ item.title }}</h5>
            <ul class="flex flex-col gap-[10px] py-[10px]">
              <li
                v-for="itemChil in item.item"
                :key="`${item.id}-${itemChil.id}`"
                class="text-black cursor-pointer"
                @click="handleChangeToProductDetails(itemChil)"
              >
                {{ itemChil.name }}
              </li>
            </ul>
          </a-flex>
        </a-flex>
      </a-flex>
      <a-flex class="text-white gap-x-[8px] flex-wrap">
        <a-dropdown
          v-for="(item, index) in data"
          :key="index"
          class="flex flex-row items-center gap-1 font-semibold hover:text-white text-nowrap basis-1/7"
        >
          <a class="ant-dropdown-link" :href="`/${item.category.slug}`">
            {{ item.category.name }}
            <AnFilledCaretDown v-if="item.products.length >= 1" />
          </a>

          <template #overlay>
            <a-menu v-if="item.products.length >= 1">
              <a-flex class="p-[15px]" vertical>
                <span
                  class="text-[#555] text-[16px] font-bold pb-[5px] uppercase"
                  >Sản phẩm nổi bật</span
                >
                <a-flex horizontal>
                  <a-menu-item
                    v-for="itemChil in item.products"
                    :key="itemChil.id"
                  >
                    <a
                      :href="`/product/${itemChil.slug}`"
                      class="hover:bg-[#F5F5F5] flex flex-col gap-1 relative"
                    >
                      <img
                        :src="
                          itemChil.image?.path ||
                          'https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty-300x240.jpg'
                        "
                        class="w-[110px] h-[110px]"
                      />
                      <span
                        class="text-black relative z-10 mt-[10px] max-w-[110px] text-center text-ellipsis overflow-hidden whitespace-nowrap uppercase font-semibold text-[14px] hover:text-[#51c9a9]"
                      >
                        {{ itemChil.name }}
                      </span>
                    </a>
                  </a-menu-item>
                </a-flex>
              </a-flex>
            </a-menu>
          </template>
        </a-dropdown>
      </a-flex>
    </a-flex>
    <a-flex class="items-center justify-end gap-4">
      <BxSearch class="icon iconHidden" @click="showSearch" />
      <SearchComponent v-if="isOpenSearch" @close-search="showSearch" />
      <a-badge
        :count="quantityProductInCart"
        show-zero
        :number-style="{
          backgroundColor: '#fff',
          color: 'black',
          boxShadow: '0 0 0 1px #d9d9d9 inset',
        }"
      >
        <BsCart2 class="icon iconShow" @click="showCart" />
      </a-badge>
      <AnOutlinedMenu class="icon iconHidden" @click="showMenu" />
      <MenuComponent v-if="isOpenMenu" @close-menu="showMenu" />
      <a-flex class="items-center whitespace-nowrap">
        <a-flex
          vertical
          v-if="isLogin"
          class="icon iconShow group relative items-center"
          ><CaUserAvatarFilledAlt class="text-[40px]" /><span
            class="text-[13px] font-medium mb-2"
            >Hello, {{ firstName }}</span
          >

          <div
            class="hidden group-hover:flex flex-col absolute bg-white text-black left-[-10px] border-[1px] border-gray-200 top-[50px] rounded-md mt-3 text-[17px] overflow-hidden"
          >
            <RouterLink
              to="/profile"
              class="hover:bg-[#02B6AC] hover:text-white px-3"
              >Profile</RouterLink
            >
            <RouterLink
              to="#"
              @click="showLogoutConfirm"
              class="hover:bg-[#02B6AC] hover:text-white px-3"
            >
              Logout
            </RouterLink>
          </div>
        </a-flex>
        <RouterLink to="/login" v-else>
          <a-flex
            class="px-4 py-2 justify-center items-center bg-white text-[#02B6AC] font-bold rounded-md cursor-pointer peer-hover:animate-ping transition-transform hover:scale-105"
            >Đăng nhập</a-flex
          >
        </RouterLink>
      </a-flex>
    </a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import MenuComponent from "../MenuComponent.vue";
import SearchComponent from "../SearchComponent.vue";
import { ref, onMounted, computed, watchEffect, watch, createVNode } from "vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import "./header.css";
import {
  BxSearch,
  BsCart2,
  AnOutlinedMenu,
  AnFilledCaretDown,
  CaUserAvatarFilledAlt,
} from "@kalimahapps/vue-icons";
import store from "@/store/store";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute();
const isLogin = ref(false);
const firstName = ref("");
const searchInputHover = ref(false);

const getUser = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API_USER}/profile`,
      {},
      {
        withCredentials: true,
      }
    );
    if (response.status === 204) {
      console.log("Token lỗi hoặc đã hết hạn!");
      sessionStorage.removeItem("user");
      isLogin.value = false;
      return;
    }
    if (response.status === 205) {
      return;
    } else if (response.data) {
      const user = response.data;
      sessionStorage.setItem("user", JSON.stringify(user));
      firstName.value = user.first_name;
      isLogin.value = true;
    } else {
      isLogin.value = false;
    }
  } catch (error) {
    console.error("Failed to fetch user profile:", error);
    if (error.response && error.response.status === 401) {
      console.log("Chưa đăng nhập");
      sessionStorage.removeItem("user");
    }
    isLogin.value = false;
  }
};

const getUserSession = () => {
  const storedUser = sessionStorage.getItem("user");
  if (storedUser) {
    const user = JSON.parse(storedUser);
    firstName.value = user.first_name;
    isLogin.value = true;
    return true;
  }
  return false;
};

const checkUserSession = () => {
  if (!getUserSession()) {
    getUser();
  } else {
    return;
  }
};
const showLogoutConfirm = () => {
  Modal.confirm({
    title: "Chắc chắn đăng xuất?",
    icon: createVNode(ExclamationCircleOutlined),
    onOk() {
      handleLogout();
    },
    onCancel() {},
    class: "test",
  });
};

const handleLogout = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API}/logout`,
      {},
      { withCredentials: true }
    );

    if (response.data) {
      sessionStorage.removeItem("user");
      isLogin.value = false;
      router.push("/");
    }
  } catch (error) {
    console.error("Đăng xuất thất bại:", error.response?.data || error.message);
    alert("Đăng xuất thất bại! Vui lòng kiểm tra lại.");
  }
};

watch(
  () => route.fullPath,
  () => {
    getdata();
    getUserSession();
  }
);

const data = ref({});

const getdata = async () => {
  try {
    const categoryResponse = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/allCategoryParent`
    );

    if (!Array.isArray(categoryResponse.data.allCategoryParent)) {
      console.error("API did not return an array:", categoryResponse.data);
      return;
    }

    const allowedCategories = [
      "may-loc-nuoc",
      "bep-tu",
      "quat-dieu-hoa",
      "binh-nuoc-nong",
    ];

    const categories = categoryResponse.data.allCategoryParent.filter(
      (category) => allowedCategories.includes(category.slug)
    );

    let maxId = 0;

    const productRequests = categories.map((category) =>
      axios
        .get(
          `${import.meta.env.VITE_APP_URL_API_PRODUCT}/product/${category.slug}`
        )
        .catch((error) => {
          console.error(
            `Error fetching products for category: ${category.slug}`,
            error
          );
          return { data: [] };
        })
    );

    const productResponses = await Promise.allSettled(productRequests);

    const categorizedProducts = categories.map((category, index) => {
      if (productResponses[index].status === "fulfilled") {
        const productsInCategory = productResponses[index].value.data;
        const shuffled = productsInCategory.sort(() => 0.5 - Math.random());

        return {
          category,
          products: shuffled.slice(0, 4),
        };
      } else {
        console.error(
          `Failed to fetch products for category: ${category.slug}`
        );
        return {
          category,
          products: [],
        };
      }
    });

    const anotherData = [
      {
        id: ++maxId,
        category: { name: "Tin tức", slug: "news" },
        products: [],
      },
      {
        id: ++maxId,
        category: { name: "Giới thiệu", slug: "about" },
        products: [],
      },
      {
        id: ++maxId,
        category: { name: "Bảo hành", slug: "guaranteeHome" },
        products: [],
      },
      { id: ++maxId, category: { name: "Thư viện" }, products: [] },
    ];

    data.value = [...categorizedProducts, ...anotherData];
  } catch (e) {
    console.log("Error: ", e);
  }
};

watchEffect(() => {
  isLogin.value = !!firstName.value;
});

const fetchData = () => {
  isLogin.value = !!firstName.value;
};

onMounted(() => {
  fetchData();
  checkUserSession();
  getdata();
});

const isOpenMenu = ref(false);
const isOpenSearch = ref(false);
const quantityProductInCart = computed(() => {
  return store.getters["product/getDataStoreCart"].length;
});
const showCart = () => {
  router.push("/cart");
};

const showMenu = () => {
  isOpenMenu.value = !isOpenMenu.value;
};

const showSearch = () => {
  isOpenSearch.value = !isOpenSearch.value;
};

const searchInput = ref("");

const filteredData = computed(() => {
  if (!searchInput.value.trim()) return data1.value;
  return data1.value
    .map((group) => ({
      ...group,
      item: group.item.filter((item) =>
        item.name.toLowerCase().includes(searchInput.value.toLowerCase())
      ),
    }))
    .filter((group) => group.item.length > 0);
});
const data1 = ref([
  {
    id: 1,
    title: "Tìm kiếm nhanh",
    item: [
      { id: 1, name: "Máy lọc nước Livotec 630" },
      { id: 2, name: "Máy lọc nước Livotec 638" },
      { id: 3, name: "Bếp từ đôi LID-888" },
      { id: 4, name: "Bình nước nóng LWH-ID30" },
    ],
  },
  {
    id: 2,
    title: "Từ khóa tìm kiếm nhiều",
    item: [
      { id: 1, name: "Bếp từ đôi" },
      { id: 2, name: "Máy lọc nước Livotec 216" },
      { id: 3, name: "Máy lọc nước Livotec 616" },
      { id: 4, name: "Bình nước nóng" },
    ],
  },
  {
    id: 3,
    title: "Từ khóa tìm kiếm nhiều",
    item: [
      { id: 1, name: "Bếp từ đôi" },
      { id: 2, name: "Máy lọc nước Livotec 216" },
      { id: 3, name: "Máy lọc nước Livotec 616" },
      { id: 4, name: "Bình nước nóng" },
    ],
  },
]);

const handleChangeToProductDetails = (value) => {
  alert("Chưa có data để đổi trang");
};

const handleBlur = () => {
  setTimeout(() => {
    searchInputHover.value = false;
  }, 200);
};

const handleMouseLeave = () => {
  setTimeout(() => {
    searchInputHover.value = false;
  }, 200);
};
</script>

<style scoped></style>
