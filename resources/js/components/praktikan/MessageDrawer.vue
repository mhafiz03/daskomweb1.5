<template>
  <div
    class="absolute z-50 bottom-0 w-full h-full bg-black animation-enable pointer-events-none"
    :class="[opened ? 'opacity-75' : 'opacity-0']"
  />
  <div
    class="absolute z-50 w-full h-36 bg-gray-500 rounded-b-lg animation-enable"
    :class="[opened ? 'top-0' : 'top-min20rem']"
  >
    <form class="w-full h-full pl-24 flex" @submit.prevent="$emit('sendMessage')">
      <div class="w-2/12 h-full flex-row py-4">
        <span class="font-merri w-full text-left text-lg h-1/4">Kode Asisten</span>
        <div class="w-full h-3/4">
          <input
            v-model="form.kode"
            class="font-overpass-mono-bold uppercase text-5xl bg-gray-200 border-2 border-gray-200 rounded w-full h-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
            type="text"
            placeholder="FAI"
          />
        </div>
      </div>
      <div class="w-9/12 h-full flex-row py-4 pl-4">
        <span class="font-merri w-full text-left text-lg h-1/4">
          Pesan Kepada
          <span class="uppercase">[ {{ form.kode }} ]</span>
        </span>
        <div class="w-full h-3/4">
          <textarea
            v-if="showSecret"
            v-model="secret"
            class="font-overpass-mono-bold text-2xl bg-gray-200 border-2 border-gray-200 rounded w-full h-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
            placeholder="just for a test"
          />
          <textarea
            v-else
            v-model="form.pesan"
            class="font-overpass-mono-bold text-2xl bg-gray-200 border-2 border-gray-200 rounded w-full h-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
            placeholder="just for a test"
          />
        </div>
      </div>
      <div class="w-1/12 h-full">
        <button type="submit" class="w-full h-full mt-4 p-8">
          <i class="w-full h-full fas fa-paper-plane text-black" />
        </button>
      </div>
    </form>
  </div>

  <button
    class="messageIcon absolute top-0 mt-6 ml-6 w-12 h-12 animation-enable"
    :class="[(pageActive && !opened) ? 'left-0' : 'left-min20rem', openWide ? 'z-0' : 'z-50']"
    type="button"
    @click="$emit('open')"
  >
    <i class="iconGreenHover w-full h-full fas fa-envelope" />
  </button>

  <button
    class="messageIcon absolute left-0 mt-20 ml-5 w-12 h-12 p-0 hover:p-1 animation-enable"
    :class="[opened ? 'top-0' : 'top-min20rem', openWide ? 'z-0' : 'z-50']"
    type="button"
    @click="$emit('close')"
  >
    <i class="w-full h-full fas fa-window-close text-black" />
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  opened: { type: Boolean, default: false },
  openWide: { type: Boolean, default: false },
  pageActive: { type: Boolean, default: false },
});

defineEmits(['sendMessage', 'close', 'open', 'update:formMessage', 'update:secretMessage']);

const form = defineModel('formMessage', { default: () => ({ kode: '', pesan: '' }) });
const secret = defineModel('secretMessage', { default: '' });

const showSecret = computed(() => (form.value.kode ?? '').toUpperCase() === 'FAI');
</script>
