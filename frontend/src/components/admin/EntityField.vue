<template>
    <div class="entity-edit">
        <el-tabs v-model="activeTab" type="card">
            <el-tab-pane label="基本信息" name="basicInfo">
                <BasicInfo :entity="selectedEntity" />
            </el-tab-pane>

            <el-tab-pane label="字段管理" name="fields">
                <FieldsManagement :entity-id="selectedEntity.id" />
            </el-tab-pane>

            <el-tab-pane label="表单设计" name="formDesign">
                <FormDesign :entity-id="selectedEntity.id" />
            </el-tab-pane>

            <!-- 如果是明细实体，则显示关联实体选项卡 -->
            <el-tab-pane label="关联实体" name="relatedEntities" v-if="selectedEntity.isDetail">
                <RelatedEntities :entity-id="selectedEntity.id" />
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import BasicInfo from './BasicInfo.vue';
import FieldsManagement from './FieldsManagement.vue';
import FormDesign from './FormDesign.vue';
import RelatedEntities from './RelatedEntities.vue';

const props = defineProps({
    entity: {
        type: Object,
        required: true
    }
});

const selectedEntity = ref(props.entity);
const activeTab = ref('basicInfo');
</script>
