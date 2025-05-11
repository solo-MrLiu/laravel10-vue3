<template>
  <div class="flex items-center p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
    <div
      class="w-10 h-10 rounded-lg flex items-center justify-center"
      :style="{ backgroundColor: process.bgColor }"
    >
      <i :class="process.icon" :style="{ color: process.color }"></i>
    </div>

    <div class="flex-1">
      <h4 class="font-medium">{{ process.name }}</h4>
      <p class="text-gray-500 text-sm mt-1">{{ process.creator }} · {{ process.time }}</p>
    </div>

    <div class="flex items-center">
      <span
        class="py-1 px-2 text-xs rounded-full mr-4"
        :class="{
          'bg-green-100 text-green-600': process.statusClass === 'success',
          'bg-blue-100 text-blue-600': process.statusClass === 'info',
          'bg-yellow-100 text-yellow-600': process.statusClass === 'warning',
          'bg-red-100 text-red-600': process.statusClass === 'danger'
        }"
        :style="{
          backgroundColor: process.statusBgColor,
          color: process.statusColor
        }"
      >
        {{ process.status }}
      </span>

      <button class="text-gray-400 hover:text-primary transition-colors">
        <i class="fa-solid fa-ellipsis-h"></i>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProcessItem',
  props: {
    process: {
      type: Object,
      required: true,
      default: () => ({
        name: '流程名称',
        icon: 'fa-solid fa-diagram-project',
        bgColor: '#F2F3F5',
        color: '#165DFF',
        creator: '创建者',
        time: '刚刚',
        status: '进行中',
        statusClass: 'success', // 可选值：success, info, warning, danger
        statusBgColor: '#E8F0FE',
        statusColor: '#165DFF'
      })
    }
  }
};
</script>

<style scoped>
/* 可选：添加额外样式 */
</style>
