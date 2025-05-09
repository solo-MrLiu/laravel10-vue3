<template>
    <section class="pt-32 pb-20 md:pt-40 md:pb-32 bg-gradient-to-b from-white to-blue-50">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-[clamp(2rem,5vw,3.5rem)] font-bold leading-tight text-dark mb-6">
                    无需编码，<span class="text-primary">快速构建</span>企业级应用
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-xl">
                    REBUILD低代码平台让业务人员和开发者共同协作，通过可视化界面快速创建、部署和迭代企业应用，缩短开发周期，降低技术门槛。
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="/register" class="px-8 py-4 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center">
                        开始免费试用 <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#demo" class="px-8 py-4 border border-gray-300 text-dark font-medium rounded-lg hover:bg-gray-50 transition-all flex items-center justify-center group">
                        <i class="fa-regular fa-circle-play text-primary mr-2 group-hover:scale-110 transition-transform"></i> 观看演示
                    </a>
                </div>
                <div class="mt-10 flex items-center space-x-6">
                    <div class="flex -space-x-2">
                        <img src="https://picsum.photos/40/40?random=1" alt="用户头像" class="w-10 h-10 rounded-full border-2 border-white">
                        <img src="https://picsum.photos/40/40?random=2" alt="用户头像" class="w-10 h-10 rounded-full border-2 border-white">
                        <img src="https://picsum.photos/40/40?random=3" alt="用户头像" class="w-10 h-10 rounded-full border-2 border-white">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-primary/10 flex items-center justify-center text-primary text-xs font-medium">+500</div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-bold text-dark">500+</span> 企业正在使用
                    </div>
                </div>
            </div>
            <div class="md:w-1/2 relative">
                <div class="relative z-10 rounded-xl overflow-hidden shadow-2xl transform md:rotate-1 hover:rotate-0 transition-transform duration-500">
                    <img src="https://picsum.photos/800/500?random=10" alt="REBUILD平台界面" class="w-full h-auto">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent flex items-end">
                        <div class="p-6 text-white">
                            <h3 class="font-bold text-xl mb-2">可视化设计，所见即所得</h3>
                            <p class="text-sm opacity-90">无需编写代码，拖拽组件即可完成页面设计</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-secondary/20 rounded-full filter blur-2xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-primary/20 rounded-full filter blur-2xl"></div>
            </div>
        </div>
    </section>
</template>

<script setup>
// 这里可以添加英雄区的逻辑，如果有的话
</script>

<style scoped>
/* 英雄区样式 */
</style>