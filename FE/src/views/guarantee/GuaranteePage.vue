<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex vertical class="flex-1 w-full">
    <img
      src="https://getflycrm.com/wp-content/uploads/2023/11/khach-hang-muc-tieu-cua-khach-san-5-sao.webp"
      class="imgGuarantee"
    />
    <a-flex vertical justify="center" class="flex-1 items-center">
      <a-flex vertical class="a">
        <h2 class="h2Text">Dịch vụ 5 sao, bảo hành tới 36 tháng</h2>
        <span class="text-[16px] font-normal text-black">
          Live Pharmacy là thương hiệu chuyên cung cấp các sản phẩm chăm sóc
          sức khỏe và dược phẩm chất lượng cao, với sứ mệnh giúp người Việt SỐNG
          KHỎE MỖI NGÀY. Chúng tôi cam kết mang đến những sản phẩm an toàn, hiệu
          quả và phù hợp với nhu cầu chăm sóc sức khỏe của mọi gia đình.<br /><br />
          Với tầm nhìn dài hạn, Live Pharmacy không ngừng đầu tư và phát
          triển để trở thành hệ thống nhà thuốc uy tín, không chỉ phục vụ khách
          hàng trong nước mà còn hướng đến thị trường quốc tế.<br /><br />
          Chúng tôi cung cấp đa dạng các sản phẩm, từ thuốc kê đơn, thực phẩm
          chức năng, vitamin, sản phẩm chăm sóc cá nhân đến các thiết bị y tế,
          đáp ứng nhu cầu từ cơ bản đến nâng cao.
        </span>
      </a-flex>
    </a-flex>

    <a-flex vertical class="contentDiv mb-[66px] flex-1">
      <h2 class="h2Text">Sản phẩm nổi bật</h2>

      <a-flex justify="center" class="flex-1 pb-[77px] max-w-[100%] ">
        <SlideGuaranteeComponent />
      </a-flex>
      <a-flex justify="center">
        <button
          class="bg-[#2268DE] text-white rounded-3xl w-[205px] py-[11px] text-[18px] font-semibold"
        >
          Nhận tư vấn
        </button>
      </a-flex>
    </a-flex>

    <a-flex vertical class="contentDiv">
      <span
        class="text-[#2268DE] text-[32px] font-bold leading-[46px] text-center"
      >
        Cửa hàng thuốc Live
      </span>
      <span
        class="text-[#2268DE] text-[32px] font-medium leading-[46px] text-center"
      >
        07 LÝ DO NÊN LỰA CHỌN Cửa hàng thuốc Live
      </span>
      <span class="text-[22px] font-normal mb-71px text-center"
        >Hàng triệu gia đình việt đã lựa chọn và hài lòng</span
      >
    </a-flex>
    <a-flex vertical class="mb-[80px]">
      <a-flex class="flex flex-wrap justify-center text-center">
        <a-flex
          vertical
          v-for="(item, index) in dataFixed"
          :key="index"
          style="flex-basis: calc((100% - 150px) / 5)"
          class="mr-[30px] mb-[120px] items-center"
        >
          <img :src="item.path" class="w-[186px] h-[100%] mb-[18px]" />
          <span
            class="text-center text-[18px] text-black font-normal whitespace-pre-line"
          >
            {{ item.text }}</span
          >
        </a-flex>
      </a-flex>
      <a-flex justify="center">
        <button
          class="bg-[#2268DE] text-white rounded-3xl w-[205px] py-[11px] text-[18px] font-semibold"
        >
          Nhận tư vấn
        </button>
      </a-flex>
    </a-flex>
    <a-flex vertical class="contentDiv">
      <span class="text-[#2268DE] text-[32px] font-bold text-center">
        Đăng ký nhận tư vấn ngay
      </span>

      <span class="text-[22px] font-normal mb-[71p]">
        (Nhận thêm nhiều ưu đãi từ thương hiệu)
      </span>
    </a-flex>
    <a-flex class="contentDiv justify-center">
      <a-flex
        class="a bg-gradient-to-b from-[#c1edff] to-[#2268DE] flex-1 justify-center py-[51px] rounded-[22px]"
      >
        <a-form
          :model="formState"
          @finish="handleSubmit"
          class="form max-w-[496px] w-[100%]"
        >
          <a-form-item>
            <a-input v-model:value="formState.username" placeholder="Họ tên" />
          </a-form-item>
          <a-form-item>
            <a-input
              v-model:value="formState.phoneNumber"
              placeholder="Số điện thoại"
            />
          </a-form-item>
          <a-form-item class="bg-white px-[27px] py-[12px] rounded-[20px]">
            <a-flex vertical class="h-[80px] gap-[10px]">
              <span class="text-[14px] text-[#999] font-normal"
                >Sản phẩm quan tâm</span
              >
              <a-dropdown :trigger="['click']">
                <template #overlay>
                  <a-menu>
                    <a-menu-item
                      v-for="item in data"
                      :key="item.id"
                      @click="handleChangeItem(item)"
                    >
                      {{ item.name }}
                    </a-menu-item>
                  </a-menu>
                </template>
                <a-button
                  class="flex flex-row items-center justify-between rounded-[22.5px]"
                >
                  {{ selectedItem }}
                  <AnFilledCaretDown class="text-[#B4B4B4]" />
                </a-button>
              </a-dropdown>
            </a-flex>
          </a-form-item>
          <a-form-item> Recaptcha </a-form-item>
          <a-form-item>
            <button
              type="primary"
              class="bg-gradient-to-b from-[#ffe818] to-[#ffa800] text-black w-[100%] h-[57px] text-[18px] font-semibold rounded-[15px] hover:text-black"
            >
              Đăng ký ngay
            </button>
          </a-form-item>
        </a-form>
      </a-flex>
    </a-flex>
    <a-flex class="contentDiv justify-center">
      <a-flex class="a">
        <a-flex
          vertical
          class="border-[1px] border-l-0 border-[gray] pr-[30px] py-[20px]"
        >
          <span class="text-[18px] font-bold uppercase mb-[20px] family-[Arial]"
            >Giới thiệu về Cửa hàng thuốc Live
          </span>
          <span class="text-[16px] my-[4px]"> Về Live </span>
          <span class="text-[16px] my-[4px]">
            Chứng nhận Cửa hàng thuốc Live QCVN/BYT
          </span>
        </a-flex>
        <a-flex
          vertical
          class="max-w-[50%] border-[1px] border-x-0 border-[gray] px-[30px] py-[20px]"
        >
          <span class="text-[18px] font-bold uppercase mb-[20px] family-[Arial]"
            >Cửa hàng thuốc Live</span
          >
          <img
            src="/medical-drug-grade-manufacturing-on-blue-background-3d-rendering_9827483-removebg-preview-removebg-preview.png"
          />
        </a-flex>
      </a-flex>
    </a-flex>
    <a-flex class="justify-center mb-0">
      <a-flex class="a flex-1 justify-between footerAfter">
        <a-flex class="max-w-[33%] text-[12px] text-black up">
          <span
            >CÔNG TY TNHH LIVE<br />
            Địa chỉ: Tầng 7, số 113 - 115 Đường Lê Duẩn, Phường Cửa Nam, Quận
            Hoàn Kiếm, thành phố Hà Nội<br />
            Email: livevn@gmail.com<br />
            Điện thoại: 1800 2298</span
          >
        </a-flex>
        <a-flex vertical class="gap-[30px] w-[300px] down">
          <a-flex style="flex-basis: calc(100% - 105px)" class="">
            <span class="flex-1">Kết nối với chúng tôi</span>
            <a-flex class="basis-[105px] justify-between">
              <img
                src="https://baohanh.livotec.com/assetsUpdate/images/icons/fb.png"
                alt=""
                class="max-w-[26px]"
              />
              <img
                src="https://baohanh.livotec.com/assetsUpdate/images/icons/yt.png"
                alt=""
                class="max-w-[26px]"
              />
              <img
                src="https://baohanh.livotec.com/assetsUpdate/images/icons/email.png
"
                alt=""
                class="max-w-[26px]"
              />
            </a-flex>
          </a-flex>
          <a-flex style="flex-basis: calc(100% - 105px)" class="">
            <span class="flex-1"> Tải app karofi </span>
            <a-flex vertical class="basis-[105px] justify-between gap-[10px]">
              <img
                src="https://baohanh.livotec.com/assetsUpdate/images/icons/appstore.png"
                alt=""
                class="max-w-[100%]"
              />
              <img
                src="https://baohanh.livotec.com/assetsUpdate/images/icons/googleplay.png"
                alt=""
                class="max-w-[100%]"
              />
            </a-flex>
          </a-flex>
        </a-flex>
      </a-flex>
    </a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { AnFilledCaretDown } from "@kalimahapps/vue-icons";
import { onMounted, ref } from "vue";
import axios from "axios";
import "./guaranteePage.css";
import SlideGuaranteeComponent from "@/components/guarantee/slideGuarantee/SlideGuaranteeComponent.vue";
import { getDataFromIndexedDB } from "@/store/indexedDB";

const handleSubmit = async () => {};

const formState = ref({
  username: "",
  phoneNumber: "",
});
const dataFixed = ref([
  {
    name: "1",
    path: "https://phongsachgmp.vn/wp-content/uploads/2019/05/gmp-who.jpg",
    text: "Đạt chuẩn GMP - WHO, an toàn & hiệu quả",
  },
  {
    name: "2",
    path: "https://cdn.nhathuoclongchau.com.vn/unsafe/800x0/filters:quality(95)/https://cms-prod.s3-sgn09.fptcloud.com/cach_phan_biet_duoc_pham_chinh_hang_va_hang_xach_tay_don_gian_ban_can_biet_89393fa38d.jpg",
    text: "Thuốc & dược phẩm chính hãng, nguồn gốc rõ ràng",
  },
  {
    name: "3",
    path: "https://vienyduocdantoc.com/wp-content/uploads/2021/07/thuc-pham-chuc-nang-ho-tro-chua-benh-tri.jpg",
    text: "Thực phẩm chức năng hỗ trợ sức khỏe toàn diện",
  },
  {
    name: "4",
    path: "https://cdn2.tuoitre.vn/thumb_w/480/2021/6/2/15286388737940828273262951986749102473157648n-1-16226320314851228811282-crop-1622632145661774901212.png",
    text: "Tư vấn sức khỏe miễn phí từ dược sĩ chuyên môn",
  },
  {
    name: "5",
    path: "https://indochinapost.vn/wp-content/uploads/2017/10/dich-vu-chuyen-phat-cod.jpg",
    text: "Giao hàng tận nơi nhanh chóng, tiện lợi",
  },
  {
    name: "6",
    path: "https://90scoffee.vn/wp-content/uploads/2023/02/chuong-trinh-khuyen-mai-1.jpg",
    text: "Ưu đãi & khuyến mãi hấp dẫn mỗi tháng",
  },
  {
    name: "7",
    path: "https://vlink.vn/wp-content/uploads/2024/04/5-yeu-to-quan-trong-khi-trien-khai-ho-tro-24-7.jpg",
    text: "Dịch vụ tận tâm, chăm sóc khách hàng 24/7",
  },
]);
const selectedItem = ref("");

const data = ref([]);
const fetchData = async () => {
  try {
    const categoryData = await getDataFromIndexedDB("category");
    data.value = categoryData.slice(0, 4);
    selectedItem.value = data.value
      ? data.value[0].name
      : "Chọn 1 loại sản phẩm";
  } catch (e) {
    console.log("Error: ", e);
  }
};

onMounted(() => fetchData());
const handleChangeItem = (value) => {
  selectedItem.value = value.name;
};
</script>

<style scoped>
.h2Text {
  padding-top: 81px;
  padding-bottom: 27px;
  text-align: center;
  color: #2268de;
  font-size: 32px;
  font-weight: 600;
  text-transform: uppercase;
}

.form::v-deep(.ant-form-item) {
  margin-bottom: 12px;
}
.form::v-deep(.ant-form-item) input {
  border-radius: 50px;
  padding-block: 10px;
  padding-inline: 27px;
  border: 1px solid #cccccc;
}
@media only screen and (max-width: 520px) {
  .footerAfter {
    flex-direction: column;
    gap: 20px;
  }
  .footerAfter .up {
    max-width: none;
    width: 100%;
    display: flex;
    flex: 1;
  }
  .footerAfter .down {
    max-width: none;
    width: 100%;
    display: flex;
    flex: 1;
  }
}
@media only screen and (max-width: 320px) {
  .imgGuarantee {
    display: none;
  }
  .h2Text {
    padding-top: 0;
  }
}
</style>
