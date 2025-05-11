<template>
    <div id="app">
        <!-- 页面内容由 Vue 渲染 -->
        <header-component />
        <sidebar-component />
        <dashboard-card />
        <process-item />
        <task-item />
        <quick-action />
    </div>
</template>

<script>
// 导入全局组件
import HeaderComponent from '@/components/HeaderComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import DashboardCard from '@/components/DashboardCard.vue';
import ProcessItem from '@/components/ProcessItem.vue';
import TaskItem from '@/components/TaskItem.vue';
import QuickAction from '@/components/QuickAction.vue';

export default {
    name: 'AuthView',
    components: {
        App,
        HeaderComponent,
        SidebarComponent,
        DashboardCard,
        ProcessItem,
        TaskItem,
        QuickAction
    }
}
</script>

<style scoped>
/* Tailwind CSS 已通过 CDN 引入，无需重复引入 */
</style>

<style global lang="postcss">
/* 自定义 Tailwind 工具类 */
@layer utilities {
    .content-auto {
        content-visibility: auto;
    }
    .transition-height {
        transition-property: height;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    .card-shadow {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(22, 93, 255, 0.15);
    }
}
</style>
