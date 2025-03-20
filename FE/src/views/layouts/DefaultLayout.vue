<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div>
    <a-flex
      class="justify-center bg-[#2268DE] sticky top-0 z-50 border-b-[1px] border-white"
    >
      <HeaderComponent />
    </a-flex>
    <main
      class="flex flex-col flex-1 gap-[100px] items-center mb-[50px] container p-0"
    >
      <slot></slot>
    </main>
    <div
      class="fixed top-[80%] right-[2%] bg-[red] z-[90] p-2 rounded-lg border-[1px] border-gray-600"
    >
      <button class="text-[30px] text-white" @click="hanldeOpenChatBot">
        ChatBot
      </button>
    </div>
    <a-modal v-model:open="openChat" @ok="handleOk" :style="{ top: '50px' }">
      <ChatBotComponent />
    </a-modal>
    <a-flex class="justify-center bg-[#EAEBF4] bottom-0">
      <FooterComponent />
    </a-flex>
  </div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import ChatBotComponent from "@/components/chatBot/ChatBotComponent.vue";
import FooterComponent from "@/components/home/FooterComponent.vue";
import HeaderComponent from "@/components/home/HeaderComponent.vue";
import { ref } from "vue";

const openChat = ref(false);
const hanldeOpenChatBot = () => {
  openChat.value = true;
};
const handleOk = () => {
  openChat.value = false;
};
</script>

<style>
.container {
  --bs-gutter-x: 1.5rem !important;
  --bs-gutter-y: 0 !important;
  width: 100% !important;
  padding-right: calc(var(--bs-gutter-x) * 0.5) !important;
  padding-left: calc(var(--bs-gutter-x) * 0.5) !important;
  margin-right: auto !important;
  margin-left: auto !important;
}

@media (min-width: 0) {
  .container {
    max-width: 576px !important;
  }
}

@media (min-width: 576px) {
  .container {
    max-width: 720px !important;
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 960px !important;
  }
}

@media (min-width: 992px) {
  .container {
    max-width: 1140px !important;
  }
}

@media (min-width: 1200px) {
  .container {
    max-width: 1320px !important;
  }
}

@media (min-width: 1400px) {
  .container {
    max-width: 1440px !important;
  }
}
</style>
