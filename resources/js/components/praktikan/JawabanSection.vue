<template>
  <div class="w-full h-full flex">
    <div class="w-1/4 h-full flex-row overflow-y-hidden bg-yellow-700 cursor-pointer" style="width: 415px">
      <div
        v-for="modul in modules"
        :key="modul.id"
        class="h-1/10 w-full flex justify-center items-center hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 bg-yellow-300-active animation-enable-short"
        :class="[
          { 'bg-yellow-500 bg-yellow-500-Active': modul.id === currentModuleId },
          { 'opacity-50 cursor-not-allowed hover:bg-yellow-700 focus:bg-yellow-700 bg-yellow-300-nonActive': !modul.isUnlocked },
        ]"
        @click="handleModuleClick(modul)"
      >
        <div class="h-full w-1/8 flex p-4 pr-0">
          <div class="h-full w-full flex font-overpass-mono-bold text-xl rounded-large justify-end items-center">
            <img :class="[{ 'fas fa-lock': !modul.isUnlocked }, { 'fas fa-unlock-alt': modul.isUnlocked }]" />
          </div>
        </div>
        <div class="h-full w-2/3 flex p-4">
          <div class="h-full w-full flex font-overpass-mono-bold text-xl rounded-large justify-start items-center">
            <div class="mt-2 text-lg text-left">
              {{ modul.judul }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="w-3/4 h-full flex" v-scrollbar>
      <div
        class="w-full h-12full m-auto overflow-y-auto animation-enable-medium scrollbar-hide"
        :class="[{ 'transform -translate-x-full': answersRefreshing }]"
      >
        <div v-if="answers.length > 0" class="w-full h-full flex-row">
          <div v-for="(jawaban, index) in answers" :key="index" class="w-full flex-row h-auto mb-8">
            <div class="w-full h-auto flex mb-2">
              <div class="h-full w-12 flex font-merri-bold text-xl">
                <div class="m-auto w-auto h-auto">{{ index + 1 }}</div>
              </div>
              <div class="h-12 px-1 w-4">
                <div class="h-full w-full bg-gray-900" />
              </div>
              <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                <span>{{ jawaban.soal }}</span>
              </div>
            </div>
            <div class="w-full h-auto flex px-5">
              <textarea
                v-model="jawaban.jawaban"
                cols="30"
                rows="15"
                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
                type="text"
                disabled
              />
            </div>
          </div>
        </div>

        <template v-else-if="!isInternational">
          <div
            v-if="answersVisible"
            class="w-full h-full flex flex-col justify-center items-center m-auto font-overpass-mono-bold animation-enable"
          >
            <div class="text-4xl">
              Jawaban kamu tidak ditemukan :'(
            </div>
            <div class="mt-3 text-base text-gray-600">
              *Jika merasa mengerjakan namun jawaban kamu tidak ada, silahkan hubungi asisten jaga modul tersebut*
            </div>
          </div>
          <div v-else class="w-full h-full flex justify-center items-center m-auto font-overpass-mono-bold text-4xl animation-enable">
            Silahkan Pilih Modul ^_^
          </div>
        </template>

        <template v-else>
          <div
            v-if="answersVisible"
            class="w-full h-full flex flex-col justify-center items-center m-auto font-overpass-mono-bold animation-enable"
          >
            <div class="text-4xl">
              Your answer is not found :'(
            </div>
            <div class="mt-3 text-base text-gray-600">
              *If you have submitted the answer but it's not found, please contact your assisstant in this module*
            </div>
          </div>
          <div v-else class="w-full h-full flex justify-center items-center m-auto font-overpass-mono-bold text-4xl animation-enable">
            Please select a module ^_^
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'JawabanSection',
  props: {
    modules: {
      type: Array,
      default: () => [],
    },
    currentModuleId: {
      type: [Number, String],
      default: '',
    },
    answers: {
      type: Array,
      default: () => [],
    },
    taAnswers: {
      type: Array,
      default: () => [],
    },
    tkAnswers: {
      type: Array,
      default: () => [],
    },
    answersVisible: {
      type: Boolean,
      default: false,
    },
    answersRefreshing: {
      type: Boolean,
      default: false,
    },
    currentUser: {
      type: Object,
      required: true,
    },
  },
  emits: ['select-module'],
  setup(props, { emit }) {
    const isInternational = computed(() => props.currentUser.kelas?.substring(6, 10) === 'INT');

    const handleModuleClick = (modul) => {
      emit('select-module', {
        id: modul.id,
        isUnlocked: modul.isUnlocked,
      });
    };

    return {
      isInternational,
      handleModuleClick,
    };
  },
};
</script>
