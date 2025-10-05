<template>
  <div class="main_container bg-green-900 w-full h-full overflow-hidden">
    <TopBar
      :nim="currentUser.nim"
      :page-active="pageActive"
      :open-wide="openWide"
      @toggle-menu="isMenuShown = !isMenuShown"
    />

    <ProfileMenu
      v-if="isMenuShown && pageActive"
      @logout="signOut"
      @close="isMenuShown = false"
    />

    <div
      class="absolute my-auto z-40 h-full pointer-events-none flex animation-enable"
      :class="[pageActive ? 'right-0' : 'right-minFull', openWide ? 'w-full' : 'w-24full']"
      @mouseover="isMenuShown = false"
    >
      <div
        class="my-auto flex w-full pointer-events-none animation-enable"
        :class="[openWide ? 'h-4full' : 'h-36full']"
      >
        <aside class="h-full w-12 flex pointer-events-auto">
          <button
            v-if="!praktikumExist"
            class="w-8 h-8 m-auto flex items-center justify-center"
            type="button"
            :aria-expanded="openWide"
            :title="openWide ? 'Collapse' : 'Expand'"
            @click="openWide = !openWide"
          >
            <i :class="['w-full h-full fas', openWide ? 'fa-caret-right' : 'fa-caret-left', 'text-white']" />
          </button>
        </aside>

        <section class="rounded-l-large h-full w-12full bg-yellow-200 pointer-events-auto overflow-y-auto">
          <ProfilePanel
            v-if="isProfil"
            :current-user="currentUser"
            :view-pass-form="viewPassForm"
            v-model:reset-pass="resetPass"
            @open-change-pwd="formPassword(true)"
            @close-change-pwd="formPassword(false)"
            @do-reset-pwd="resetPassword"
            @travel="travel"
          />

          <TPPanel
            v-else-if="isTP"
            :pembahasan-tp="pembahasanTp"
            :soal-tp-essay="soalTPEssay"
            :soal-tp-program="soalTPProgram"
            :qrcode-shown="qrcodeShown"
            v-model:jawaban-tp="jawabanTP"
            v-model:soal-opened="soalOpened"
            @save-jawaban="saveJawabanTP"
          />

          <NilaiPanel
            v-else-if="isNilai"
            :chart-data="allNilaiData"
          />

          <JawabanPanel
            v-else-if="isJawaban"
            :all-modul="all_modul"
            :current-user="currentUser"
            :all-jawaban-jurnal="allJawabanJurnal"
            :current-jawaban-jurnal="currentJawabanJurnal"
            :jawaban-shown="jawabanShown"
            :jawaban-changed="jawabanChanged"
            @pick-modul="showJawabanJurnal"
          />

          <PraktikumPanel
            v-else
            :current-praktikum="current_praktikum"
            :current-modul="current_modul"
            :is-polling-enabled="isPollingEnabled"
            :polling-complete="pollingComplete_mutable"
            :pilihan-polling="pilihanPolling"
            v-model:chosen-asisten="chosenAsisten"
            v-model:modul-shown="modulShown"
            v-model:show-nilai-ta="showNilaiTA"
            v-model:show-nilai-tk="showNilaiTK"
            :nilai-ta="nilaiTA"
            :nilai-tk="nilaiTK"
            :programming-quote="programmingQuote"
            :quote-author="quoteAuthor"
            :soal-ta="soalTA"
            :jawaban-ta="jawabanTA"
            :soal-tk="soalTK"
            :jawaban-tk="jawabanTK"
            :soal-jurnal="soalJurnal"
            :jawaban-jurnal="jawabanJurnal"
            :soal-fitb="soalFitb"
            :jawaban-fitb="jawabanFitb"
            :soal-mandiri="soalMandiri"
            :jawaban-mandiri="jawabanMandiri"
            :soal-runmod="soalRunmod"
            :jawaban-runmod="jawabanRunmod"
            :chosen-jawaban="chosenJawaban"
            :all-asisten="allAsisten"
            :all-asisten-polling="allAsistenPolling"
            :all-polling="allPolling"
            :is-runmod="isRunmod"
            :generate-score-text="generateScoreText"
            :on-choose-jawaban="chooseJawaban"
            :on-set-pilihan-polling="setPilihanPolling"
            :on-save-polling="savePolling"
            :on-toggle-show-nilai-ta="value => (showNilaiTA = value)"
            :on-toggle-show-nilai-tk="value => (showNilaiTK = value)"
            :on-toggle-modul-shown="value => (modulShown = value)"
            :on-finish-praktikum="finishPraktikum"
          />
        </section>
      </div>
    </div>

    <MainMenu
      :active="activeView"
      :page-active="pageActive"
      :open-wide="openWide"
      @show-profil="showProfil"
      @show-praktikum="showPraktikum"
      @show-tp="showTP"
      @show-nilai="showNilai"
      @show-jawaban="showJawaban"
    />

    <MessageDrawer
      :opened="messageOpened"
      :open-wide="openWide"
      :page-active="pageActive"
      v-model:form-message="formMessage"
      v-model:secret-message="secretMessage"
      @send-message="sendMessage"
      @open="messageOpened = true"
      @close="messageOpened = false"
    />
  </div>
</template>
<style>
.youngYellowIcon {
  color: #faf089;
}

.iconGreenHover {
  color: #c6f6d5;
}

.iconGreenHover:hover {
  color: #48bb78;
}

.iconYellowHover {
  color: #d69e2e;
}

.iconYellowHover:hover{
  color: #faf089;
}
.bg-yellow-300-active:active{
  background-color: #faf089 !important;
}
.bg-yellow-300-nonActive:active{
  background-color: #b7791f !important;
}
.bg-yellow-500-Active:active{
  background-color: #ecc94b !important;
}
.main_container {
  position: relative;
}

</style>

<script>
import { defineComponent, ref, reactive, computed, toRefs, onMounted, onBeforeUnmount, inject, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3';
import CryptoJS from 'crypto-js';

import TopBar from '../components/praktikan/TopBar.vue';
import ProfileMenu from '../components/praktikan/ProfileMenu.vue';
import MainMenu from '../components/praktikan/MainMenu.vue';
import MessageDrawer from '../components/praktikan/MessageDrawer.vue';
import ProfilePanel from '../components/praktikan/panels/ProfilePanel.vue';
import TPPanel from '../components/praktikan/panels/TPPanel.vue';
import NilaiPanel from '../components/praktikan/panels/NilaiPanel.vue';
import JawabanPanel from '../components/praktikan/panels/JawabanPanel.vue';
import PraktikumPanel from '../components/praktikan/panels/PraktikumPanel.vue';

const SUCCESS_SCORE_MESSAGES = [
  'Mantap gini nih kalau sebelum praktikum belajar',
  'Wah beruntung apa gimana nih ?'
];

const IMPROVEMENT_SCORE_MESSAGES = [
  'Ayo lebih semangat lagi belajarnya!!!',
  'Waduh jangan mau kalah sama temen yang lain'
];

export default defineComponent({
  name: 'PraktikanPage',
  components: {
    TopBar,
    ProfileMenu,
    MainMenu,
    MessageDrawer,
    ProfilePanel,
    TPPanel,
    NilaiPanel,
    JawabanPanel,
    PraktikumPanel,
  },
  props: {
    comingFrom: {
      type: String,
      default: '',
    },
    currentUser: {
      type: Object,
      required: true,
    },
    allAsisten: {
      type: Array,
      default: () => [],
    },
    allAsistenPolling: {
      type: Array,
      default: () => [],
    },
    isRunmod: {
      type: [Boolean, Number],
      default: false,
    },
    pollingComplete: {
      type: Boolean,
      default: false,
    },
    allPolling: {
      type: Array,
      default: () => [],
    },
    allModul: {
      type: Array,
      default: () => [],
    },
    allJurnal: {
      type: Array,
      default: () => [],
    },
  },
  setup(props) {
    const injectedAxios = inject('axios', null);
    const instance = getCurrentInstance();
    const proxy = instance?.proxy;
    const toasted = proxy?.$toasted ?? null;
    const http = injectedAxios ?? proxy?.$axios ?? window.axios;

    const state = reactive({
      pageActive: false,
      isMenuShown: false,
      messageOpened: false,
      openWide: false,
      modulShown: false,
      currentJawabanJurnal: '',
      jawabanShown: false,
      jawabanChanged: false,
      isPollingEnabled: false,
      pollingComplete_mutable: props.pollingComplete,
      praktikumExist: false,
      pilihanPolling: props.allPolling.map((poll) => ({ ...poll })),
      chosenAsisten: 0,
      current_praktikum: {
        kelas_id: '',
        modul_id: '',
        asisten_id: '',
        status: '',
      },
      current_modul: {
        judul: '',
        deskripsi: '',
        isi: '',
      },
      soalOpened: true,
      formMessage: {
        kode: '',
        pesan: '',
        praktikan_id: props.currentUser.id,
        kelas_id: props.currentUser.kelas_id,
      },
      all_modul: [],
      choosenModulID: '',
      allJawabanJurnal: [],
      programmingQuote: 'nothing',
      quoteAuthor: '',
      randomNumber: '',
      ATCnim: '',
      soalPresentasi: [],
      soalTA: [],
      soalTK: [],
      soalTPEssay: [],
      soalTPProgram: [],
      soalMandiri: [],
      soalJurnal: [],
      soalFitb: [],
      soalRunmod: [],
      jawabanTA: [],
      jawabanTK: [],
      chosenJawaban: [],
      jawabanPraktikan: {
        soal_id: '',
        jawaban: '',
      },
      pembahasanTp: {
        modul_id: '',
        pembahasan: '',
      },
      qrcodeData: {
        praktikan_id: '',
        modul_id: '',
        kelas_id: '',
        allJawaban_id: '',
      },
      ecnryptedData: '',
      qrcodeShown: false,
      jawabanFitb: [],
      jawabanJurnal: [],
      jawabanMandiri: [],
      jawabanTP: [],
      jawabanRunmod: [],
      laporanPraktikan: {
        pesan: '',
        rating_asisten: 0,
        rating_praktikum: 0,
        asisten_id: '',
        praktikan_id: '',
        modul_id: '',
      },
      allNilaiData: {
        labels: [],
        datasets: [],
      },
      allNilaiTP: [],
      allNilaiTA: [],
      allNilaiJURNAL: [],
      allNilaiTK: [],
      allNilaiSKILL: [],
      allNilaiDISKON: [],
      showNilaiTA: false,
      showNilaiTK: false,
      nilaiTA: '',
      nilaiTK: '',
      goodScoreText: SUCCESS_SCORE_MESSAGES,
      badScoreText: IMPROVEMENT_SCORE_MESSAGES,
      secretMessage: 'VnRjdHggQU4gdnAgV1RZUCBxbGNsaGxqX2VjdHhwIG9weXJseSBhcGRseSA6IHF3bHJ7cGxkZXBjX3Bycl8xX290ZXB4ZnZseX0=',
      resetPass: {
        password: '',
        repeatpass: '',
      },
      viewPassForm: false,
      echoChannel: null,
    });

    const activeView = ref('profil');
    const isRunmod = computed(() => Boolean(props.isRunmod));

    const isProfil = computed(() => activeView.value === 'profil');
    const isPraktikum = computed(() => activeView.value === 'praktikum');
    const isTP = computed(() => activeView.value === 'tp');
    const isNilai = computed(() => activeView.value === 'nilai');
    const isJawaban = computed(() => activeView.value === 'jawaban');

    const choiceClass = (index, option) => {
      return state.chosenJawaban[index]?.jawaban === option
        ? 'bg-green-500 text-white hover:bg-green-500'
        : 'bg-green-200 hover:bg-green-300';
    };

    const isChoiceSelected = (index, option) => state.chosenJawaban[index]?.jawaban === option;

    function showError(payload) {
      toasted?.global.showError(payload);
    }

    function showSuccess(payload) {
      toasted?.global.showSuccess(payload);
    }

    function setActiveView(view) {
      activeView.value = view;
      state.allJawabanJurnal = [];
      state.jawabanShown = false;
      state.currentJawabanJurnal = '';
    }

    function showPraktikum() {
      setActiveView('praktikum');
    }

    function showNilai() {
      setActiveView('nilai');
    }

    function showTP() {
      setActiveView('tp');
    }

    function showProfil() {
      setActiveView('profil');
    }

    function showJawaban() {
      setActiveView('jawaban');
    }

    function generateScoreText(nilai) {
      const pool = nilai > 50 ? state.goodScoreText : state.badScoreText;
      return pool[Math.floor(Math.random() * pool.length)] ?? '';
    }

    function tpPickRandomProgram() {
      return Math.floor(Math.random() * 3) + 5;
    }

    function pickATCnim() {
      return props.currentUser.kelas.substring(6, 10) === 'INT' ? '1101170002' : '1101170001';
    }

    function shuffleArr(array) {
      const arr = [...array];
      for (let i = arr.length - 1; i > 0; i -= 1) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
      }
      return arr;
    }

    function chooseJawaban(jawaban, soalId, soalIndex) {
      const target = state.chosenJawaban[soalIndex];
      if (!target) {
        return;
      }

      if (target.soal_id !== soalId) {
        const fallback = state.chosenJawaban.find((item) => item.soal_id === soalId);
        if (!fallback) {
          return;
        }
        fallback.jawaban = jawaban;
        return;
      }

      target.jawaban = jawaban;
    }

    function setPilihanPolling(pollIndex, asistenId) {
      if (!state.pilihanPolling[pollIndex]) {
        return;
      }

      state.pilihanPolling[pollIndex] = {
        ...state.pilihanPolling[pollIndex],
        praktikan_id: props.currentUser.id,
        asisten_id: asistenId,
      };
    }

    function savePolling() {
      http.post('/savePolling', state.pilihanPolling).then((response) => {
        if (response.data.message === 'success') {
          state.pollingComplete_mutable = true;
          return;
        }

        showError({ message: response.data.message });
      });
    }

    function showJawabanJurnal(modulId, modulUnlocked) {
      state.jawabanChanged = Boolean(modulUnlocked);
      if (!modulUnlocked) {
        return;
      }

      setTimeout(() => {
        state.jawabanShown = true;
        state.currentJawabanJurnal = modulId;
        http
          .post(`/praktikanGetJurnal/${props.currentUser.id}/${modulId}`)
          .then((response) => {
            if (response.data.message === 'success') {
              state.allJawabanJurnal = response.data.allJawabanJurnal ?? [];
              return;
            }

            showError({ message: response.data.message });
          });
      }, 250);

      setTimeout(() => {
        if (state.jawabanChanged) {
          state.jawabanChanged = false;
        }
      }, 1000);
    }

    function travel(destination) {
      setTimeout(() => {
        router.get(`/${destination}`, {}, { replace: true });
      }, 500);
    }

    function signOut() {
      state.pageActive = false;
      state.isMenuShown = false;
      setTimeout(() => {
        router.get('/logoutPraktikan', {}, { replace: true });
      }, 1010);
    }

    function sendMessage() {
      http
        .post('/sendPesan', state.formMessage)
        .then((response) => {
          if (response.data.message === 'success') {
            showSuccess({ message: 'Pesan berhasil terkirim' });
            state.messageOpened = false;
            return;
          }

          showError({ message: response.data.message });
        })
        .catch((error) => {
          const errors = error?.response?.data?.errors;
          if (errors?.kode?.length) {
            showError({ message: errors.kode[0] });
          }
          if (errors?.pesan?.length) {
            showError({ message: errors.pesan[0] });
          }
        });
    }

    function formPassword(visible) {
      state.viewPassForm = visible;
      if (!visible) {
        state.resetPass.password = '';
        state.resetPass.repeatpass = '';
      }
    }

    function resetPassword() {
      http
        .post('/resetPass', state.resetPass)
        .then((response) => {
          if (response.data.message === 'success') {
            showSuccess({ message: 'Password berhasil diperbaharui' });
            signOut();
            return;
          }

          showError({ message: response.data.message });
        })
        .catch((error) => {
          const errors = error?.response?.data?.errors;
          if (errors?.password?.length) {
            showError({ message: errors.password[0] });
          }
          if (errors?.repeatpass?.length) {
            showError({ message: errors.repeatpass[0] });
          }
        });
    }

    function finishPraktikum() {
      if (!state.laporanPraktikan.asisten_id) {
        showError({
          message: 'Pilih asisten yang mengajar anda terlebih dahulu <br> (dibagian paling atas samping kiri rating)',
        });
        return;
      }

      if (!state.laporanPraktikan.pesan) {
        showError({ message: 'Masukkan pesan untuk praktikum / asisten terlebih dahulu' });
        return;
      }

      if (state.laporanPraktikan.pesan.length < 20) {
        showError({ message: 'Pesan berisi minimal 20 karakter' });
        return;
      }

      if (state.laporanPraktikan.rating_asisten === 0) {
        showError({ message: 'Beri rating untuk asisten terlebih dahulu' });
        return;
      }

      if (state.laporanPraktikan.rating_praktikum === 0) {
        showError({ message: 'Beri rating untuk praktikum terlebih dahulu' });
        return;
      }

      state.laporanPraktikan.praktikan_id = props.currentUser.id;
      state.laporanPraktikan.modul_id = state.current_praktikum.modul_id;

      http.post('/sendLaporan', state.laporanPraktikan).then((response) => {
        if (response.data.message === 'success') {
          state.current_praktikum.status = 777;
          showSuccess({ message: 'Praktikum telah selesai :)' });
          return;
        }

        showError({ message: response.data.message });
      });
    }

    function generateQRCODE() {
      http.post('/sendTempJawabanTP', state.jawabanTP).then((response) => {
        if (response.data.message === 'success' && response.data.allJawaban_id !== null) {
          state.qrcodeShown = true;
          state.qrcodeData.allJawaban_id = response.data.allJawaban_id;
          state.qrcodeData.praktikan_id = props.currentUser.id;
          state.qrcodeData.kelas_id = props.currentUser.kelas_id;

          const ciphertext = CryptoJS.AES.encrypt(JSON.stringify(state.qrcodeData), 'daskom_aja');
          state.ecnryptedData = ciphertext.toString();
        }
      });
    }

    function saveJawabanTP() {
      http.post('/saveJawabanTP', state.jawabanTP).then((response) => {
        if (response.data.message === 'success') {
          showSuccess({ message: 'TP ANDA BERHASIL DISIMPAN' });
        }
      });
    }

    function saveJawabanMandiri() {
      http.post('/sendJawabanMandiri', state.jawabanMandiri).then((response) => {
        if (response.data.message === 'success') {
          showSuccess({ message: 'Jawaban mandiri berhasil disimpan' });
          return;
        }

        showError({ message: response.data.message });
      });
    }

    function saveJawabanRunmod() {
      http.post('/sendJawabanJurnal', state.jawabanRunmod).then((response) => {
        if (response.data.message === 'success') {
          showSuccess({ message: 'Jawaban runmod berhasil disimpan' });
          return;
        }

        showError({ message: response.data.message });
      });
    }

    function tpPickRandomEssay() {
      if (!state.soalTPEssay.length) {
        return null;
      }
      const index = Math.floor(Math.random() * state.soalTPEssay.length);
      return state.soalTPEssay[index];
    }

    function setCurrentPraktikumState(currentPraktikum, isRealtime) {
      state.current_praktikum = {
        asisten_id: currentPraktikum.asisten_id,
        modul_id: currentPraktikum.modul_id,
        kelas_id: currentPraktikum.kelas_id,
        status: currentPraktikum.status,
      };

      if (state.current_praktikum.kelas_id !== props.currentUser.kelas_id) {
        return;
      }

      http.post(`/getModul/${state.current_praktikum.modul_id}`).then((response) => {
        if (response.data.message === 'success' && response.data.modul) {
          state.current_modul.judul = response.data.modul.judul;
          state.current_modul.deskripsi = response.data.modul.deskripsi;
          state.current_modul.isi = response.data.modul.isi;

          if (state.current_praktikum.status === 0) {
            http.get('http://api.quotable.io/random').then((quoteResponse) => {
              state.programmingQuote = quoteResponse.data.content;
              state.quoteAuthor = quoteResponse.data.author;
            });
          }
        } else {
          showError({ message: response.data.message });
        }
      });

      switch (state.current_praktikum.status) {
        case 0:
          state.praktikumExist = true;
          showPraktikum();
          state.openWide = true;
          break;
        case 1:
          http.get(`/getSoalTA/${state.current_praktikum.modul_id}/${state.current_praktikum.kelas_id}`).then((response) => {
            if (response.data.message !== 'success' || !response.data.all_soal) {
              showError({ message: response.data.message });
              return;
            }

            state.soalTA = response.data.all_soal;
            state.chosenJawaban = [];
            state.jawabanTA = [];

            state.soalTA.forEach((soal) => {
              let allJawaban = [
                soal.jawaban_benar,
                soal.jawaban_salah1,
                soal.jawaban_salah2,
                soal.jawaban_salah3,
              ];

              allJawaban = shuffleArr(allJawaban);

              state.chosenJawaban.push({
                modul_id: state.current_praktikum.modul_id,
                praktikan_id: props.currentUser.id,
                soal_id: soal.id,
                jawaban: '',
              });

              state.jawabanTA.push(allJawaban);
            });
          });
          break;
        case 2:
          if (isRealtime) {
            http.post('/sendJawabanTA', state.chosenJawaban).then((response) => {
              if (response.data.message === 'success') {
                state.nilaiTA = response.data.nilaiTa;
                state.showNilaiTA = true;
                return;
              }

              showError({ message: response.data.message });
            });
          }

          http.get('/api/soal/jurnal').then((response) => {
            if (response.data.message === 'success' && response.data.all_soal) {
              state.soalJurnal = response.data.all_soal.filter((el) => el != null);
              state.jawabanJurnal = state.soalJurnal.map((soal) => ({
                soal_id: soal.id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: '',
              }));
            } else {
              showError({ message: response.data.message });
            }
          });

          http.get('/api/soal/fitb').then((response) => {
            if (response.data.message === 'success' && response.data.all_soal) {
              state.soalFitb = response.data.all_soal.filter((el) => el != null);
              state.jawabanFitb = state.soalFitb.map((soal) => ({
                soal_id: soal.id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: '',
              }));
            } else {
              showError({ message: response.data.message });
            }
          });
          break;
        case 3:
          if (isRealtime) {
            http.post('/sendJawabanJurnal', state.jawabanJurnal).then((response) => {
              if (response.data.message !== 'success') {
                showError({ message: response.data.message });
              }
            });

            http.post('/sendJawabanFitb', state.jawabanFitb).then((response) => {
              if (response.data.message !== 'success') {
                showError({ message: response.data.message });
              }
            });
          }

          http.get(`/getSoalMANDIRI/${state.current_praktikum.modul_id}/${state.current_praktikum.kelas_id}`).then((response) => {
            if (response.data.message === 'success' && response.data.all_soal) {
              state.soalMandiri = response.data.all_soal;
              state.jawabanMandiri = state.soalMandiri.map((soal) => ({
                soal_id: soal.id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: '',
              }));
            } else {
              showError({ message: response.data.message });
            }
          });
          break;
        case 4:
          if (isRealtime) {
            http.post('/sendJawabanMandiri', state.jawabanMandiri).then((response) => {
              if (response.data.message !== 'success') {
                showError({ message: response.data.message });
              }
            });
          }

          http.get(`/getSoalTK/${state.current_praktikum.modul_id}/${state.current_praktikum.kelas_id}`).then((response) => {
            if (response.data.message === 'success' && response.data.all_soal) {
              state.soalTK = response.data.all_soal;
              state.chosenJawaban = [];
              state.jawabanTK = [];

              state.soalTK.forEach((soal) => {
                let allJawaban = [
                  soal.jawaban_benar,
                  soal.jawaban_salah1,
                  soal.jawaban_salah2,
                  soal.jawaban_salah3,
                ];

                allJawaban = shuffleArr(allJawaban);

                state.chosenJawaban.push({
                  modul_id: state.current_praktikum.modul_id,
                  praktikan_id: props.currentUser.id,
                  soal_id: soal.id,
                  jawaban: '',
                });

                state.jawabanTK.push(allJawaban);
              });
            } else {
              showError({ message: response.data.message });
            }
          });
          break;
        case 5:
          if (isRunmod.value) {
            if (isRealtime) {
              http.post('/sendJawabanJurnal', state.jawabanRunmod).then((response) => {
                if (response.data.message !== 'success') {
                  showError({ message: response.data.message });
                }
              });
            }
          } else if (isRealtime) {
            http.post('/sendJawabanTK', state.chosenJawaban).then((response) => {
              if (response.data.message === 'success') {
                state.nilaiTK = response.data.nilaiTk;
                state.showNilaiTK = true;
              } else {
                showError({ message: response.data.message });
              }
            });
          }

          http.post(`/getLaporan/${props.currentUser.id}/${state.current_praktikum.modul_id}`).then((response) => {
            if (response.data.message === 'done') {
              state.current_praktikum.status = 777;
              state.praktikumExist = false;
              state.openWide = false;
            }
          });
          break;
        case 123:
          http.get('/api/soal/runmod').then((response) => {
            if (response.data.message === 'success' && response.data.all_soal) {
              state.soalRunmod = response.data.all_soal;
              state.jawabanRunmod = state.soalRunmod.map((soal) => ({
                soal_id: soal.id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: '',
              }));
            } else {
              showError({ message: response.data.message });
            }
          });
          break;
        default:
          state.current_praktikum.status = 777;
          state.praktikumExist = false;
          state.openWide = false;
          break;
      }
    }
    onMounted(() => {
      document.body.classList.add('closed');
      showProfil();

      if (props.comingFrom === 'login' || props.comingFrom === 'none') {
        setTimeout(() => {
          state.pageActive = true;
        }, 10);
      }

      http.post('/checkPolling').then((response) => {
        if (response.data.message === 'success') {
          state.isPollingEnabled = response.data.isPollingEnabled;
          return;
        }
        showError({ message: response.data.message });
      });

      http.post('/checkPraktikum').then((response) => {
        if (response.data.message === 'success' && response.data.current_praktikum) {
          if (response.data.current_praktikum.kelas_id === props.currentUser.kelas_id) {
            setCurrentPraktikumState(response.data.current_praktikum, false);
          }
          return;
        }
        showError({ message: response.data.message });
      });

      http.post(`/getAllNilai/${props.currentUser.id}`).then((response) => {
        if (response.data.message !== 'success') {
          showError({ message: response.data.message });
          return;
        }

        response.data.allNilai.forEach((nilai) => {
          state.allNilaiData.labels.push(nilai.judul);
          state.allNilaiTP.push(nilai.tp);
          state.allNilaiTA.push(nilai.ta);
          state.allNilaiJURNAL.push(nilai.jurnal);
          state.allNilaiTK.push(nilai.tk);
          state.allNilaiSKILL.push(nilai.skill);
          state.allNilaiDISKON.push(nilai.diskon);
        });

        const datasetFactories = [
          { label: 'TP', key: 'tp' },
          { label: 'TA', key: 'ta' },
          { label: 'JURNAL', key: 'jurnal' },
          { label: 'TK', key: 'tk' },
          { label: 'SKILL', key: 'skill' },
          { label: 'DISKON', key: 'diskon' },
        ];

        datasetFactories.forEach(({ label, key }) => {
          const dataset = {
            label,
            backgroundColor: 'rgba(75,192,192,0.1)',
            borderColor: '#00c853',
            pointBackgroundColor: 'black',
            borderWidth: 2,
            pointBorderColor: 'black',
            data: [],
          };

          response.data.allNilai.forEach((nilai) => {
            dataset.data.push(nilai[key]);
          });

          state.allNilaiData.datasets.push(dataset);
        });
      });

      if (props.currentUser.kelas.substring(6, 10) === 'INT') {
        state.all_modul = props.allModul
          .filter((modul) => modul.isEnglish === 1 && modul.id <= 20)
          .map((modul) => ({
            ...modul,
            isUnlocked: modul.isUnlocked === 1,
          }));

        http.get(`/getSoalTP/true/${props.currentUser.id}`).then((response) => {
          if (response.data.message !== 'success') {
            return;
          }

          if (response.data.all_soalEssay) {
            state.soalTPEssay = response.data.all_soalEssay;
            state.soalTPEssay.forEach((soal) => {
              state.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: soal.jawaban ?? '',
              });
            });
          }

          if (response.data.all_soalProgram) {
            state.soalTPProgram = response.data.all_soalProgram;
            state.soalTPProgram.forEach((soal) => {
              state.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: soal.jawaban ?? '',
              });
            });
          }
        });

        http.post('/getPembahasanTP/true').then((response) => {
          if (response.data.message === 'success' && response.data.tp) {
            state.pembahasanTp.modul_id = response.data.tp.modul_id;
            state.pembahasanTp.pembahasan = response.data.tp.pembahasan;
            state.qrcodeData.modul_id = response.data.tp.modul_id;
          }
        });
      } else {
        state.all_modul = props.allModul
          .filter((modul) => modul.isEnglish === 0 && modul.id <= 20)
          .map((modul) => ({
            ...modul,
            isUnlocked: modul.isUnlocked === 1,
          }));

        http.get(`/getSoalTP/false/${props.currentUser.id}`).then((response) => {
          if (response.data.message !== 'success') {
            return;
          }

          if (response.data.all_soalEssay) {
            state.soalTPEssay = response.data.all_soalEssay;
            state.soalTPEssay.forEach((soal) => {
              state.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: soal.jawaban ?? '',
              });
            });
          }

          if (response.data.all_soalProgram) {
            state.soalTPProgram = response.data.all_soalProgram;
            state.soalTPProgram.forEach((soal) => {
              state.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: props.currentUser.id,
                jawaban: soal.jawaban ?? '',
              });
            });
          }
        });

        http.post('/getPembahasanTP/false').then((response) => {
          if (response.data.message === 'success' && response.data.tp) {
            state.pembahasanTp.modul_id = response.data.tp.modul_id;
            state.pembahasanTp.pembahasan = response.data.tp.pembahasan;
            state.qrcodeData.modul_id = response.data.tp.modul_id;
          }
        });
      }

      if (window.Echo) {
        state.echoChannel = window.Echo.channel(`praktikum.${props.currentUser.kelas_id}`)
          .listen('.PraktikumStatusUpdated', (data) => {
            setCurrentPraktikumState(data.current_praktikum, true);
          });
      }
    });

    onBeforeUnmount(() => {
      if (window.Echo) {
        window.Echo.leave(`praktikum.${props.currentUser.kelas_id}`);
      }
    });

    return {
      ...toRefs(state),
      allPolling: props.allPolling,
      activeView,
      isProfil,
      isPraktikum,
      isTP,
      isNilai,
      isJawaban,
      isRunmod,
      choiceClass,
      isChoiceSelected,
      showPraktikum,
      showNilai,
      showTP,
      showProfil,
      showJawaban,
      generateScoreText,
      tpPickRandomProgram,
      pickATCnim,
      shuffleArr,
      chooseJawaban,
      setPilihanPolling,
      savePolling,
      showJawabanJurnal,
      travel,
      signOut,
      sendMessage,
      formPassword,
      resetPassword,
      finishPraktikum,
      generateQRCODE,
      saveJawabanTP,
      saveJawabanMandiri,
      saveJawabanRunmod,
      tpPickRandomEssay,
      setCurrentPraktikumState,
    };
  },
});
</script>
