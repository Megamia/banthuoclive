<template>
  <div>
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
</template>

<script setup>


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
</script>

<style scoped></style>
