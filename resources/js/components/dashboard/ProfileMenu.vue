<template>
  <div class="absolute top-0 right-0 z-40 w-24full h-24 flex" @mouseleave="$emit('close')">
    <div class="m-auto h-full w-56 flex-row bg-gray-600 mt-2 rounded-lg">
      <div class="w-full h-2/3" />
      <div class="w-full h-1/3 flex">
        <button
          type="button"
          class="rounded-b-lg bg-gray-400 hover:bg-gray-500 w-full h-full flex"
          @click="$emit('logout')"
        >
          <span class="m-auto font-monda-bold text-sm text-right w-full">Logout</span>
          <i class="select-none p-2 h-full w-auto mr-16 m-auto fas fa-sign-out-alt" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineEmits(['logout', 'close']);
</script>
