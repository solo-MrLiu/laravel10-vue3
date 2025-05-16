<template>
  <!-- 顶部导航栏 -->
        <Header />
  <!-- 侧边栏 -->
        <Sidebar />
  <!-- 缓存需要保持的组件 -->
    <keep-alive>
        <router-view v-if="$route.meta.keepalive"></router-view>
    </keep-alive>
  <!-- 不缓存的组件 -->
    <router-view v-if="!$route.meta.keepalive"></router-view>

</template>

<script setup>
import Header from '@/components/layout/Header.vue'
import Sidebar from '@/components/admin/DynamicLoading.vue'
</script>