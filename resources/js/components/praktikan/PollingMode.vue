<template>
  <div class="w-full h-full flex">
    <div v-if="isComplete" class="font-monda-bold h-auto w-auto m-auto text-center text-5xl">
      <span>Polling telah selesai<br>Selamat anda telah menyelesaikan praktikum<br>Dasar Komputer 2022/2023 🎉🎉</span>
    </div>
    <div v-else class="w-full h-full py-4 relative">
      <div
        class="absolute top-0 m-4 right-0 animation-enable-short rounded-lg bg-green-400 p-3 hover:p-4 hover:bg-green-500 cursor-pointer pointer-events-auto w-auto h-auto flex"
        @click="savePolling"
      >
        <div class="font-overpass-mono-bold text-white text-center text-xl h-auto w-auto pointer-events-none m-auto">
          <span>SAVE</span>
        </div>
      </div>

      <div class="w-full h-3/4 flex-row">
        <div class="w-full h-8 flex">
          <div class="w-auto mx-auto h-full flex">
            <div class="font-monda-bold h-auto w-auto m-auto text-center text-2xl">
              <span v-if="activeAssistant.nama">
                {{ activeAssistant.nama }} ({{ activeAssistant.kode }})
              </span>
              <span v-else>
                Pilih asisten untuk dipolling
              </span>
            </div>
          </div>
        </div>
        <div class="w-full h-full flex">
          <div class="w-120full mx-auto h-full flex">
            <div class="w-16 h-full flex">
              <div class="w-12 h-12 p-0 hover:p-1 mr-auto my-auto animation-enable-short pointer-events-auto">
                <span
                  class="w-full h-full cursor-pointer text-black"
                  :class="[{ 'opacity-100': !reachedFirst }, { 'opacity-25': reachedFirst }]"
                  @click="selectPrevious"
                >
                  <img class="w-full h-full fas fa-caret-left" />
                </span>
              </div>
            </div>

            <div class="w-full h-12full rounded-large flex bg-green-600 my-auto shadow-xl">
              <div class="w-1/3 h-full rounded-l-large flex bg-green-400">
                <div
                  class="select-none w-full h-full bg-center bg-cover rounded-l-large"
                  :style="assistantBackgroundStyle"
                />
              </div>
              <div class="w-2/3 h-full flex">
                <div class="font-merri-bold h-auto w-auto m-auto text-center text-xl text-white p-4">
                  <span>{{ activeAssistant.deskripsi || 'Tidak ada deskripsi yang tersedia.' }}</span>
                </div>
              </div>
            </div>

            <div class="w-16 h-full flex">
              <div class="w-12 h-12 p-0 hover:p-1 mr-auto my-auto animation-enable-short pointer-events-auto">
                <span
                  class="w-full h-full cursor-pointer text-black"
                  :class="[{ 'opacity-100': !reachedLast }, { 'opacity-25': reachedLast }]"
                  @click="selectNext"
                >
                  <img class="w-full h-full fas fa-caret-right" />
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full h-1/4 flex overflow-y-hidden overflow-x-scroll">
        <div class="animation-enable-short w-auto h-full flex m-auto">
          <div
            v-for="(polling, index) in pollingSelections"
            :key="polling.id"
            class="animation-enable-short relative w-auto h-auto my-auto flex mx-2"
          >
            <div
              class="animation-enable-short w-auto h-auto rounded-lg flex bg-teal-200 hover:bg-teal-300 p-3 hover:p-4 pointer-events-auto cursor-pointer"
              @click="selectPolling(index)"
            >
              <div class="font-overpass-bold h-auto w-auto m-auto text-center text-lg text-black pointer-events-none">
                <span>{{ polling.judul }} [{{ pollingAssistantCode(polling) }}]</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from '@/composables/useToast';

export default {
  name: 'PollingMode',
  props: {
    allAsisten: {
      type: Array,
      default: () => [],
    },
    allAsistenPolling: {
      type: Array,
      default: () => [],
    },
    allPolling: {
      type: Array,
      default: () => [],
    },
    pollingComplete: {
      type: Boolean,
      default: false,
    },
    currentUser: {
      type: Object,
      required: true,
    },
  },
  emits: ['saved'],
  data() {
    return {
      chosenAsisten: 0,
      pollingSelections: this.clonePolling(this.allPolling),
      isComplete: this.pollingComplete,
      toast: null,
    };
  },
  computed: {
    activeAssistant() {
      return this.allAsistenPolling[this.chosenAsisten] || {};
    },
    assistantBackgroundStyle() {
      if (!this.activeAssistant.kode) {
        return {};
      }

      return {
        backgroundImage: `url(/assets/${this.activeAssistant.kode}.jpg)`,
      };
    },
    reachedFirst() {
      return this.chosenAsisten <= 0;
    },
    reachedLast() {
      return this.allAsistenPolling.length === 0 || this.chosenAsisten >= this.allAsistenPolling.length - 1;
    },
  },
  watch: {
    pollingComplete(value) {
      this.isComplete = value;
    },
    allPolling: {
      deep: true,
      handler(newValue) {
        this.pollingSelections = this.clonePolling(newValue);
      },
    },
    allAsistenPolling(newValue) {
      if (newValue.length === 0) {
        this.chosenAsisten = 0;
      } else if (this.chosenAsisten >= newValue.length) {
        this.chosenAsisten = newValue.length - 1;
      }
    },
  },
  created() {
    this.toast = useToast();
  },
  methods: {
    clonePolling(source) {
      return Array.isArray(source)
        ? source.map(item => ({ ...item }))
        : [];
    },
    selectPrevious() {
      if (!this.reachedFirst) {
        this.chosenAsisten -= 1;
      }
    },
    selectNext() {
      if (!this.reachedLast) {
        this.chosenAsisten += 1;
      }
    },
    pollingAssistantCode(polling) {
      const found = this.allAsisten.find(asisten => asisten.id === polling.asisten_id);
      return found ? found.kode : '';
    },
    setPollingSelection(index, asistenId) {
      const existing = this.pollingSelections[index];
      if (!existing) {
        return;
      }

      const updated = {
        ...existing,
        praktikan_id: this.currentUser.id,
        asisten_id: asistenId,
      };

      this.pollingSelections.splice(index, 1, updated);
    },
    selectPolling(index) {
      const assistant = this.activeAssistant;

      if (!assistant.id) {
        return;
      }

      this.setPollingSelection(index, assistant.id);
    },
    savePolling() {
  this.$axios.post('/praktikan/polling', this.pollingSelections).then(response => {
        if (response.data.message === 'success') {
          this.isComplete = true;
          this.$emit('saved');
        } else {
          this.toast.error(response.data.message);
        }
      }).catch(error => {
        if (error.response && error.response.data && error.response.data.message) {
          this.toast.error(error.response.data.message);
        } else {
          this.toast.error('Terjadi kesalahan saat menyimpan polling');
        }
      });
    },
  },
};
</script>
