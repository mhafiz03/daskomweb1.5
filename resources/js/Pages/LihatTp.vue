<template>
  <div class="bg-green-900 w-full h-full overflow-hidden">

    <!-- Main Menu -->
    <SidebarMenu
      :page-active="pageActive"
      :items="visibleMenuItems"
      :menu-container-class="menuContainerClass"
      :highlighted-menu="highlightedMenu"
      :menu-ref="menuRefWrapped.ref"
      @hover="isMenuShown = false"
      @select="handleMenuSelect"
    />

    <!-- Profile Menu -->
    <AsistenProfilePanel
      :page-active="pageActive"
      :is-menu-shown="isMenuShown"
      :current-user="currentUser"
      :user-role="userRole"
      @update:isMenuShown="isMenuShown = $event"
      @sign-out="signOut"
    />

    <div class="absolute h-full w-120full animation-enable"
        :class="[{ 'left-minFull': !currentPage },
                { 'left-0': currentPage }]">
      <div class="w-full h-full py-4 flex">
        <!-- TP Data Display -->
        <div class="w-full h-screen py-4 flex"
            :class="[{ 'visible': !tpDataShown },
                    { 'hidden': tpDataShown }]">
          <div class="w-72 h-72 m-auto">
            <div class="w-full h-full flex-row">
              <div class="w-full py-2 px-5 h-1/4 flex-row">
                <span class="font-merri w-full text-left text-yellow-400 text-lg h-1/4">
                  NIM
                </span>
                <div class="w-full h-3/4">
                  <input v-model="praktikanNim"
                        class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                        id="Kelas" type="text" placeholder="101022130600">
                </div>
              </div>
              <div class="w-full h-1/4 flex-row py-2 px-5">
                <span class="font-merri w-full text-left text-yellow-400 text-lg h-1/4">
                  Pilihan modul
                </span>
                <select v-model="chosenModulID"
                      class="block font-monda-bold text-xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500">
                  <option v-for="modul in allModul" v-bind:key="modul.id" :value="modul.id">
                    {{ modul.judul }}
                  </option>
                </select>
              </div>
              <div class="w-full h-1/4 mt-8 p-2 hover:p-3 cursor-pointer animation-enable-short flex"
                  v-on:click="cekTpPraktikan()">
                <div class="w-full h-full bg-green-600 rounded-lg flex">
                  <div class="w-auto h-auto m-auto font-monda-bold text-white text-2xl">
                    <span>CEK TP</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TP Results Display -->
        <div v-if="tpDataShown" class="w-full h-full">
          <div class="w-full h-12 sm:flex flex-row bg-green-600 shadow-xl">
            <div class="sm:w-1/2 w-auto sm:h-auto h-1/2 m-auto text-lg text-yellow-500 font-overpass-bold flex">
              <span class="m-auto">{{ praktikanNim != '' ? praktikanNim : "none" }}</span>
            </div>
            <div class="sm:w-1/2 w-auto sm:h-auto h-1/2 m-auto text-lg text-yellow-500 font-overpass-bold flex">
              <span class="m-auto truncate">{{ chosenModulID != '' ? allModul[chosenModulID-1].judul : "none" }}</span>
            </div>
          </div>

          <div class="w-full h-full" v-scrollbar>
            <div>
              <div class="w-full h-auto flex-row">
                <div v-for="(jawaban, index) in allTpData" v-bind:key="jawaban.id" 
                    class="w-full flex-row h-auto">
                  <div class="w-full h-auto flex my-10">
                    <div class="h-full w-12 flex font-merri-bold text-sm sm:text-xl text-white">
                      <div class="m-auto w-auto h-auto">{{ index +1 }}</div>
                    </div>
                    <div class="h-12 px-1 w-4">
                      <div class="h-full w-full bg-white"/>
                    </div>
                    <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-lg sm:text-2xl text-white">
                      <span>{{ jawaban.soal }}</span>
                    </div>
                  </div>
                  <div class="w-full h-auto flex px-5">
                    <textarea v-model="allTpData[index].jawaban" cols="30" rows="10"
                          class="font-overpass-mono-bold resize-none text-lg sm:text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                          type="text" disabled/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { MENU_ITEMS, PRIVILEGES } from '@/constants';
import { ref, toRef, toRefs } from 'vue';
import { useNavigation } from '@/composables/useNavigation';
import { useToast } from '@/composables/useToast';
import { useSidebarMenu } from '@/composables/useSidebarMenu';
import SidebarMenu from '@/components/asisten/SidebarMenu.vue';
import AsistenProfilePanel from '@/components/asisten/ProfilePanel.vue';

export default {
  props: [
    'comingFrom',
    'currentUser',
    'position',
    'userRole',
    'allModul',
  ],

  components: {
    SidebarMenu,
    AsistenProfilePanel
  },


  setup(props) {
    const menuRef = ref(null);

    // Initialize navigation composable
    const navigation = useNavigation({
      userType: 'asisten',
      menuRef: menuRef,
      currentPage: 'lihat_tp'
    });
    const navigationRefs = toRefs(navigation);

    // Initialize menu state based on comingFrom prop
    navigation.initializeMenu(props.comingFrom, true);
    const toast = useToast();
    
    // Initialize sidebar menu
    const sidebarMenu = useSidebarMenu({
      menuItems: MENU_ITEMS,
      privileges: PRIVILEGES,
      currentUser: toRef(props, 'currentUser'),
      currentPageId: 'lihat_tp',
      changePage: navigationRefs.changePage,
    });
    

    // Return all navigation state and methods
    return {
      toast,
      menuRef,
      menuRefWrapped: { ref: menuRef }, // Wrapped for template
      ...navigationRefs,
      ...sidebarMenu,
    };
  },

  data() {
    return {
      privileges: { ...PRIVILEGES},

      soalPriviledge: "all",
      
      pageActive: true,
      isMenuShown: false,

      allTpData: [],
      tpDataShown: false,
      praktikanNim: '',
      chosenModulID: '',
    };
  },

  mounted() {
    $('body').addClass('closed');
    
    // Restore scroll position if provided
    if (this.menuRef && this.position != null) {
      this.$nextTick(() => {
        const target = Number(this.position) || 0;
        
        // menuRef is auto-unwrapped in Options API, so we can access properties directly
        if (this.menuRef && this.menuRef.scrollTop !== undefined) {
          this.menuRef.scrollTop = target;
        }
      });
    }

    const globe = this;

    if(this.comingFrom === 'asisten' ||
        this.comingFrom === 'none' ||
        this.comingFrom === 'kelas'||
        this.comingFrom === 'soal'||
        this.comingFrom === 'praktikum'||
        this.comingFrom === 'plotting' ||
        this.comingFrom === 'konfigurasi' ||
        this.comingFrom === 'tp' ||
        this.comingFrom === 'history'||
        this.comingFrom === 'nilai'||
        this.comingFrom === 'pelanggaran'||
        this.comingFrom === 'polling' ||
        this.comingFrom === 'setpraktikan'||
        this.comingFrom === 'rating' ||
        this.comingFrom === 'allLaporan' ||
        this.comingFrom === 'jawaban'){

      setTimeout(
        function() {
          globe.currentPage = true;
        }, 10
      );
    }
  },

  methods: {
    handleMenuSelect(target) {
      this.setActiveMenu(target);
      this.travel(target);
    },
    
    cekTpPraktikan: function(){
      const globe = this;

      if(this.praktikanNim === '') {
        this.toast.error("Isikan nim nya terlebih dahulu");
        return;
      }

      if(this.chosenModulID === ''){
        this.toast.error("Pilih modul nya terlebih dahulu");
        return;
      }

      globe.$axios.post('/api/get-tp/'+this.praktikanNim+'/'+this.chosenModulID).then(response => {
        if(response.data.message === "success") {
          globe.tpDataShown = true;
          globe.allTpData = response.data.all_tp;
        } else {
          this.toast.error(response.data.message);
        }
      });
    },

    // Navigation methods
    travel: function(destination) {
      // Set active menu
      this.setActiveMenu(destination);
        
      // Get scroll position
      const scrollPosition = this.menuRef ? this.menuRef.scrollTop : 0;
        
      // Navigate to the destination
      this.$inertia.get('/asisten/' + destination, {
        comingFrom: 'lihat_tp',
        position: scrollPosition
      });
    },

    signOut: function() {
      this.pageActive = false;
      this.currentPage = false;
      
      const globe = this;
      setTimeout(
        function() {
          globe.$inertia.get('/auth/asisten/logout', {}, {
            replace: true,
          });
        }, 1010); 
    }
  }
}
</script>