<template>
  <div class="w-full h-full flex">
    <div v-if="qrcodeShown" class="w-full h-full flex">
      <div class="font-monda-bold h-auto w-auto m-auto text-center text-4xl">
        QR Code sudah tersedia. Silakan buka halaman cetak untuk melihat detailnya.
      </div>
    </div>

    <div v-else class="w-full h-full flex">
      <div v-if="!hasSoal" class="w-full h-full flex">
        <div class="font-monda-bold h-auto w-auto m-auto text-center text-5xl">
          Tidak ada <br> Tugas Pendahuluan saat ini <br>
          <span class="text-xl">Silahkan cek kembali setelah ada pengumuman di OA line: @875lgds</span>
        </div>
      </div>

      <div v-else class="h-full w-full flex-row relative">
        <Disclosure top label="PEMBAHASAN" :open="!soalOpened" @update:open="value => updateOpen(!value)">
          <div class="whitespace-pre-wrap break-words p-5 font-overpass text-2xl">
            <span>{{ pembahasan.pembahasan }}</span>
          </div>
        </Disclosure>

        <Disclosure bottom label="SOAL (klik tombol simpan di paling bawah untuk menyimpan)" :open="soalOpened" @update:open="value => updateOpen(value)">
          <div class="p-2">
            <SoalEssay v-for="(soal, index) in essayList" :key="`essay-${soal.id}`" :index="index + 1" :soal="soal.soal">
              <textarea
                v-model="jawabanList[index].jawaban"
                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
                autocomplete="off"
                placeholder="Ketik jawabanmu disini ..."
              />
            </SoalEssay>

            <SoalEssay
              v-for="(soal, index) in programList"
              :key="`program-${soal.id}`"
              :index="index + 1 + essayList.length"
              :soal="soal.soal"
            >
              <textarea
                v-model="jawabanList[index + essayList.length].jawaban"
                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
                autocomplete="off"
                placeholder="Ketik jawabanmu disini ..."
              />
            </SoalEssay>

            <div class="w-1/2 h-20 mx-auto">
              <button type="button" class="w-full h-full p-4 hover:p-5 animation-enable-short" @click="$emit('saveJawaban')">
                <div class="w-full h-full font-overpass-bold text-xl text-white flex pt-1 rounded-full bg-green-600">
                  <div class="m-auto">Simpan</div>
                </div>
              </button>
            </div>
          </div>
        </Disclosure>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Disclosure from '../ui/Disclosure.vue';
import SoalEssay from '../ui/SoalEssay.vue';

const props = defineProps({
  pembahasanTp: { type: Object, default: () => ({}) },
  soalTpEssay: { type: Array, default: () => [] },
  soalTpProgram: { type: Array, default: () => [] },
  qrcodeShown: { type: Boolean, default: false },
});

defineEmits(['saveJawaban', 'update:soalOpened']);

const jawabanList = defineModel('jawabanTp', { default: () => [] });
const soalOpenedModel = defineModel('soalOpened', { default: true });

const pembahasan = computed(() => props.pembahasanTp ?? {});
const essayList = computed(() => props.soalTpEssay ?? []);
const programList = computed(() => props.soalTpProgram ?? []);
const qrcodeShown = computed(() => props.qrcodeShown);

const hasSoal = computed(() => essayList.value.length > 0 || programList.value.length > 0);

const soalOpened = computed({
  get: () => soalOpenedModel.value,
  set: (val) => {
    soalOpenedModel.value = val;
  },
});

function updateOpen(val) {
  soalOpened.value = val;
}
</script>
