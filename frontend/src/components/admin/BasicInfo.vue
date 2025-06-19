<!-- BasicInfo.vue -->
<template>
    <div class="basic-info">
        <h3>基本信息</h3>
        <el-form label-width="120px">
            <el-form-item label="实体名称">
                <el-input v-model="entity.label" disabled />
            </el-form-item>
            <el-form-item label="实体描述">
                <el-input v-model="entity.comments" type="textarea" rows="3" />
            </el-form-item>
            <el-form-item label="是否明细实体">
                <el-switch v-model="entity.isDetail" disabled />
            </el-form-item>
            <el-form-item label="主实体" v-if="entity.isDetail">
                <el-select v-model="entity.main_entity_id" placeholder="请选择主实体" style="width: 100%">
                    <el-option
                            v-for="entity in mainEntities"
                            :key="entity.id"
                            :label="entity.label"
                            :value="entity.id"
                    />
                </el-select>
            </el-form-item>
        </el-form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    entity: {
        type: Object,
        required: true
    }
});

const entity = ref({ ...props.entity });
const mainEntities = ref([]);

const fetchMainEntities = async () => {
    try {
        const res = await axios.get('http://localhost:8000/api/entity');
        mainEntities.value = res.data.filter(e => !e.isDetail);
    } catch (error) {
        console.error('获取主实体失败:', error);
    }
};

onMounted(() => {
    if (entity.value) {
        fetchMainEntities();
    }
});
</script>
