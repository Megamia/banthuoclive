<template>
  <DefaultLayout class="bg-[#F8F7F5]">
    <a-flex vertical class="w-[100%] px-[20px] pt-[60px] a">
      <a-flex class="w-full gap-[50px] mb-[80px]">
        <a-flex vertical class="flex-1">
          <h1 class="text-[31px] text-[#2268DE] font-bold mb-4 uppercase">
            Về chúng tôi
          </h1>
          <span class="text-[15px] leading-[25px] font-medium"
            >Livotec là một thương hiệu điện gia dụng đa ngành, được thành lập
            với sứ mệnh giúp người Việt SỐNG KHỎE MỖI NGÀY. Với tầm nhìn dài
            hạn, Livotec đầu tư và phát triển để trở thành một thương hiệu điện
            gia dụng không chỉ đáp ứng nhu cầu của người dân Việt Nam mà còn
            vươn ra thị trường quốc tế.<br /><br />

            Livotec định vị là thương hiệu cung cấp các sản phẩm chất lượng tốt
            mang đến sự chăm sóc sức khỏe và tiện lợi cho khách hàng trong phân
            khúc phổ thông và cao cấp hơn.<br /><br />

            Bên cạnh CHẤT LƯỢNG VƯỢT TRỘI, các sản phẩm của Livotec còn chú
            trọng đến yếu tố AN TOÀN – BỀN BỈ, đồng thời giúp tiết kiệm chi phí,
            phù hợp với người tiêu dùng có thu nhập phổ thông và trên phổ
            thông.</span
          >
        </a-flex>
        <a-flex class="flex-1">
          <img
            src="https://livotec.com/wp-content/uploads/2024/05/KV-Livotec-PC-1.jpg.webp"
            class="w-full h-1/2"
          />
        </a-flex>
      </a-flex>
      <a-flex v-for="item in anotherNews" :key="item.id">
        <a-flex vertical class="flex-1 justify-center items-center ">
          <span class="text-[2rem] font-bold ">
            {{ item.title }}
          </span>
          <span class="text-black text-[14px] font-medium mb-[20px]">
            {{ item.description }}
          </span>
          <img :src="item.img" class="mb-[20px]"/>
        </a-flex>
      </a-flex>
    </a-flex>
  </DefaultLayout>
</template>

<script setup>
import { ref } from "vue";
import DefaultLayout from "../layouts/DefaultLayout.vue";

const anotherNews = ref([
  {
    id: 1,
    title: "ĐẠT CHUẨN QCVN 6-1:2010/BYT",
    description:
      "Máy lọc nước Livotec đạt chuẩn nước nước uống tinh khiết chuẩn đóng chai QCVN 6-1:2010/BYT do Viện Sức khỏe nghề nghiệp & Môi trường cấp theo tiêu chuẩn khắt khe của Bộ Y Tế và WHO. Nước sau lọc có thể uống trực tiếp tại vòi, không cần đun sôi. ",
    img: "https://livotec.com/wp-content/uploads/2024/08/QCVN-Livotec-PC.jpg",
  },
  {
    id: 2,
    title: "HỆ LÕI LỌC HIỆU SUẤT CAO HIỆN ĐẠI MAX PERFORMANCE",
    description:
      "Hệ lõi lọc hiệu suất cao Max Performance với cụm lõi lọc thô hiện đại, màng RO sản xuất tại Hàn Quốc  có khả năng tăng gấp 1,5 lần hiệu suất lọc, loại bỏ đến 99,99% tạp chất, chất hữu cơ, chất gây mùi, vi khuẩn, virus, ion kim loại nặng có trong nước. Bên cạnh đó, tăng gấp 1,5 lần tuổi thọ lõi lọc, giúp người dùng tiết kiệm thời gian và chi phí thay lõi trong quá trình sử dụng. Đặc biệt, qua hệ lõi chức năng nước được bổ sung thêm hàm lượng lớn khoáng chất, tốt cho sức khỏe.  ",
    img: "https://livotec.com/wp-content/uploads/2024/05/pc_loi-loc-1-1.jpg",
  },
  {
    id: 3,
    title: "DỊCH VỤ 5 SAO, BẢO HÀNH ĐẾN 36 THÁNG",
    description:
      "Livotec mang đến dịch vụ chăm sóc khách hàng chuẩn 5 sao, thời gian bảo hành lên đến 36 tháng cho mọi sản phẩm để người dùng an tâm chăm sóc sức khỏe mỗi ngày.  ",
    img: "https://livotec.com/wp-content/uploads/2024/04/livotec-bao-hanh-36-thang-dich-vu-5-sao.jpg.webp",
  },
]);
</script>

<style scoped></style>
