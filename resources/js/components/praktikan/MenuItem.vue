<template>
  <button
    type="button"
    class="h-full flex animation-enable pointer-events-auto px-3"
    :class="active ? 'youngYellowIcon' : 'iconYellowHover'"
    @click="$emit('click')"
  >
    <i class="w-6 h-6 self-center fas" :class="icon" />
    <span class="self-center text-left font-monda-bold text-lg text-white ml-2" v-show="active">
      {{ label }}
    </span>
  </button>
</template>

<script setup>
const props = defineProps({
  icon: { type: String, required: true },
  label: { type: String, required: true },
  active: { type: Boolean, default: false },
});

defineEmits(['click']);
</script>
