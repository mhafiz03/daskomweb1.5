<template>
  <div class="w-full h-full flex">
    <ChartView class="w-full h-full p-4" :chartdata="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { toRef } from 'vue';
import ChartView from '../../ChartView.vue';

const props = defineProps({
  chartData: { type: Object, default: () => ({ labels: [], datasets: [] }) },
});

const chartData = toRef(props, 'chartData');

const chartOptions = {
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
  },
};
</script>
