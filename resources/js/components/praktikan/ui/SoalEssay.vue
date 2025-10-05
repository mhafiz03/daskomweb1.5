<template>
  <div class="w-full flex-row h-auto">
    <div class="w-full h-auto flex my-10">
      <div class="h-full w-12 flex font-merri-bold text-xl">
        <div class="m-auto">{{ index }}</div>
      </div>
      <div class="h-12 px-1 w-4">
        <div class="h-full w-full bg-gray-900" />
      </div>
      <div class="h-full flex px-2 font-monda text-2xl break-words whitespace-pre-wrap">
        <span>{{ soal }}</span>
      </div>
    </div>
    <div class="w-full h-auto flex px-5">
      <slot />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  index: { type: Number, required: true },
  soal: { type: String, required: true },
});
</script>
