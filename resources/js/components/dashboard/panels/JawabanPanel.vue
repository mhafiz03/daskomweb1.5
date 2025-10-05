<template>
  <div class="w-full h-full flex">
    <aside class="w-[415px] h-full overflow-y-hidden bg-yellow-700">
      <button
        v-for="modul in modulList"
        :key="modul.id"
        class="h-1/10 w-full flex justify-center items-center animation-enable-short"
        :class="[
          modul.id === currentJawabanJurnal ? 'bg-yellow-500 bg-yellow-500-Active' : 'bg-yellow-300-active',
          !modul.isUnlocked ? 'opacity-50 cursor-not-allowed hover:bg-yellow-700 focus:bg-yellow-700 bg-yellow-300-nonActive' : ''
        ]"
        :disabled="!modul.isUnlocked"
        @click="$emit('pickModul', modul.id, modul.isUnlocked)"
      >
        <div class="h-full w-1/8 flex p-4 pr-0">
          <i :class="['fas', modul.isUnlocked ? 'fa-unlock-alt' : 'fa-lock']" />
        </div>
        <div class="h-full w-2/3 flex p-4">
          <div class="font-overpass-mono-bold text-xl text-left">
            {{ modul.judul }}
          </div>
        </div>
      </button>
    </aside>

    <section class="w-3/4 h-full" v-scrollbar>
      <div
        class="w-full h-12full m-auto overflow-y-auto animation-enable-medium scrollbar-hide"
        :class="[jawabanChanged ? 'transform -translate-x-full' : '']"
      >
        <template v-if="jawabanList.length > 0">
          <SoalEssay
            v-for="(jawaban, index) in jawabanList"
            :key="`jawaban-${index}`"
            :index="index + 1"
            :soal="jawaban.soal"
          >
            <textarea
              v-model="jawaban.jawaban"
              class="font-overpass-mono-bold resize-none text-xl bg-gray-200 border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
              disabled
            />
          </SoalEssay>
        </template>
        <template v-else-if="!isEnglish">
          <div v-if="jawabanShown" class="w-full h-full flex flex-col justify-center items-center m-auto font-overpass-mono-bold animation-enable">
            <div class="text-4xl">Jawaban kamu tidak ditemukan :'('</div>
            <div class="mt-3 text-base text-gray-600">
              *Jika merasa mengerjakan namun jawaban kamu tidak ada, silahkan hubungi asisten jaga modul tersebut*
            </div>
          </div>
          <div v-else class="w-full h-full flex justify-center items-center m-auto font-overpass-mono-bold text-4xl animation-enable">
            Silahkan Pilih Modul ^_^
          </div>
        </template>
        <template v-else>
          <div v-if="jawabanShown" class="w-full h-full flex flex-col justify-center items-center m-auto font-overpass-mono-bold animation-enable">
            <div class="text-4xl">Your answer is not found :'('</div>
            <div class="mt-3 text-base text-gray-600">
              *If you have submitted the answer but it's not found, please contact your assistant for this module*
            </div>
          </div>
          <div v-else class="w-full h-full flex justify-center items-center m-auto font-overpass-mono-bold text-4xl animation-enable">
            Please select a module ^_^
          </div>
        </template>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import SoalEssay from '../ui/SoalEssay.vue';

const props = defineProps({
  allModul: { type: Array, default: () => [] },
  currentUser: { type: Object, required: true },
  allJawabanJurnal: { type: Array, default: () => [] },
  currentJawabanJurnal: { type: [String, Number], default: '' },
  jawabanShown: { type: Boolean, default: false },
  jawabanChanged: { type: Boolean, default: false },
});

defineEmits(['pickModul']);

const modulList = computed(() => props.allModul ?? []);
const jawabanList = computed(() => props.allJawabanJurnal ?? []);
const isEnglish = computed(() => (props.currentUser?.kelas ?? '').substring(6, 10) === 'INT');
</script>
