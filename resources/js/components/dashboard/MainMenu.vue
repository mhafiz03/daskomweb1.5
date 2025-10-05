<template>
  <div
    class="absolute w-24full right-0 h-16 flex animation-enable"
    :class="[pageActive ? 'bottom-0' : 'bottom-min4rem']"
  >
    <nav class="m-auto h-full w-3/5 flex">
      <MenuItem icon="fa-address-card" label="Profil" :active="active === 'profil'" @click="$emit('showProfil')" />
      <MenuItem icon="fa-code" label="Praktikum" :active="active === 'praktikum'" @click="$emit('showPraktikum')" />
      <MenuItem icon="fa-file-code" label="Tugas Pendahuluan" :active="active === 'tp'" @click="$emit('showTP')" />
      <MenuItem icon="fa-chart-area" label="Nilai" :active="active === 'nilai'" @click="$emit('showNilai')" />
      <MenuItem icon="fa-book" label="Jawaban" :active="active === 'jawaban'" @click="$emit('showJawaban')" />
    </nav>
  </div>
</template>

<script setup>
import MenuItem from './MenuItem.vue';

const props = defineProps({
  active: { type: String, default: 'profil' },
  pageActive: { type: Boolean, default: false },
  openWide: { type: Boolean, default: false },
});

defineEmits(['showProfil', 'showPraktikum', 'showTP', 'showNilai', 'showJawaban']);
</script>
