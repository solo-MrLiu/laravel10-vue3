<template>
    <div class="flex min-h-screen">
        <!-- 侧边栏 -->
        <SidebarComponent/>

        <!-- 主体内容 -->
        <main class="flex-1 p-4 md:p-6 overflow-x-hidden">

            <HeaderComponent/>
            <!-- 欢迎区域 -->
            <section class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-[clamp(1.5rem,3vw,2.5rem)] font-bold text-dark">你好，管理员</h1>
                        <p class="text-gray-500 mt-1">今天是 {{ currentDate }}, 祝你工作愉快！</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button
                                class="py-2 px-4 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center"
                        >
                            <i class="fa-solid fa-plus mr-2"></i>
                            <span>发起流程</span>
                        </button>
                    </div>
                </div>

                <!-- 数据卡片 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <DashboardCard
                            icon="fa-solid fa-list-ol"
                            title="总流程数"
                            count="24"
                            change="+12%"
                            color="#165DFF"
                            bgColor="bg-primary/10"
                    />
                    <DashboardCard
                            icon="fa-solid fa-spinner"
                            title="运行中流程"
                            count="16"
                            change="+8%"
                            color="#FF7D00"
                            bgColor="bg-warning/10"
                    />
                    <DashboardCard
                            icon="fa-solid fa-clock"
                            title="待办任务"
                            count="5"
                            change="+3%"
                            color="#F53F3F"
                            bgColor="bg-danger/10"
                    />
                    <DashboardCard
                            icon="fa-solid fa-check-circle"
                            title="已完成任务"
                            count="38"
                            change="+23%"
                            color="#00B42A"
                            bgColor="bg-success/10"
                    />
                </div>
            </section>

            <!-- 图表区域 -->
            <section class="mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl p-5 card-shadow lg:col-span-2">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="font-semibold text-lg">流程趋势</h2>
                            <div class="flex space-x-2">
                                <button class="py-1 px-3 text-sm bg-primary/10 text-primary rounded-full">周</button>
                                <button class="py-1 px-3 text-sm text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                                    月
                                </button>
                                <button class="py-1 px-3 text-sm text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                                    年
                                </button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="processTrendChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 card-shadow">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="font-semibold text-lg">流程类型分布</h2>
                            <button class="text-gray-400 hover:text-primary transition-colors">
                                <i class="fa-solid fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="h-80">
                            <canvas id="processTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 最近流程和待办任务 -->
            <section class="mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-xl p-5 card-shadow">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="font-semibold text-lg">最近流程</h2>
                                <a href="#" class="text-primary text-sm hover:underline">查看全部</a>
                            </div>

                            <div class="space-y-4">
                                <ProcessItem v-for="process in recentProcesses" :key="process.id" :process="process"/>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl p-5 card-shadow">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="font-semibold text-lg">待办任务</h2>
                                <a href="#" class="text-primary text-sm hover:underline">查看全部</a>
                            </div>

                            <div class="space-y-4">
                                <TaskItem v-for="task in pendingTasks" :key="task.id" :task="task"/>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 快速访问 -->
            <section>
                <div class="bg-white rounded-xl p-5 card-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-semibold text-lg">快速访问</h2>
                        <button class="text-primary text-sm hover:underline">自定义</button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        <QuickAction
                                icon="fa-solid fa-file-signature"
                                title="请假申请"
                                color="#165DFF"
                                bgColor="bg-primary/10"
                        />
                        <QuickAction
                                icon="fa-solid fa-money-bill"
                                title="报销申请"
                                color="#0FC6C2"
                                bgColor="bg-secondary/10"
                        />
                        <QuickAction
                                icon="fa-solid fa-user-plus"
                                title="入职申请"
                                color="#00B42A"
                                bgColor="bg-success/10"
                        />
                        <QuickAction
                                icon="fa-solid fa-laptop"
                                title="设备申请"
                                color="#FF7D00"
                                bgColor="bg-warning/10"
                        />
                        <QuickAction
                                icon="fa-solid fa-file-contract"
                                title="合同申请"
                                color="#F53F3F"
                                bgColor="bg-danger/10"
                        />
                        <QuickAction icon="fa-solid fa-plus" title="更多" color="#86909C" bgColor="bg-info/10"/>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import HeaderComponent from '@/components/HeaderComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import DashboardCard from '@/components/DashboardCard.vue';
import ProcessItem from '@/components/ProcessItem.vue';
import TaskItem from '@/components/TaskItem.vue';
import QuickAction from '@/components/QuickAction.vue';


export default {
    name: 'App',
    components: {
        HeaderComponent,
        SidebarComponent,
        DashboardCard,
        ProcessItem,
        TaskItem,
        QuickAction
    },
    data() {
        return {
            currentDate: '',
            recentProcesses: [
                {
                    id: 1,
                    name: '请假申请流程',
                    creator: '王小明',
                    time: '2小时前',
                    icon: 'fa-solid fa-file-signature',
                    color: '#165DFF',
                    bgColor: 'bg-primary/10',
                    status: '处理中',
                    statusColor: '#FF7D00',
                    statusBgColor: 'bg-warning/10',
                    statusClass: 'bg-warning/10 text-warning'
                },
                {
                    id: 2,
                    name: '报销审批流程',
                    creator: '李小红',
                    time: '昨天',
                    icon: 'fa-solid fa-money-bill',
                    color: '#0FC6C2',
                    bgColor: 'bg-secondary/10',
                    status: '已完成',
                    statusColor: '#00B42A',
                    statusBgColor: 'bg-success/10',
                    statusClass: 'bg-success/10 text-success'
                },
                {
                    id: 3,
                    name: '入职审批流程',
                    creator: '张新员工',
                    time: '2天前',
                    icon: 'fa-solid fa-user-plus',
                    color: '#86909C',
                    bgColor: 'bg-info/10',
                    status: '待审批',
                    statusColor: '#F53F3F',
                    statusBgColor: 'bg-danger/10',
                    statusClass: 'bg-danger/10 text-danger'
                },
                {
                    id: 4,
                    name: '合同审批流程',
                    creator: '赵经理',
                    time: '3天前',
                    icon: 'fa-solid fa-pencil-alt',
                    color: '#165DFF',
                    bgColor: 'bg-primary/10',
                    status: '已完成',
                    statusColor: '#00B42A',
                    statusBgColor: 'bg-success/10',
                    statusClass: 'bg-success/10 text-success'
                },
                {
                    id: 5,
                    name: '设备采购申请',
                    creator: '技术部',
                    time: '4天前',
                    icon: 'fa-solid fa-laptop',
                    color: '#FF7D00',
                    bgColor: 'bg-warning/10',
                    status: '处理中',
                    statusColor: '#FF7D00',
                    statusBgColor: 'bg-warning/10',
                    statusClass: 'bg-warning/10 text-warning'
                }
            ],
            pendingTasks: [
                {
                    id: 1,
                    name: '请假审批',
                    priority: '紧急',
                    priorityColor: '#F53F3F',
                    priorityClass: 'danger',
                    description: '王小明申请请假3天，需要您审批',
                    action: '审批'
                },
                {
                    id: 2,
                    name: '报销审核',
                    priority: '中等',
                    priorityColor: '#FF7D00',
                    priorityClass: 'warning',
                    description: '李小红提交了差旅费报销申请，共计2500元',
                    action: '审核'
                },
                {
                    id: 3,
                    name: '新员工入职确认',
                    priority: '普通',
                    priorityColor: '#86909C',
                    priorityClass: 'info',
                    description: '张新员工的入职资料已提交，需要您确认',
                    action: '确认'
                },
                {
                    id: 4,
                    name: '合同会签',
                    priority: '中等',
                    priorityColor: '#FF7D00',
                    priorityClass: 'warning',
                    description: '与XX科技有限公司的合作合同需要您会签',
                    action: '会签'
                },
                {
                    id: 5,
                    name: '设备采购审批',
                    priority: '普通',
                    priorityColor: '#86909C',
                    priorityClass: 'info',
                    description: '技术部申请采购3台笔记本电脑，需要您审批',
                    action: '审批'
                }
            ]
        };
    },
    mounted() {
        // 设置当前日期
        const date = new Date();
        const options = {year: 'numeric', month: 'long', day: 'numeric', weekday: 'long'};
        this.currentDate = date.toLocaleDateString('zh-CN', options);

        // 初始化图表
        this.initCharts();
    },
    methods: {
        initCharts() {
            // 流程趋势图表
            const trendCtx = document.getElementById('processTrendChart').getContext('2d');
            // eslint-disable-next-line no-unused-vars
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                    datasets: [
                        {
                            label: '发起流程',
                            data: [5, 7, 6, 8, 10, 4, 3],
                            borderColor: '#165DFF',
                            backgroundColor: 'rgba(22, 93, 255, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: '完成流程',
                            data: [3, 4, 5, 6, 8, 3, 2],
                            borderColor: '#0FC6C2',
                            backgroundColor: 'rgba(15, 198, 194, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 流程类型分布图表
            const typeCtx = document.getElementById('processTypeChart').getContext('2d');
            // eslint-disable-next-line no-unused-vars
            const typeChart = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['审批流程', '业务流程', '日程流程', '协作流程'],
                    datasets: [
                        {
                            data: [40, 30, 15, 15],
                            backgroundColor: ['#165DFF', '#0FC6C2', '#FF7D00', '#F53F3F'],
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    }
};
</script>

<style scoped>
/* 可选：添加额外样式 */
.card-shadow {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
</style>
