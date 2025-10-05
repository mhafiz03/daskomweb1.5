<template>
  <div>
    <div
      v-for="item in visibleItems"
      :key="item.key"
      :class="itemClasses(item)"
      @click="handleClick(item)"
    >
      <div class="w-7/12 my-2 flex">
        <div class="w-4/6" />
        <i :class="['select-none m-auto w-2/6 h-auto fas', item.icon]" />
      </div>
      <span class="ml-6 font-merri-bold font-medium w-full text-start self-center text-xl">
        {{ item.label }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  items: { type: Array, default: () => [] },
  changePage: { type: Boolean, default: false },
  activeKey: { type: String, default: '' },
  interactive: { type: Boolean, default: false },
});

const emits = defineEmits(['select']);

const visibleItems = computed(() => props.items.filter((item) => item.visible !== false));

const baseClass = 'w-full p-4 h-24 flex select-none animation-enable';

const itemClasses = (item) => {
  if (props.interactive) {
    const isActive = props.activeKey === item.key;
    return [
      baseClass,
      'cursor-pointer hover:text-white',
      isActive && props.changePage
        ? 'bg-yellow-500 text-white'
        : 'bg-yellow-400 hover:bg-yellow-600 text-black',
    ];
  }

  return [
    baseClass,
    props.changePage ? 'bg-yellow-400 text-black' : 'bg-yellow-500 text-white',
  ];
};

function handleClick(item) {
  if (!props.interactive || !item.route) {
    return;
  }

  emits('select', item.route);
}
</script>
