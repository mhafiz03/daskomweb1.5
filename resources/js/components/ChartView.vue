<template>
    <Line :data="chartdata" :options="options" />
</template>

<script>
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Filler,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Filler);

export default {
    name: 'ChartView',
    components: {
        Line,
    },
    props: {
        chartdata: {
            type: Object,
            required: true,
        },
        options: {
            type: Object,
            default: () => ({}),
        },
    },
};
</script>
