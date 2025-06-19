<template>
    <div class="entity-management">

        <!-- 实体卡片列表 -->
        <div class="card-list">
            <!-- 已有实体卡片 -->
            <el-card
                    v-for="entity in entityList"
                    :key="entity.id"
                    class="entity-card card-hover-shadow"
                    @click="handleEdit(entity)"
            >
                <template #default>
                    <div class="card-content">
                        <div class="card-header">
                            <p>{{ entity.label }}</p>
                        </div>
                        <div class="card-body">
                            <p>{{ entity.comments }}</p>
                        </div>
                    </div>
                </template>

            </el-card>

            <!-- 新建实体卡片 -->
            <el-card class="entity-card new-entity-card card-hover-shadow" @click="showCreateDialog = true">
                <template #default>
                    <div class="new-card-content">
                        <el-icon>
                            <Plus/>
                        </el-icon>
                        <p>新建实体</p>
                    </div>
                </template>
            </el-card>
        </div>

        <!-- 新建实体弹窗 -->
        <el-dialog v-model="showCreateDialog" title="新建实体" width="450px" center>
            <el-form ref="formRef" :model="createForm" :rules="rules" label-width="120px">
                <el-form-item label="实体名称" prop="label">
                    <el-input v-model="createForm.label" />
                </el-form-item>

                <el-form-item label="实体描述" prop="comments">
                    <el-input v-model="createForm.comments" />
                </el-form-item>

                <!-- 是否明细实体（Switch 方式） -->
                <el-form-item label="是否明细实体">
                    <el-switch v-model="createForm.isDetail" />
                </el-form-item>

                <!-- 关联主实体（仅在 isDetail 为 true 时显示） -->
                <el-form-item label="关联主实体" v-if="createForm.isDetail">
                    <el-select v-model="createForm.main_entity" placeholder="请选择主实体" style="width: 100%">
                        <el-option
                            v-for="entity in entityList"
                            :key="entity.id"
                            :label="entity.label"
                            :value="entity.id"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item style="margin-top: 20px">
                    <el-button type="primary" @click="submitCreate">提交</el-button>
                    <el-button @click="showCreateDialog = false">取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>


    </div>
</template>


<script setup>
import {onMounted, ref} from 'vue'
import axios from 'axios'
import {ElMessage} from "element-plus";
import router from "@/router/index.js";

const entityList = ref([])

// 获取实体列表
const fetchEntityList = async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/entity')
        entityList.value = response.data
    } catch (error) {
        console.error('获取实体列表失败:', error)
        ElMessage.error('获取实体列表失败')
    }
}

// 跳转到实体编辑页面
const handleEdit = (entity) => {
    // 跳转到实体编辑页面
    router.push(`/admin/entity/edit/${entity.id}`)
}



// 表单控制
const showCreateDialog = ref(false)
const createForm = ref({
    label: '',
    comments: '',
    isDetail: false, // 默认不开启明细实体
    main_entity: null
})

const rules = {
    label: [
        {required: true, message: '请输入实体名称', trigger: 'blur'},
        {min: 2, max: 50, message: '长度在2到50个字符', trigger: 'blur'}
    ],
    comments: [
        {required: false, message: '请输入实体描述', trigger: 'blur'},
        {min: 2, max: 50, message: '长度在2到50个字符', trigger: 'blur'}
    ]
}

const formRef = ref(null)

// 提交新建
const submitCreate = async () => {
    try {
        const res = await axios.post('http://localhost:8000/api/entity/', createForm.value)
        ElMessage.success('创建成功')
        showCreateDialog.value = false
        createForm.value = {name: '', label: ''}
        await fetchEntityList()
    } catch (errors) {
        ElMessage.error(errors.response?.data?.message || '创建失败')
    }
}


onMounted(() => {
    fetchEntityList()
})
</script>

<style scoped>
.entity-management {
    padding: 16px;
    margin-left: 170px;
}

.card-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));/* 设置卡片宽度 */
    gap: 16px;
    margin-bottom: 16px;
}

/* 卡片基础样式 */
.entity-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 6px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    height: 60px; /* 设置卡片高度 */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* 悬停阴影效果 */
.card-hover-shadow:hover {
    transform: translateY(-3px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
}
:deep(.el-card__body) {
    padding: 8px 8px;
}
.card-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* 左对齐 */
    overflow: hidden;
    height: 100%;
}

.card-header p {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
    font-size: 10px;
    font-weight: bold;
    width: 100%;
}

.card-body p {
    white-space: normal;
    word-break: break-word;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
    margin: 2px 0 0;
    font-size: 8px;
    color: #666;
    width: 100%;
}

.card-header {
    font-weight: bold;
    margin-bottom: 2px;
}


/* 新建实体卡片样式 */
.new-entity-card {
    background-color: #fafbff;
    border: 2px dashed #dcdfe6;
    text-align: center;
    color: #999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.new-entity-card:hover {
    background-color: #f0f3ff;
    border-color: #b3c0d4;
}

.new-card-content {
    font-size: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.new-card-content .el-icon {
    font-size: 20px;
    margin-bottom: 6px;
}



</style>
