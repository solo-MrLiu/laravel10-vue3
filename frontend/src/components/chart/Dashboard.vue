<template>
    <div>
        <el-button @click="addDashboard">添加仪表盘</el-button>
        <el-card v-for="dashboard in dashboards" :key="dashboard.id">
            <template #header>
                <span>{{ dashboard.title }}</span>
                <el-button @click="editDashboard(dashboard)">编辑</el-button>
                <el-button @click="deleteDashboard(dashboard)">删除</el-button>
                <el-button @click="addChart(dashboard.id)">添加图表</el-button>
            </template>
            <div class="grid-stack" ref="gridStack">
                <div v-for="chart in dashboard.charts" :key="chart.id" class="grid-stack-item" :style="{ left: `${chart.x * 100}px`, top: `${chart.y * 100}px`, width: `${chart.w * 100}px`, height: `${chart.h * 100}px` }">
                    <div class="grid-stack-item-content">{{ chart.title }}</div>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const dashboards = ref([]);
const gridStack = ref(null);
import { ElMessageBox } from 'element-plus';

const fetchDashboards = async () => {
    const response = await axios.get('/api/dashboards');
    dashboards.value = response.data;
    dashboards.value.forEach(async (dashboard) => {
        const chartResponse = await axios.get(`/api/dashboards/${dashboard.id}/charts`);
        dashboard.charts = chartResponse.data;
    });
};

//添加仪表盘
const addDashboard = async () => {
    ElMessageBox.prompt('请输入仪表盘名称', '添加仪表盘', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
    }).then(({ value }) => {
        if (value) {
            axios.post('http://localhost:8000//api/dashboards', {
                title: value,
                share_to: 'SELF',
            }).then((response) => {
                dashboards.value.push(response.data);
            });
        }
    }).catch(() => {
        // 取消操作
    });
};

const editDashboard = async (dashboard) => {
    const { value: form } = await this.$prompt('请输入新的仪表盘名称', '编辑仪表盘', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: dashboard.title,
    });
    if (form) {
        const response = await axios.put(`/api/dashboards/${dashboard.id}`, {
            title: form,
            share_to: dashboard.share_to,
        });
        const index = dashboards.value.findIndex((d) => d.id === dashboard.id);
        dashboards.value[index] = response.data;
    }
};

const deleteDashboard = async (dashboard) => {
    const confirm = await this.$confirm('确定要删除该仪表盘吗？', '删除仪表盘', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
    });
    if (confirm) {
        await axios.delete(`/api/dashboards/${dashboard.id}`);
        const index = dashboards.value.findIndex((d) => d.id === dashboard.id);
        dashboards.value.splice(index, 1);
    }
};

const addChart = async (dashboardId) => {
    const { value: form } = await this.$prompt('请输入图表名称', '添加图表', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
    });
    if (form) {
        const response = await axios.post(`/api/dashboards/${dashboardId}/charts`, {
            title: form,
            type: 'default',
            x: 0,
            y: 0,
            w: 2,
            h: 2,
        });
        const dashboardIndex = dashboards.value.findIndex((d) => d.id === dashboardId);
        dashboards.value[dashboardIndex].charts.push(response.data);
    }
};

onMounted(() => {
    fetchDashboards();
});
</script>

<style scoped>
.grid-stack {
    position: relative;
}

.grid-stack-item {
    position: absolute;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
}

.grid-stack-item-content {
    padding: 10px;
}
</style>