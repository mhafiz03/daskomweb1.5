<template>
  <div class="w-full h-full flex">
            <!-- When polling enabled -->
            <div v-if="isPollingEnabled" class="w-full h-full flex">
              <div v-if="pollingComplete_mutable" class="font-monda-bold h-auto w-auto m-auto text-center text-5xl"> 
                <span>Polling telah selesai<br>Selamat anda telah menyelesaikan praktikum<br>Dasar Komputer 2022/2023 🎉🎉</span>
              </div>
              <div v-if="!pollingComplete_mutable" class="w-full h-full py-4 relative">

                <div class="absolute top-0 m-4 right-0 animation-enable-short rounded-lg bg-green-400 p-3 hover:p-4 hover:bg-green-500 cursor-pointer pointer-events-auto w-auto h-auto flex"
                    v-on:click="savePolling()">
                  <div class="font-overpass-mono-bold text-white text-center text-xl h-auto w-auto pointer-events-none m-auto">
                    <span>SAVE</span>
                  </div>
                </div>

                <div class="w-full h-3/4 flex-row">
                  <div class="w-full h-8 flex">
                    <div class="w-auto mx-auto h-full flex">
                      <div class="font-monda-bold h-auto w-auto m-auto text-center text-2xl">
                        <span>{{ allAsistenPolling[chosenAsisten].nama }} ({{ allAsistenPolling[chosenAsisten].kode }})</span>
                      </div>
                    </div>
                  </div>
                  <div class="w-full h-full flex">
                    <div class="w-120full mx-auto h-full flex">
                      <div class="w-16 h-full flex">
                        <div class="w-12 h-12 p-0 hover:p-1 mr-auto my-auto animation-enable-short pointer-events-auto">
                          <span class="w-full h-full cursor-pointer text-black"
                              :class="[{ 'opacity-100': chosenAsisten > 0 },
                                { 'opacity-25': chosenAsisten == 0 }]"
                            v-on:click="chosenAsisten -= chosenAsisten > 0 ? 1 : 0">
                            <img class="w-full h-full fas fa-caret-left"/>
                          </span>
                        </div>
                      </div>

                      <div class="w-full h-12full rounded-large flex bg-green-600 my-auto shadow-xl">
                        <div class="w-1/3 h-full rounded-l-large flex bg-green-400">
                          <img class="select-none w-full h-full bg-center bg-cover rounded-l-large" 
                            :style="'background-image: url(/assets/' + allAsistenPolling[chosenAsisten].kode + '.jpg);'">
                        </div>
                        <div class="w-2/3 h-full flex">
                          <div class="font-merri-bold h-auto w-auto m-auto text-center text-xl text-white p-4">
                            <span>{{ allAsistenPolling[chosenAsisten].deskripsi }}</span>
                          </div>
                        </div>
                      </div>

                      <div class="w-16 h-full flex">
                        <div class="w-12 h-12 p-0 hover:p-1 mr-auto my-auto animation-enable-short pointer-events-auto">
                          <span class="w-full h-full cursor-pointer text-black"
                              :class="[{ 'opacity-100': chosenAsisten < (allAsisten.length-1) },
                                { 'opacity-25': chosenAsisten == (allAsisten.length-1) }]"
                            v-on:click="chosenAsisten += chosenAsisten < (allAsisten.length-1) ? 1 : 0">
                            <img class="w-full h-full fas fa-caret-right"/>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="w-full h-1/4 flex overflow-y-hidden overflow-x-scroll">
                  <div class="animation-enable-short w-auto h-full flex m-auto">
                    <div v-for="(polling, index) in allPolling" v-bind:key="polling.id"
                        class="animation-enable-short relative w-auto h-auto my-auto flex mx-2">
                      <div class="animation-enable-short w-auto h-auto rounded-lg flex bg-teal-200 hover:bg-teal-300 p-3 hover:p-4 pointer-events-auto cursor-pointer"
                          v-on:click="setPilihanPolling(index, allAsistenPolling[chosenAsisten].id)">
                        <div class="font-overpass-bold h-auto w-auto m-auto text-center text-lg text-black pointer-events-none"> 
                          <span>{{ polling.judul }} [{{ polling.asisten_id == undefined ? '' : allAsisten.find(x=> x.id === polling.asisten_id).kode }}]</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- When polling disabled -->
            <div v-if="!isPollingEnabled" class="w-full h-full flex">
              <div v-if="current_praktikum.status === '' || 
                        current_praktikum.status === 777" 
                  class="w-full h-full flex">
                <div class="font-monda-bold h-auto w-auto m-auto text-center text-5xl">
                  Tidak ada <br> Praktikum saat ini
                </div> 
              </div>
              
              <!-- Praktikum just been started by Praktikum PJ -->
              <div v-if="current_praktikum.status === 0"
                  class="w-full h-full flex-row">
                <div class="w-full h-24full flex">
                  <div class="font-overpass text-3xl m-auto px-16">
                    <span>Bersiap untuk praktikum modul <br><span class="font-merri-bold text-5xl"> {{ current_modul.judul }} </span></span>
                  </div>
                </div>
                <div v-if="programmingQuote !== 'nothing'" class="w-full h-24 flex">
                  <div class="font-overpass-mono-thin text-sm m-auto flex">
                    <div class="m-auto text-center">"{{ programmingQuote }}"<br>by {{ quoteAuthor }}</div>
                  </div>
                </div>
              </div>
              
              <!-- Soal TA already started by Praktikum PJ -->
              <div v-if="current_praktikum.status === 1"
                  class="w-full h-full flex">
                <div class="w-1/4 h-full flex-row overflow-y-hidden">
                  <div class="w-full h-2/3 flex bg-yellow-700 px-2 overflow-x-hidden rounded-tl-large overflow-y-auto">
                    <div class="h-auto text-white w-auto m-auto text-center font-monda-bold text-3xl">
                      Modul <br> <span class="font-merri">{{ current_modul.judul }}</span>
                    </div>
                  </div>
                  <div class="w-full h-1/3 flex bg-yellow-600 rounded-bl-large">
                    <div class="h-auto text-white text-center w-auto m-auto font-monda-bold text-2xl">
                      TES <br> AWAL
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex">
                  <div class="w-full h-full overflow-y-auto">
                    <div class="w-full h-auto flex-row">
                      <div v-for="(soal, index) in soalTA" v-bind:key="soal.id" 
                          class="w-full flex-row h-auto">
                        <div class="w-full h-auto flex my-10">
                          <div class="h-full w-12 flex font-merri-bold text-xl">
                            <div class="m-auto w-auto h-auto">{{ index+1 }}</div>
                          </div>
                          <div class="h-12 px-1 w-4">
                            <div class="h-full w-full bg-gray-900"/>
                          </div>
                          <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                            <span>{{ soal.pertanyaan }}</span>
                          </div>
                        </div>
                        <div v-for="(jawaban, i) in jawabanTA[index]" v-bind:key="i"
                            class="w-full h-auto flex-row">
                          <div class="w-full px-8 my-2 h-auto cursor-pointer flex">
                            <div class="w-full px-4 py-2 rounded-large font-overpass-bold break-words whitespace-pre-wrap text-xl transition-colors duration-200"
                                :class="choiceClass(index, jawaban)"
                                @click="chooseJawaban(jawaban, soal.id, index)">
                              <span>{{ jawaban }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Soal Jurnal already started by Praktikum PJ -->
              <div v-if="current_praktikum.status === 2"
                  class="w-full h-full flex">
                <div class="w-full h-full flex overflow-hidden"
                      :class="[{ 'hidden' : !showNilaiTA },
                                { 'visible' : showNilaiTA }]">
                  <div class="w-24full h-16full m-auto flex-row">
                    <div class="w-full h-1/3 flex">
                      <span class="w-auto h-auto mt-auto font-overpass text-3xl text-black">
                        Nilai TA Kamu 
                          <span :class="[{ 'text-red-500' : nilaiTA <= 50 },
                                        { 'text-green-500' : nilaiTA > 50 }]">
                            {{ nilaiTA != '' ? nilaiTA : "ERROR" }}
                          </span>
                      </span>
                    </div>
                    <div class="w-full h-2/3 flex-row">
                      <span class="w-auto h-auto mb-auto font-monda-bold text-4xl text-black">
                        {{ generateScoreText(nilaiTA) }}
                      </span>

                      <div class="w-64 h-24 flex">
                        <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                            v-on:click="showNilaiTA = false">
                          <div class="h-full w-full flex font-merri text-xl bg-gray-800 rounded-lg text-center m-auto">
                            <div class="m-auto text-white">
                              Lanjut Ke Jurnal
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-1/4 h-full flex-row overflow-y-hidden"
                      :class="[{ 'visible' : !showNilaiTA },
                                { 'hidden' : showNilaiTA }]">
                  <div class="w-full h-2/3 flex bg-yellow-700 px-2 overflow-x-hidden rounded-tl-large overflow-y-auto">
                    <div class="h-auto text-white w-auto m-auto text-center font-monda-bold text-3xl">
                      Modul <br> <span class="font-merri">{{ current_modul.judul }}</span>
                    </div>
                  </div>
                  <div class="w-full h-1/3 flex-row bg-yellow-600 rounded-bl-large">
                    <div class="h-1/2 text-white flex text-center w-auto m-auto font-monda-bold text-2xl">
                      <div class="m-auto">
                        JURNAL
                      </div>
                    </div>
                    <div class="h-1/2 w-full flex"
                        v-if="modulShown">
                      <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                          v-on:click="modulShown = false">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl bg-gray-300 rounded-large text-center m-auto">
                          <div class="m-auto">
                            Lihat Soal
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="h-1/2 w-full flex"
                        v-if="!modulShown">
                      <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                          v-on:click="modulShown = true">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl bg-gray-300 rounded-large text-center m-auto">
                          <div class="m-auto">
                            Lihat Modul
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex" 
                      :class="[{ 'visible' : !showNilaiTA },
                                { 'hidden' : showNilaiTA }]"
                      v-if="!modulShown">
                  <div class="w-full h-full overflow-y-auto">
                    <div class="w-full h-auto flex-row">
                      <div v-for="(soal, index) in soalFitb" v-bind:key="index" 
                          class="w-full flex-row h-auto">
                        <div class="w-full h-auto flex my-10">
                          <div class="h-full w-12 flex font-merri-bold text-xl">
                            <div class="m-auto w-auto h-auto">{{ index+1 }}</div>
                          </div>
                          <div class="h-12 px-1 w-4">
                            <div class="h-full w-full bg-gray-900"/>
                          </div>
                          <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                            <span>{{ soal.soal }}</span>
                          </div>
                        </div>
                        <div class="w-full h-auto flex px-5">
                          <textarea v-model="jawabanFitb[index].jawaban" cols="30" rows="10"
                                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                type="text" placeholder="Ketik jawabanmu disini ..."/>
                        </div>
                      </div>
                      <div v-for="(soal, index) in soalJurnal" v-bind:key="index+1" 
                          class="w-full flex-row h-auto">
                        <div class="w-full h-auto flex my-10">
                          <div class="h-full w-12 flex font-merri-bold text-xl">
                            <div class="m-auto w-auto h-auto">{{ soalFitb.length+(index+1) }}</div>
                          </div>
                          <div class="h-12 px-1 w-4">
                            <div class="h-full w-full bg-gray-900"/>
                          </div>
                          <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                            <span>{{ soal.soal }}</span>
                          </div>
                        </div>
                        <div class="w-full h-auto flex px-5">
                          <textarea v-model="jawabanJurnal[index].jawaban" cols="30" rows="10"
                                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                type="text" placeholder="Ketik jawabanmu disini ..."/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex" 
                    v-if="modulShown">
                  <div class="w-full h-full font-overpass text-xl whitespace-pre-wrap p-4 text-justify break-words overflow-y-auto">
                    <span>{{ current_modul.isi }}</span>
                  </div>
                </div>
              </div>
              
              <!-- Soal RUNMOD already started by Praktikum PJ -->
              <!-- JUST FOR SPECIAL CASE (RUNMOD) -->
              <div v-if="current_praktikum.status === 123"
                  class="w-full h-full flex">  
                <div class="w-1/4 h-full flex-row overflow-y-hidden">
                  <div class="w-full h-2/3 flex bg-yellow-700 px-2 overflow-x-hidden rounded-tl-large overflow-y-auto">
                    <div class="h-auto text-white w-auto m-auto text-center font-monda-bold text-3xl">
                      Modul <br> <span class="font-merri">{{ current_modul.judul }}</span>
                    </div>
                  </div>
                  <div class="w-full h-1/3 flex-row bg-yellow-600 rounded-bl-large">
                    <div class="h-1/2 text-white flex text-center w-auto m-auto font-monda-bold text-2xl">
                      <div class="m-auto">
                        JURNAL
                      </div>
                    </div>
                    <div class="h-1/2 w-full flex"
                        v-if="modulShown">
                      <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                          v-on:click="modulShown = false">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl bg-gray-300 rounded-large text-center m-auto">
                          <div class="m-auto">
                            Lihat Soal
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="h-1/2 w-full flex"
                        v-if="!modulShown">
                      <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                          v-on:click="modulShown = true">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl bg-gray-300 rounded-large text-center m-auto">
                          <div class="m-auto">
                            Lihat Modul
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex" 
                    v-if="!modulShown">
                  <div class="w-full h-full overflow-y-auto">
                    <div class="w-full h-auto flex-row">
                      <div v-for="(soal, index) in soalRunmod" v-bind:key="soal.id" 
                          class="w-full flex-row h-auto">
                        <div class="w-full h-auto flex my-10">
                          <div class="h-full w-12 flex font-merri-bold text-xl">
                            <div class="m-auto w-auto h-auto">{{ index+1 }}</div>
                          </div>
                          <div class="h-12 px-1 w-4">
                            <div class="h-full w-full bg-gray-900"/>
                          </div>
                          <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                            <span>{{ soal.soal }}</span>
                          </div>
                        </div>
                        <div class="w-full h-auto flex px-5">
                          <textarea v-model="jawabanRunmod[index].jawaban" cols="30" rows="10"
                                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                type="text" placeholder="Ketik jawabanmu disini ..."/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex" 
                    v-if="modulShown">
                  <div class="w-full h-full font-overpass text-xl whitespace-pre-wrap p-4 text-justify break-words overflow-y-auto">
                    <span>{{ current_modul.isi }}</span>
                  </div>
                </div>
              </div>
              
              <!-- Soal JURNAL already started by Praktikum PJ -->
              <div v-if="current_praktikum.status === 3"
                  class="w-full h-full flex">
                <div class="w-1/4 h-full flex-row overflow-y-hidden">
                  <div class="w-full h-2/3 flex bg-yellow-700 px-2 overflow-x-hidden rounded-tl-large overflow-y-auto">
                    <div class="h-auto text-white w-auto m-auto text-center font-monda-bold text-3xl">
                      Modul <br> <span class="font-merri">{{ current_modul.judul }}</span>
                    </div>
                  </div>
                  <div class="w-full h-1/3 flex-row bg-yellow-600 rounded-bl-large">
                    <div class="h-1/2 text-white flex text-center w-auto m-auto font-monda-bold text-2xl">
                      <div class="m-auto">
                        MANDIRI
                      </div>
                    </div>
                    <div class="h-1/2 w-full flex"
                        v-if="modulShown">
                      <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                          v-on:click="modulShown = false">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl bg-gray-300 rounded-large text-center m-auto">
                          <div class="m-auto">
                            Lihat Soal
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="h-1/2 w-full flex"
                        v-if="!modulShown">
                      <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                          v-on:click="modulShown = true">
                        <div class="h-full w-full flex font-overpass-mono-bold text-xl bg-gray-300 rounded-large text-center m-auto">
                          <div class="m-auto">
                            Lihat Modul
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex" 
                    v-if="!modulShown">
                  <div class="w-full h-full overflow-y-auto">
                    <div class="w-full h-auto flex-row">
                      <div v-for="(soal, index) in soalMandiri" v-bind:key="soal.id" 
                          class="w-full flex-row h-auto">
                        <div class="w-full h-auto flex my-10">
                          <div class="h-full w-12 flex font-merri-bold text-xl">
                            <div class="m-auto w-auto h-auto">{{ index+1 }}</div>
                          </div>
                          <div class="h-12 px-1 w-4">
                            <div class="h-full w-full bg-gray-900"/>
                          </div>
                          <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                            <span>{{ soal.soal }}</span>
                          </div>
                        </div>
                        <div class="w-full h-auto flex px-5">
                          <textarea v-model="jawabanMandiri[index].jawaban" cols="30" rows="10"
                                class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" 
                                type="text" placeholder="Ketik jawabanmu disini ..."/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex" 
                    v-if="modulShown">
                  <div class="w-full h-full font-overpass text-xl whitespace-pre-wrap p-4 text-justify break-words overflow-y-auto">
                    <span>{{ current_modul.isi }}</span>
                  </div>
                </div>
              </div>

              <!-- Soal TK already started by Praktikum PJ -->
              <div v-if="current_praktikum.status === 4"
                  class="w-full h-full flex">
                <div class="w-1/4 h-full flex-row overflow-y-hidden">
                  <div class="w-full h-2/3 flex bg-yellow-700 px-2 overflow-x-hidden rounded-tl-large overflow-y-auto">
                    <div class="h-auto text-white w-auto m-auto text-center font-monda-bold text-3xl">
                      Modul <br> <span class="font-merri">{{ current_modul.judul }}</span>
                    </div>
                  </div>
                  <div class="w-full h-1/3 flex bg-yellow-600 rounded-bl-large">
                    <div class="h-auto text-white text-center w-auto m-auto font-monda-bold text-2xl">
                      TES <br> AKHIR
                    </div>
                  </div>
                </div>
                <div class="w-3/4 h-full flex">
                  <div class="w-full h-full overflow-y-auto">
                    <div class="w-full h-auto flex-row">
                      <div v-for="(soal, index) in soalTK" v-bind:key="soal.id" 
                          class="w-full flex-row h-auto">
                        <div class="w-full h-auto flex my-10">
                          <div class="h-full w-12 flex font-merri-bold text-xl">
                            <div class="m-auto w-auto h-auto">{{ index+1 }}</div>
                          </div>
                          <div class="h-12 px-1 w-4">
                            <div class="h-full w-full bg-gray-900"/>
                          </div>
                          <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
                            <span>{{ soal.pertanyaan }}</span>
                          </div>
                        </div>
                        <div v-for="(jawaban, i) in jawabanTK[index]" v-bind:key="i"
                            class="w-full h-auto flex-row">
                          <div class="w-full px-8 my-2 h-auto cursor-pointer flex">
                            <div class="w-full px-4 py-2 rounded-large font-overpass-bold break-words whitespace-pre-wrap text-xl transition-colors duration-200"
                                :class="choiceClass(index, jawaban)"
                                @click="chooseJawaban(jawaban, soal.id, index)">
                              <span>{{ jawaban }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- All Praktikum Proses have done -->
              <!-- Show laporan praktikan's layout -->
              <div v-if="current_praktikum.status !== 777 &&
                          current_praktikum.status !== 0 &&
                          current_praktikum.status !== '' &&
                          current_praktikum.status !== 1 &&
                          current_praktikum.status !== 2 &&
                          current_praktikum.status !== 3 &&
                          current_praktikum.status !== 4 && 
                          current_praktikum.status !== 123"
                  class="w-full h-full flex">
                <div class="w-full h-full flex overflow-hidden"
                      :class="[{ 'hidden' : !showNilaiTK },
                                { 'visible' : showNilaiTK }]">
                  <div class="w-24full h-16full m-auto flex-row">
                    <div class="w-full h-1/3 flex">
                      <span class="w-auto h-auto mt-auto font-overpass text-3xl text-black">
                        Nilai TK Kamu 
                          <span :class="[{ 'text-red-500' : nilaiTK <= 50 },
                                        { 'text-green-500' : nilaiTK > 50 }]">
                            {{ nilaiTK != '' ? nilaiTK : "ERROR" }}
                          </span>
                      </span>
                    </div>
                    <div class="w-full h-2/3 flex-row">
                      <span class="w-auto h-auto mb-auto font-monda-bold text-4xl text-black">
                        {{ generateScoreText(nilaiTK) }}
                      </span>

                      <div class="w-64 h-24 flex">
                        <div class="h-full w-full flex p-4 hover:p-5 cursor-pointer animation-enable-short"
                            v-on:click="showNilaiTK = false">
                          <div class="h-full w-full flex font-merri text-xl bg-gray-800 rounded-lg text-center m-auto">
                            <div class="m-auto text-white">
                              Lanjut Ke Feedback
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-full h-full flex-row overflow-y-auto overflow-x-hidden"
                      :class="[{ 'visible' : !showNilaiTK },
                                { 'hidden' : showNilaiTK }]">
                  <div class="w-full h-24 flex mt-4">
                    <div class="w-1/2 h-full flex">
                      <select v-model="laporanPraktikan.asisten_id" 
                            class="block font-monda-bold text-4xl appearance-none w-2/3 ml-auto mr-2 my-auto h-3/4 bg-gray-600 border border-gray-600 text-white py-2 px-2 pr-8 rounded leading-tight focus:outline-none focus:bg-gray-500 focus:border-teal-500" id="grid-state">
                        <option value="" disabled selected>Pilih Asisten Jaga</option>
                        <option v-for="asisten in allAsisten" v-bind:key="asisten.id" :value="asisten.id">{{ asisten.kode }} [{{ asisten.nama }}]</option>
                      </select>
                    </div>
                    <div class="w-1/2 h-full flex">
                      <star-rating class="mr-auto ml-2 my-auto"
                        style="width: 250px;" 
                        v-model="laporanPraktikan.rating_asisten"
                        :increment="0.01" 
                        :fixed-points="2"
                        :show-rating="false"
                        :star-size='50'/>
                    </div>
                  </div>
                  <div class="w-3/4 h-2 bg-black m-auto mt-4 rounded-full"/>
                  <div class="w-3/4 mx-auto h-auto flex mt-4">
                    <div class="w-1/2 h-auto flex-row">
                      <div class="w-auto h-auto m-auto font-overpass-bold text-3xl text-center break-words">
                        Bagaimana menurutmu <br> praktikum saat ini ?
                      </div>
                      <div class="w-full h-auto flex mt-2">
                        <star-rating class="m-auto"
                          style="width: 250px;" 
                          v-model="laporanPraktikan.rating_praktikum"
                          :increment="0.01" 
                          :fixed-points="2"
                          :show-rating="false"
                          :star-size='50'/>
                      </div>
                    </div>
                    <div class="w-1/2 h-auto flex">
                      <textarea v-model="laporanPraktikan.pesan" cols="30" rows="5" 
                            class="font-overpass-mono-bold resize-none text-2xl bg-gray-600 appearance-none border-2 border-gray-300 rounded w-full h-full py-2 px-4 text-white leading-tight focus:outline-none focus:bg-gray-700 focus:border-teal-500" 
                            type="text" placeholder="Ketikkan feedback mengenai praktikum / asisten saat ini ..."/>
                    </div>
                  </div>
                  <div class="w-full h-24 flex mt-4 mb-8">
                    <div class="w-1/2 h-full mx-auto p-4 hover:p-5 cursor-pointer animation-enable-short"
                        v-on:click="finishPraktikum()">
                      <div class="w-full h-full flex rounded-full font-overpass-bold text-xl text-white pt-1 bg-green-600">
                        <div class="m-auto">
                          SELESAI
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  </div>
</template>


<script setup>
import { toRef } from 'vue';

const props = defineProps({
  currentPraktikum: { type: Object, required: true },
  currentModul: { type: Object, required: true },
  isPollingEnabled: { type: Boolean, default: false },
  pollingComplete: { type: Boolean, default: false },
  pilihanPolling: { type: Array, default: () => [] },
  nilaiTa: { type: [String, Number], default: '' },
  nilaiTk: { type: [String, Number], default: '' },
  programmingQuote: { type: String, default: 'nothing' },
  quoteAuthor: { type: String, default: '' },
  soalTa: { type: Array, default: () => [] },
  jawabanTa: { type: Array, default: () => [] },
  soalTk: { type: Array, default: () => [] },
  jawabanTk: { type: Array, default: () => [] },
  soalJurnal: { type: Array, default: () => [] },
  jawabanJurnal: { type: Array, default: () => [] },
  soalFitb: { type: Array, default: () => [] },
  jawabanFitb: { type: Array, default: () => [] },
  soalMandiri: { type: Array, default: () => [] },
  jawabanMandiri: { type: Array, default: () => [] },
  soalRunmod: { type: Array, default: () => [] },
  jawabanRunmod: { type: Array, default: () => [] },
  chosenJawaban: { type: Array, default: () => [] },
  allAsisten: { type: Array, default: () => [] },
  allAsistenPolling: { type: Array, default: () => [] },
  allPolling: { type: Array, default: () => [] },
  isRunmod: { type: Boolean, default: false },
  generateScoreText: { type: Function, default: () => '' },
  onChooseJawaban: { type: Function, default: () => {} },
  onSetPilihanPolling: { type: Function, default: () => {} },
  onSavePolling: { type: Function, default: () => {} },
  onFinishPraktikum: { type: Function, default: () => {} },
});

const chosenAsisten = defineModel('chosenAsisten', { default: 0 });
const modulShown = defineModel('modulShown', { default: false });
const showNilaiTA = defineModel('showNilaiTa', { default: false });
const showNilaiTK = defineModel('showNilaiTk', { default: false });

const current_praktikum = toRef(props, 'currentPraktikum');
const current_modul = toRef(props, 'currentModul');
const pilihanPolling = toRef(props, 'pilihanPolling');
const nilaiTA = toRef(props, 'nilaiTa');
const nilaiTK = toRef(props, 'nilaiTk');
const programmingQuote = toRef(props, 'programmingQuote');
const quoteAuthor = toRef(props, 'quoteAuthor');
const soalTA = toRef(props, 'soalTa');
const jawabanTA = toRef(props, 'jawabanTa');
const soalTK = toRef(props, 'soalTk');
const jawabanTK = toRef(props, 'jawabanTk');
const soalJurnal = toRef(props, 'soalJurnal');
const jawabanJurnal = toRef(props, 'jawabanJurnal');
const soalFitb = toRef(props, 'soalFitb');
const jawabanFitb = toRef(props, 'jawabanFitb');
const soalMandiri = toRef(props, 'soalMandiri');
const jawabanMandiri = toRef(props, 'jawabanMandiri');
const soalRunmod = toRef(props, 'soalRunmod');
const jawabanRunmod = toRef(props, 'jawabanRunmod');
const chosenJawaban = toRef(props, 'chosenJawaban');
const allAsisten = toRef(props, 'allAsisten');
const allAsistenPolling = toRef(props, 'allAsistenPolling');
const allPolling = toRef(props, 'allPolling');
const isPollingEnabled = toRef(props, 'isPollingEnabled');
const pollingComplete_mutable = toRef(props, 'pollingComplete');
const isRunmod = toRef(props, 'isRunmod');

function savePolling() {
  props.onSavePolling?.();
}

function setPilihanPolling(index, asistenId) {
  props.onSetPilihanPolling?.(index, asistenId);
}

function chooseJawaban(jawaban, soalId, soalIndex) {
  props.onChooseJawaban?.(jawaban, soalId, soalIndex);
}

function finishPraktikum() {
  props.onFinishPraktikum?.();
}

function choiceClass(index, option) {
  return chosenJawaban.value[index]?.jawaban === option
    ? 'bg-green-500 text-white hover:bg-green-500'
    : 'bg-green-200 hover:bg-green-300';
}
</script>
