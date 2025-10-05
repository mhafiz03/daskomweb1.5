<template>
  <div>
    <header class="h-auto w-full flex">
      <div class="ml-6 mt-6 flex-row w-auto h-auto whitespace-pre-wrap font-monda-bold text-5xl">
        <span>{{ user.nama }}</span>
        <span class="font-merri-italic text-4xl mt-2"> ({{ user.email }})</span>
      </div>
    </header>

    <section v-if="!viewPassForm" class="pb-2">
      <div class="h-1/3 w-full flex">
        <div class="w-auto h-auto ml-16 mt-8">
          <span class="font-overpass text-3xl">Kelas : </span>
          <span class="whitespace-pre-wrap font-overpass-bold text-3xl"> {{ user.kelas }}</span>
        </div>
      </div>
      <div class="h-1/3 w-full flex">
        <div class="w-auto h-auto ml-16 mt-4">
          <span class="font-overpass text-3xl">Nomor Telepon : </span>
          <span class="whitespace-pre-wrap font-overpass-bold text-3xl"> {{ user.nomor_telepon }}</span>
        </div>
      </div>
      <div class="h-1/3 w-full flex">
        <div class="w-auto h-auto ml-16 mt-4">
          <span class="font-overpass text-3xl">Alamat : </span>
          <span class="whitespace-pre-wrap font-overpass-bold text-3xl"> {{ user.alamat }}</span>
        </div>
      </div>
      <div class="h-1/3 w-full flex pb-4">
        <button
          type="button"
          class="ml-16 mt-4 font-overpass text-2xl bg-red-500 text-white p-3 pb-2 rounded-lg hover:bg-red-600"
          @click="$emit('openChangePwd')"
        >
          Ganti Password <i class="ml-1 p-1 fas fa-pen fa-lg" />
        </button>
        <button
          type="button"
          class="ml-4 mt-4 font-overpass text-2xl bg-green-800 text-yellow-300 p-3 pb-2 rounded-lg hover:bg-green-600"
          @click="$emit('travel', 'contact_asisten')"
        >
          Kontak Asisten <i class="ml-1 p-1 fas fa-users fa-lg" />
        </button>
      </div>
    </section>

    <section v-else class="pb-4">
      <div class="w-2/3 h-auto ml-16 mt-2 flex-col">
        <div class="font-overpass-bold">Input Password baru:</div>
        <input
          v-model="model.password"
          class="bg-gray-200 border-2 border-gray-200 rounded w-1/4 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
          type="password"
          placeholder="******************"
        />
      </div>
      <div class="w-2/3 h-auto ml-16 mt-2 flex-col">
        <div class="font-overpass-bold">Ulangi Password baru:</div>
        <input
          v-model="model.repeatpass"
          class="bg-gray-200 border-2 border-gray-200 rounded w-1/4 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
          type="password"
          placeholder="******************"
        />
      </div>
      <div class="w-2/3 h-auto ml-16 mt-2 flex">
        <button
          type="button"
          class="p-2 px-3 bg-red-600 font-merri-bold text-xl text-white rounded-lg hover:bg-red-700"
          @click="$emit('closeChangePwd')"
        >
          <i class="p-1 fas fa-times fa-lg" />
        </button>
        <button
          type="button"
          class="p-2 bg-green-600 font-merri-bold text-xl text-white rounded-lg hover:bg-green-700 mx-2"
          @click="$emit('doResetPwd')"
        >
          <i class="p-1 fas fa-check fa-lg" />
        </button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed, toRef } from 'vue';

const props = defineProps({
  currentUser: { type: Object, required: true },
  viewPassForm: { type: Boolean, default: false },
});

defineEmits(['openChangePwd', 'closeChangePwd', 'doResetPwd', 'travel']);

const model = defineModel('resetPass', { default: () => ({ password: '', repeatpass: '' }) });
const user = computed(() => props.currentUser ?? {});
</script>
