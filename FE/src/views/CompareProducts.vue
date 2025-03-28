<template>
  <div class="w-full flex flex-1 flex-col">
    <div class="w-full border-b-[1px] border-gray-300 py-3 mb-4">
      <span class="text-[#2268DE] font-bold">So sánh sản phẩm</span>
    </div>
    <div class="w-full flex justify-center items-center">
      <h1 class="text-[#2268DE] font-bold text-3xl">SO SÁNH SẢN PHẨM</h1>
    </div>
    <div class="product-comparison p-[10px] overflow-x-auto" v-if="haveData">
      <table>
        <thead>
          <tr class="remove">
            <th>&nbsp;</th>
            <td v-for="(item, index) in specs" :key="index" class="text-center">
              <span @click="deleteItem(item.id)" class="spanDel"
                >Xóa
                <span class="x">x</span>
              </span>
            </td>
          </tr>
        </thead>
        <tbody>
          <tr class="name">
            <th>Tên sản phẩm</th>
            <td v-for="(item, index) in specs" :key="index" class="text-center">
              {{ item.name ? item.name : "Chưa có tên" }}
            </td>
          </tr>
          <tr class="price">
            <th>Giá</th>
            <td
              v-for="(item, index) in specs"
              :key="index"
              class="text-center text-[#02B6AC] font-bold"
              :style="{ color: '#02B6AC !important' }"
            >
              {{ formatCurrency(item.price) }}
            </td>
          </tr>
          <tr class="desc">
            <th>Mô tả</th>
            <td v-for="(item, index) in specs" :key="index" class="text-center">
              <div
                v-html="item.description ? item.description : 'Chưa có mô tả'"
                class="description"
              />
            </td>
          </tr>
          <tr v-for="title in allThongSoTitles" :key="title.id">
            <th>{{ title ? title : "Chưa có dữ liệu" }}</th>

            <td v-for="item in specs" :key="item.id" class="text-center">
              {{
                getThongSoValue(item.thongso, title)
                  ? getThongSoValue(item.thongso, title)
                  : "Chưa có dữ liệu thông số"
              }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div
      v-else
      class="flex flex-1 justify-center items-center my-[20px] border-[1px] border-[ #dddddd]"
    >
      <span>Không có sản phẩm nào được thêm vào bảng so sánh.</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import store from "@/store/store";

const specs = ref([]);
const haveData = ref(false);

onMounted(() => fetchData());

const fetchData = () => {
  const dataStore = store.getters["product/getDataStoreProducts"];
  if (dataStore && dataStore.length > 0) {
    specs.value = dataStore;
    haveData.value = true;
  } else {
    haveData.value = false;
  }
};
const deleteItem = async (itemId) => {
  store
    .dispatch("product/deleteItemProduct", itemId)
    .then(() => {
      alert(`Sản phẩm với id ${itemId} đã bị xóa thành công.`);
      fetchData();
    })
    .catch((error) => {
      console.error("Lỗi khi xóa sản phẩm:", error);
    });
};
const allThongSoTitles = computed(() => {
  const dataStore = store.getters["product/getDataStoreProducts"];
  if (!dataStore || dataStore.length <= 1) return null;

  const allTitles = new Set();
  dataStore.forEach((item) => {
    item.thongso?.forEach((thongso) => {
      allTitles.add(thongso.thuoc_tinh.trim()); 
    });
  });

  return [...allTitles];
});

const getThongSoValue = (thongsoList, thuoc_tinh) => {
  if (!thongsoList) return "-";

  const found = thongsoList.find(
    (t) => t.thuoc_tinh.trim() === thuoc_tinh.trim()
  );
  return found ? found.gia_tri.trim() || "-" : "-";
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};
</script>

<style scoped>
.product-comparison table {
  border: 1px solid #dddddd;
  table-layout: auto;
  width: 100%;
}
.product-comparison th,
.product-comparison td {
  border: 1px solid #dddddd;
  color: #747373;
}
.product-comparison table td {
  width: 220px;
  max-width: 220px;
  min-width: 220px;
}

.product-comparison table th {
  width: 150px;
  max-width: 150px;
  min-width: 150px;
  overflow: hidden;
  text-align: left;
  color: #333333;
  background-color: #f2f2f2;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 14px;
}
.product-comparison tbody th,
.product-comparison th {
  height: 41px;
  border: 1px solid #dddddd;
}

.product-comparison tbody tr th {
  padding-left: 12px;
}
td:nth-child(even) {
  background-color: #f7f7f7;
}

td:nth-child(odd) {
  background-color: #ffffff;
}
.secs {
  min-width: 220px;
  max-width: 220px;
}
.secs2 {
  width: 100%;
}
</style>

<style>
.description ul {
  list-style: none;
  padding-inline-start: 40px;
}

.description ul li {
  position: relative;
  padding-left: 1.5em;
  margin-top: 10px;
}

.description ul li::before {
  content: "•";
  color: #747373;
  font-size: 1.5em;
  position: absolute;
  left: 10px;
  top: -5px;
}
</style>

<style lang="scss">
.spanDel {
  cursor: pointer;
  .x {
    border-radius: 100% 100% 100% 100%;
    color: red;
    display: inline-block;
    font-size: 1em;
    font-weight: 600;
    height: 1em;
    line-height: 0.81em;
    text-align: center;
    text-decoration: none;
    width: 1em;
  }
  &:hover {
    color: black;

    .x {
      background-color: red;
      color: white;
    }
  }
}
</style>
