<template>
    <button
        type="button"
        class="relative inline-flex items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        :class="modelValue ? 'bg-green-500' : 'bg-gray-300'"
        :style="{ width: `${width}px`, height: `${height}px` }"
        @click="toggle"
    >
        <span
            v-if="labels"
            class="absolute inset-0 flex items-center justify-center text-white font-semibold select-none"
            :style="{ fontSize: `${fontSize}px` }"
        >
            {{ modelValue ? trueLabel : falseLabel }}
        </span>
        <span
            class="absolute rounded-full bg-white shadow transform transition-transform duration-200"
            :style="{
                width: `${thumbSize}px`,
                height: `${thumbSize}px`,
                left: modelValue ? `${width - thumbSize - inset}px` : `${inset}px`,
            }"
        />
    </button>
</template>

<script>
export default {
    name: 'ToggleSwitch',
    props: {
        modelValue: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        width: {
            type: Number,
            default: 56,
        },
        height: {
            type: Number,
            default: 30,
        },
        inset: {
            type: Number,
            default: 4,
        },
        labels: {
            type: Boolean,
            default: false,
        },
        trueLabel: {
            type: String,
            default: 'ON',
        },
        falseLabel: {
            type: String,
            default: 'OFF',
        },
        fontSize: {
            type: Number,
            default: 14,
        },
    },
    emits: ['update:modelValue', 'change'],
    computed: {
        thumbSize() {
            return this.height - this.inset * 2;
        },
    },
    methods: {
        toggle() {
            if (this.disabled) {
                return;
            }

            const next = !this.modelValue;
            this.$emit('update:modelValue', next);
            this.$emit('change', next);
        },
    },
};
</script>
