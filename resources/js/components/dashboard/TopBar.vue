<template>
  <div
    class="absolute right-0 w-24full h-16 flex animation-enable"
    :class="[pageActive ? 'top-0' : 'top-min4rem', openWide ? 'z-0' : 'z-40']"
  >
    <div class="m-auto h-12 mt-4 w-48 flex items-center font-monda-bold text-lg text-white">
      <button
        type="button"
        class="m-auto flex items-center z-10"
        @mouseover="$emit('toggleMenu')"
      >
        {{ nim }}
        <span class="w-4" />
        <i class="select-none w-8 h-8 fas fa-arrow-circle-down text-white" />
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  nim: { type: String, default: '' },
  pageActive: { type: Boolean, default: false },
  openWide: { type: Boolean, default: false },
});

defineEmits(['toggleMenu']);
</script>
