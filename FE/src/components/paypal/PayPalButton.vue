<template>
  <div id="paypal-button-container"></div>
</template>

<script setup>
import { onMounted } from "vue";
import { loadScript } from "@paypal/paypal-js";
import axios from "axios";

const emit = defineEmits(["payment-success"]);

const props = defineProps({
  amount: {
    type: Number,
    required: true,
  },
});

onMounted(async () => {
  const paypal = await loadScript({
    "client-id": import.meta.env.VITE_YOUR_PAYPAL_CLIENT_ID,
    currency: "USD",
  });

  if (!paypal) {
    console.error("PayPal SDK failed to load.");
    return;
  }

  paypal
    .Buttons({
      createOrder: async () => {
        try {
          const response = await axios.post(
            `${import.meta.env.VITE_APP_URL_API_PAYMENT}/createOrder`,
            { amount: props.amount }
          );

          const data = response.data;
          if (data.status === "success") {
            console.log("Đã tạo đơn hàng"); // Kiểm tra orderID
            return data.orderID; // Trả về orderID đúng
          } else {
            console.error("Error creating order:", data.message);
            return null;
          }
        } catch (error) {
          console.error("Error in createOrder:", error);
          return null;
        }
      },

      onApprove: async (data) => {
        try {
          const response = await axios.post(
            `${import.meta.env.VITE_APP_URL_API_PAYMENT}/captureOrder`,
            { orderID: data.orderID }
          );

          const result = response.data;
          console.log("Phản hồi xác nhận thanh toán");

          emit("payment-success", data.orderID);
        } catch (error) {
          console.error("Error in onApprove:", error);
        }
      },
      onError: (err) => {
        console.error("PayPal error:", err);
      },
    })
    .render("#paypal-button-container");
});
</script>
