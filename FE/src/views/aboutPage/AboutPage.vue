<template>
  <a-flex vertical class="w-[100%] px-[20px] pt-[60px] a">
    <a-flex class="w-full gap-[50px] mb-[80px]">
      <a-flex vertical class="flex-1">
        <h1 class="text-[31px] text-[#2268DE] font-bold mb-4 uppercase">
          Về chúng tôi
        </h1>
        <span class="text-[15px] leading-[25px] font-medium"
          >Live Pharmacy là thương hiệu chuyên cung cấp các sản phẩm chăm sóc
          sức khỏe và dược phẩm chất lượng cao, với sứ mệnh giúp người Việt SỐNG
          KHỎE MỖI NGÀY. Chúng tôi cam kết mang đến những sản phẩm an toàn, hiệu
          quả và phù hợp với nhu cầu chăm sóc sức khỏe của mọi gia đình.<br /><br />
          Với tầm nhìn dài hạn, Live Pharmacy không ngừng đầu tư và phát triển
          để trở thành hệ thống nhà thuốc uy tín, không chỉ phục vụ khách hàng
          trong nước mà còn hướng đến thị trường quốc tế.<br /><br />
          Chúng tôi cung cấp đa dạng các sản phẩm, từ thuốc kê đơn, thực phẩm
          chức năng, vitamin, sản phẩm chăm sóc cá nhân đến các thiết bị y tế,
          đáp ứng nhu cầu từ cơ bản đến nâng cao.</span
        >
      </a-flex>
      <a-flex class="flex-1">
        <img
          src="https://subiz.com.vn/blog/wp-content/uploads/2024/01/subiz-kien-nhan-voi-khach-hang.jpg"
          class="flex flex-1 h-[80%] "
        />
      </a-flex>
    </a-flex>
    <a-flex v-for="item in anotherNews" :key="item.id">
      <a-flex vertical class="flex-1 justify-center items-center">
        <span class="text-[2rem] font-bold">
          {{ item.title }}
        </span>
        <span class="text-black text-[14px] font-medium mb-[20px]">
          {{ item.description }}
        </span>
        <img :src="item.img" class="mb-[20px]" />
      </a-flex>
    </a-flex>
  </a-flex>
</template>

<script setup>
import { ref } from "vue";
import DefaultLayout from "../layouts/DefaultLayout.vue";

const anotherNews = ref([
  {
    id: 1,
    title: "Live Pharmacy – Sản phẩm đạt chuẩn GMP & WHO",
    description:
      "Live Pharmacy cam kết cung cấp các sản phẩm chăm sóc sức khỏe đạt tiêu chuẩn GMP, WHO và được Bộ Y tế kiểm định nghiêm ngặt. Chúng tôi mang đến giải pháp sức khỏe an toàn, hiệu quả cho mọi gia đình, giúp người Việt SỐNG KHỎE MỖI NGÀY.",
    img: "https://phongsachgmp.vn/wp-content/uploads/2019/05/gmp-who.jpg",
  },
  {
    id: 2,
    title: "SẢN PHẨM CHẤT LƯỢNG CAO – AN TOÀN & HIỆU QUẢ",
    description:
      "Live Pharmacy cung cấp dược phẩm và thực phẩm chức năng đạt tiêu chuẩn chất lượng cao, an toàn và hiệu quả, giúp chăm sóc sức khỏe toàn diện. Chúng tôi không ngừng cải tiến để mang đến sản phẩm tốt nhất cho người tiêu dùng.",
    img: "https://www.vinamilk.com.vn/phat-trien-ben-vung/bao-cao/2022/images/vnm/share-vi.png",
  },
  {
    id: 3,
    title: "DỊCH VỤ CHĂM SÓC KHÁCH HÀNG CHUẨN 5 SAO",
    description:
      "Live Pharmacy cung cấp dịch vụ chăm sóc khách hàng tận tâm, chuyên nghiệp, với chính sách hỗ trợ linh hoạt, giúp khách hàng an tâm khi sử dụng sản phẩm và dịch vụ của chúng tôi.",
    img: "https://vietnambusinessinsider.vn/zoom-share/1200x630/uploads/wp-content/uploads/2020/06/customerservice1-1024x620.jpg",
  },
]);
</script>

