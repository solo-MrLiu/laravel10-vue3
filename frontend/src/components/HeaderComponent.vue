<template>
    <header class="font-inter">
        <!-- 桌面端导航 -->
        <div class="hidden md:flex items-center justify-between px-6 py-4 bg-white shadow-sm sticky top-0 z-50">
<!--            <div class="flex items-center">-->
<!--                <i class="fa-solid fa-cubes text-primary text-2xl mr-2"></i>-->
<!--                <span class="text-xl font-semibold">FlowManager</span>-->
<!--            </div>-->
<!--            <div class="flex items-center space-x-4">-->

<!--                <nav class="ml-10">-->
<!--                    <ul class="flex space-x-8">-->
<!--                        <li><a href="#" class="text-primary font-medium">首页</a></li>-->
<!--                        <li>-->
<!--                            <a href="#" class="text-gray-600 hover:text-primary transition-colors">流程管理</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="#" class="text-gray-600 hover:text-primary transition-colors">表单设计</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="#" class="text-gray-600 hover:text-primary transition-colors">数据统计</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="#" class="text-gray-600 hover:text-primary transition-colors">系统设置</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </nav>-->
<!--            </div>-->
            <div class="flex items-center space-x-6">
<!--                <div class="relative">-->
<!--                    <input-->
<!--                            type="text"-->
<!--                            placeholder="搜索..."-->
<!--                            class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all w-64"-->
<!--                    />-->
<!--                    <i-->
<!--                            class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"-->
<!--                    ></i>-->
<!--                </div>-->
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-500 hover:text-primary transition-colors">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full"></span>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img
                                src="https://picsum.photos/id/1005/200/200"
                                alt="用户头像"
                                class="w-8 h-8 rounded-full object-cover border-2 border-primary"
                        />
                        <span class="font-medium">管理员</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 移动端导航 -->
        <div class="md:hidden bg-white shadow-sm sticky top-0 z-50">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center">
                    <i class="fa-solid fa-cubes text-primary text-2xl mr-2"></i>
                    <span class="text-xl font-semibold">FlowManager</span>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="toggleMobileMenu" class="p-2 text-gray-500">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div class="relative">
                        <button class="relative p-2 text-gray-500">
                            <i class="fa-solid fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 移动端菜单 -->
            <transition name="slide">
                <div v-show="mobileMenuOpen" class="bg-white border-t overflow-hidden">
                    <div class="px-4 py-3">
                        <div class="relative mb-4">
                            <input
                                    type="text"
                                    placeholder="搜索..."
                                    class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all w-full"
                            />
                            <i
                                    class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                            ></i>
                        </div>
                        <nav>
                            <ul class="space-y-3 pb-3">
                                <li>
                                    <a href="#" class="block py-2 px-3 bg-primary/10 text-primary rounded-lg"
                                    >首页</a
                                    >
                                </li>
                                <li>
                                    <a
                                            href="#"
                                            class="block py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                    >流程管理</a
                                    >
                                </li>
                                <li>
                                    <a
                                            href="#"
                                            class="block py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                    >表单设计</a
                                    >
                                </li>
                                <li>
                                    <a
                                            href="#"
                                            class="block py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                    >数据统计</a
                                    >
                                </li>
                                <li>
                                    <a
                                            href="#"
                                            class="block py-2 px-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                    >系统设置</a
                                    >
                                </li>
                                <li class="pt-2 border-t">
                                    <div class="flex items-center space-x-3 px-3">
                                        <img
                                                src="https://picsum.photos/id/1005/200/200"
                                                alt="用户头像"
                                                class="w-8 h-8 rounded-full object-cover border-2 border-primary"
                                        />
                                        <span class="font-medium">管理员</span>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </transition>
        </div>
    </header>
</template>

<script>
export default {
    name: 'HeaderComponent',
    data() {
        return {
            mobileMenuOpen: false
        };
    },
    methods: {
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        }
    }
};
</script>

<style scoped>
/* 可选：添加自定义动画 */
.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.3s ease-out;
    max-height: 200px;
}

.slide-enter-from,
.slide-leave-to {
    max-height: 0;
}
</style>
