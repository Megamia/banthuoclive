<template>
  <a-flex vertical class="bg-[#F3F4F6] rounded-lg pb-[20px] w-[300px]">
    <a-flex vertical align="center" class="flex-1">
      <div class="w-full relative pt-[20px] justify-center flex">
        <img
          :src="product.image?.path || 'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'"
          alt="Product Image"
          class="h-[300px] w-[300px]"
        />
        <div
          v-if="product.sold_out >= 10"
          class="absolute bg-[#ffdc37] top-[20px] right-0 rounded-l-md z-10 bestseller"
        >
          <span class="text-black"
            >Bán chạy <br />
            nhất</span
          >
        </div>
        <div
          v-else
          class="absolute bg-[#e20008] top-[20px] right-0 rounded-l-md label z-10 new"
        >
          <span class="font-bold text-white">Mới nhất</span>
        </div>
        <div
          class="absolute cursor-pointer h-[100%] top-0 w-[100%] bg-gradient-to-r from-black/50 to-black/50 text-white p-2 description description-nav rounded-t-lg z-20"
        >
          <div
            v-html="product.description ? product.description : 'Chưa có mô tả'"
            class="max-h-[90%] overflow-y-scroll text-left test"
          ></div>
        </div>
      </div>
      <a-flex class="px-[10px] w-[70%] h-full text-center">
        <a-flex gap="12" vertical class="flex-1">
          <span
            class="text-[16px] font-bold w-[100%] hover:text-[#02B6AC] cursor-pointer line-clamp-2 h-[48px] mt-[10px]"
          >
            {{ product.name ? product.name : "Chưa có tên" }}
          </span>
          <span class="text-[16px] font-bold text-[#02B6AC]">
            {{ product.price ? formatCurrency(product.price) : "Chưa có giá" }}
          </span>
          <a-flex vertical class="gap-[10px] text-[16px]">
            <button
              class="flex-1 font-bold px-[12px] py-[10px] rounded-[9999px] text-white hover:bg-[#CC020B] bg-[linear-gradient(270deg,_#e20008_0%,_rgba(226,_0,_8,_0.7)_100%,_rgba(226,_0,_8,_0.68)_100%)] shadow-[#ff0000] shadow-sm"
            >
              Mua ngay
            </button>

            <button
              class="flex-1 font-sans border-[1px] border-[#4fa8e7] px-[12px] py-[10px] rounded-full text-white bg-[#02b6ac] hover:bg-[linear-gradient(270deg,_#ccf7fb_2.05%,_#fff_100%)] hover:text-[#424242]"
              @click="handleProductDetail(product.slug)"
            >
              Chi tiết
            </button>
          </a-flex>
        </a-flex>
      </a-flex>
    </a-flex>
  </a-flex>
</template>

<script setup>
import { defineProps } from "vue";
import { useRouter } from "vue-router";
import "./ProductComponent.css";
import "./NavProductComponent.css"; 

const router = useRouter();
const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const handleProductDetail = (items) => {
  router.push(`/product/${items}`);
};
</script>

<style scoped>
.bestseller span::after {
  content: "";
  display: block;
  width: 0;
  height: 0;
  border-right: 10px solid #e3c849;
  border-bottom: 10px solid transparent;
  position: absolute;
  bottom: -10px;
  right: 0;
}

.bestseller span {
  text-align: center;
  font-size: 14px;
  line-height: 13px;
  font-weight: 700;
  min-height: 38px;
  justify-content: center;
  display: flex;
  padding: 7px 10px;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
  position: relative;
}
</style>

<style>
.test {
  padding: 10px 20px;
}

.test ul li::before {
  content: "•";
  margin-right: 0.5rem;
  position: absolute;
  left: 0;
  top: 0;
}
.test ul li {
  position: relative;
  padding-left: 10px;
  color: white;
}
.test::-webkit-scrollbar {
  width: 5px;
}

.test::-webkit-scrollbar-track {
  background: #9b9b9c;
  border-radius: 4px;
}
.test::-webkit-scrollbar-thumb {
  background: #f0f0f0;
  border-radius: 4px;
}

.test::-webkit-scrollbar-thumb:hover {
  background: #f0f0f0;
}

.test::-webkit-scrollbar {
  width: 5px;
}

.test::-webkit-scrollbar-track {
  background: #9b9b9c;
  border-radius: 4px;
}
.test::-webkit-scrollbar-thumb {
  background: #f0f0f0;
  border-radius: 4px;
}

.test::-webkit-scrollbar-thumb:hover {
  background: #f0f0f0;
}
</style>
