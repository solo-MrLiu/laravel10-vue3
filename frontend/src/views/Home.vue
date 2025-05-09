<template>

    <div class="app-container">

        <!-- 顶部导航栏 - 移动端和PC端共用 -->
        <header class="header bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
            <div class="container mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <el-button
                            v-if="isMobile"
                            size="small"
                            icon="ElBars"
                            circle
                            @click="toggleSidebar"
                    />
                    <el-icon class="text-primary ml-2 md:ml-0">
                        <House/>
                    </el-icon>
                    <span class="ml-2 text-lg font-bold text-gray-800 hidden sm:inline">REBUILD</span>
                </div>

                <div class="hidden md:flex items-center space-x-4 flex-1 max-w-xl mx-4">
                    <el-input
                            v-model="searchQuery"
                            placeholder="搜索..."
                            prefix-icon="ElSearch"
                            size="small"
                            clearable
                            class="w-full"
                    />
                </div>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <el-badge value="3" type="primary">
                        <el-button
                                size="small"
                                icon="ElBell"
                                circle
                                @click="showNotifications"
                        />
                    </el-badge>

                    <el-dropdown @command="handleCommand">
                        <div class="flex items-center cursor-pointer">
                            <img src="https://picsum.photos/32/32?random=1" alt="用户头像"
                                 class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                            <span class="ml-2 font-medium text-gray-700 hidden md:inline">John Doe</span>
                            <el-icon class="ml-1">
                                <CaretBottom/>
                            </el-icon>
                        </div>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item command="profile">
                                    <el-icon>
                                        <User/>
                                    </el-icon>
                                    <span>个人资料</span>
                                </el-dropdown-item>
                                <el-dropdown-item command="settings">
                                    <el-icon>
                                        <House/>
                                    </el-icon>
                                    <span>设置</span>
                                </el-dropdown-item>
                                <el-dropdown-item command="logout" divided>
                                    <el-icon>
                                        <House/>
                                    </el-icon>
                                    <span>退出登录</span>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </div>
            </div>
        </header>

        <!-- 移动端侧边栏菜单 -->
        <el-drawer
                title="菜单导航"
                :visible.sync="sidebarVisible"
                direction="ltr"
                size="260px"
                :with-header="false"
                class="bg-white shadow-md h-full fixed inset-y-0 left-0 z-50"
                @close="sidebarVisible = false"
        >
            <template #content>
                <div class="p-4 border-b flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <el-icon class="text-primary">
                            <House/>
                        </el-icon>
                        <span class="text-lg font-bold text-gray-800">REBUILD</span>
                    </div>
                    <el-button
                            size="small"
                            icon="ElArrowLeft"
                            circle
                            @click="sidebarVisible = false"
                    />
                </div>

                <el-menu
                        class="border-none"
                        router
                        :default-active="activeMenu"
                        background-color="#ffffff"
                        text-color="#303133"
                        active-text-color="#165DFF"
                >
                    <el-menu-item index="1">
                        <el-icon>
                            <House/>
                        </el-icon>
                        <span>首页</span>
                    </el-menu-item>
                    <el-menu-item index="2">
                        <el-icon>
                            <House/>
                        </el-icon>
                        <span>仪表盘</span>
                    </el-menu-item>
                    <el-menu-item index="3">
                        <el-icon>
                            <House/>
                        </el-icon>
                        <span>表单管理</span>
                    </el-menu-item>
                    <el-menu-item index="4">
                        <el-icon>
                            <House/>
                        </el-icon>
                        <span>流程设计</span>
                    </el-menu-item>
                    <el-menu-item index="5">
                        <el-icon>
                            <User/>
                        </el-icon>
                        <span>用户管理</span>
                    </el-menu-item>
                </el-menu>
            </template>
        </el-drawer>

        <!-- PC端侧边栏菜单 -->
        <aside class="sidebar fixed top-0 left-0 bottom-0 w-64 bg-white shadow-md z-40 pt-16 hidden md:block transition-all duration-300"
               :class="{ '-translate-x-full': !sidebarOpen }">
            <el-menu
                    class="border-none h-full"
                    router
                    :default-active="activeMenu"
                    background-color="#ffffff"
                    text-color="#303133"
                    active-text-color="#165DFF"
            >
                <el-menu-item index="1">
                    <el-icon>
                        <House/>
                    </el-icon>
                    <span>首页</span>
                </el-menu-item>
                <el-menu-item index="2">
                    <el-icon>
                        <House/>
                    </el-icon>
                    <span>仪表盘</span>
                </el-menu-item>
                <el-menu-item index="3">
                    <el-icon>
                        <House/>
                    </el-icon>
                    <span>表单管理</span>
                </el-menu-item>
                <el-menu-item index="4">
                    <el-icon>
                        <House/>
                    </el-icon>
                    <span>流程设计</span>
                </el-menu-item>
                <el-menu-item index="5">
                    <el-icon>
                        <User/>
                    </el-icon>
                    <span>用户管理</span>
                </el-menu-item>
            </el-menu>
        </aside>

        <!-- 主内容区域 -->
        <main class="main-content pt-16 pb-10 md:ml-64 transition-all duration-300">
            <div class="container mx-auto px-4 py-6">
                <div class="mb-6">
                    <h1 class="text-[clamp(1.5rem,3vw,2.5rem)] font-bold text-gray-800">欢迎回来，John</h1>
                    <p class="text-gray-500">今天是 {{ currentDate }}</p>
                </div>

                <!-- 数据卡片 -->
                <el-row :gutter="20">
                    <el-col :span="24" :md-span="12" :lg-span="6" class="mb-4">
                        <el-card shadow="hover" class="bg-white rounded-xl p-6 border border-gray-100 h-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 font-medium">待办事项</h3>
                                <el-tag type="primary" effect="light">
                                    <el-icon>
                                        <Document/>
                                    </el-icon>
                                </el-tag>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">5</p>
                                    <p class="text-sm text-green-500 flex items-center">
                                        <el-icon class="mr-1">
                                            <ArrowUp/>
                                        </el-icon>
                                        2 个新增
                                    </p>
                                </div>
                                <div class="w-16 h-16">
                                    <el-progress type="circle" :percentage="65" color="#3c9ee5" width="60"/>
                                </div>
                            </div>
                        </el-card>
                    </el-col>

                    <el-col :span="24" :md-span="12" :lg-span="6" class="mb-4">
                        <el-card shadow="hover" class="bg-white rounded-xl p-6 border border-gray-100 h-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 font-medium">今日访问量</h3>
                                <el-tag type="success" effect="light">
                                    <el-icon>
                                        <House/>
                                    </el-icon>
                                </el-tag>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">120</p>
                                    <p class="text-sm text-green-500 flex items-center">
                                        <el-icon class="mr-1">
                                            <ArrowUp/>
                                        </el-icon>
                                        12%
                                    </p>
                                </div>
                                <div class="w-16 h-16">
                                    <el-progress type="circle" :percentage="80" color="#4CC790" width="60"/>
                                </div>
                            </div>
                        </el-card>
                    </el-col>

                    <el-col :span="24" :md-span="12" :lg-span="6" class="mb-4">
                        <el-card shadow="hover" class="bg-white rounded-xl p-6 border border-gray-100 h-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 font-medium">新消息</h3>
                                <el-tag type="warning" effect="light">
                                    <el-icon>
                                        <House/>
                                    </el-icon>
                                </el-tag>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">3</p>
                                    <p class="text-sm text-red-500 flex items-center">
                                        <el-icon class="mr-1">
                                            <ArrowUp/>
                                        </el-icon>
                                        1 个新增
                                    </p>
                                </div>
                                <div class="w-16 h-16">
                                    <el-progress type="circle" :percentage="40" color="#FF9F1E" width="60"/>
                                </div>
                            </div>
                        </el-card>
                    </el-col>

                    <el-col :span="24" :md-span="12" :lg-span="6" class="mb-4">
                        <el-card shadow="hover" class="bg-white rounded-xl p-6 border border-gray-100 h-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 font-medium">已完成项目</h3>
                                <el-tag type="info" effect="light">
                                    <el-icon>
                                        <House/>
                                    </el-icon>
                                </el-tag>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">12</p>
                                    <p class="text-sm text-green-500 flex items-center">
                                        <el-icon class="mr-1">
                                            <ArrowUp/>
                                        </el-icon>
                                        3 个新增
                                    </p>
                                </div>
                                <div class="w-16 h-16">
                                    <el-progress type="circle" :percentage="90" color="#8A4DFF" width="60"/>
                                </div>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>

                <!-- 最近活动和任务 -->
                <el-row :gutter="20" class="mt-6">
                    <el-col :span="24" :lg-span="16" class="mb-6">
                        <el-card shadow="hover" class="bg-white rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800">最近活动</h3>
                                <el-button text type="primary" size="small" @click="viewAllActivities">查看全部
                                </el-button>
                            </div>

                            <el-timeline>
                                <el-timeline-item
                                        v-for="(activity, index) in recentActivities"
                                        :key="index"
                                        :timestamp="activity.time"
                                        :color="activity.color"
                                >
                                    <div class="flex items-start">
                                        <el-avatar :size="40" :icon="() => h(activity.icon)"
                                                   :style="{ backgroundColor: activity.bgColor }"/>
                                        <div class="ml-3">
                                            <p class="text-sm">
                                                <span class="font-medium text-gray-800">{{ activity.title }}</span>
                                                {{ activity.content }}
                                            </p>
                                        </div>
                                    </div>
                                </el-timeline-item>
                            </el-timeline>
                        </el-card>
                    </el-col>

                    <el-col :span="24" :lg-span="8">
                        <el-card shadow="hover" class="bg-white rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800">待办任务</h3>
                                <el-button text type="primary" size="small" @click="manageTasks">管理任务</el-button>
                            </div>

                            <el-list :data="tasks" border>
                                <template #item="{ item }">
                                    <el-card class="task-card" :style="{ borderLeftColor: item.priorityColor }">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-800">{{ item.title }}</p>
                                                <p class="text-xs text-gray-500 mt-1">截止日期: {{ item.deadline }}</p>
                                            </div>
                                            <el-tag :type="item.priorityType" size="small">{{ item.priority }}</el-tag>
                                        </div>
                                    </el-card>
                                </template>
                            </el-list>

                            <el-button
                                    class="w-full mt-4"
                                    type="primary"
                                    size="small"
                                    icon="ElPlus"
                                    @click="addTask"
                            >
                                添加新任务
                            </el-button>
                        </el-card>
                    </el-col>
                </el-row>
            </div>
        </main>
    </div>
</template>

<script setup>
import {ref, onMounted, h, computed, onUnmounted} from 'vue';
import {
    House, User,
    CaretBottom, ArrowUp,
    Document,
    Check, Warning,
} from '@element-plus/icons-vue';//Cube,  BarChart, Table, Cog,Bars, Bell,Search,Eye, Envelope, Project, Exit, Plus,Time, ArrowUpBold

// 响应式状态
const sidebarVisible = ref(true);
const sidebarOpen = ref(true);
const activeMenu = ref('1');
const searchQuery = ref('');
const currentDate = ref('');

// 设备检测
const isMobile = computed(() => {
    return window.innerWidth < 768;
});

// 初始化数据
const recentActivities = ref([
    {
        title: '创建了新表单',
        content: '员工信息登记表',
        time: '2分钟前',
        icon: Document,
        color: '#165DFF',
        bgColor: '#e6f4ff'
    },
    {
        title: '完成了任务',
        content: '审批采购申请 #12345',
        time: '30分钟前',
        icon: Check,
        color: '#00B42A',
        bgColor: '#f0f9eb'
    },
    {
        title: '待处理任务',
        content: '审核新用户注册申请',
        time: '2小时前',
        icon: House,
        color: '#FF7D00',
        bgColor: '#fff7e6'
    },
    {
        title: '系统提醒',
        content: '服务器磁盘空间不足',
        time: '昨天',
        icon: Warning,
        color: '#F53F3F',
        bgColor: '#ffeeee'
    }
]);

const tasks = ref([
    {
        title: '审批采购申请 #12345',
        deadline: '今天',
        priority: '紧急',
        priorityType: 'danger',
        priorityColor: '#F53F3F'
    },
    {
        title: '审核新用户注册申请',
        deadline: '明天',
        priority: '中等',
        priorityType: 'warning',
        priorityColor: '#FF7D00'
    },
    {title: '更新系统文档', deadline: '周五', priority: '一般', priorityType: 'info', priorityColor: '#86909C'},
    {title: '参加团队周会', deadline: '周一', priority: '一般', priorityType: 'info', priorityColor: '#86909C'}
]);

// 方法
const toggleSidebar = () => {
    if (isMobile.value) {
        sidebarVisible.value = !sidebarVisible.value;
    } else {
        sidebarOpen.value = !sidebarOpen.value;
    }
};

const showNotifications = () => {
    console.log('显示通知');
};

const handleCommand = (command) => {
    switch (command) {
        case 'profile':
            console.log('查看个人资料');
            break;
        case 'settings':
            console.log('打开设置');
            break;
        case 'logout':
            console.log('退出登录');
            break;
    }
};

const viewAllActivities = () => {
    console.log('查看全部活动');
};

const manageTasks = () => {
    console.log('管理任务');
};

const addTask = () => {
    console.log('添加新任务');
};

// 初始化
onMounted(() => {
    const options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    currentDate.value = new Date().toLocaleDateString('zh-CN', options);

    // 监听窗口大小变化，调整布局



    window.addEventListener('resize', handleResize);
    handleResize(); // 初始化调用一次
});

// 处理窗口大小变化
const handleResize = () => {


    if (isMobile.value) {
        sidebarOpen.value = false; // 移动端默认隐藏侧边栏
    } else {
        sidebarOpen.value = true; // 桌面端默认显示侧边栏
        sidebarVisible.value = false; // 确保移动端抽屉关闭
    }
};

// 组件销毁时移除事件监听
onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});


</script>

<style scoped>
.app-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.header {
    height: 64px;
}

.sidebar {
    overflow-y: auto;
}

.main-content {
    flex-grow: 1;
    background-color: #f5f7fa;
}

.task-card {
    border-left-width: 4px;
    margin-bottom: 10px;
    padding: 10px;
    background-color: rgba(242, 243, 245, 0.3);
    border-radius: 0 4px 4px 0;
}

.task-card:last-child {
    margin-bottom: 0;
}

/* 移动端优化 */
@media (max-width: 768px) {
    .el-card {
        padding: 1rem !important;
    }

    .el-timeline-item {
        padding: 0.5rem 0;
    }

    .el-avatar {
        width: 32px !important;
        height: 32px !important;
    }

    .el-button--text {
        padding: 0 !important;
    }

    .el-input {
        max-width: 120px !important;
    }
}
</style>