<template>
    <div class="star-rating" :class="{ 'pointer-events-none': readOnly }">
        <button
            v-for="(star, index) in stars"
            :key="index"
            type="button"
            class="relative inline-flex items-center justify-center"
            :style="buttonStyle"
            @mousemove="onHover($event, index)"
            @mouseleave="onLeave"
            @click="onSelect($event, index)"
        >
            <font-awesome-icon
                :icon="['fas', 'star']"
                class="text-slate-300"
                :style="iconStyle"
            />
            <font-awesome-icon
                :icon="['fas', 'star']"
                class="absolute top-0 left-0 text-yellow-400"
                :style="filledStyle(index)"
            />
        </button>
        <span v-if="showRating" class="ml-3 font-semibold">
            {{ displayRating.toFixed(fixedPoints) }}
        </span>
    </div>
</template>

<script>
import { computed, ref } from 'vue';

export default {
    name: 'StarRating',
    props: {
        modelValue: {
            type: Number,
            default: null,
        },
        rating: {
            type: Number,
            default: 0,
        },
        readOnly: {
            type: Boolean,
            default: false,
        },
        increment: {
            type: Number,
            default: 0.5,
        },
        fixedPoints: {
            type: Number,
            default: 1,
        },
        showRating: {
            type: Boolean,
            default: true,
        },
        starSize: {
            type: Number,
            default: 24,
        },
        maxRating: {
            type: Number,
            default: 5,
        },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const hoverRating = ref(null);

        const activeRating = computed(() => (hoverRating.value ?? props.modelValue ?? props.rating ?? 0));
        const displayRating = computed(() => Number(activeRating.value) || 0);

        const stars = computed(() => Array.from({ length: props.maxRating }));

        const iconStyle = computed(() => ({
            width: `${props.starSize}px`,
            height: `${props.starSize}px`,
        }));

        const buttonStyle = computed(() => ({
            width: `${props.starSize}px`,
            height: `${props.starSize}px`,
        }));

        const clamp = (value) => {
            const min = 0;
            const max = props.maxRating;
            return Math.min(Math.max(value, min), max);
        };

        const roundToIncrement = (value) => {
            if (props.increment <= 0) {
                return value;
            }

            const rounded = Math.round(value / props.increment) * props.increment;
            const precision = Math.max(0, props.fixedPoints);
            return Number(clamp(rounded).toFixed(precision));
        };

        const calculateValue = (event, index) => {
            const bounding = event.currentTarget.getBoundingClientRect();
            const ratio = (event.clientX - bounding.left) / bounding.width;
            const value = index + ratio + 1e-6; // prevent 0 when at far left
            return roundToIncrement(index + ratio + 1);
        };

        const filledStyle = (index) => {
            const value = displayRating.value - index;
            const percentage = Math.max(0, Math.min(1, value)) * 100;
            return {
                width: `${props.starSize}px`,
                height: `${props.starSize}px`,
                clipPath: `inset(0 ${100 - percentage}% 0 0)`,
            };
        };

        const onHover = (event, index) => {
            if (props.readOnly) {
                return;
            }

            hoverRating.value = calculateValue(event, index);
        };

        const onLeave = () => {
            hoverRating.value = null;
        };

        const onSelect = (event, index) => {
            if (props.readOnly) {
                return;
            }

            const value = calculateValue(event, index);
            emit('update:modelValue', value);
            hoverRating.value = null;
        };

        return {
            stars,
            filledStyle,
            iconStyle,
            buttonStyle,
            onHover,
            onLeave,
            onSelect,
            displayRating,
        };
    },
};
</script>

<style scoped>
.star-rating {
    display: inline-flex;
    align-items: center;
}

.star-rating button {
    color: inherit;
}

.star-rating button:focus-visible {
    outline: 2px solid #facc15;
    outline-offset: 2px;
}
</style>
