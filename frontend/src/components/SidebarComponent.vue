<template>

  <aside
    class="hidden md:block w-64 bg-white shadow-sm h-[calc(100vh-64px)] sticky top-[64px] overflow-y-auto transition-all duration-300"
  >
      <div class="flex items-center">
      <i class="fa-solid fa-cubes text-primary text-2xl mr-2"></i>
      <span class="text-xl font-semibold">FlowManager</span>
  </div>
    <div class="p-4">
      <div class="flex items-center justify-between mb-6">
        <h3 class="font-semibold text-lg">工作区</h3>
        <button class="p-1 text-gray-400 hover:text-primary transition-colors">
          <i class="fa-solid fa-ellipsis-h"></i>
        </button>
      </div>

      <nav class="space-y-1 mb-8">
        <a href="#" class="flex items-center py-2 px-3 text-primary bg-primary/10 rounded-lg">
          <i class="fa-solid fa-home w-5 text-center mr-3"></i>
          <span>工作台</span>
        </a>
        <a
          href="#"
          class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <i class="fa-solid fa-list-ul w-5 text-center mr-3"></i>
          <span>我的流程</span>
        </a>
        <a
          href="#"
          class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <i class="fa-solid fa-clock-rotate-left w-5 text-center mr-3"></i>
          <span>待办任务</span>
          <span class="ml-auto bg-danger text-white text-xs px-2 py-0.5 rounded-full">5</span>
        </a>
        <a
          href="#"
          class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <i class="fa-solid fa-check-circle w-5 text-center mr-3"></i>
          <span>已完成任务</span>
        </a>
        <a
          href="#"
          class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <i class="fa-solid fa-share-from-square w-5 text-center mr-3"></i>
          <span>我发起的</span>
        </a>
        <a
          href="#"
          class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <i class="fa-solid fa-users w-5 text-center mr-3"></i>
          <span>我参与的</span>
        </a>
        <a
          href="#"
          class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <i class="fa-solid fa-star w-5 text-center mr-3"></i>
          <span>收藏的流程</span>
        </a>
      </nav>

      <div class="mb-8">
        <h3 class="font-semibold text-lg mb-4">流程分类</h3>
        <nav class="space-y-1">
          <a
            href="#"
            class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <i class="fa-solid fa-file-alt w-5 text-center mr-3"></i>
            <span>审批流程</span>
          </a>
          <a
            href="#"
            class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <i class="fa-solid fa-cogs w-5 text-center mr-3"></i>
            <span>业务流程</span>
          </a>
          <a
            href="#"
            class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <i class="fa-solid fa-calendar-check w-5 text-center mr-3"></i>
            <span>日程流程</span>
          </a>
          <a
            href="#"
            class="flex items-center py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <i class="fa-solid fa-people-carry-box w-5 text-center mr-3"></i>
            <span>协作流程</span>
          </a>
        </nav>
      </div>

      <div>
        <h3 class="font-semibold text-lg mb-4">快捷操作</h3>
        <button
          class="w-full py-2 px-4 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center"
        >
          <i class="fa-solid fa-plus mr-2"></i>
          <span>新建流程</span>
        </button>
      </div>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'SidebarComponent'
};
</script>

<style scoped>
/* 可选：添加额外样式 */
</style>
