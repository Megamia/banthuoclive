<template>
  <div v-html="items.summary" class="post"></div>
</template>

<script setup>
defineProps({
  items: {
    required: true,
  },
});
</script>

<style>
.post ul {
  list-style: none;
  padding: 0;
}

.post ul li {
  position: relative;
  padding-left: 1.5em;
  margin-top: 10px;
}

.post ul li::before {
  content: "•";
  color: #38b6ac;
  font-size: 1.5em;
  position: absolute;
  left: 0;
  top: -5px;
}
</style>
