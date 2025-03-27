<template>
  <div class="flex flex-col gap-6">
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

    <div
      v-if="result && filteredConcepts.length > 0"
      class="gap-[30px] flex flex-col"
    >
      <a-flex vertical class="gap-[10px]">
        <h3>Tên sản phẩm:</h3>
        <ul v-if="filteredConcepts.length > 0">
          <li
            v-for="(concept, index) in filteredConcepts"
            :key="index"
            class="capitalize"
          >
            {{ concept.name || "Không tìm thấy" }} ({{
              (concept.value * 100).toFixed(2)
            }}%)
          </li>
        </ul>
      </a-flex>
      <a-flex vertical class="gap-[10px]">
        <h3>Sản phẩm tương tự:</h3>
        <div v-if="similarProducts.length > 0" class="flex flex-wrap gap-4">
          <div
            v-for="product in similarProducts"
            :key="product.id"
            class="text-center flex-1"
          >
            <img
              :src="product.imageUrl"
              alt="Similar Product"
              class="w-24 h-24 object-cover"
            />
            <p class="text-sm">{{ product.name }}</p>
          </div>
        </div>
      </a-flex>
    </div>
    <p v-else class="text-gray-500">Chưa nhận diện được sản phẩm này!!</p>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const imageUrl = ref("");
const result = ref(null);
const similarProducts = ref([]);
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
  result.value = null;

  try {
    let clarifaiResponse = await callClarifaiAPI("thuoc");

    if (!clarifaiResponse || !clarifaiResponse.outputs[0].data.concepts.some(c => c.value >= 0.5)) {
      clarifaiResponse = await callClarifaiAPI("aaa03c23b3724a16a56b629203edc62c");
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

    fetchSimilarProducts(validConcepts.map((c) => c.name));
  } catch (err) {
    error.value = err.message;
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
    alert("❌ Không có concepts nào được nhận diện.");
    return [];
  }
  return result.value.outputs[0].data.concepts
    .filter((c) => c.value >= 0.5)
    .sort((a, b) => b.value - a.value)
    .slice(0, 3);
});

const fetchSimilarProducts = async (concepts) => {
  if (!concepts.length) return;

  let keyword = concepts[0];

  try {
    const response = await fetch(
      `https://api.unsplash.com/search/photos?query=${encodeURIComponent(
        keyword
      )}&per_page=6&client_id=${import.meta.env.VITE_UNSPLASH_API_KEY}`
    );
    const data = await response.json();

    similarProducts.value = data.results
      .filter((photo) => !photo.alt_description.includes("person"))
      .slice(0, 6)
      .map((photo) => ({
        id: photo.id,
        imageUrl: photo.urls.small,
        name: photo.alt_description || "Ảnh liên quan",
      }));

  } catch (err) {
    error.value = "Lỗi khi tìm kiếm ảnh tương tự.";
  }
};
</script>
