<template>
  <div class="bg-green-900 w-full h-full overflow-hidden">

    <!-- Main Layout -->
    <div class="absolute my-auto z-40 h-full pointer-events-none flex animation-enable"
        :class="[{ 'right-0': pageActive },
                { 'right-minFull': !pageActive },
                  { 'w-24full': !openWide },
                  { 'w-full': openWide }]" @mouseover="isMenuShown = false">
      <div class="my-auto flex w-full pointer-events-none animation-enable"
          :class="[{ 'h-36full': !openWide },
                  { 'h-4full': openWide }]">
        <div class="h-full w-12 flex pointer-events-auto">
          <div class="w-8 h-8 m-auto"
            :class="[{ 'visible': !praktikumExist },
                    { 'hidden': praktikumExist }]">
            <span class="w-full h-full cursor-pointer"
                :class="[{ 'visible': !openWide },
                        { 'hidden': openWide }]"
                v-on:click="openWide = true;">
              <img class="w-full h-full fas fa-caret-left text-white">
            </span>
            <span class="w-full h-full cursor-pointer"
                :class="[{ 'visible': openWide },
                        { 'hidden': !openWide }]"
                v-on:click="openWide = false;">
              <img class="w-full h-full fas fa-caret-right text-white">
            </span>
          </div>
        </div>
        <div class="rounded-l-large h-full w-12full bg-yellow-200 pointer-events-auto overflow-y-auto">
        
          <!-- Profil Layout -->
          <div v-if="isProfil">
            <div class="h-auto w-full flex">
              <div class="ml-6 mt-6 flex-row w-auto h-auto whitespace-pre-wrap font-monda-bold text-5xl">
                <span>{{ currentUser.nama }}</span>
                <span class="font-merri-italic text-4xl mt-2"> ({{ currentUser.email }})</span>
              </div>
            </div>
            <div class="h-auto w-full flex-row pb-2"
                  :class="[{'hidden':viewPassForm},
                          {'visible':!viewPassForm}]">
              <div class="h-1/3 w-full flex"> 
                <div class="w-auto h-auto ml-16 mt-8">
                  <span class="font-overpass text-3xl">Kelas : </span>
                  <span class="whitespace-pre-wrap font-overpass-bold text-3xl"> {{ currentUser.kelas }}</span>
                </div>
              </div>
              <div class="h-1/3 w-full flex">
                <div class="w-auto h-auto ml-16 mt-4">
                  <span class="font-overpass text-3xl">Nomor Telepon : </span>
                  <span class="whitespace-pre-wrap font-overpass-bold text-3xl"> {{ currentUser.nomor_telepon }}</span>
                </div>
              </div>
              <div class="h-1/3 w-full flex">
                <div class="w-auto h-auto ml-16 mt-4">
                  <span class="font-overpass text-3xl">Alamat : </span>
                  <span class="whitespace-pre-wrap font-overpass-bold text-3xl"> {{ currentUser.alamat }}</span>
                </div>
              </div>
            </div>
            <div class="h-1/3 w-full flex pb-4">
                <div class="w-auto h-auto ml-16 mt-4"
                  :class="[{'hidden':viewPassForm},
                          {'visible':!viewPassForm}]">
                  <span class="font-overpass text-2xl bg-red-500 text-white p-3 pb-2 rounded-lg hover:bg-red-600 cursor-pointer duration-300 hover:duration-300"
                  v-on:click="formPassword(true)">Ganti Password<img class="ml-1 p-1 fas fa-pen fa-lg"></span>
                </div>
                 <div class="w-auto h-auto ml-4 mt-4"
                  :class="[{'hidden':viewPassForm},
                          {'visible':!viewPassForm}]">
                  <span class="font-overpass text-2xl bg-green-800 text-yellow-300 p-3 pb-2 rounded-lg hover:bg-green-600 cursor-pointer duration-300 hover:duration-300" v-on:click='travel("contact-asisten")'
                  >Kontak Asisten<img class="ml-1 p-1 fas fa-users fa-lg"></span>
                </div>
            </div>
            <div class="h-1/3 w-full flex-col pb-4"
                  :class="[{'hidden':!viewPassForm},
                          {'visible':viewPassForm}]">
                <div class="w-2/3 h-auto ml-16 mt-2 flex-col">
                  <div class="font-overpass-bold"> Input Password baru:</div>
                  <input v-model="resetPass.password" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-1/4 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="password" type="password" placeholder="******************">
                </div>
                <div class="w-2/3 h-auto ml-16 mt-2 flex-col">
                  <div class="font-overpass-bold"> Ulangi Password baru:</div>
                  <input v-model="resetPass.repeatpass" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-1/4 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="repeatpass" type="password" placeholder="******************">
                </div>
                <div class="w-2/3 h-auto ml-16 mt-2 flex">
                  <span class="p-2 px-3 bg-red-600 font-merri-bold text-xl cursor-pointer text-white rounded-lg hover:bg-red-700 animation-enable"
                      v-on:click="formPassword(false)">
                      <img class="p-1 fas fa-times fa-lg">
                  </span>
                  <span class="p-2 bg-green-600 font-merri-bold text-xl cursor-pointer text-white rounded-lg hover:bg-green-700 animation-enable mx-2"
                      v-on:click="resetPassword">
                      <img class="p-1 fas fa-check fa-lg">
                  </span>
                </div>
            </div>
          </div>

          <!-- TP Layout -->
          <div v-if="isTP" class="w-full h-full flex">
            <div class="w-full h-full flex" v-if="!qrcodeShown">
              <div v-if="soalTPEssay.length === 0 && soalTPProgram.length === 0" 
                  class="w-full h-full flex">
                <div class="font-monda-bold h-auto w-auto m-auto text-center text-5xl">
                  Tidak ada <br> Tugas Pendahuluan saat ini <br>
                  <span class="text-xl">Silahkan cek kembali setelah ada pengumuman di OA line: @875lgds</span>
                </div>
              </div>

              <div class="h-full w-full flex-row relative"
                  v-if="soalTPEssay.length > 0 && soalTPProgram.length > 0">
                <div class="w-full flex absolute top-0 rounded-tl-large animation-enable"
                    :class="[{ 'bg-green-400 h-12': soalOpened },
                            { 'bg-green-100 h-12full': !soalOpened }]">
                  <div class="w-full h-full relative flex">
                    <div class="h-12 w-full select-none absolute bottom-0 flex pb-1 mx-auto font-overpass-mono-bold text-2xl animation-enable"
                        :class="[{ 'text-yellow-100 cursor-pointer': soalOpened },
                                { 'text-black': !soalOpened }]"
                        v-on:click="soalOpened = false">
                      <span class="m-auto">PEMBAHASAN</span> 
                    </div>
                    <div class="absolute top-0 w-full h-12full flex">
                      <div class="w-full h-full" v-scrollbar>
                        <div>
                          <div class="whitespace-pre-wrap break-words p-5 font-overpass text-2xl">
                            <span>{{ pembahasanTp.pembahasan }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-full flex absolute bottom-0 rounded-bl-large animation-enable"
                    :class="[{ 'bg-green-100 h-12full': soalOpened },
                            { 'bg-green-400 h-12': !soalOpened }]">
                  <div class="w-full h-full relative flex">
                    <div class="h-12 z-30 w-full select-none absolute top-0 flex pt-1 font-overpass-mono-bold text-2xl animation-enable"
                        :class="[{ 'text-yellow-100 cursor-pointer': !soalOpened },
                                { 'text-black': soalOpened }]"
                        v-on:click="soalOpened = true">
                      <span class="m-auto">SOAL <span class="font-overpass text-lg pt-0">(klik tombol simpan di paling bawah untuk menyimpan)</span></span>
                    </div>
                    <div class="absolute bottom-0 w-full h-12full flex">
                      <div class="w-full h-full" v-scrollbar>
                        <div>
                          <QuestionBlock
                              :questions="soalTPEssay"
                              :answers="jawabanTP"
                              :secure-text="true"
                              :on-answer-change="payload => onTextAnswerChange('jawabanTP', payload)"
                          />

                          <QuestionBlock
                              :questions="soalTPProgram"
                              :answers="jawabanTP"
                              :secure-text="true"
                              :numbering-offset="soalTPEssay.length"
                              :answer-index-offset="soalTPEssay.length"
                              :on-answer-change="payload => onTextAnswerChange('jawabanTP', payload)"
                          />

                          <div class="w-1/2 h-20 mx-auto">
                            <div class="w-full h-full p-4 cursor-pointer hover:p-5 animation-enable-short"
                                v-on:click="saveJawabanTP()">
                              <div class="w-full h-full font-overpass-bold text-xl text-white flex pt-1 rounded-full bg-green-600">
                                <div class="m-auto">
                                  Simpan
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Nilai Layout -->
          <div v-if="isNilai" class="w-full h-full flex">
            <chart class="w-full h-full p-4"
                :chartdata="allNilaiData"
                :options="{
                  responsive: true,
                  maintainAspectRatio: false,

                  scales: {
                    y: {
                      beginAtZero: true,
                      grid: {
                        display: false,
                      },
                    },
                    x: {
                      grid: {
                        display: false,
                      },
                    },
                  }
                }">
            </chart>
          </div>

          <!--Jawaban Layout -->
          <div v-if="isJawaban" class="w-full h-full flex">
                <div class="w-1/4 h-full flex-row overflow-y-hidden bg-yellow-700 cursor-pointer" style="width: 415px">
                    <div class="h-1/10 w-full flex justify-center items-center hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 bg-yellow-300-active animation-enable-short"
                          :class="[{'bg-yellow-500 bg-yellow-500-Active' : modul.id === currentJawabanJurnal},
                                   {'opacity-50 cursor-not-allowed hover:bg-yellow-700 focus:bg-yellow-700 bg-yellow-300-nonActive' : !modul.isUnlocked}]"
                          v-on:click="showJawabanJurnal(modul.id, modul.isUnlocked)" v-for="modul in all_modul" v-bind:key="modul.id">
                      <div class="h-full w-1/8 flex p-4 pr-0">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl rounded-large justify-end items-center">
                          <img :class="[{'fas fa-lock' : !modul.isUnlocked},
                                        {'fas fa-unlock-alt' : modul.isUnlocked}]">
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
                    <div class="w-full h-12full m-auto overflow-y-auto animation-enable-medium scrollbar-hide"
                          :class="[{'transform -translate-x-full' : jawabanChanged}]">
                        <div v-if="allJawabanJurnal.length > 0" 
                              class="w-full h-full flex-row">
                            <div v-for="(jawaban, index) in allJawabanJurnal" v-bind:key="index"
                                class="w-full flex-row h-auto mb-8">
                              <div class="w-full h-auto flex mb-2">
                                <div class="h-full w-12 flex font-merri-bold text-xl">
                                  <div class="m-auto w-auto h-auto">{{ index+1 }}</div>
                                </div>
                                <div class="h-12 px-1 w-4">
                                  <div class="h-full w-full bg-gray-900"/>
                                </div>
                                <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                                  <span>{{ jawaban.soal }}</span>
                                </div>
                              </div>
                              <div class="w-full h-auto flex px-5">
                                <textarea v-model="jawaban.jawaban" cols="30" rows="15"
                                      class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                      type="text" disabled/>
                              </div>
                            </div>
                        </div>
                        <template v-else-if="currentUser.kelas.substring(6, 10) !== 'INT'">
                          <div v-if="jawabanShown === true" class="w-full h-full flex flex-col justify-center items-center m-auto font-overpass-mono-bold animation-enable">
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
                        <template v-else-if="currentUser.kelas.substring(6, 10) === 'INT'">
                          <div v-if="jawabanShown === true" class="w-full h-full flex flex-col justify-center items-center m-auto font-overpass-mono-bold animation-enable">
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

          <!-- Praktikum Layout -->
          <PraktikumSection
            v-if="isPraktikum"
            :is-polling-enabled="isPollingEnabled"
            :polling-complete="pollingComplete_mutable"
            :current-user="currentUser"
            :all-asisten="allAsisten"
            :all-asisten-polling="allAsistenPolling"
            :all-polling="allPolling"
            :current-praktikum="current_praktikum"
            :current-modul="current_modul"
            :programming-quote="programmingQuote"
            :quote-author="quoteAuthor"
            :modul-shown="modulShown"
            :show-nilai-ta="showNilaiTA"
            :show-nilai-tk="showNilaiTK"
            :soal-fitb="soalFitb"
            :jawaban-fitb="jawabanFitb"
            :soal-jurnal="soalJurnal"
            :jawaban-jurnal="jawabanJurnal"
            :soal-runmod="soalRunmod"
            :jawaban-runmod="jawabanRunmod"
            :soal-mandiri="soalMandiri"
            :jawaban-mandiri="jawabanMandiri"
            :soal-ta="soalTA"
            :jawaban-ta="jawabanTA"
            :soal-tk="soalTK"
            :jawaban-tk="jawabanTK"
            :laporan-praktikan="laporanPraktikan"
            :nilai-ta="nilaiTA"
            :nilai-tk="nilaiTK"
            :generate-score-text="generateScoreText"
            @polling-saved="handlePollingSaved"
            @finish-praktikum="finishPraktikum"
            @text-answer-change="handleTextAnswerChange"
            @question-option-select="handleQuestionOptionSelect"
            @update:modulShown="value => modulShown = value"
            @update:showNilaiTa="value => showNilaiTA = value"
            @update:showNilaiTk="value => showNilaiTK = value"
            @update:laporanPraktikan="value => laporanPraktikan = value"
          />
        </div>
      </div>
    </div>

    <!-- Main Menu -->
    <div class="absolute w-24full right-0 h-16 flex animation-enable"
        :class="[{ 'bottom-0': pageActive },
                { 'bottom-min4rem': !pageActive }]">
      <div class="m-auto h-full w-3/5 flex">

        <!-- Dummy For Animation -->
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <!-- END -->

        <div class="h-full flex animation-enable pointer-events-none"
            :class="[{ 'w-1/11': !isProfil },
                    { 'w-9/11': isProfil }]"
            v-on:click="showProfil">
          <div class="h-full flex animation-enable pointer-events-none"
              :class="[{ 'w-full': !isProfil },
                      { 'w-1/2': isProfil }]">
            <div class="h-full animation-enable pointer-events-none"
              :class="[{ 'w-0': !isProfil },
                      { 'w-9/12': isProfil }]"/>
            <img class="profilIcon w-full iconYellowHover select-none cursor-pointer pointer-events-auto self-center h-8 fas fa-address-card animation-enable">
          </div>
          <span class="self-center text-left font-monda-bold text-lg text-white animation-enable-short"
              :class="[{ 'w-0 opacity-0 tracking-tighter': !isProfil },
                      { 'w-1/2 opacity-100 tracking-widest': isProfil }]">
            Profil
          </span>
        </div>

        <div class="h-full flex animation-enable pointer-events-none"
            :class="[{ 'w-1/11': !isPraktikum },
                    { 'w-9/11': isPraktikum }]"
            v-on:click="showPraktikum">
          <div class="h-full flex animation-enable pointer-events-none"
              :class="[{ 'w-full': !isPraktikum },
                      { 'w-1/2': isPraktikum }]">
            <div class="h-full animation-enable pointer-events-none"
              :class="[{ 'w-0': !isPraktikum },
                      { 'w-9/12': isPraktikum }]"/>
            <img class="praktikumIcon w-full iconYellowHover select-none cursor-pointer pointer-events-auto self-center h-8 fas fa-code animation-enable">
          </div>
          <span class="self-center text-left font-monda-bold text-lg text-white animation-enable-short"
              :class="[{ 'w-0 opacity-0 tracking-tighter': !isPraktikum },
                      { 'w-1/2 opacity-100 tracking-widest': isPraktikum }]">
            Praktikum
          </span>
        </div>
        
        <div class="h-full flex animation-enable pointer-events-none"
            :class="[{ 'w-1/11': !isTP },
                    { 'w-9/11': isTP }]"
            v-on:click="showTP">
          <div class="h-full flex animation-enable pointer-events-none"
              :class="[{ 'w-full': !isTP },
                      { 'w-1/2': isTP }]">
            <div class="h-full animation-enable pointer-events-none"
              :class="[{ 'w-0': !isTP },
                      { 'w-9/12': isTP }]"/>
            <img class="tpIcon w-full iconYellowHover select-none cursor-pointer pointer-events-auto self-center h-8 fas fa-file-code animation-enable">
          </div>
          <span class="self-center text-left font-monda-bold text-lg text-white animation-enable-short"
              :class="[{ 'w-0 opacity-0 tracking-tighter': !isTP },
                      { 'w-1/2 opacity-100 tracking-widest': isTP }]">
            Tugas Pendahuluan
          </span>
        </div>

        <div class="h-full flex animation-enable pointer-events-none"
            :class="[{ 'w-1/11': !isNilai },
                    { 'w-9/11': isNilai }]"
            v-on:click="showNilai">
          <div class="h-full flex animation-enable pointer-events-none"
              :class="[{ 'w-full': !isNilai },
                      { 'w-1/2': isNilai }]">
            <div class="h-full animation-enable pointer-events-none"
              :class="[{ 'w-0': !isNilai },
                      { 'w-9/12': isNilai }]"/>
            <img class="nilaiIcon w-full iconYellowHover select-none cursor-pointer pointer-events-auto self-center h-8 fas fa-chart-area animation-enable">
          </div>
          <span class="self-center text-left font-monda-bold text-lg text-white animation-enable-short"
              :class="[{ 'w-0 opacity-0 tracking-tighter': !isNilai },
                      { 'w-1/2 opacity-100 tracking-widest': isNilai }]">
            Nilai
          </span>
        </div>

        <div class="h-full flex animation-enable pointer-events-none"
            :class="[{ 'w-1/11': !isJawaban },
                    { 'w-9/11': isJawaban }]"
            v-on:click="showJawaban">
          <div class="h-full flex animation-enable pointer-events-none"
              :class="[{ 'w-full': !isJawaban },
                      { 'w-1/2': isJawaban }]">
            <div class="h-full animation-enable pointer-events-none"
              :class="[{ 'w-0': !isJawaban },
                      { 'w-9/12': isJawaban }]"/>
            <img class="jawabanIcon w-full iconYellowHover select-none cursor-pointer pointer-events-auto self-center h-8 fas fa-book animation-enable">
          </div>
          <span class="self-center text-left font-monda-bold text-lg text-white animation-enable-short"
              :class="[{ 'w-0 opacity-0 tracking-tighter': !isJawaban },
                      { 'w-1/2 opacity-100 tracking-widest': isJawaban }]">
            Jawaban
          </span>
        </div>

        <!-- Dummy For Animation -->
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <div class="h-full animation-enable"
            :class="[{ 'w-1/11': !isPraktikum && !isNilai && !isTP && !isProfil && !isJawaban },
                    { 'w-0': isPraktikum || isNilai || isTP || isProfil || isJawaban }]"/>
        <!-- END -->

      </div>
    </div>

    <!-- Profile Menu -->
    <div class="absolute top-0 right-0 z-40 w-24full h-24 flex"
        :class="[{ 'visible': isMenuShown && pageActive },
                { 'hidden': !isMenuShown }]" @mouseleave="isMenuShown = false">
      <div class="m-auto h-full w-56 flex-row bg-gray-600 mt-2 rounded-lg">
        <div class="w-full h-2/3"/>
        <div class="w-full h-1/3 flex">
          <div class="rounded-b-lg bg-gray-400 flex hover:bg-gray-500 w-full h-full cursor-pointer" v-on:click="signOut">
            <span class="m-auto font-monda-bold text-sm text-right w-full">
              Logout
            </span>
            <img class="select-none p-2 h-full w-auto mr-16 m-auto fas fa-sign-out-alt">
          </div>
        </div>
      </div>
    </div>

    <!-- Profile Layout -->
    <div class="absolute right-0 w-24full h-16 flex animation-enable"
        :class="[{ 'top-0': pageActive },
                { 'top-min4rem': !pageActive },
                { 'z-40': !openWide },
                { 'z-0': openWide }]">
      <div class="m-auto h-12 mt-4 w-48 flex items-center font-monda-bold text-lg text-white">
        <span class="m-auto flex items-center z-10" @mouseover="isMenuShown = true">
          {{ currentUser.nim }}
          <div class="w-4"/>
          <img class="select-none w-8 h-8 fas fa-arrow-circle-down" style="color: white;">
        </span>
      </div>
    </div>

    <!-- Message Layout -->
    <div class="absolute z-50 bottom-0 w-full h-full bg-black animation-enable pointer-events-none"
        :class="[{ 'opacity-75': messageOpened },
                { 'opacity-0': !messageOpened }]"/>
    <div class="absolute z-50 w-full h-36 bg-gray-500 rounded-b-lg animation-enable"
        :class="[{ 'top-0': messageOpened },
                { ' top-min20rem': !messageOpened }]">
      <form id="messageForm" class="w-full h-full pl-24 flex">
        <div class="w-2/12 h-full flex-row py-4">
          <span class="font-merri w-full text-left text-lg h-1/4">
            Kode Asisten
          </span>
          <div class="w-full h-3/4">
            <input v-model="formMessage.kode" 
                  class="font-overpass-mono-bold uppercase text-5xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                  id="Kode" type="text" placeholder="FAI">
          </div>
        </div>
        <div class="w-9/12 h-full flex-row py-4 pl-4">
          <span class="font-merri w-full text-left text-lg h-1/4">
            Pesan Kepada 
            <span class="uppercase">
              [ {{ formMessage.kode }} ]
            </span>
          </span>
          <div class="w-full h-3/4">
            <textarea v-model="secretMessage" cols="30" rows="10" 
                  class="font-overpass-mono-bold text-2xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
                  :class="[{ 'hidden': formMessage.kode.toUpperCase() != 'FAI' },
                          { 'visible': formMessage.kode.toUpperCase() == 'FAI' }]" 
                  id="Kode" type="text" placeholder="just for a test"/>
            <textarea v-model="formMessage.pesan" cols="30" rows="10" 
                  class="font-overpass-mono-bold text-2xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                  :class="[{ 'hidden': formMessage.kode.toUpperCase() == 'FAI' },
                          { 'visible': formMessage.kode.toUpperCase() != 'FAI' }]"
                  id="Kode" type="text" placeholder="just for a test"/>
          </div>
        </div>
        <div class="w-1/12 h-full">
          <span class="w-full h-full cursor-pointer" v-on:click="sendMessage">
            <img class="w-full h-full mt-4 p-8 fas fa-paper-plane" style="color: black;">
          </span>
        </div>
      </form>
    </div>

    <!-- Message Menu (OPEN) -->
    <span class="messageIcon absolute top-0 mt-6 ml-6 w-12 h-12 cursor-pointer animation-enable"
        :class="[{ 'left-min20rem': !pageActive || messageOpened },
                { 'left-0': pageActive && !messageOpened },
                { 'z-50': !openWide },
                { 'z-0': openWide }]"
        v-on:click="messageOpened = true">
      <img class="iconGreenHover w-full h-full fas fa-envelope">
    </span>

    <!-- Message Menu (CLOSE) -->
    <span class="messageIcon absolute left-0 mt-20 ml-5 w-12 h-12 p-0 hover:p-1 cursor-pointer animation-enable"
        :class="[{ 'top-0': messageOpened },
                { 'top-min20rem': !messageOpened },
                { 'z-50': !openWide },
                { 'z-0': openWide }]"
        v-on:click="messageOpened = false">
      <img class="w-full h-full fas fa-window-close" style="color: black;">
    </span>
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
</style>

<script>
import { useToast } from '@/composables/useToast';
import QuestionBlock from '@/components/praktikan/sections/QuestionBlock.vue';
import PraktikumSection from '@/components/praktikan/sections/PraktikumSection.vue';
export default {
  props: [
    'comingFrom',
    'currentUser',
    'allAsisten',
    'allAsistenPolling',
    'isRunmod',
    'pollingComplete',
    'allPolling',
    'allModul',
    'allJurnal',
  ],

  components: {
    QuestionBlock,
    PraktikumSection,
  },

  data() {
    return {
      pageActive: false,
      isPraktikum: false,
      isTP: false,
      isNilai: false,
      isJawaban: false,
      isProfil: false,
      isMenuShown: false,
      messageOpened: false,
      openWide: false,
      modulShown: false,

      currentJawabanJurnal: '',
      jawabanShown: false,
      jawabanChanged: false,

      isPollingEnabled: false,

      pollingComplete_mutable: this.pollingComplete,
      praktikumExist: false,

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
        praktikan_id: this.currentUser.id,
        kelas_id: this.currentUser.kelas_id,
      },

      all_modul: [],
      choosenModulID: '',
      allJawabanJurnal: [],

      programmingQuote: 'nothing',
      quoteAuthor: '',
      randomNumber:'',
      ATCnim:'',
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
        datasets: []
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

      goodScoreText: [
        "Mantap gini nih kalau sebelum praktikum belajar",
        "Wah beruntung apa gimana nih ?"
      ],

      badScoreText: [
        "Ayo lebih semangat lagi belajarnya!!!",
        "Waduh jangan mau kalah sama temen yang lain"
      ],

      secretMessage: 'VnRjdHggQU4gdnAgV1RZUCBxbGNsaGxqX2VjdHhwIG9weXJseSBhcGRseSA6IHF3bHJ7cGxkZXBjX3Bycl8xX290ZXB4ZnZseX0=',
      
      resetPass:{
        password:'',
        repeatpass: '',
      },

      viewPassForm: false,

    }
  },

  mounted() {
    this.toast = useToast();

    $('body').addClass('closed');
    this.showProfil();

    const globe = this;
    if(this.comingFrom === 'login'||
        this.comingFrom === 'none' ){

      setTimeout(
        function() {
          globe.pageActive = true;
        }, 10); 
    }

    globe.$axios.post('/checkPolling').then(response => {

      if(response.data.message === "success") {

        globe.isPollingEnabled = Boolean(response.data.isPollingEnabled);

      } else {
        globe.toast.error(response.data.message
        );
      }
    });

    globe.$axios.post('/checkPraktikum').then(response => {

      if(response.data.message === "success") {

        if(response.data.current_praktikum != null){

          //There is currently active praktikum && kelas_id === current_praktikum.kelas_id
          if(response.data.current_praktikum.kelas_id === globe.currentUser.kelas_id){
            globe.setCurrentPraktikumState(response.data.current_praktikum, false);
          }
        }

      } else {
        globe.toast.error(response.data.message
        );
      }
    });

    globe.$axios.post('/getAllNilai/'+globe.currentUser.id).then(response => {

      if(response.data.message === "success") {

        response.data.allNilai.forEach(nilai => {

          globe.allNilaiData.labels.push(nilai.judul);
          globe.allNilaiTP.push(nilai.tp);
          globe.allNilaiTA.push(nilai.ta);
          globe.allNilaiJURNAL.push(nilai.jurnal);
          globe.allNilaiTK.push(nilai.tk);
          globe.allNilaiSKILL.push(nilai.skill);
          globe.allNilaiDISKON.push(nilai.diskon);
        });

        for (let index = 0; index < 6; index++) {

          switch(index) {

            case 0:
              globe.allNilaiData.datasets.push({

                label: "TP",
                backgroundColor : 'rgba(75,192,192,0.1)',
                borderColor : '#00c853',
                pointBackgroundColor: 'black', 
                borderWidth: 2, 
                pointBorderColor: 'black',
                data: []
              });

              response.data.allNilai.forEach(nilai => {

                globe.allNilaiData.datasets[index].data.push(nilai.tp);
              });
              break;
            
            case 1:
              globe.allNilaiData.datasets.push({

                label: "TA",
                backgroundColor : 'rgba(75,192,192,0.1)',
                borderColor : '#00c853',
                pointBackgroundColor: 'black', 
                borderWidth: 2, 
                pointBorderColor: 'black',
                data: []
              });

              response.data.allNilai.forEach(nilai => {

                globe.allNilaiData.datasets[index].data.push(nilai.ta);
              });
              break;

            case 2:
              globe.allNilaiData.datasets.push({

                label: "JURNAL",
                backgroundColor : 'rgba(75,192,192,0.1)',
                borderColor : '#00c853',
                pointBackgroundColor: 'black', 
                borderWidth: 2, 
                pointBorderColor: 'black',
                data: []
              });

              response.data.allNilai.forEach(nilai => {

                globe.allNilaiData.datasets[index].data.push(nilai.jurnal);
              });
              break;

            case 3:
              globe.allNilaiData.datasets.push({

                label: "TK",
                backgroundColor : 'rgba(75,192,192,0.1)',
                borderColor : '#00c853',
                pointBackgroundColor: 'black', 
                borderWidth: 2, 
                pointBorderColor: 'black',
                data: []
              });

              response.data.allNilai.forEach(nilai => {

                globe.allNilaiData.datasets[index].data.push(nilai.tk);
              });
              break;

            case 4:
              globe.allNilaiData.datasets.push({

                label: "SKILL",
                backgroundColor : 'rgba(75,192,192,0.1)',
                borderColor : '#00c853',
                pointBackgroundColor: 'black', 
                borderWidth: 2, 
                pointBorderColor: 'black',
                data: []
              });

              response.data.allNilai.forEach(nilai => {

                globe.allNilaiData.datasets[index].data.push(nilai.skill);
              });
              break;

            case 5:
              globe.allNilaiData.datasets.push({

                label: "DISKON",
                backgroundColor : 'rgba(75,192,192,0.1)',
                borderColor : '#00c853',
                pointBackgroundColor: 'black', 
                borderWidth: 2, 
                pointBorderColor: 'black',
                data: []
              });

              response.data.allNilai.forEach(nilai => {

                globe.allNilaiData.datasets[index].data.push(nilai.diskon);
              });
              break;
          }
        }

      } else {
        globe.toast.error(response.data.message
        );
      }
    }); 

    if(globe.currentUser.kelas.substring(6, 10) == 'INT'){
      globe.all_modul = globe.allModul.filter(function (modul){
        modul.isUnlocked === 1 ? modul.isUnlocked = true : modul.isUnlocked = false;
        return modul.isEnglish === 1 && modul.id <= 20;
      });
      globe.$axios.get('/getSoalTP/true/' + globe.currentUser.id).then(response => {

        if(response.data.message === "success") {

          if(response.data.all_soalEssay !== null){

            globe.soalTPEssay = response.data.all_soalEssay;
            for (let index = 0; index < globe.soalTPEssay.length; index++) {
              const soal = globe.soalTPEssay[index];
              globe.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: globe.currentUser.id,
                jawaban: soal.jawaban === null ? '' : soal.jawaban,
              });
            }
          }

          if(response.data.all_soalProgram !== null){

            globe.soalTPProgram = response.data.all_soalProgram;
            for (let index = 0; index < globe.soalTPProgram.length; index++) {
              const soal = globe.soalTPProgram[index];
              globe.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: globe.currentUser.id,
                jawaban: soal.jawaban === null ? '' : soal.jawaban,
              });
            }
          }
        }
      }); 

      globe.$axios.post('/getPembahasanTP/true').then(response => {

        if(response.data.message === "success") {

          if(response.data.tp !== null) {

            globe.pembahasanTp.modul_id = response.data.tp.modul_id;
            globe.pembahasanTp.pembahasan = response.data.tp.pembahasan;
            globe.qrcodeData.modul_id = response.data.tp.modul_id;
          }
        }
      });

    } else {
      globe.all_modul = globe.allModul.filter(function (modul){
        return modul.isEnglish === 0 && modul.id <= 20;
      });
      globe.$axios.get('/getSoalTP/false/' + globe.currentUser.id).then(response => {

        if(response.data.message === "success") {

          if(response.data.all_soalEssay !== null){

            globe.soalTPEssay = response.data.all_soalEssay;
            for (let index = 0; index < globe.soalTPEssay.length; index++) {
              const soal = globe.soalTPEssay[index];
              globe.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: globe.currentUser.id,
                jawaban: soal.jawaban === null ? '' : soal.jawaban,
              });
            }
          }

          if(response.data.all_soalProgram !== null){

            globe.soalTPProgram = response.data.all_soalProgram;
            for (let index = 0; index < globe.soalTPProgram.length; index++) {
              const soal = globe.soalTPProgram[index];
              globe.jawabanTP.push({
                soal_id: soal.soal_id == null ? soal.id : soal.soal_id,
                modul_id: soal.modul_id,
                praktikan_id: globe.currentUser.id,
                jawaban: soal.jawaban === null ? '' : soal.jawaban,
              });
            }
          }
        }
      }); 

      globe.$axios.post('/getPembahasanTP/false').then(response => {

        if(response.data.message === "success") {

          if(response.data.tp !== null) {

            globe.pembahasanTp.modul_id = response.data.tp.modul_id;
            globe.pembahasanTp.pembahasan = response.data.tp.pembahasan;
            globe.qrcodeData.modul_id = response.data.tp.modul_id;
          }
        }
      });
    } 

    if (window.Echo) {
      globe.echoChannel = window.Echo.channel(`praktikum.${globe.currentUser.kelas_id}`)
        .listen('.PraktikumStatusUpdated', (data) => {
          globe.setCurrentPraktikumState(data.current_praktikum, true);
        });
    }
  },

  beforeUnmount() {
    const globe = this;
    if (window.Echo) {
      window.Echo.leave(`praktikum.${globe.currentUser.kelas_id}`);
    }
  },

  methods: {

    generateScoreText: function($nilai) {

      const globe = this;
      if($nilai > 50)
        return globe.goodScoreText[Math.floor(Math.random() * globe.goodScoreText.length)];
      else 
        return globe.badScoreText[Math.floor(Math.random() * globe.badScoreText.length)];
    },

    handleTextAnswerChange(event) {

      if (!event || !event.arrayName) {
        return;
      }

      this.onTextAnswerChange(event.arrayName, event.payload || {});
    },

    handleQuestionOptionSelect(event) {

      if (!event || !event.type) {
        return;
      }

      this.onQuestionOptionSelect(event.type, event.payload || {});
    },

    onTextAnswerChange(arrayName, payload) {

      const target = this[arrayName];
      if (!Array.isArray(target)) {
        return;
      }

      const { answerIndex, value } = payload;
      const entry = target[answerIndex];
      if (entry && typeof entry === 'object') {
        entry.jawaban = value;
      }
    },

    onQuestionOptionSelect(type, payload) {

      const question = payload.question || {};
      const soalId = question.id || question.soal_id || question.question_id || question.soalId;
      if (!soalId) {
        return;
      }

      this.chooseJawaban(
        type,
        payload.option,
        soalId,
        payload.questionIndex,
        payload.optionIndex,
      );
    },

    tpPickRandomProgram: function()
    {
         return Math.floor(Math.random()*3)+5;
    },
    
    pickATCnim: function()
    {
      const globe=this;
        if(globe.currentUser.kelas.substring(6, 10) == 'INT'){
          return '1101170002';
        } else return '1101170001';
    },

    shuffleArr: function($arr){

      for (let i = $arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [$arr[i], $arr[j]] = [$arr[j], $arr[i]];
      }

      return $arr;
    },

    saveJawabanTP: function() {

      const globe = this;
      globe.$axios.post('/saveJawabanTP', globe.jawabanTP).then(response => {

        if(response.data.message === "success") {
          
          globe.toast.success("TP ANDA BERHASIL DISIMPAN"
          );
        }
      }); 
    },
    
    finishPraktikum: function(){

      const globe = this;
      if(this.laporanPraktikan.asisten_id === ''){
        globe.toast.error('Pilih asisten yang mengajar anda terlebih dahulu <br> (dibagian paling atas samping kiri rating)');
        return;
      }

      if(this.laporanPraktikan.pesan === ''){
        globe.toast.error('Masukkan pesan untuk praktikum / asisten terlebih dahulu');
        return;
      }

      if(this.laporanPraktikan.pesan.length < 20){
        globe.toast.error('Pesan berisi minimal 20 karakter');
        return;
      }

      if(this.laporanPraktikan.rating_asisten === 0){
        globe.toast.error('Beri rating untuk asisten terlebih dahulu');
        return;
      }

      if(this.laporanPraktikan.rating_praktikum === 0){
        globe.toast.error('Beri rating untuk praktikum terlebih dahulu');
        return;
      }

      globe.laporanPraktikan.praktikan_id = this.currentUser.id;
      globe.laporanPraktikan.modul_id = this.current_praktikum.modul_id;
      globe.$axios.post('/sendLaporan', globe.laporanPraktikan).then(response => {

        if(response.data.message === "success") {
          globe.current_praktikum.status = 777;
          globe.toast.success("Praktikum telah selesai :)"
          );
        } else {
          globe.toast.error(response.data.message
          );
        }
      });
    },

    setCurrentPraktikumState: function(current_praktikum, isRealtime){
      
      const globe = this;
      globe.current_praktikum.asisten_id = current_praktikum.asisten_id;
      globe.current_praktikum.modul_id = current_praktikum.modul_id;
      globe.current_praktikum.kelas_id = current_praktikum.kelas_id;
      globe.current_praktikum.status = current_praktikum.status;

      // Check if the praktikan is in the kelas runnning the praktikum
      if(globe.current_praktikum.kelas_id === this.currentUser.kelas_id){

        //  Initialization of the praktikum
        globe.$axios.post('/getModul/'+globe.current_praktikum.modul_id).then(response => {

          if(response.data.message === "success") {

            if(response.data.modul !== null){

              //There is currently active praktikum
              globe.current_modul.judul = response.data.modul.judul;
              globe.current_modul.deskripsi = response.data.modul.deskripsi;
              globe.current_modul.isi = response.data.modul.isi;

              // http://quotes.stormconsultancy.co.uk/random.json 
              // (API for random programming quote)
              if(globe.current_praktikum.status === 0)
                globe.$axios.get('http://api.quotable.io/random').then(response => {
                  globe.programmingQuote = response.data.content;
                  globe.quoteAuthor = response.data.author;
                }); 
            }

          } else {
            globe.toast.error(response.data.message
            );
          }
        });       

        switch (globe.current_praktikum.status) {

          case 0:

            globe.praktikumExist = true;
            globe.showPraktikum();
            globe.openWide = true;
            break;

          case 1:
            
            // Start Opening TA and grab all SOAL from it
            // (get RANDOMIZED soal from soal__tas)
            globe.$axios.get('/getSoalTA/'+globe.current_praktikum.modul_id+'/'+globe.current_praktikum.kelas_id).then(response => {

              if(response.data.message === "success") {

                if(response.data.all_soal !== null){

                  globe.soalTA = response.data.all_soal;
                  globe.soalTA.forEach(soal => {
                    var all_jawaban = [];
                    all_jawaban.push(soal.jawaban_benar);
                    all_jawaban.push(soal.jawaban_salah1);
                    all_jawaban.push(soal.jawaban_salah2);
                    all_jawaban.push(soal.jawaban_salah3);

                    //randomizing all the "jawaban" for each soal
                    all_jawaban = globe.shuffleArr(all_jawaban);

                    globe.chosenJawaban.push({
                      modul_id: globe.current_praktikum.modul_id,
                      praktikan_id: globe.currentUser.id,
                      soal_id: soal.id,
                      jawaban: '',
                    });
                      
                    globe.jawabanTA.push(all_jawaban);
                  });
                }

              } else {
                globe.toast.error(response.data.message
                );
              }
            }); 
            break;

          case 2:
            // Realtime connection make changes to status 2
            // Meaning we should send jawaban in status 1 (Soal TA)
            if(isRealtime){
              
              globe.$axios.post('/sendJawabanTA', globe.chosenJawaban).then(response => {

                if(response.data.message === "success") {
                  
                  globe.nilaiTA = response.data.nilaiTa;
                  globe.showNilaiTA = true;
                  
                } else {
                  globe.toast.error(response.data.message
                  );
                }
              }); 
            }
            
            // Start opening jurnal section, 
            // get all soal from: get soal from soal__jurnal, soal__fitb
            globe.$axios.get('/getSoalJURNAL').then(response => {

              if(response.data.message === "success") {

                if(response.data.all_soal !== null){

                  globe.soalJurnal = response.data.all_soal.filter(function (el) {return el != null;});
                  for (let index = 0; index < globe.soalJurnal.length; index++) {
                    const soal = globe.soalJurnal[index];
                    globe.jawabanJurnal.push({
                      soal_id: soal.id,
                      modul_id: soal.modul_id,
                      praktikan_id: globe.currentUser.id,
                      jawaban: '',
                    });
                  }
                }
 
              } else {
                globe.toast.error(response.data.message
                );
              }
            }); 
            globe.$axios.get('/getSoalFITB').then(response => {

              if(response.data.message === "success") {

                if(response.data.all_soal !== null){

                  globe.soalFitb = response.data.all_soal.filter(function (el) {return el != null;});
                  for (let index = 0; index < globe.soalFitb.length; index++) {
                    const soal = globe.soalFitb[index];
                    globe.jawabanFitb.push({
                      soal_id: soal.id,
                      modul_id: soal.modul_id,
                      praktikan_id: globe.currentUser.id,
                      jawaban: '',
                    });
                  }
                }

              } else {
                globe.toast.error(response.data.message
                );
              }
            });
            break;

          case 3:
            
            // Realtime connection make changes to status 3
            // Meaning we should send jawaban in status 2 (Soal Jurnal and Soal FITB)
            if(isRealtime){
              
              globe.$axios.post('/sendJawabanJurnal', globe.jawabanJurnal).then(response => {

                if(response.data.message === "success") {
                  // Do nothing as all of jawaban successfully saved to the DB
                  
                } else {
                  globe.toast.error(response.data.message
                  );
                }
              }); 

              globe.$axios.post('/sendJawabanFitb', globe.jawabanFitb).then(response => {

                if(response.data.message === "success") {
                  // Do nothing as all of jawaban successfully saved to the DB
                  
                } else {
                  globe.toast.error(response.data.message
                  );
                }
              }); 
            }

            // Start opening Mandiri Section and get all SOAL from
            // get soal from soal__mandiris
            globe.$axios.get('/getSoalMANDIRI/'+globe.current_praktikum.modul_id+'/'+globe.current_praktikum.kelas_id).then(response => {

              if(response.data.message === "success") {

                if(response.data.all_soal !== null){

                  globe.soalMandiri = response.data.all_soal;
                  for (let index = 0; index < globe.soalMandiri.length; index++) {
                    const soal = globe.soalMandiri[index];
                    globe.jawabanMandiri.push({
                      soal_id: soal.id,
                      modul_id: soal.modul_id,
                      praktikan_id: globe.currentUser.id,
                      jawaban: '',
                    });
                  }
                }

              } else {
                globe.toast.error(response.data.message
                );
              }
            }); 
            break;

          case 4:
            
            // Realtime connection make changes to status 4
            // Meaning we should send jawaban in status 3 (Soal Mandiri)
            if(isRealtime){
              
              globe.$axios.post('/sendJawabanMandiri', globe.jawabanMandiri).then(response => {

                if(response.data.message === "success") {
                  // Do nothing as all of jawaban successfully saved to the DB
                  
                } else {
                  globe.toast.error(response.data.message
                  );
                }
              }); 
            }

            // Start Opening TK and grab all SOAL from it
            // (get RANDOMIZED soal from soal__tks)
            globe.$axios.get('/getSoalTK/'+globe.current_praktikum.modul_id+'/'+globe.current_praktikum.kelas_id).then(response => {

              if(response.data.message === "success") {

                if(response.data.all_soal !== null){

                  globe.soalTK = response.data.all_soal;
                  globe.chosenJawaban = [];
                  globe.soalTK.forEach(soal => {
                    var all_jawaban = [];
                    all_jawaban.push(soal.jawaban_benar);
                    all_jawaban.push(soal.jawaban_salah1);
                    all_jawaban.push(soal.jawaban_salah2);
                    all_jawaban.push(soal.jawaban_salah3);

                    //randomizing all the "jawaban" for each soal
                    all_jawaban = globe.shuffleArr(all_jawaban);

                    globe.chosenJawaban.push({
                      modul_id: globe.current_praktikum.modul_id,
                      praktikan_id: globe.currentUser.id,
                      soal_id: soal.id,
                      jawaban: '',
                    });
                      
                    globe.jawabanTK.push(all_jawaban);
                  });
                }

              } else {
                globe.toast.error(response.data.message
                );
              }
            }); 
            break;

          case 5: 

            if(globe.isRunmod) {
              
              // Realtime connection make changes to status 5
              // Meaning we should send jawaban in status 4 (Soal Jurnal (RUNMOD))
              if(isRealtime){
                
                globe.$axios.post('/sendJawabanJurnal', globe.jawabanRunmod).then(response => {

                  if(response.data.message === "success") {
                    // Do nothing as all of jawaban successfully saved to the DB
                    
                  } else {
                    globe.toast.error(response.data.message
                    );
                  }
                }); 
              }
            } else {
              
              // Realtime connection make changes to status 5
              // Meaning we should send jawaban in status 4 (Soal TK)
              if(isRealtime){
                
                globe.$axios.post('/sendJawabanTK', globe.chosenJawaban).then(response => {

                  if(response.data.message === "success") {
                  
                    globe.nilaiTK = response.data.nilaiTk;
                    globe.showNilaiTK = true;
                    
                  } else {
                    globe.toast.error(response.data.message
                    );
                  }
                }); 
              }
            }

            // Check if laporan Praktikan already exists
            globe.$axios.post('/getLaporan/'+globe.currentUser.id+'/'+globe.current_praktikum.modul_id).then(response => {

              if(response.data.message === "done") {
                
                // Change status to 777 if the praktikan already fill in the laporan form
                globe.current_praktikum.status = 777;
                globe.praktikumExist = false;
                globe.openWide = false;
              }

              //else just run it as usual
            }); 
            break;

          case 123:

            // Start opening Runmod Section and get all SOAL from
            // get soal from soal_jurnals
            globe.$axios.get('/getSoalRUNMOD').then(response => {

              if(response.data.message === "success") {

                if(response.data.all_soal !== null){

                  globe.soalRunmod = response.data.all_soal;
                  for (let index = 0; index < globe.soalRunmod.length; index++) {
                    const soal = globe.soalRunmod[index];
                    globe.jawabanRunmod.push({
                      soal_id: soal.id,
                      modul_id: soal.modul_id,
                      praktikan_id: globe.currentUser.id,
                      jawaban: '',
                    });
                  }
                }

              } else {
                globe.toast.error(response.data.message
                );
              }
            }); 
            break;

          default:

            // Ignore other case and cast the status to 777
            globe.current_praktikum.status = 777;
            globe.praktikumExist = false;
            globe.openWide = false;
            break;
        }
      }
    },

    chooseJawaban: function($soalType, $jawaban, $soalId, $soalIndex, $jawabanIndex) {
      // Determine which array to use based on soal type
      const jawabanArray = $soalType === "TA" ? this.jawabanTA : this.jawabanTK;
      
      // Find the corresponding chosen jawaban entry
      const chosenJawabanIndex = this.chosenJawaban.findIndex(item => item.soal_id === $soalId);
      
      if (chosenJawabanIndex !== -1) {
        const currentAnswer = this.chosenJawaban[chosenJawabanIndex].jawaban;
        
        // If there's a previous answer, reset its styling
        if (currentAnswer !== '') {
          for (let i = 0; i < jawabanArray[chosenJawabanIndex].length; i++) {
            if (jawabanArray[chosenJawabanIndex][i] === currentAnswer) {
              const previousSelector = `.jawaban-${chosenJawabanIndex}${i}`;
              $(previousSelector)
                .addClass('bg-green-200 hover:bg-green-300')
                .removeClass('bg-green-500 text-white');
              break;
            }
          }
        }
        
        // Update the answer in the chosen jawaban array
        this.chosenJawaban[chosenJawabanIndex].jawaban = $jawaban;
      }
      
      // Update styling for the newly selected answer
      const currentSelector = `.jawaban-${$soalIndex}${$jawabanIndex}`;
      $(currentSelector)
        .removeClass('bg-green-200 hover:bg-green-300')
        .addClass('bg-green-500 text-white');
    },

    handlePollingSaved() {
      this.pollingComplete_mutable = true;
    },

    showPraktikum: function(){

      this.isPraktikum = true;
      this.isTP = false;
      this.isNilai = false;
      this.isProfil = false;
      this.isJawaban = false;
            
      this.allJawabanJurnal = [];
      this.jawabanShown = false;
      this.currentJawabanJurnal = '';

      $('.tpIcon , .nilaiIcon , .profilIcon, .jawabanIcon').removeClass('w-3/12');
      $('.tpIcon , .nilaiIcon , .profilIcon, .jawabanIcon').removeClass('youngYellowIcon');
      $('.tpIcon , .nilaiIcon , .profilIcon, .jawabanIcon').addClass('iconYellowHover');
      $('.tpIcon , .nilaiIcon , .profilIcon, .jawabanIcon').addClass('w-full');
      
      $('.praktikumIcon').removeClass('w-full');
      $('.praktikumIcon').removeClass('iconYellowHover');
      $('.praktikumIcon').addClass('youngYellowIcon');
      $('.praktikumIcon').addClass('w-3/12');
    },

    showNilai: function(){
      this.isPraktikum = false;
      this.isTP = false;
      this.isNilai = true;
      this.isProfil = false;
      this.isJawaban = false;
            
      this.allJawabanJurnal = [];
      this.jawabanShown = false;
      this.currentJawabanJurnal = '';

      $('.praktikumIcon , .tpIcon , .profilIcon, .jawabanIcon').removeClass('w-3/12');
      $('.praktikumIcon , .tpIcon , .profilIcon, .jawabanIcon').removeClass('youngYellowIcon');
      $('.praktikumIcon , .tpIcon , .profilIcon, .jawabanIcon').addClass('iconYellowHover');
      $('.praktikumIcon , .tpIcon , .profilIcon, .jawabanIcon').addClass('w-full');
      
      $('.nilaiIcon').removeClass('w-full');
      $('.nilaiIcon').removeClass('iconYellowHover');
      $('.nilaiIcon').addClass('youngYellowIcon');
      $('.nilaiIcon').addClass('w-3/12');
    },

    showTP: function(){
      this.isPraktikum = false;
      this.isTP = true;
      this.isNilai = false;
      this.isProfil = false;
      this.isJawaban = false;
      
      this.allJawabanJurnal = [];
      this.jawabanShown = false;
      this.currentJawabanJurnal = '';

      $('.praktikumIcon , .nilaiIcon , .profilIcon, .jawabanIcon').removeClass('w-3/12');
      $('.praktikumIcon , .nilaiIcon , .profilIcon, .jawabanIcon').removeClass('youngYellowIcon');
      $('.praktikumIcon , .nilaiIcon , .profilIcon, .jawabanIcon').addClass('iconYellowHover');
      $('.praktikumIcon , .nilaiIcon , .profilIcon, .jawabanIcon').addClass('w-full');
      
      $('.tpIcon').removeClass('iconYellowHover');
      $('.tpIcon').removeClass('w-full');
      $('.tpIcon').addClass('youngYellowIcon');
      $('.tpIcon').addClass('w-3/12');
    },

    showProfil: function(){
      this.isPraktikum = false;
      this.isTP = false;
      this.isNilai = false;
      this.isProfil = true;
      this.isJawaban = false;

      this.allJawabanJurnal = [];
      this.jawabanShown = false;
      this.currentJawabanJurnal = '';

      $('.praktikumIcon , .nilaiIcon , .tpIcon, .jawabanIcon').removeClass('w-3/12');
      $('.praktikumIcon , .nilaiIcon , .tpIcon, .jawabanIcon').removeClass('youngYellowIcon');
      $('.praktikumIcon , .nilaiIcon , .tpIcon, .jawabanIcon').addClass('iconYellowHover');
      $('.praktikumIcon , .nilaiIcon , .tpIcon, .jawabanIcon').addClass('w-full');
      
      $('.profilIcon').removeClass('iconYellowHover');
      $('.profilIcon').removeClass('w-full');
      $('.profilIcon').addClass('youngYellowIcon');
      $('.profilIcon').addClass('w-3/12');
    },

    showJawaban: function(){
      this.isPraktikum = false;
      this.isTP = false;
      this.isNilai = false;
      this.isProfil = false;
      this.isJawaban = true;

      $('.praktikumIcon , .nilaiIcon , .tpIcon, .profilIcon').removeClass('w-3/12');
      $('.praktikumIcon , .nilaiIcon , .tpIcon, .profilIcon').removeClass('youngYellowIcon');
      $('.praktikumIcon , .nilaiIcon , .tpIcon, .profilIcon').addClass('iconYellowHover');
      $('.praktikumIcon , .nilaiIcon , .tpIcon, .profilIcon').addClass('w-full');
      
      $('.jawabanIcon').removeClass('iconYellowHover');
      $('.jawabanIcon').removeClass('w-full');
      $('.jawabanIcon').addClass('youngYellowIcon');
      $('.jawabanIcon').addClass('w-3/12');
    },

    showJawabanJurnal: function($modul_id, $modul_isUnlocked){

      const globe = this;
      $modul_isUnlocked ? globe.jawabanChanged = true : globe.jawabanChanged = false;

      setTimeout(
        function() {
          if($modul_isUnlocked){

            globe.jawabanShown = true;
            globe.currentJawabanJurnal = $modul_id;
            globe.$axios.post('/praktikanGetJurnal/'+globe.currentUser.id+"/"+$modul_id).then(response => {
              
              if(response.data.message === "success"){
      
                globe.allJawabanJurnal = response.data.allJawabanJurnal;
              } else {
                globe.toast.error(response.data.message
                );
              }
            });  
          };
        }, 250);

      setTimeout(
        function() {
          if (globe.jawabanChanged === true) 
            globe.jawabanChanged = false;
        }, 1000);
    },

    travel: function($whereTo){
      const globe = this;
      setTimeout(
        function() {
          globe.$inertia.get('/praktikan/' + $whereTo, {}, {
            replace: true,
          });
        }, 500); 
    },

    signOut: function() {

      const globe = this;
      this.pageActive = false;
      this.isMenuShown = false;
      setTimeout(
        function() {
          globe.$inertia.get('/logoutPraktikan', {}, {
            replace: true,
          });
        }, 1010); 
    },

    sendMessage: function(){

      const globe = this;
      this.$axios.post('/praktikan/pesan', this.formMessage).then(response => {
        
        if(response.data.message === "success") {

          globe.toast.success("Pesan berhasil terkirim"
          );
          globe.messageOpened = false;

        } else {

          globe.toast.error(response.data.message
          );
        }
      }).catch(function (error) {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          if(error.response.data.errors != null){
            if(error.response.data.errors.kode != null)
              globe.toast.error(error.response.data.errors.kode[0]
              );
            if(error.response.data.errors.pesan != null)
              globe.toast.error(error.response.data.errors.pesan[0]
              );
          } 
        }
      });
    },

    formPassword: function($bool){
        this.viewPassForm = $bool;
      if(!$bool){
        this.resetPass.password = '';
        this.resetPass.repeatpass = '';
      }
    },

    resetPassword: function(){
      const globe = this;
        this.$axios.post('/resetPass', this.resetPass).then(response => {

        if(response.data.message === "success") {
          globe.toast.success("Password berhasil diperbaharui"   
          );
      

          this.signOut();

        } else {
          globe.toast.error(response.data.message
          );
        }
      }).catch(function (error) {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          if(error.response.data.errors != null){
            if(error.response.data.errors.password != null)
              globe.toast.error(error.response.data.errors.password[0]
              );
            if(error.response.data.errors.repeatpass != null)
              globe.toast.error(error.response.data.errors.repeatpass[0]
              );
          } 
        }
      });
    }
  }
}
</script>
