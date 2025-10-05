<template>
  <div
    class="w-full h-full flex absolute bg-black opacity-97 z-30 top-0"
    :class="[visible ? 'visible' : 'hidden']"
  >
    <div
      class="w-24 h-4full flex-row"
      :class="[(praktikanBanned.length > 0 || asistenBanned.length > 0) ? 'visible' : 'hidden']"
    >
      <div
        v-for="praktikan in praktikanBanned"
        :key="`praktikan-${praktikan.nim}`"
        class="w-full h-8 py-1 px-2 flex"
      >
        <div class="w-full h-full flex rounded-sm bg-gray-400 text-black text-xl font-overpass-mono-bold">
          <span class="m-auto">{{ praktikan.nim }}</span>
        </div>
      </div>
      <div
        v-for="asisten in asistenBanned"
        :key="`asisten-${asisten.kode}`"
        class="w-full h-8 py-1 px-2 flex"
      >
        <div class="w-full h-full flex rounded-sm bg-yellow-400 text-black text-xl font-overpass-mono-bold">
          <span class="m-auto">{{ asisten.kode }}</span>
        </div>
      </div>
    </div>

    <div
      class="h-4full flex-row"
      :class="[(praktikanBanned.length > 0 || asistenBanned.length > 0) ? 'w-24full' : 'w-4full']"
    >
      <div class="w-full h-16 flex">
        <div class="w-full h-full flex">
          <div class="w-1/3 h-full flex py-2 px-8 hover:px-10 hover:py-3 animation-enable-short cursor-pointer" @click="$emit('close')">
            <div class="w-full h-full flex font-monda-bold text-lg bg-yellow-400 rounded-lg">
              <div class="w-auto select-none h-auto m-auto">CLOSE</div>
            </div>
          </div>
          <div
            class="w-1/3 h-full flex py-2 px-8 hover:px-10 hover:py-3 animation-enable-short cursor-pointer"
            :class="[spaceToggled ? 'hidden' : 'visible']"
            @click="$emit('shuffle')"
          >
            <div class="w-full h-full flex font-monda-bold text-lg bg-yellow-400 rounded-lg">
              <div class="w-auto select-none h-auto m-auto">SHUFFLE</div>
            </div>
          </div>
          <div
            class="w-1/3 h-full flex py-2 px-8 hover:px-10 hover:py-3 animation-enable-short cursor-pointer"
            @click="$emit('save')"
          >
            <div class="w-full h-full flex font-monda-bold text-lg bg-yellow-400 rounded-lg">
              <div class="w-auto select-none h-auto m-auto">SAVE</div>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full h-16full flex-row pt-4 px-4">
        <div
          v-for="(plotRow, rowIndex) in computedPlots"
          :key="`row-${rowIndex}`"
          class="w-full flex"
          :style="{ height: `${100 / computedPlots.length}%` }"
        >
          <div
            v-for="(space, colIndex) in plotRow"
            :key="`space-${rowIndex}-${colIndex}`"
            class="h-full flex p-1 hover:p-2 animation-enable-short"
            :class="`space-${rowIndex}-${colIndex}`"
            :style="{ width: `${100 / plotRow.length}%` }"
            @click="$emit('toggle-space', space)"
          >
            <div
              class="w-full h-full flex rounded-sm text-black text-xl font-overpass-mono-bold"
              :class="spaceClass(space)"
            >
              <div
                class="w-auto h-auto m-auto"
                :class="space.startsWith('3') ? 'visible' : 'hidden'"
              >
                <span :id="`asisten-${space}`">{{ getAsistenCode(space) }}</span>
              </div>

              <div
                class="w-auto h-auto m-auto"
                :class="space.startsWith('1') || space.startsWith('2') ? 'visible' : 'hidden'"
              >
                <span :id="`praktikan-${space}`">{{ getPraktikanSuffix(space) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  visible: { type: Boolean, default: false },
  plots: { type: Array, default: () => [] },
  listAllAsisten: { type: Array, default: () => [] },
  listAllPraktikan: { type: Array, default: () => [] },
  praktikanBanned: { type: Array, default: () => [] },
  asistenBanned: { type: Array, default: () => [] },
  spaceToggled: { type: Boolean, default: false },
});

const computedPlots = computed(() => props.plots ?? []);

function spaceClass(space) {
  const type = space.substring(0, 1);
  return {
    'opacity-0': type === '0',
    'bg-red-600 cursor-pointer': type === '1',
    'bg-blue-600 cursor-pointer': type === '2',
    'bg-yellow-600 cursor-pointer': type === '3',
    'bg-yellow-700': type === '4',
    'bg-green-700': type === '5',
    'bg-yellow-900': type === '6',
    'bg-orange-700': type === '7',
  };
}

function getAsistenCode(space) {
  if (!space.startsWith('3')) {
    return '';
  }

  const index = parseInt(space.substring(1, 3), 10);
  const asisten = props.listAllAsisten?.[index];
  return asisten?.kode ?? '';
}

function getPraktikanSuffix(space) {
  if (!space.startsWith('1') && !space.startsWith('2')) {
    return '';
  }

  const groupIndex = parseInt(space.substring(1, 3), 10);
  const memberIndex = parseInt(space.substring(3, 4), 10);
  const listIndex = groupIndex * 3 + memberIndex - 1;
  const praktikan = props.listAllPraktikan?.[listIndex];
  return praktikan?.nim?.substring(6, 10) ?? '';
}
</script>
