<template>
  <div
    class="flex flex-col items-center p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
    @click="handleClick"
  >
    <div
      class="w-12 h-12 rounded-lg flex items-center justify-center"
      :style="{ backgroundColor: bgColor }"
    >
      <i :class="icon" :style="{ color: color }" class="text-xl"></i>
    </div>
    <span class="text-sm text-center mt-2">{{ title }}</span>
  </div>
</template>

<script>
export default {
  name: 'QuickAction',
  props: {
    icon: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: '#165DFF'
    },
    bgColor: {
      type: String,
      default: '#F2F3F5'
    }
  },
  methods: {
    handleClick() {
      this.$emit('click'); // 支持点击事件传递
    }
  }
};
</script>

<style scoped>
/* 可选：添加额外样式 */
</style>
