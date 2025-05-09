<template>
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-medium rounded-full mb-4">核心功能</span>
                <h2 class="text-[clamp(1.8rem,4vw,2.5rem)] font-bold text-dark mb-6">强大功能，满足企业级需求</h2>
                <p class="text-gray-600 text-lg">REBUILD提供丰富的功能模块，帮助企业快速搭建和管理各类应用系统</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- 功能卡片1 -->
                <div class="bg-white rounded-xl shadow-md p-8 card-hover">
                    <div class="w-14 h-14 rounded-lg bg-primary/10 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-paint-brush text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">表单设计器</h3>
                    <p class="text-gray-600 mb-4">通过拖放操作创建复杂表单，支持字段级验证、动态显示规则和数据绑定。</p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>可视化拖拽设计</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>丰富的字段类型</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>实时表单预览</span>
                        </li>
                    </ul>
                    <a href="#designer" class="text-primary font-medium flex items-center hover:underline">
                        了解更多 <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <!-- 功能卡片2 -->
                <div class="bg-white rounded-xl shadow-md p-8 card-hover">
                    <div class="w-14 h-14 rounded-lg bg-primary/10 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-sitemap text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">流程设计器</h3>
                    <p class="text-gray-600 mb-4">可视化设计工作流程，支持分支、并行、条件判断等复杂流程配置。</p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>图形化流程建模</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>多条件路由配置</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>审批意见跟踪</span>
                        </li>
                    </ul>
                    <a href="#process" class="text-primary font-medium flex items-center hover:underline">
                        了解更多 <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <!-- 功能卡片3 -->
                <div class="bg-white rounded-xl shadow-md p-8 card-hover">
                    <div class="w-14 h-14 rounded-lg bg-primary/10 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-chart-bar text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">数据可视化</h3>
                    <p class="text-gray-600 mb-4">基于数据模型自动生成报表，支持自定义图表和数据筛选条件。</p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>多种图表类型</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>实时数据更新</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fa-solid fa-check text-secondary mr-2"></i>
                            <span>自定义仪表盘</span>
                        </li>
                    </ul>
                    <a href="#dashboard" class="text-primary font-medium flex items-center hover:underline">
                        了解更多 <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
// 这里可以添加核心功能区的逻辑，如果有的话
</script>

<style scoped>
.card-hover {
    transition: all 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}
</style>