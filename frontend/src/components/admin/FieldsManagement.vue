<template>
    <div class="fields-management">
        <h3>字段管理</h3>
        <el-table :data="fields" border style="width: 100%">
            <el-table-column prop="name" label="字段名"></el-table-column>
            <el-table-column prop="label" label="显示名"></el-table-column>
            <el-table-column prop="type" label="类型"></el-table-column>
            <el-table-column prop="description" label="描述"></el-table-column>
            <el-table-column prop="is_required" label="是否必填">
                <template #default="{ row }">
                    {{ row.is_required ? '是' : '否' }}
                </template>
            </el-table-column>
        </el-table>
        <el-button @click="addField">添加字段</el-button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    entityId: {
        type: Number,
        required: true
    }
});

const fields = ref([]);

const fetchFields = async () => {
    try {
        const res = await axios.get(`http://localhost:8000/api/entity/${props.entityId}/fields`);
        fields.value = res.data;
    } catch (error) {
        console.error('获取字段失败:', error);
    }
};

const addField = () => {
    // 可扩展新增字段逻辑
};

onMounted(() => {
    fetchFields();
});
</script>
