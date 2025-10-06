<template>
  <div
    class="absolute w-120 z-20 h-48full bottom-0 right-0 animation-enable"
    :class="positionClasses"
    @mouseover="$emit('hover')"
  >
    <div
      class="w-full h-full animation-enable overflow-y-auto rounded-tl-large"
      :class="menuContainerClass"
      :ref="setContainerRef"
    >
      <div
        v-for="item in items"
        :key="item.id"
        :class="itemClasses(item)"
        @click="onItemClick(item)"
      >
        <div class="w-7/12 my-2 flex">
          <div class="w-4/6"></div>
          <img :class="['select-none m-auto w-2/6 h-auto', item.icon]" />
        </div>
        <span class="ml-6 font-merri-bold font-medium w-full text-start self-center text-xl">
          {{ item.label }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SidebarMenu',
  props: {
    pageActive: {
      type: Boolean,
      default: false,
    },
    items: {
      type: Array,
      default: () => [],
    },
    menuContainerClass: {
      type: [String, Array, Object],
      default: '',
    },
    highlightedMenu: {
      type: String,
      default: '',
    },
    menuRef: {
      type: [Object, Function],
      default: null,
    },
  },
  emits: ['hover', 'select'],
  computed: {
    positionClasses() {
      return [this.pageActive ? 'right-0' : 'right-min20rem'];
    },
  },
  methods: {
    itemClasses(item) {
      const baseClasses = [
        'w-full',
        'p-4',
        'h-24',
        'flex',
        'select-none',
        'animation-enable',
      ];

      const isCurrent = this.highlightedMenu === item.id;
      const isClickable = !item.isCurrentPage;

      if (isClickable) {
        baseClasses.push('cursor-pointer', 'hover:text-white');
        baseClasses.push(isCurrent ? 'bg-yellow-500 text-white' : 'bg-yellow-400 hover:bg-yellow-600');
      } else {
        baseClasses.push('cursor-default');
        baseClasses.push(isCurrent ? 'bg-yellow-500 text-white' : 'bg-yellow-400 text-black');
      }

      return baseClasses;
    },
    onItemClick(item) {
      if (item.isCurrentPage) {
        return;
      }
      this.$emit('select', item.id);
    },
    setContainerRef(el) {
      console.log('[SidebarMenu setContainerRef] el:', el);
      console.log('[SidebarMenu setContainerRef] menuRef type:', typeof this.menuRef);
      console.log('[SidebarMenu setContainerRef] menuRef:', this.menuRef);
      
      // Handle both callback function and ref object
      if (typeof this.menuRef === 'function') {
        console.log('[SidebarMenu setContainerRef] Calling function');
        this.menuRef(el);
      } else if (this.menuRef && typeof this.menuRef === 'object') {
        console.log('[SidebarMenu setContainerRef] Setting ref.value');
        this.menuRef.value = el;
        console.log('[SidebarMenu setContainerRef] ref.value after set:', this.menuRef.value);
      }
    },
  },
};
</script>
