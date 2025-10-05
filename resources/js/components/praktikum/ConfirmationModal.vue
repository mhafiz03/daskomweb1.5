<template>
  <div
    class="w-full h-full flex absolute bg-black opacity-90 z-30 top-0"
    :class="[open ? 'visible' : 'hidden']"
  >
    <div class="w-3/4 h-60 flex-row m-auto items-end z-40">
      <div class="w-full h-1/2 font-monda-bold text-center items-end flex text-3xl text-white">
        <div class="mx-auto" v-html="message" />
      </div>
      <div class="w-full h-1/2 flex">
        <div class="w-1/2 h-full p-4">
          <button
            type="button"
            class="w-full h-full p-4 cursor-pointer hover:p-5 animation-enable-short"
            @click="$emit('cancel')"
          >
            <div class="w-full h-full font-overpass-bold text-xl text-white flex pt-1 rounded-full bg-red-600">
              <div class="m-auto">
                {{ cancelLabel }}
              </div>
            </div>
          </button>
        </div>
        <div class="w-1/2 h-full p-4">
          <button
            type="button"
            class="w-full h-full p-4 cursor-pointer hover:p-5 animation-enable-short"
            @click="$emit('confirm')"
          >
            <div class="w-full h-full font-overpass-bold text-xl text-white flex pt-1 rounded-full bg-green-600">
              <div class="m-auto">
                {{ confirmLabel }}
              </div>
            </div>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  open: { type: Boolean, default: false },
  message: { type: String, default: '' },
  cancelLabel: { type: String, default: 'Cancel' },
  confirmLabel: { type: String, default: 'Confirm' },
});
</script>
