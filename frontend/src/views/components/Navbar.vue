<template>
    <header id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 rounded-lg bg-gradient-blue flex items-center justify-center">
                    <i class="fa-solid fa-cubes text-white text-xl"></i>
                </div>
                <span class="text-xl font-bold text-primary">REBUILD</span>
            </div>
            <!-- 桌面导航 -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#features" class="font-medium hover:text-primary transition-colors">功能</a>
                <a href="#designer" class="font-medium hover:text-primary transition-colors">设计器</a>
                <a href="#process" class="font-medium hover:text-primary transition-colors">工作流</a>
                <a href="#pricing" class="font-medium hover:text-primary transition-colors">价格</a>
                <a href="#faq" class="font-medium hover:text-primary transition-colors">常见问题</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="/login" class="px-4 py-2 text-primary font-medium hover:bg-primary/5 rounded-lg transition-colors">登录</a>
                <a href="/register" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-colors shadow-md hover:shadow-lg">免费试用</a>
                <button id="menu-toggle" class="md:hidden text-dark hover:text-primary" @click="toggleMobileMenu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        <!-- 移动端导航菜单 -->
        <div :class="{ 'hidden':!isMobileMenuOpen, 'md:hidden': true }" id="mobile-menu" class="bg-white border-t shadow-lg">
            <div class="container mx-auto px-4 py-3 flex flex-col space-y-3">
                <a href="#features" class="py-2 font-medium hover:text-primary transition-colors">功能</a>
                <a href="#designer" class="py-2 font-medium hover:text-primary transition-colors">设计器</a>
                <a href="#process" class="py-2 font-medium hover:text-primary transition-colors">工作流</a>
                <a href="#pricing" class="py-2 font-medium hover:text-primary transition-colors">价格</a>
                <a href="#faq" class="py-2 font-medium hover:text-primary transition-colors">常见问题</a>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref } from 'vue'

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
    isMobileMenuOpen.value =!isMobileMenuOpen.value
}
</script>

<style scoped>
.bg-gradient-blue {
    background: linear-gradient(135deg, #165DFF 0%, #0040C9 100%);
}
</style>