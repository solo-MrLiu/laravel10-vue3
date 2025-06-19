<template>
    <div class="related-entities">
        <h3>关联实体</h3>
        <el-select v-model="selectedMainEntity" placeholder="请选择主实体">
            <el-option
                    v-for="entity in mainEntities"
                    :key="entity.id"
                    :label="entity.label"
                    :value="entity.id"
            />
        </el-select>
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

const selectedMainEntity = ref(null);
const mainEntities = ref([]);

const fetchMainEntities = async () => {
    try {
        const res = await axios.get('http://localhost:8000/api/entity');
        mainEntities.value = res.data.filter(entity => !entity.isDetail);
    } catch (error) {
        console.error('获取主实体失败:', error);
    }
};

onMounted(() => {
    fetchMainEntities();
});
</script>
