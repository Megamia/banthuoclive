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
    <p v-if="isLoading" class="text-blue-500 text-center">Đang nhận diện hình ảnh...</p>
    <div
      v-else-if="isAnalyzed && result && filteredConcepts.length > 0"
      class="gap-[30px] flex flex-col"
    >
      <a-flex vertical class="text-[30px] gap-[10px]">
        <h3>Tên sản phẩm:</h3>
        <ul v-if="filteredConcepts.length > 0">
          <li
            v-for="(concept, index) in filteredConcepts"
            :key="index"
            class="capitalize text-[15px]"
          >
            {{ concept.name || "Không tìm thấy" }} ({{
              (concept.value * 100).toFixed(2)
            }}%)
          </li>
        </ul>
      </a-flex>
      <a-flex vertical class="text-[30px] gap-[20px]">
        <h3>Sản phẩm tương tự:</h3>
        <div v-if="similarProducts.length > 0" class="flex flex-wrap gap-4">
          <div
            v-for="product in similarProducts"
            :key="product.id"
            class="text-center flex-1"
          >
            <a-flex
              vertical
              class="items-center gap-[10px] border-[1px] p-[10px] h-[200px] overflow-hidden"
            >
              <img
                :src="product.imageUrl"
                alt="Similar Product"
                class="w-24 h-24 object-cover"
              />
              <p class="text-sm font-bold">{{ product.name }}</p>
            </a-flex>
          </div>
        </div>
      </a-flex>
    </div>
    <p v-else-if="isAnalyzed" class="text-gray-500 text-center">
      Chưa nhận diện được sản phẩm này!!
    </p>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const imageUrl = ref("");
const result = ref(null);
const similarProducts = ref([]);
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

const analyzeImage = async () => {
  if (!imageUrl.value) {
    error.value = "Vui lòng cung cấp ảnh!";
    return;
  }
  error.value = null;
  isLoading.value = true;
  isAnalyzed.value = true;
  result.value = null;

  try {
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
      throw new Error("Không tìm thấy kết quả từ cả hai mô hình!");
    }

    result.value = clarifaiResponse;

    const validConcepts = filteredConcepts.value;
    if (validConcepts.length === 0) {
      error.value = "Không tìm thấy sản phẩm hợp lệ.";
      return;
    }

    fetchTikiProducts(validConcepts.map((c) => c.name));
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
  if (
    !result.value ||
    !result.value.outputs ||
    !result.value.outputs[0].data ||
    !result.value.outputs[0].data.concepts
  ) {
    alert("Không có concepts nào được nhận diện.");
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
    console.error("❌ Lỗi khi fetch sản phẩm từ Tiki:", err);
  }
};
</script>
