<template>
  <div>
    <div
      v-for="(question, questionIndex) in questions"
      :key="questionKeyValue(question, questionIndex)"
      class="w-full flex-row h-auto"
    >
      <div class="w-full h-auto flex my-10">
        <div class="h-full w-12 flex font-merri-bold text-xl">
          <div class="m-auto w-auto h-auto">{{ displayNumber(questionIndex) }}</div>
        </div>
        <div class="h-12 px-1 w-4">
          <div class="h-full w-full bg-gray-900" />
        </div>
        <div class="h-full w-16full break-words whitespace-pre-wrap flex px-2 font-monda text-2xl">
          <span>{{ resolveQuestionText(question, questionIndex) }}</span>
        </div>
      </div>

      <div v-if="isTextareaMode" class="w-full h-auto flex px-5">
        <textarea
          :value="getAnswerValue(questionIndex)"
          :cols="cols"
          :rows="rows"
          class="font-overpass-mono-bold resize-none text-xl bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full h-full py-4 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500"
          type="text"
          :placeholder="placeholder"
          :autocomplete="secureText ? 'off' : null"
          @input="updateAnswerValue(questionIndex, $event.target.value)"
          @selectstart="handleSecureEvent"
          @paste="handleSecureEvent"
          @copy="handleSecureEvent"
          @cut="handleSecureEvent"
          @drag="handleSecureEvent"
          @drop="handleSecureEvent"
        />
      </div>

      <div v-else-if="isOptionsMode" class="w-full h-auto flex-row">
        <div
          v-for="(option, optionIndex) in resolveOptions(questionIndex)"
          :key="optionIndex"
          class="w-full px-8 my-2 h-auto cursor-pointer flex"
          @click="onSelectOption(option, question, questionIndex, optionIndex)"
        >
          <div
            class="w-full px-4 py-2 rounded-large font-overpass-bold break-words whitespace-pre-wrap text-xl"
            :class="[
              optionClass(questionIndex, optionIndex),
              selectedAnswers[`${questionIndex}-${optionIndex}`] 
                ? 'bg-green-500 text-white' 
                : 'bg-green-200 hover:bg-green-300'
            ]"
          >
            <span>{{ option }}</span>
          </div>
        </div>
      </div>

      <slot
        name="after-question"
        :question="question"
        :questionIndex="questionIndex"
        :displayNumber="displayNumber(questionIndex)"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'QuestionBlock',
  props: {
    questions: {
      type: Array,
      required: true,
    },
    answers: {
      type: Array,
      default: () => [],
    },
    mode: {
      type: String,
      default: 'textarea',
      validator: (value) => ['textarea', 'options'].includes(value),
    },
    answerKey: {
      type: String,
      default: 'jawaban',
    },
    questionKey: {
      type: String,
      default: 'soal',
    },
    questionTextAccessor: {
      type: Function,
      default: null,
    },
    numberingOffset: {
      type: Number,
      default: 0,
    },
    answerIndexOffset: {
      type: Number,
      default: 0,
    },
    optionsList: {
      type: Array,
      default: () => [],
    },
    placeholder: {
      type: String,
      default: 'Ketik jawabanmu disini ...',
    },
    rows: {
      type: [Number, String],
      default: 10,
    },
    cols: {
      type: [Number, String],
      default: 30,
    },
    secureText: {
      type: Boolean,
      default: false,
    },
    optionClassPrefix: {
      type: String,
      default: 'jawaban-',
    },
    selectedAnswers: {
      type: Object,
      default: () => ({}),
    },
    onAnswerChange: {
      type: Function,
      default: null,
    },
    onOptionSelect: {
      type: Function,
      default: null,
    },
    trackBy: {
      type: Function,
      default: null,
    },
  },
  computed: {
    isTextareaMode() {
      return this.mode === 'textarea';
    },
    isOptionsMode() {
      return this.mode === 'options';
    },
  },
  methods: {
    displayNumber(index) {
      return this.numberingOffset + index + 1;
    },
    resolveAnswerIndex(index) {
      return index + this.answerIndexOffset;
    },
    getAnswerValue(index) {
      const answerEntry = this.answers[this.resolveAnswerIndex(index)];
      if (!answerEntry || typeof answerEntry !== 'object') {
        return '';
      }
      const value = answerEntry[this.answerKey];
      return typeof value === 'string' ? value : value ?? '';
    },
    updateAnswerValue(index, value) {
      if (typeof this.onAnswerChange === 'function') {
        this.onAnswerChange({
          questionIndex: index,
          answerIndex: this.resolveAnswerIndex(index),
          value,
          question: this.questions[index],
        });
      }
    },
    resolveQuestionText(question, index) {
      if (typeof this.questionTextAccessor === 'function') {
        return this.questionTextAccessor(question, index);
      }
      if (question && Object.prototype.hasOwnProperty.call(question, this.questionKey)) {
        return question[this.questionKey];
      }
      return '';
    },
    resolveOptions(index) {
      const options = this.optionsList[index];
      return Array.isArray(options) ? options : [];
    },
    onSelectOption(option, question, questionIndex, optionIndex) {
      if (typeof this.onOptionSelect === 'function') {
        this.onOptionSelect({
          option,
          question,
          questionIndex,
          optionIndex,
          answerIndex: this.resolveAnswerIndex(questionIndex),
        });
      }
    },
    optionClass(questionIndex, optionIndex) {
      return `${this.optionClassPrefix}${questionIndex}${optionIndex}`;
    },
    questionKeyValue(question, index) {
      if (typeof this.trackBy === 'function') {
        return this.trackBy(question, index);
      }
      if (!question || typeof question !== 'object') {
        return index;
      }
      return (
        question.id ??
        question.soal_id ??
        question.question_id ??
        question.soalId ??
        index);
    },
    handleSecureEvent(event) {
      if (this.secureText) {
        event.preventDefault();
      }
    },
  },
};
</script>
