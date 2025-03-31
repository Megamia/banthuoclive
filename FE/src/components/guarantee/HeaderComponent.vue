<template>
  <a-flex class="flex-1 relative justify-between p-[20px] gap-[30px] main">
    <a-flex>
      <router-link to="/" class="hover:bg-[#007BFF]">
        <img
          src="/medical-drug-grade-manufacturing-on-blue-background-3d-rendering_9827483-removebg-preview.png "
          class="w-[80px] imgLivo ml-[50px]"
        />
      </router-link>
    </a-flex>

    <a-flex class="flex-1 justify-center gap-[100px] content items-center">
      <span
        :class="{
          'active-liText': liActive === '1',
        }"
        class="liText"
        @click="changeLiActive('1')"
      >
        Trang chủ
      </span>
      <span
        :class="{
          'active-liText': liActive === '2',
        }"
        class="liText"
        @click="changeLiActive('2')"
      >
        Kích hoạt bảo hành
      </span>
      <span
        :class="{
          'active-liText': liActive === '3',
        }"
        class="liText"
        @click="changeLiActive('3')"
      >
        Đăng ký bảo hành tận nhà
      </span>
    </a-flex>
  </a-flex>
</template>

<script setup>
import { ref } from "vue";
import "./header.css";

const liActive = ref("1");

const changeLiActive = (value) => {
  liActive.value = value;
};
</script>

<style scoped>
.liText {
  color: white;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  padding-inline: 19px;
  padding-block: 3px;
  border-radius: 3px;
  text-align: center;
  transition: all 0.3s ease-in-out;
}

.liText:hover {
  background-color: white;
  color: #2268de;
  border-radius: 15px;
}

.active-liText {
  background-color: white !important;
  color: #2268de !important;
  border-radius: 15px !important;
  white-space: nowrap;
  overflow: hidden;
  max-width: 100%;
}
@media only screen and (max-width: 720px) {
  .imgLivo {
    display: none;
  }
  .main {
    padding-inline: 0;
  }
  .content {
    gap: 0;
  }
}
</style>
