<template>
  <div class="flex flex-col gap-6">
    <h1 class="text-center font-bold text-[30px]">TÌM KIẾM SẢN PHẨM</h1>

    <input type="file" @change="handleFileUpload" />
    <input
      type="text"
      v-model="imageUrl"
      placeholder="Image URL"
      class="border-[1px] p-[5px]"
    />

    <button
      @click="analyzeImage"
      class="bg-[#007BFF] p-[10px] rounded-md text-white text-[20px]"
    >
      Phân tích hình ảnh
    </button>

    <div v-if="error" class="text-red-500">{{ error }}</div>
    <p v-if="isLoading" class="text-blue-500 text-center">
      Đang nhận diện hình ảnh...
    </p>

    <div v-else-if="isAnalyzed">
      <a-flex vertical class="text-[30px] gap-[10px]">
        <h3>
          Tên sản phẩm:
          <span v-if="filteredConcepts.length > 0">
            {{
              filteredConcepts
                .map((c) => `${c.name} (${(c.value * 100).toFixed(2)}%)`)
                .join(", ")
            }}
          </span>
          <span v-else>Không tìm thấy</span>
        </h3>
      </a-flex>

      <a-flex vertical class="text-[30px] gap-[20px]">
        <div v-if="productsFromDB.length > 0" class="flex flex-col gap-4">
          <h3>Sản phẩm tìm được trong kho:</h3>
          <a-flex class="gap-4 flex-wrap overflow-hidden">
            <div
              v-for="product in productsFromDB"
              :key="product.id"
              class="text-center flex-1 border p-4 flex items-center flex-col gap-[10px] min-h-[200px] overflow-hidden"
            >
              <img
                :src="product.image.path || 'https://via.placeholder.com/100'"
                class="w-24 h-24 object-cover"
              />
              <a
                :href="`product/${product.slug}`"
                class="text-sm font-bold capitalize hover:bg-white hover:text-[#2268DE] nameProduct"
                >{{ product.name }}</a
              >
              <p class="text-sm text-red-500">
                {{ formatCurrency(product.price) }}
              </p>
            </div>
          </a-flex>
        </div>
        <div v-else>
          <p>Không tìm thấy sản phẩm trong kho.</p>
        </div>
      </a-flex>

      <button
        @click="fetchTikiProducts(filteredConcepts.map((c) => c.name))"
        class="bg-[#007BFF] text-white p-2 rounded-md mt-5"
      >
        Tìm trên Tiki
      </button>

      <a-flex vertical class="text-[30px] gap-[20px]">
        <div v-if="similarProducts.length > 0" class="flex flex-col">
          <h3 class="mb-[10px]">Sản phẩm tương tự trên Tiki:</h3>
          <a-flex class="gap-4 flex-wrap overflow-hidden">
            <div
              v-for="product in similarProducts"
              :key="product.id"
              class="flex flex-col p-4 min-w-[150px] flex-1 min-h-[200px] max-h-[300px] text-center items-center gap-[10px] border"
            >
              <img :src="product.imageUrl" class="w-24 h-24 object-cover" />
              <p class="text-sm font-bold nameProduct">
                {{ product.name }}
              </p>
              <p class="text-sm text-red-500">{{ product.price }}</p>
              <a
                :href="product.link"
                target="_blank"
                class="text-blue-500 whitespace-nowrap text-[20px] hover:bg-[white] hover:underline"
                >Xem trên Tiki</a
              >
            </div>
          </a-flex>
        </div>
      </a-flex>
    </div>

    <p v-else class="text-gray-500 text-center">
      Chưa nhận diện được sản phẩm này!!
    </p>
  </div>
</template>
<script setup>
import { getDataFromIndexedDB } from "@/store/indexedDB";
import { ref, computed } from "vue";

const imageUrl = ref("");
const result = ref(null);
const similarProducts = ref([]);
const productsFromDB = ref([]);
const isLoading = ref(false);
const isAnalyzed = ref(false);
const error = ref(null);

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    const base64String = reader.result.split(",")[1];
    imageUrl.value = `data:${file.type};base64,${base64String}`;
  };
  reader.readAsDataURL(file);
};

const categoryMapping = {
  Vitamin: "Vitamin & Khoáng chất",
  "Cải thiện chức năng tăng cường": "Cải thiện tăng cường chức năng",
  "Thuốc tiêu hóa gan mật": "Thuốc tiêu hoá & gan mật",
  "Cơ xương khớp": "Cơ-xương-khớp",
};

const analyzeImage = async () => {
  if (!imageUrl.value) {
    error.value = "Vui lòng cung cấp ảnh!";
    return;
  }

  error.value = null;
  isLoading.value = true;
  isAnalyzed.value = true;
  result.value = null;
  productsFromDB.value = [];

  try {
    const dataProduct = await getDataFromIndexedDB("products");

    let clarifaiResponse = await callClarifaiAPI("thuoc");

    if (
      !clarifaiResponse ||
      !clarifaiResponse.outputs[0].data.concepts.some((c) => c.value >= 0.5)
    ) {
      clarifaiResponse = await callClarifaiAPI(
        "aaa03c23b3724a16a56b629203edc62c"
      );
    }

    if (!clarifaiResponse) {
      throw new Error("Không tìm thấy kết quả từ mô hình!");
    }

    result.value = clarifaiResponse;

    let detectedCategory = result.value.outputs[0].data.concepts[0].name.trim();

    detectedCategory = detectedCategory.replace(/\s+/g, " ");
    detectedCategory = categoryMapping[detectedCategory] || detectedCategory;

    productsFromDB.value = dataProduct.filter(
      (item) => item.category?.name === detectedCategory
    );

    if (productsFromDB.value.length === 0) {
      console.log("Không tìm thấy sản phẩm trong kho.");
    }
  } catch (err) {
    error.value = err.message;
  } finally {
    isLoading.value = false;
  }
};

const callClarifaiAPI = async (modelId) => {
  const response = await fetch(
    `https://api.clarifai.com/v2/models/${modelId}/outputs`,
    {
      method: "POST",
      headers: {
        Authorization: `Key ${import.meta.env.VITE_CLARIFAI_API_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        inputs: [
          {
            data: {
              image: imageUrl.value.startsWith("data:image")
                ? { base64: imageUrl.value.split(",")[1] }
                : { url: imageUrl.value },
            },
          },
        ],
      }),
    }
  );

  const data = await response.json();

  if (
    data.status.code !== 10000 ||
    !data.outputs ||
    !data.outputs[0].data.concepts
  ) {
    return null;
  }
  return data;
};

const filteredConcepts = computed(() => {
  if (!result.value || !result.value.outputs[0]?.data?.concepts) {
    return [];
  }
  return result.value.outputs[0].data.concepts
    .filter((c) => c.value >= 0.5)
    .sort((a, b) => b.value - a.value)
    .slice(0, 3);
});

const fetchTikiProducts = async (keywords) => {
  if (!keywords.length) return;

  let keyword = keywords[0];

  try {
    const response = await fetch(
      `https://tiki.vn/api/v2/products?limit=6&q=${encodeURIComponent(
        keyword
      )}`,
      { method: "GET" }
    );

    const data = await response.json();

    if (!data || !data.data) throw new Error("Không tìm thấy sản phẩm.");

    similarProducts.value = data.data.map((product) => ({
      id: product.id,
      imageUrl:
        product.thumbnail_url ||
        "https://img.freepik.com/premium-vector/no-data-concept-illustration_86047-488.jpg?semt=ais_hybrid",
      name: product.name,
      price: product.price.toLocaleString() + " VND",
      link: `https://tiki.vn/${product.url_path}`,
    }));
  } catch (err) {
    error.value = "Lỗi khi tìm kiếm sản phẩm trên Tiki.";
    console.error("Lỗi khi fetch sản phẩm từ Tiki:", err);
  }
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};
</script>
<style scoped>
.nameProduct {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
