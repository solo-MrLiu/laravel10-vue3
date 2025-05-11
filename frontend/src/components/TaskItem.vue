<template>
  <div class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
    <div class="flex items-center justify-between mb-2">
      <h4 class="font-medium">{{ task.name }}</h4>
      <span
        class="text-xs px-2 py-0.5 rounded-full"
        :class="{
          'bg-danger/10 text-danger': task.priorityClass === 'danger',
          'bg-warning/10 text-warning': task.priorityClass === 'warning',
          'bg-success/10 text-success': task.priorityClass === 'success',
          'bg-info/10 text-info': task.priorityClass === 'info'
        }"
        :style="{ color: task.priorityColor }"
      >
        {{ task.priority }}
      </span>
    </div>

    <p class="text-gray-500 text-sm mb-3">{{ task.description }}</p>

    <div class="flex justify-end space-x-2">
      <button
        class="py-1 px-3 text-sm border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors"
      >
        拒绝
      </button>
      <button
        class="py-1 px-3 text-sm bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors"
      >
        {{ task.action }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TaskItem',
  props: {
    task: {
      type: Object,
      required: true,
      default: () => ({
        name: '任务名称',
        description: '这里是任务描述',
        priority: '高',
        priorityClass: 'danger',
        priorityColor: '#F53F3F',
        action: '处理'
      })
    }
  }
};
</script>

<style scoped>
/* 可选：添加额外样式 */
</style>
