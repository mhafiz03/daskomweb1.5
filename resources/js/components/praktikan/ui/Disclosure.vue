<template>
  <div
    class="w-full flex absolute"
    :class="[top ? 'top-0 rounded-tl-large' : 'bottom-0 rounded-bl-large', open ? 'bg-green-100 h-12full' : 'bg-green-400 h-12']"
  >
    <div class="w-full h-full relative flex">
      <div
        :class="[top ? 'absolute bottom-0 pb-1' : 'absolute top-0 pt-1', 'h-12 w-full select-none flex font-overpass-mono-bold text-2xl']"
      >
        <button
          type="button"
          class="m-auto"
          :class="[open ? 'text-black' : 'text-yellow-100']"
          @click="$emit('update:open', !open)"
        >
          {{ label }}
        </button>
      </div>
      <div :class="[top ? 'absolute top-0' : 'absolute bottom-0', 'w-full h-12full flex']">
        <div class="w-full h-full" v-if="open">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  label: { type: String, required: true },
  open: { type: Boolean, default: false },
  top: { type: Boolean, default: false },
});

defineEmits(['update:open']);
</script>
