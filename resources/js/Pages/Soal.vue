<template>
  <div class="bg-green-900 w-full h-full overflow-hidden">

    <!-- Main Menu -->
    <div class="absolute w-120 z-20 h-48full bottom-0 right-0 animation-enable"
        :class="[{ 'right-0': pageActive },
                { 'right-min20rem': !pageActive }]" @mouseover="isMenuShown = false">
      <div class="w-full h-full animation-enable overflow-y-auto rounded-tl-large"
          :class="menuContainerClass" ref="menuRef">
        <div
          v-for="item in visibleMenuItems"
          :key="item.id"
          :class="menuItemClasses(item)"
          @click="!item.isCurrentPage && travel(item.id)">
          <div class="w-7/12 my-2 flex">
            <div class="w-4/6"></div>
            <img :class="['select-none m-auto w-2/6 h-auto', item.icon]">
          </div>
          <span class="ml-6 font-merri-bold font-medium w-full text-start self-center text-xl">
            {{ item.label }}
          </span>
        </div>
      </div>
    </div>

    <!-- Profile Menu -->
    <div class="w-72 bg-gray-200 absolute top-0 mr-6 mt-4 h-40 rounded-lg flex-row animation-enable"
        :class="[{ 'hidden': !isMenuShown },
                { 'visible': isMenuShown },
                { 'right-min20rem': !pageActive },
                { 'right-0': pageActive }]" @mouseover="isMenuShown = true" @mouseleave="isMenuShown = false">
        <div class="w-full h-3/4"/>
        <div class="w-full h-1/4 flex">
          <div class="rounded-b-lg bg-gray-400 flex hover:bg-gray-500 w-full h-full cursor-pointer" v-on:click="signOut">
            <span class="m-auto font-monda-bold text-lg text-right w-full">
              Logout
            </span>
            <img class="select-none p-3 h-full w-auto mr-20 m-auto fas fa-sign-out-alt">
          </div>
        </div>
    </div>

    <!-- Assistant's Profile -->
    <div class="absolute top-0 w-120 flex animation-enable"
        :class="[{ 'right-0': pageActive },
                { 'right-min20rem': !pageActive },
                { 'h-48': !isMenuShown },
                { 'h-36': isMenuShown }]" @mouseover="isMenuShown = true">
      <div class="w-auto m-auto h-full flex">
        <div class="w-24 h-full flex mr-4">
          <div class="flex w-24 h-24 m-auto rounded-full"
              :class="[{ 'bg-green-400': !isMenuShown },
                      { 'bg-green-600': isMenuShown }]">
            <img class="select-none w-20 h-20 m-auto rounded-full bg-white object-cover" :src="'/assets/'+currentUser.kode+'.jpg'" alt="daskom logo">
          </div>
        </div>
        <div class="w-auto h-full flex-row ml-4 cursor-default">
          <div class="h-3/5 w-full flex">
            <span class="select-none3 font-overpass-mono-bold text-5xl self-end text-left w-full -mb-2 uppercase tracking-widest"
                :class="[{ 'text-black': isMenuShown },
                        { 'text-white ': !isMenuShown }]">
              {{ currentUser.kode }}
            </span>
          </div>
          <div class="h-2/5 text-xl w-full text-left -mt-2">
            <span class="selec-none font-overpass-thin font-semibold capitalize"
                :class="[{ 'text-black': isMenuShown },
                        { 'text-white ': !isMenuShown }]">
              [ {{ userRole }} ]
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Soal Layout -->
    <div class="absolute py-8 pr-8 h-full w-120full flex animation-enable"
        :class="[{ 'left-0': currentPage },
                { 'left-minFull': !currentPage }]" @mouseover="isMenuShown = false">
      <div class="rounded-r-large relative bg-green-400 text-center font-monda-bold text-3xl w-full h-full flex-row">
        <div class="relative w-full h-16 flex">
          <div class="w-1/6 h-16 p-3 hover:bg-green-200 cursor-pointer"
              v-on:click='setActiveSoal("TP")'>
            TP
          </div>
          <div class="w-1/6 h-16 p-3 hover:bg-green-200 cursor-pointer"
              v-on:click='setActiveSoal("TA")'>
            TA
          </div>
          <div class="w-1/6 h-16 p-3 hover:bg-green-200 cursor-pointer"
              v-on:click='setActiveSoal("TK")'>
            TK
          </div>
          <div class="w-1/6 h-16 p-3 hover:bg-green-200 cursor-pointer"
              v-on:click='setActiveSoal("Jurnal")'>
            Jurnal
          </div>
          <div class="w-1/6 h-16 p-3 hover:bg-green-200 cursor-pointer"
              v-on:click='setActiveSoal("Mandiri")'>
            Mandiri
          </div>
          <div class="w-1/6 h-16 p-3 hover:bg-green-200 cursor-pointer rounded-tr-large"
              v-on:click='setActiveSoal("FITB")'>
            FITB
          </div>
          <div class="absolute pointer-events-none w-full h-16">
            <div class="absolute bottom-0 w-full flex h-1">
              <div class="h-full animation-enable-medium"
                  :class="[{ 'w-1/6': isTA },
                          { 'w-0': !isTA }]"/>
              <div class="h-full animation-enable-medium"
                  :class="[{ 'w-2/6': isTK },
                          { 'w-0': !isTK }]"/>
              <div class="h-full animation-enable-medium"
                  :class="[{ 'w-3/6': isJurnal },
                          { 'w-0': !isJurnal }]"/>
              <div class="h-full animation-enable-medium"
                  :class="[{ 'w-4/6': isMandiri },
                          { 'w-0': !isMandiri }]"/>
              <div class="h-full animation-enable-medium"
                  :class="[{ 'w-5/6': isFITB },
                          { 'w-0': !isFITB }]"/>
              <div class="w-1/6 px-4 h-full">
                <div class="w-full h-full rounded-full bg-black" ref="chosenMenu"/>
              </div>
            </div>
          </div>
        </div>

        <div class="relative w-full h-12 flex">
          <div class="w-full h-full flex-row px-5">
            <select v-model="chosenModulID"
                  class="block font-monda-bold text-xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500">
            <option class="hidden" value="" disabled selected>
              Pilih modul
            </option>
            <option v-for="modul in allModul" v-bind:key="modul.id" :value="modul.id" :disabled="!modul.id">
              {{ modul.judul }}
            </option>  
            </select>
          </div>
        </div>

        <!-- Active List Soal Layout -->
        <div class="w-full h-full pb-30">
          <div class="w-full h-full" v-scrollbar>
            <div class="px-8 pt-4">
              <div v-if="!isTA && !isTK && !isJurnal && !isMandiri && !isFITB" class="w-full h-full flex-row">
                <transition-group name="soal-list" tag="div">
                  <div v-for="soal in listAllTP.filter( function(item){return (item.modul_id===chosenModulID);} )" v-bind:key="soal.id"
                      class="animation-enable w-full h-48 mb-4">
                    <div class="w-full h-full flex rounded-lg bg-yellow-200">
                      <div class="w-2/5 h-full flex-row">
                        <div class="w-full h-2/3 flex rounded-tl-lg font-merri-bold text-2xl bg-yellow-400">
                          <div class="w-auto h-auto m-auto">
                            {{ soal.judul }}
                          </div>
                        </div>
                        <div class="w-full h-1/3 flex font-overpass-mono-bold text-xl rounded-bl-lg bg-yellow-300">
                          <div class="w-auto h-auto m-auto">
                            <span v-if="soal.isEssay">
                              Essay 
                            </span>
                            <span v-if="soal.isProgram">
                              Program 
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="w-3/5 h-full flex">
                        <div class="w-16full h-full flex">
                          <div class="w-full h-full overflow-y-auto break-words whitespace-pre-wrap p-4 text-left font-overpass-bold text-xl">
                            <span>{{ soal.soal }}</span>  
                          </div>
                        </div>
                        <div class="w-16 bg-gray-400 rounded-r-lg h-full flex-row"
                            v-if="editPriviledge.includes(currentUser.role_id)">

                          <!-- Open this only when needed -->
                          <div class="w-full h-1/2 flex">
                            <span class="w-full h-full p-3 hover:p-4 cursor-pointer animation-enable-short"
                                v-on:click="deleteSoal(soal.id)">
                              <img class="w-full h-full p-1 fas fa-trash">
                            </span>
                          </div>

                          <div class="w-full h-1/2 flex">
                            <span class="w-full h-full p-3 visible hover:p-4 cursor-pointer animation-enable-short"
                                :class="'editOpenTP-'+soal.id"
                                v-on:click="editSoal(soal, true)">
                              <img class="w-full h-full p-1 fas fa-pen">
                            </span>
                            <span class="w-full h-full hidden p-3 hover:p-4 cursor-pointer animation-enable-short"
                                :class="'editCloseTP-'+soal.id"
                                v-on:click="editSoal(soal, false)">
                              <img class="w-full h-full p-1 fas fa-times">
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </transition-group>
              </div>
              <div v-if="isTA || isTK" class="w-full h-full">
                <div v-if="isTA" class="w-full h-full flex-row">
                  <transition-group name="soal-list" tag="div">
                    <div v-for="soal in listAllTA.filter( function(item){return (item.modul_id===chosenModulID);} )" v-bind:key="soal.id"
                        class="animation-enable w-full h-64 mb-4">
                      <div class="w-full h-full flex rounded-lg bg-yellow-200">
                        <div class="w-1/2 h-full flex-row">
                          <div class="w-full h-1/3 flex rounded-tl-lg font-merri-bold text-xl bg-yellow-400">
                            <div class="w-auto h-auto m-auto">
                              {{ soal.judul }} 
                            </div>
                          </div>
                          <div class="w-full h-2/3 flex font-overpass-mono-bold text-xl rounded-bl-lg bg-yellow-300 overflow-y-auto">
                            <div class="w-auto h-auto text-left p-4 break-words whitespace-pre-wrap m-auto">
                              <span>{{ soal.pertanyaan }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="w-1/2 h-full flex">
                          <div class="w-full h-full flex-row overflow-y-auto overflow-x-hidden p-4 text-left font-overpass-bold text-xl">
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-green-500 rounded-lg">
                              <span>{{ soal.jawaban_benar }}</span>
                            </div>
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-red-500 rounded-lg">
                              <span>{{ soal.jawaban_salah1 }}</span>
                            </div>
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-red-500 rounded-lg">
                              <span>{{ soal.jawaban_salah2 }}</span>
                            </div>
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-red-500 rounded-lg">
                              <span>{{ soal.jawaban_salah3 }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="w-16 bg-gray-400 rounded-r-lg h-full flex-row"
                            v-if="editPriviledge.includes(currentUser.role_id)">

                          <!-- Open this only when needed -->
                          <div class="w-full h-1/2 flex">
                            <span class="w-full h-full p-3 hover:p-4 cursor-pointer animation-enable-short"
                                v-on:click="deleteSoal(soal.id)">
                              <img class="w-full h-full p-1 fas fa-trash">
                            </span>
                          </div>

                          <div class="w-full h-1/2 flex">
                            <span class="w-full h-full p-3 visible hover:p-4 cursor-pointer animation-enable-short"
                                :class="'editOpenTA-'+soal.id"
                                v-on:click="editSoal(soal, true)">
                              <img class="w-full h-full p-1 fas fa-pen">
                            </span>
                            <span class="w-full h-full hidden p-3 hover:p-4 cursor-pointer animation-enable-short"
                                :class="'editCloseTA-'+soal.id"
                                v-on:click="editSoal(soal, false)">
                              <img class="w-full h-full p-1 fas fa-times">
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </transition-group>
                </div>
                <div v-if="isTK" class="w-full h-full flex-row">
                  <transition-group name="soal-list" tag="div">
                    <div v-for="soal in listAllTK.filter( function(item){return (item.modul_id===chosenModulID);} )" v-bind:key="soal.id"
                        class="animation-enable w-full h-64 mb-4">
                      <div class="w-full h-full flex rounded-lg bg-yellow-200">
                        <div class="w-1/2 h-full flex-row">
                          <div class="w-full h-1/3 flex rounded-tl-lg font-merri-bold text-xl bg-yellow-400">
                            <div class="w-auto h-auto m-auto">
                              {{ soal.judul }} 
                            </div>
                          </div>
                          <div class="w-full h-2/3 flex font-overpass-mono-bold text-xl rounded-bl-lg bg-yellow-300 overflow-y-auto">
                            <div class="w-auto h-auto text-left p-4 break-words whitespace-pre-wrap m-auto">
                              <span>{{ soal.pertanyaan }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="w-1/2 h-full flex">
                          <div class="w-full h-full flex-row overflow-y-auto p-4 text-left font-overpass-bold text-xl">
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-green-500 rounded-lg">
                              <span>{{ soal.jawaban_benar }}</span>
                            </div>
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-red-500 rounded-lg">
                              <span>{{ soal.jawaban_salah1 }}</span>
                            </div>
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-red-500 rounded-lg">
                              <span>{{ soal.jawaban_salah2 }}</span>
                            </div>
                            <div class="w-full h-auto break-words whitespace-pre-wrap mb-4 p-4 bg-red-500 rounded-lg">
                              <span>{{ soal.jawaban_salah3 }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="w-16 bg-gray-400 rounded-r-lg h-full flex-row"
                            v-if="editPriviledge.includes(currentUser.role_id)">

                          <!-- Open this only when needed -->
                          <div class="w-full h-1/2 flex">
                            <span class="w-full h-full p-3 hover:p-4 cursor-pointer animation-enable-short"
                                v-on:click="deleteSoal(soal.id)">
                              <img class="w-full h-full p-1 fas fa-trash">
                            </span>
                          </div>

                          <div class="w-full h-1/2 flex">
                            <span class="w-full h-full p-3 visible hover:p-4 cursor-pointer animation-enable-short"
                                :class="'editOpenTK-'+soal.id"
                                v-on:click="editSoal(soal, true)">
                              <img class="w-full h-full p-1 fas fa-pen">
                            </span>
                            <span class="w-full h-full hidden p-3 hover:p-4 cursor-pointer animation-enable-short"
                                :class="'editCloseTK-'+soal.id"
                                v-on:click="editSoal(soal, false)">
                              <img class="w-full h-full p-1 fas fa-times">
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </transition-group>
                </div>
              </div>
              <div v-if="isJurnal || isMandiri || isFITB" class="w-full h-full">
                <div v-if="isJurnal" class="w-full h-full flex-row">
                  <transition-group name="soal-list" tag="div">
                    <div v-for="soal in listAllJurnal.filter( function(item){return (item.modul_id===chosenModulID);} )" v-bind:key="soal.id"
                        class="animation-enable w-full h-120 mb-4">
                      <div class="w-full h-full flex rounded-lg bg-yellow-200">
                        <div class="w-1/3 h-full flex-row">
                          <div class="w-full h-full flex rounded-l-lg font-merri-bold text-3xl bg-yellow-400">
                            <div class="w-auto h-auto m-auto">
                              {{ soal.judul }} <br> {{ soal.isSulit ? '(Sulit)' : '(Sedang)' }}
                            </div>
                          </div>
                        </div>
                        <div class="w-2/3 h-full flex">
                          <div class="w-16full h-full flex">
                            <div class="w-full h-full break-words whitespace-pre-wrap flex-row overflow-y-auto p-4 text-left font-overpass-bold text-2xl">
                              <span>{{ soal.soal }}</span>
                            </div>
                          </div>
                          <div class="w-16 bg-gray-400 rounded-r-lg h-full flex-row"
                            v-if="editPriviledge.includes(currentUser.role_id)">

                            <!-- Open this only when needed -->
                            <div class="w-full h-1/2 flex">
                              <span class="w-full h-full p-3 hover:p-4 cursor-pointer animation-enable-short"
                                  v-on:click="deleteSoal(soal.id)">
                                <img class="w-full h-full p-1 fas fa-trash">
                              </span>
                            </div>

                            <div class="w-full h-1/2 flex">
                              <span class="w-full h-full p-3 visible hover:p-4 cursor-pointer animation-enable-short"
                                  :class="'editOpenJurnal-'+soal.id"
                                  v-on:click="editSoal(soal, true)">
                                <img class="w-full h-full p-1 fas fa-pen">
                              </span>
                              <span class="w-full h-full hidden p-3 hover:p-4 cursor-pointer animation-enable-short"
                                  :class="'editCloseJurnal-'+soal.id"
                                  v-on:click="editSoal(soal, false)">
                                <img class="w-full h-full p-1 fas fa-times">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </transition-group>
                </div>
                <div v-if="isMandiri" class="w-full h-full flex-row">
                  <transition-group name="soal-list" tag="div">
                    <div v-for="soal in listAllMandiri.filter( function(item){return (item.modul_id===chosenModulID);} )" v-bind:key="soal.id"
                        class="animation-enable w-full h-120 mb-4">
                      <div class="w-full h-full flex rounded-lg bg-yellow-200">
                        <div class="w-1/3 h-full flex-row">
                          <div class="w-full h-full flex rounded-l-lg font-merri-bold text-3xl bg-yellow-400">
                            <div class="w-auto h-auto m-auto">
                              {{ soal.judul }}
                            </div>
                          </div>
                        </div>
                        <div class="w-2/3 h-full flex">
                          <div class="w-16full h-full flex">
                            <div class="w-full h-full break-words whitespace-pre-wrap flex-row overflow-y-auto p-4 text-left font-overpass-bold text-2xl">
                              <span>{{ soal.soal }}</span>
                            </div>
                          </div>
                          <div class="w-16 bg-gray-400 rounded-r-lg h-full flex-row"
                            v-if="editPriviledge.includes(currentUser.role_id)">

                            <!-- Open this only when needed -->
                            <div class="w-full h-1/2 flex">
                              <span class="w-full h-full p-3 hover:p-4 cursor-pointer animation-enable-short"
                                  v-on:click="deleteSoal(soal.id)">
                                <img class="w-full h-full p-1 fas fa-trash">
                              </span>
                            </div>

                            <div class="w-full h-1/2 flex">
                              <span class="w-full h-full p-3 visible hover:p-4 cursor-pointer animation-enable-short"
                                  :class="'editOpenMandiri-'+soal.id"
                                  v-on:click="editSoal(soal, true)">
                                <img class="w-full h-full p-1 fas fa-pen">
                              </span>
                              <span class="w-full h-full hidden p-3 hover:p-4 cursor-pointer animation-enable-short"
                                  :class="'editCloseMandiri-'+soal.id"
                                  v-on:click="editSoal(soal, false)">
                                <img class="w-full h-full p-1 fas fa-times">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </transition-group>
                </div>
                <div v-if="isFITB" class="w-full h-full flex-row">
                  <transition-group name="soal-list" tag="div">
                    <div v-for="soal in listAllFITB.filter( function(item){return (item.modul_id===chosenModulID);} )" v-bind:key="soal.id"
                        class="animation-enable w-full h-120 mb-4">
                      <div class="w-full h-full flex rounded-lg bg-yellow-200">
                        <div class="w-1/3 h-full flex-row">
                          <div class="w-full h-full flex rounded-l-lg font-merri-bold text-3xl bg-yellow-400">
                            <div class="w-auto h-auto m-auto">
                              {{ soal.judul }}
                            </div>
                          </div>
                        </div>
                        <div class="w-2/3 h-full flex">
                          <div class="w-16full h-full flex">
                            <div class="w-full h-full break-words whitespace-pre-wrap flex-row overflow-y-auto p-4 text-left font-overpass-bold text-2xl">
                              <span>{{ soal.soal }}</span>
                            </div>
                          </div>
                          <div class="w-16 bg-gray-400 rounded-r-lg h-full flex-row"
                            v-if="editPriviledge.includes(currentUser.role_id)">

                            <!-- Open this only when needed -->
                            <div class="w-full h-1/2 flex">
                              <span class="w-full h-full p-3 hover:p-4 cursor-pointer animation-enable-short"
                                  v-on:click="deleteSoal(soal.id)">
                                <img class="w-full h-full p-1 fas fa-trash">
                              </span>
                            </div>

                            <div class="w-full h-1/2 flex">
                              <span class="w-full h-full p-3 visible hover:p-4 cursor-pointer animation-enable-short"
                                  :class="'editOpenFITB-'+soal.id"
                                  v-on:click="editSoal(soal, true)">
                                <img class="w-full h-full p-1 fas fa-pen">
                              </span>
                              <span class="w-full h-full hidden p-3 hover:p-4 cursor-pointer animation-enable-short"
                                  :class="'editCloseFITB-'+soal.id"
                                  v-on:click="editSoal(soal, false)">
                                <img class="w-full h-full p-1 fas fa-times">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </transition-group>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Menu Layout -->
        <div class="absolute bottom-0 w-full h-140 pb-8 flex pointer-events-none"
            v-if="editPriviledge.includes(currentUser.role_id)">
          <div class="absolute w-4full pb-8 h-full flex animation-enable pointer-events-auto"
              :class="[{ 'left-0': soalMenuShown },
                      { 'left-minFull': !soalMenuShown }]">
            <div class="w-16full h-full bg-gray-400">
              <form id="tatkForm" 
                  class="w-full h-full p-4 flex"
                  :class="[{ 'visible': isTA || isTK },
                          { 'hidden': !isTA && !isTK }]">
                <div class="w-1/2 h-full flex-row">
                  <div class="w-full h-2/5">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-1/3">
                        <span class="h-auto my-auto">
                          Modul
                        </span>
                      </div>
                      <div class="w-full h-2/3 tatkOption">
                        <select v-model="formTATK.modul_id" 
                              class="block font-monda-bold text-xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="grid-state">
                          <option class="hidden" value="" disabled selected>
                            Pilih modul
                          </option>
                          <option v-for="modul in allModul" v-bind:key="modul.id" :value="modul.id" :disabled="!modul.id">
                            {{ modul.judul }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="w-full h-3/5">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-1/4">
                        <span class="h-auto my-auto">
                          Pertanyaan
                        </span>
                      </div>
                      <div class="w-full h-3/4">
                        <textarea v-model="formTATK.pertanyaan" cols="30" rows="10"
                              class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                              id="Pertanyaan" type="text" placeholder="Siapakah penemu bahasa C ?"/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-1/2 h-full">
                  <div class="w-full h-full flex-row overflow-y-auto">
                    <div class="w-full h-40 p-2 mb-2">
                      <div class="w-full h-full">
                        <div class="font-merri w-full text-teal-600 flex text-left text-lg h-1/5">
                          <span class="h-auto my-auto">
                            Jawaban Benar
                          </span>
                        </div>
                        <div class="w-full h-4/5">
                          <textarea v-model="formTATK.jawaban_benar" cols="30" rows="10"
                                class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                id="Pertanyaan" type="text" placeholder="Dennis Ritchie"/>
                        </div>
                      </div>
                    </div>
                    <div class="w-full h-40 p-2 mb-2">
                      <div class="w-full h-full">
                        <div class="font-merri w-full flex text-left text-red-600 text-lg h-1/5">
                          <span class="h-auto my-auto">
                            Jawaban Salah
                          </span>
                        </div>
                        <div class="w-full h-4/5">
                          <textarea v-model="formTATK.jawaban_salah1" cols="30" rows="10"
                                class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                id="Pertanyaan" type="text" placeholder="Steve Wozniak"/>
                        </div>
                      </div>
                    </div>
                    <div class="w-full h-40 p-2 mb-2">
                      <div class="w-full h-full">
                        <div class="font-merri w-full flex text-left text-red-600 text-lg h-1/5">
                          <span class="h-auto my-auto">
                            Jawaban Salah
                          </span>
                        </div>
                        <div class="w-full h-4/5">
                          <textarea v-model="formTATK.jawaban_salah2" cols="30" rows="10"
                                class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                id="Pertanyaan" type="text" placeholder="Muhammad Fakhri"/>
                        </div>
                      </div>
                    </div>
                    <div class="w-full h-40 p-2">
                      <div class="w-full h-full">
                        <div class="font-merri w-full flex text-left text-red-600 text-lg h-1/5">
                          <span class="h-auto my-auto">
                            Jawaban Salah
                          </span>
                        </div>
                        <div class="w-full h-4/5">
                          <textarea v-model="formTATK.jawaban_salah3" cols="30" rows="10"
                                class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                id="Pertanyaan" type="text" placeholder="Linus Torvalds"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <form id="tpForm" 
                  class="w-full h-full p-4 flex"
                  :class="[{ 'visible': !isTA && !isTK && !isJurnal && !isMandiri && !isFITB },
                          { 'hidden': isTA || isTK || isJurnal || isMandiri || isFITB }]">
                <div class="w-1/3 h-full flex-row">
                  <div class="w-full h-1/2">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-1/3">
                        <span class="h-auto my-auto">
                          Modul
                        </span>
                      </div>
                      <div class="w-full h-2/3 tpOption">
                        <select v-model="formTP.modul_id" 
                              class="block font-monda-bold text-xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="grid-state">
                          <option class="hidden" value="" disabled selected>
                            Pilih modul
                          </option>
                          <option v-for="modul in allModul" v-bind:key="modul.id" :value="modul.id" :disabled="!modul.id">
                            {{ modul.judul }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="w-full h-1/2">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-1/3">
                        <span class="h-auto my-auto">
                          Jenis Soal
                        </span>
                      </div>
                      <div class="w-full h-2/3 jenisSoalOption">
                        <select v-model="formTP.jenisSoal" 
                              class="block font-monda-bold text-xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="grid-state">
                          <option class="hidden" value="" disabled selected>
                            Pilih jenis soal
                          </option>
                          <option value="essay">Essay</option>
                          <option value="program">Program</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-2/3 h-full">
                  <div class="w-full h-full flex">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-8">
                        <span class="h-auto my-auto">
                          Pertanyaan
                        </span>
                      </div>
                      <div class="w-full h-4full flex">
                        <textarea v-model="formTP.soal" cols="30" rows="10"
                              class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                              id="Pertanyaan" type="text" placeholder="Siapakah penemu bahasa C ?"/>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <form id="jmfitbForm" 
                  class="w-full h-full p-4 flex-row"
                  :class="[{ 'visible': isJurnal },
                          { 'hidden': !isJurnal }]">
                <div class="w-full h-1/4 flex-row">
                  <div class="w-full h-full flex">
                    <div class="w-1/2 px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-1/3">
                        <span class="h-auto my-auto">
                          Modul
                        </span>
                      </div>
                      <div class="w-full h-2/3 jmfitbOption">
                        <select v-model="formJMFITB.modul_id" 
                              class="block font-monda-bold text-3xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="grid-state">
                          <option class="hidden" value="" disabled selected>
                            Pilih modul
                          </option>
                          <option v-for="modul in allModul" v-bind:key="modul.id" :value="modul.id" :disabled="!modul.id">
                            {{ modul.judul }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="w-1/2 h-full m-auto flex">
                      <div class="w-1/2 h-full flex">
                        <div class="w-auto h-auto my-auto ml-auto font-merri text-lg text-gray-700">
                          <span>isSulit</span>
                        </div>
                      </div>
                      <div class="w-1/2 h-full flex">
                        <div class="w-auto h-auto m-auto">
                          <toggle-button
                            v-model="formJMFITB.isSulit"
                            :value="formJMFITB.isSulit"
                            :sync="true"
                            :labels="true"
                            :width="100"
                            :height="30"
                            :font-size="15"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-full h-3/4">
                  <div class="w-full h-full flex">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-8">
                        <span class="h-auto my-auto">
                          Pertanyaan
                        </span>
                      </div>
                      <div class="w-full h-4full flex">
                        <textarea v-model="formJMFITB.soal" cols="30" rows="10"
                              class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                              id="Pertanyaan" type="text" placeholder="Siapakah penemu bahasa C ?"/>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <form id="jmfitbForm" 
                  class="w-full h-full p-4 flex-row"
                  :class="[{ 'visible': isMandiri || isFITB },
                          { 'hidden': !isMandiri && !isFITB }]">
                <div class="w-full h-1/4 flex-row">
                  <div class="w-full h-full">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-1/3">
                        <span class="h-auto my-auto">
                          Modul
                        </span>
                      </div>
                      <div class="w-full h-2/3 jmfitbOption">
                        <select v-model="formJMFITB.modul_id" 
                              class="block font-monda-bold text-3xl appearance-none w-full h-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="grid-state">
                          <option class="hidden" value="" disabled selected>
                            Pilih modul
                          </option>
                          <option v-for="modul in allModul" v-bind:key="modul.id" :value="modul.id" :disabled="!modul.id">
                            {{ modul.judul }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-full h-3/4">
                  <div class="w-full h-full flex">
                    <div class="w-full px-2 h-full flex-row">
                      <div class="font-merri w-full flex text-left text-gray-700 text-lg h-8">
                        <span class="h-auto my-auto">
                          Pertanyaan
                        </span>
                      </div>
                      <div class="w-full h-4full flex">
                        <textarea v-model="formJMFITB.soal" cols="30" rows="10"
                              class="font-overpass-mono-bold text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                              id="Pertanyaan" type="text" placeholder="Siapakah penemu bahasa C ?"/>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="w-16 h-full flex-row rounded-r-large bg-gray-600">
              <div class="w-16 h-full text-white m-auto p-5 hover:p-6 cursor-pointer animation-enable-short"
                  :class="[{ 'hidden': editing },
                          { 'visible': !editing }]"
                  v-on:click="createSoal">
                <span class="w-full h-full flex">
                  <img class="w-full h-full fas fa-plus">
                </span>
              </div>
              <div class="w-16 h-full text-white m-auto p-5 hover:p-6 cursor-pointer animation-enable-short"
                  :class="[{ 'hidden': !editing },
                          { 'visible': editing }]"
                  v-on:click="updateSoal">
                <span class="w-full h-full flex">
                  <img class="w-full h-full fas fa-check">
                </span>
              </div>
            </div>
          </div>
          <div class="absolute right-0 h-full pb-8 animation-enable flex pointer-events-none"
              :class="[{ 'w-8': soalMenuShown },
                      { 'w-full': !soalMenuShown }]">
            <div class="w-8 h-full flex text-gray-700 animation-enable pointer-events-none">
              <div class="w-8 h-full m-auto p-1 hover:p-2 cursor-pointer animation-enable-short pointer-events-auto"
                  v-on:click="soalMenuShown = !soalMenuShown">
                <span class="w-full h-full"
                      :class="[{ 'visible': soalMenuShown },
                              { 'hidden': !soalMenuShown }]">
                  <img class="w-full h-full p-1 fas fa-caret-left">
                </span>
                <span class="w-full h-full"
                      :class="[{ 'visible': !soalMenuShown },
                              { 'hidden': soalMenuShown }]">
                  <img class="w-full h-full p-1 fas fa-caret-right">
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>   
    </div>
  </div>
</template>

<style>
.soal-list-enter, .soal-list-leave-to{
  opacity: 0;
  transform: translateX(-100%);
}
.soal-list-leave-active {
  position: absolute;
}
</style>

<script>
import { MENU_ITEMS, PRIVILEGES, SOAL_TABS } from './Soal/constants';

import { ref, toRefs } from 'vue';
import { useNavigation } from '@/composables/useNavigation';
export default {
  props: [
    'comingFrom',
    'currentUser',
    'position',
    'allModul',
    'userRole',

    'allTP',
    'allTA',
    'allTK',
    'allJurnal',
    'allMandiri',
    'allFITB',
  ],

  setup(props) {
    const menuRef = ref(null);

    // Initialize navigation composable
    const navigation = useNavigation({
      userType: 'asisten',
      menuRef: menuRef,
      currentPage: 'soal'
    });

    // Initialize menu state based on comingFrom prop
    navigation.initializeMenu(props.comingFrom, true);

    // Return all navigation state and methods
    return {
      menuRef,
      ...toRefs(navigation),
    };
  },

  data() {
    return {
      privileges: { ...PRIVILEGES },
      menuItems: MENU_ITEMS,
      soalTabs: SOAL_TABS,

      pageActive: true,
      isMenuShown: false,

      chosenModulID: '',
      soalMenuShown: true,
      activeMenu: null,
      activeSoalType: 'TP',

      editing: false,
      activeEditId: null,

      listAllTP: this.allTP ?? [],
      listAllTA: this.allTA ?? [],
      listAllTK: this.allTK ?? [],
      listAllJurnal: this.allJurnal ?? [],
      listAllMandiri: this.allMandiri ?? [],
      listAllFITB: this.allFITB ?? [],

      // Form for TA and TK
      formTATK: {
        id: '',
        oldModul_id: '',
        modul_id: '',
        oldPertanyaan: '',
        pertanyaan: '',
        jawaban_benar: '',
        jawaban_salah1: '',
        jawaban_salah2: '',
        jawaban_salah3: '',
      },

      // Form for Jurnal Mandiri and FITB
      formJMFITB: {
        id: '',
        oldSoal: '',
        soal: '',
        oldModul_id: '',
        modul_id: '',
        isSulit: false,
      },

      // Form for Tugas Pendahuluan
      formTP: {
        id: '',
        jenisSoal: '',
        oldModul_id: '',
        modul_id: '',
        oldSoal: '',
        soal: '',
        isEssay: false,
        isProgram: false,
      },
    }
  },

  computed: {
    highlightedMenu() {
      return this.changePage ? this.activeMenu : 'soal';
    },

    visibleMenuItems() {
      return this.menuItems.filter(item => this.hasMenuAccess(item));
    },

    menuContainerClass() {
      const navigatingToProfile = this.changePage && this.highlightedMenu === 'asisten';
      return navigatingToProfile ? 'rounded-none' : 'rounded-tl-large';
    },

    editPriviledge() {
      return this.privileges.edit === 'all' ? 'all' : (this.privileges.edit || []);
    },

    canEdit() {
      if (this.editPriviledge === 'all') {
        return true;
      }
      return this.editPriviledge.includes(this.currentUser.role_id);
    },

    isTA() {
      return this.activeSoalType === 'TA';
    },

    isTK() {
      return this.activeSoalType === 'TK';
    },

    isJurnal() {
      return this.activeSoalType === 'Jurnal';
    },

    isMandiri() {
      return this.activeSoalType === 'Mandiri';
    },

    isFITB() {
      return this.activeSoalType === 'FITB';
    },

    activeTabIndex() {
      return this.soalTabs.findIndex(tab => tab.id === this.activeSoalType);
    },
  },

  mounted() {
    document.body.classList.add('closed');

    if (this.$refs.menuRef) {
      if (this.$refs.menuRef && this.position != null) {
      this.$nextTick(() => {
      });
    }
    }

    const incomingFrom = [
      'asisten',
      'none',
      'kelas',
      'modul',
      'plotting',
      'praktikum',
      'konfigurasi',
      'tp',
      'polling',
      'nilai',
      'history',
      'pelanggaran',
      'setpraktikan',
      'rating',
      'allLaporan',
      'jawaban',
    ];

    if (incomingFrom.includes(this.comingFrom)) {
      setTimeout(() => {
        this.currentPage = true;
      }, 10);
    }
  },

  methods: {
    hasMenuAccess(item) {
      if (!item.privilege) {
        return true;
      }
      const privilege = this.privileges[item.privilege];
      if (privilege === 'all') {
        return true;
      }
      if (Array.isArray(privilege)) {
        return privilege.includes(this.currentUser.role_id);
      }
      return false;
    },

    menuItemClasses(item) {
      const baseClasses = [
        'w-full',
        'p-4',
        'h-24',
        'flex',
        'select-none',
        'animation-enable',
      ];

      const isCurrent = this.highlightedMenu === item.id;
      const isClickable = !item.isCurrentPage;

      if (isClickable) {
        baseClasses.push('cursor-pointer', 'hover:text-white');
        baseClasses.push(isCurrent ? 'bg-yellow-500 text-white' : 'bg-yellow-400 hover:bg-yellow-600');
      } else {
        baseClasses.push('cursor-default');
        baseClasses.push(isCurrent ? 'bg-yellow-500 text-white' : 'bg-yellow-400 text-black');
      }

      return baseClasses.join(' ');
    },

    setCurrentMenu(target) {
      this.activeMenu = target;
    },

    getTypeConfig(type = this.activeSoalType) {
      const configs = {
        TP: {
          listKey: 'listAllTP',
          createUrl: '/asisten/soal/tp',
          updateUrl: '/asisten/soal/tp',
          deleteUrl: id => `/asisten/soal/tp/${id}`,
          messages: {
            create: 'Soal TP berhasil ditambahkan',
            update: 'Soal TP berhasil diperbaharui',
            delete: 'Soal TP berhasil dihapus',
          },
          errorFields: ['modul_id', 'soal', 'isEssay', 'isProgram'],
        },
        TA: {
          listKey: 'listAllTA',
          createUrl: '/asisten/soal/ta',
          updateUrl: '/asisten/soal/ta',
          deleteUrl: id => `/asisten/soal/ta/${id}`,
          messages: {
            create: 'Soal TA berhasil ditambahkan',
            update: 'Soal TA berhasil diperbaharui',
            delete: 'Soal TA berhasil dihapus',
          },
          errorFields: ['modul_id', 'pertanyaan', 'jawaban_benar', 'jawaban_salah1', 'jawaban_salah2', 'jawaban_salah3'],
        },
        TK: {
          listKey: 'listAllTK',
          createUrl: '/asisten/soal/tk',
          updateUrl: '/asisten/soal/tk',
          deleteUrl: id => `/asisten/soal/tk/${id}`,
          messages: {
            create: 'Soal TK berhasil ditambahkan',
            update: 'Soal TK berhasil diperbaharui',
            delete: 'Soal TK berhasil dihapus',
          },
          errorFields: ['modul_id', 'pertanyaan', 'jawaban_benar', 'jawaban_salah1', 'jawaban_salah2', 'jawaban_salah3'],
        },
        Jurnal: {
          listKey: 'listAllJurnal',
          createUrl: '/asisten/soal/jurnal',
          updateUrl: '/asisten/soal/jurnal',
          deleteUrl: id => `/asisten/soal/jurnal/${id}`,
          messages: {
            create: 'Soal Jurnal berhasil ditambahkan',
            update: 'Soal Jurnal berhasil diperbaharui',
            delete: 'Soal Jurnal berhasil dihapus',
          },
          errorFields: ['modul_id', 'soal'],
        },
        Mandiri: {
          listKey: 'listAllMandiri',
          createUrl: '/asisten/soal/mandiri',
          updateUrl: '/asisten/soal/mandiri',
          deleteUrl: id => `/asisten/soal/mandiri/${id}`,
          messages: {
            create: 'Soal Mandiri berhasil ditambahkan',
            update: 'Soal Mandiri berhasil diperbaharui',
            delete: 'Soal Mandiri berhasil dihapus',
          },
          errorFields: ['modul_id', 'soal'],
        },
        FITB: {
          listKey: 'listAllFITB',
          createUrl: '/asisten/soal/fitb',
          updateUrl: '/asisten/soal/fitb',
          deleteUrl: id => `/asisten/soal/fitb/${id}`,
          messages: {
            create: 'Soal FITB berhasil ditambahkan',
            update: 'Soal FITB berhasil diperbaharui',
            delete: 'Soal FITB berhasil dihapus',
          },
          errorFields: ['modul_id', 'soal'],
        },
      };

      return configs[type];
    },

    getFormReference(type = this.activeSoalType) {
      if (type === 'TP') {
        return this.formTP;
      }
      if (type === 'TA' || type === 'TK') {
        return this.formTATK;
      }
      return this.formJMFITB;
    },

    findModulTitle(modulId) {
      const modul = this.allModul.find(item => item.id === modulId);
      return modul ? modul.judul : '';
    },

    prepareTpFlags() {
      if (this.formTP.jenisSoal === 'essay') {
        this.formTP.isEssay = true;
        this.formTP.isProgram = false;
      } else if (this.formTP.jenisSoal === 'program') {
        this.formTP.isEssay = false;
        this.formTP.isProgram = true;
      } else {
        this.formTP.isEssay = false;
        this.formTP.isProgram = false;
      }
    },

    resetFormTP() {
      this.formTP.id = '';
      this.formTP.jenisSoal = '';
      this.formTP.oldModul_id = '';
      this.formTP.modul_id = '';
      this.formTP.oldSoal = '';
      this.formTP.soal = '';
      this.formTP.isEssay = false;
      this.formTP.isProgram = false;
    },

    resetFormTATK() {
      this.formTATK.id = '';
      this.formTATK.oldModul_id = '';
      this.formTATK.modul_id = '';
      this.formTATK.oldPertanyaan = '';
      this.formTATK.pertanyaan = '';
      this.formTATK.jawaban_benar = '';
      this.formTATK.jawaban_salah1 = '';
      this.formTATK.jawaban_salah2 = '';
      this.formTATK.jawaban_salah3 = '';
    },

    resetFormJMFITB() {
      this.formJMFITB.id = '';
      this.formJMFITB.oldSoal = '';
      this.formJMFITB.soal = '';
      this.formJMFITB.oldModul_id = '';
      this.formJMFITB.modul_id = '';
      this.formJMFITB.isSulit = false;
    },

    resetFormByType(type = this.activeSoalType) {
      if (type === 'TP') {
        this.resetFormTP();
      } else if (type === 'TA' || type === 'TK') {
        this.resetFormTATK();
      } else {
        this.resetFormJMFITB();
      }
    },

    resetEditing(type = this.activeSoalType) {
      this.editing = false;
      this.activeEditId = null;
      this.resetFormByType(type);
    },

    isEditingSoal(id) {
      return this.editing && this.activeEditId === id;
    },

    handleValidationErrors(error, fields = []) {
      if (!error.response || !error.response.data || !error.response.data.errors) {
        return;
      }

      fields.forEach(field => {
        if (error.response.data.errors[field]) {
          this.$toasted.global.showError({
            message: error.response.data.errors[field][0],
          });
        }
      });
    },

    populateFormForCurrentType(soal) {
      if (this.activeSoalType === 'TP') {
        this.formTP.id = soal.id;
        this.formTP.oldModul_id = soal.modul_id;
        this.formTP.modul_id = soal.modul_id;
        this.formTP.oldSoal = soal.soal;
        this.formTP.soal = soal.soal;
        this.formTP.isEssay = Boolean(soal.isEssay);
        this.formTP.isProgram = Boolean(soal.isProgram);
        if (soal.isEssay) {
          this.formTP.jenisSoal = 'essay';
        } else if (soal.isProgram) {
          this.formTP.jenisSoal = 'program';
        } else {
          this.formTP.jenisSoal = '';
        }
      } else if (this.activeSoalType === 'TA' || this.activeSoalType === 'TK') {
        this.formTATK.id = soal.id;
        this.formTATK.oldModul_id = soal.modul_id;
        this.formTATK.modul_id = soal.modul_id;
        this.formTATK.oldPertanyaan = soal.pertanyaan;
        this.formTATK.pertanyaan = soal.pertanyaan;
        this.formTATK.jawaban_benar = soal.jawaban_benar;
        this.formTATK.jawaban_salah1 = soal.jawaban_salah1;
        this.formTATK.jawaban_salah2 = soal.jawaban_salah2;
        this.formTATK.jawaban_salah3 = soal.jawaban_salah3;
      } else {
        this.formJMFITB.id = soal.id;
        this.formJMFITB.oldModul_id = soal.modul_id;
        this.formJMFITB.modul_id = soal.modul_id;
        this.formJMFITB.oldSoal = soal.soal;
        this.formJMFITB.soal = soal.soal;
        this.formJMFITB.isSulit = Boolean(soal.isSulit);
      }
    },

    updateQuestionInList(listKey, updated) {
      const list = this[listKey];
      const index = Array.isArray(list) ? list.findIndex(item => item.id === updated.id) : -1;
      if (index !== -1) {
        list[index] = updated;
      }
    },

    removeQuestionFrom(listKey, id) {
      const list = this[listKey];
      if (!Array.isArray(list)) {
        return;
      }
      const index = list.findIndex(item => item.id === id);
      if (index !== -1) {
        list.splice(index, 1);
      }
    },

    appendNewSoal(config, response) {
      const list = this[config.listKey];
      const form = this.getFormReference();
      const modulTitle = this.findModulTitle(form.modul_id);

      if (this.activeSoalType === 'TP') {
        list.push({
          id: response.id,
          judul: modulTitle,
          modul_id: form.modul_id,
          soal: form.soal,
          isEssay: form.isEssay,
          isProgram: form.isProgram,
        });
      } else if (this.activeSoalType === 'TA' || this.activeSoalType === 'TK') {
        list.push({
          id: response.id,
          judul: modulTitle,
          modul_id: form.modul_id,
          pertanyaan: form.pertanyaan,
          jawaban_benar: form.jawaban_benar,
          jawaban_salah1: form.jawaban_salah1,
          jawaban_salah2: form.jawaban_salah2,
          jawaban_salah3: form.jawaban_salah3,
        });
      } else if (this.activeSoalType === 'Jurnal') {
        list.push({
          id: response.id,
          judul: modulTitle,
          modul_id: form.modul_id,
          soal: form.soal,
          isSulit: form.isSulit,
        });
      } else {
        list.push({
          id: response.id,
          judul: modulTitle,
          modul_id: form.modul_id,
          soal: form.soal,
        });
      }
    },

    setActiveSoal(soalMenu) {
      if (this.activeSoalType === soalMenu) {
        return;
      }
      this.resetEditing(this.activeSoalType);
      this.activeSoalType = soalMenu;
    },

    editSoal(soal, editing) {
      if (!this.canEdit) {
        return;
      }

      if (editing) {
        this.editing = true;
        this.activeEditId = soal.id;
        this.populateFormForCurrentType(soal);
      } else {
        this.resetEditing();
      }
    },

    createSoal() {
      const config = this.getTypeConfig();
      if (!config) {
        return;
      }

      if (this.activeSoalType === 'TP') {
        this.prepareTpFlags();
      }

      const form = this.getFormReference();

      this.$axios.post(config.createUrl, form).then(response => {
        if (response.data.message === 'success') {
          this.$toasted.global.showSuccess({
            message: config.messages.create,
          });
          this.appendNewSoal(config, response.data);
          this.resetFormByType();
        } else {
          this.$toasted.global.showError({
            message: response.data.message,
          });
        }
      }).catch(error => {
        this.handleValidationErrors(error, config.errorFields);
      });
    },

    updateSoal() {
      const config = this.getTypeConfig();
      if (!config) {
        return;
      }

      if (this.activeSoalType === 'TP') {
        this.prepareTpFlags();
      }

      const form = this.getFormReference();

      this.$axios.put(config.updateUrl, form).then(response => {
        if (response.data.message === 'success') {
          const updated = response.data.soal;
          const modulTitle = this.findModulTitle(updated.modul_id);
          const enriched = { ...updated, judul: modulTitle };

          this.updateQuestionInList(config.listKey, enriched);
          this.$toasted.global.showSuccess({
            message: config.messages.update,
          });
          this.resetEditing();
        } else {
          this.$toasted.global.showError({
            message: response.data.message,
          });
        }
      }).catch(error => {
        this.handleValidationErrors(error, config.errorFields);
      });
    },

    deleteSoal(id) {
      const config = this.getTypeConfig();
      if (!config) {
        return;
      }

      this.$axios.delete(config.deleteUrl(id)).then(response => {
        if (response.data.message === 'success') {
          this.$toasted.global.showSuccess({
            message: config.messages.delete,
          });
          this.removeQuestionFrom(config.listKey, id);
          if (this.isEditingSoal(id)) {
            this.resetEditing();
          }
        } else {
          this.$toasted.global.showError({
            message: response.data.message,
          });
        }
      }).catch(error => {
        this.handleValidationErrors(error, config.errorFields);
      });
    },

    travel(whereTo) {
      if (whereTo === 'soal') {
        return;
      }

      this.setCurrentMenu(whereTo);
      this.changePage = true;
      this.currentPage = false;

      const position = this.$refs.menuRef ? this.$refs.menuRef.scrollTop : 0;

      setTimeout(() => {
        this.$inertia.get(`/asisten/${whereTo}?comingFrom=soal&position=${position}`, {}, {
          replace: true,
        });
      }, 501);
    },

    signOut() {
      this.pageActive = false;
      this.currentPage = false;
      setTimeout(() => {
        this.$inertia.get('/auth/asisten/logout', {}, {
          replace: true,
        });
      }, 1010);
    },
  }

}
</script>
