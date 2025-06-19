<template>
    <div class="classification-management">
        <!-- 左侧主分类 -->
        <div class="category-list">
            <h3>
                分类列表
                <el-button circle size="small" type="primary" @click="openAddProjectDialog">
                    <el-icon>
                        <Plus/>
                    </el-icon>
                </el-button>
            </h3>

            <el-scrollbar max-height="600px">
                <el-card
                        v-for="(project, index) in projects"
                        :key="index"
                        class="project-card"
                        :body-style="{ padding: '8px' }"
                        :class="{ active: selectedProject?.id === project.id }"
                        @click="selectProject(project)"
                >
                    <div class="card-content">
                        <span>{{ project.name }}</span>
                        <div class="actions">
                            <el-button link type="primary" size="small"
                                       @click.stop="openEditDialog('project', project)">
                                <el-icon>
                                    <Edit/>
                                </el-icon>
                            </el-button>
                            <el-button link type="danger" size="small"
                                       @click.stop="confirmDelete('project', project, index)">
                                <el-icon>
                                    <Delete/>
                                </el-icon>
                            </el-button>
                        </div>
                    </div>
                </el-card>
            </el-scrollbar>
        </div>

        <!-- 右侧子分类内容 -->
        <div class="content-panel">
            <h3>分类内容 - {{ selectedProject?.name || '请选择' }}</h3>

            <!-- 分类层级 -->
            <div class="level-group">
                <template v-for="level in [1, 2, 3, 4]" :key="level">
                    <div class="level-item" v-if="level <= levelLimit">
                        <div class="level-header">
                            <div class="level-title">{{ level }} 级分类</div>
                            <el-button
                                    type="primary"
                                    size="small"
                                    :disabled="!canAdd(level)"
                                    @click="openAddCategoryDialog(level)"
                            >
                                添加
                            </el-button>
                        </div>
                        <el-scrollbar max-height="400px">
                            <table class="category-table">
                                <tr
                                        v-for="item in categories[level]"
                                        :key="item.id"
                                        :class="{ active: isSelected(item, level) }"
                                        @click="handleSelectCategory(item, level)"
                                >
                                    <td>{{ item.name }}</td>
                                    <td>
                                        <el-button link type="primary" size="small"
                                                   @click.stop="openEditCategoryDialog(item, level)">
                                            <el-icon>
                                                <Edit/>
                                            </el-icon>
                                        </el-button>
                                        <el-button link type="danger" size="small"
                                                   @click.stop="confirmDelete(`level${level}`, item, level)">
                                            <el-icon>
                                                <Delete/>
                                            </el-icon>
                                        </el-button>
                                    </td>
                                </tr>
                            </table>
                        </el-scrollbar>
                    </div>
                </template>
            </div>
        </div>

        <!-- 新增主分类弹窗 -->
        <el-dialog v-model="addProjectDialogVisible" title="添加分类" width="30%">
            <el-form @submit.prevent="handleSubmitProject">
                <el-form-item label="分类名称">
                    <el-input v-model="newProjectName" autofocus/>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="handleSubmitProject">提交</el-button>
                    <el-button @click="addProjectDialogVisible = false">取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <!-- 新增/编辑子分类弹窗 -->
        <el-dialog v-model="editCategoryDialogVisible" :title="dialogTitle" width="40%">
            <el-form @submit.prevent="handleSubmitCategory">
                <el-form-item label="分类名称">
                    <el-input v-model="categoryForm.name" @input="updateFullName"/>
                </el-form-item>
                <el-form-item label="分类编码">
                    <el-input v-model="categoryForm.code"/>
                </el-form-item>
                <el-form-item label="全称">
                    <el-input v-model="categoryForm.fullName" readonly class="readonly-field">
                        <template #suffix>
                            <el-icon>
                                <Lock/>
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>

                <el-form-item label="父级ID">
                    <el-input v-model.number="categoryForm.parent_id" readonly class="readonly-field">
                        <template #suffix>
                            <el-icon>
                                <Lock/>
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>

                <el-form-item label="层级">
                    <el-input v-model.number="categoryForm.level" readonly class="readonly-field">
                        <template #suffix>
                            <el-icon>
                                <Lock/>
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>

                <el-form-item label="分类项目ID">
                    <el-input v-model.number="categoryForm.dataId" readonly class="readonly-field">
                        <template #suffix>
                            <el-icon>
                                <Lock/>
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" native-type="submit">提交</el-button>
                    <el-button @click="editCategoryDialogVisible = false">取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <!-- 删除确认弹窗 -->
        <el-dialog v-model="deleteConfirmVisible" title="确认删除" width="30%">
            <p>确定要删除该分类吗？此操作不可恢复。</p>
            <el-button type="danger" @click="handleDeleteConfirmed">确定</el-button>
            <el-button @click="deleteConfirmVisible = false">取消</el-button>
        </el-dialog>
    </div>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue'
import axios from 'axios'
import {
    Edit,
    Delete,
    Plus
} from '@element-plus/icons-vue'

// 主分类数据
const projects = ref([])
const newProjectName = ref('')
const selectedProject = ref(null)

// 子分类数据
const categories = ref({
    1: [],
    2: [],
    3: [],
    4: []
})

const selectedLevels = ref({
    1: null,
    2: null,
    3: null
})

// 弹窗状态
const addProjectDialogVisible = ref(false)
const editCategoryDialogVisible = ref(false)
const deleteConfirmVisible = ref(false)

// 编辑相关
const editingItem = ref(null)
const editingLevel = ref(1)

// 当前层级
const currentLevel = ref(1)

// 分类表单模型
const categoryForm = ref({
    id: null,
    name: '',
    code: '',
    fullName: '',
    parent_id: null,
    level: 1,
    dataId: null // 关联主分类ID
})

// 辅助计算属性
const levelLimit = computed(() => {
    return selectedProject.value ? 4 : 0
})

const dialogTitle = computed(() => (categoryForm.value.id ? '编辑分类' : '添加分类'))

function canAdd(level) {
    if (level === 1) return !!selectedProject.value
    return !!selectedLevels.value[level - 1]
}

// API 请求
async function fetchProjects() {
    const res = await axios.get('http://localhost:8000/api/classification/')
    projects.value = res.data
}

function addProject(name) {
    if (!name.trim()) return
    axios.post('http://localhost:8000/api/classification/store', {
        name: name.trim()
    }).then(res => {
        projects.value.push(res.data)
        newProjectName.value = ''
        addProjectDialogVisible.value = false
    })
}

function selectProject(project) {
    selectedProject.value = project
    loadSubCategories(project.id, null)
}

async function loadSubCategories(projectId, parentId = null) {
    const res = await axios.get(`http://localhost:8000/api/classification/content/${projectId}/${parentId}`)
    const level = parentId ? getLevelByParent(parentId) + 1 : 1
    categories.value[level] = res.data
    resetChildren(level)
}

function getLevelByParent(id) {
    for (let i = 1; i <= 3; i++) {
        if (selectedLevels.value[i]?.id === id) return i
    }
    return 0
}

function resetChildren(level) {
    for (let i = level + 1; i <= 4; i++) {
        categories.value[i] = []
        if (i <= 3) selectedLevels.value[i] = null
    }
}

// 操作方法
function handleAddCategory(data) {
    axios.post('http://localhost:8000/api/classification/content', data).then(res => {
        categories.value[data.level].push(res.data)
        editCategoryDialogVisible.value = false
    })
}

function handleUpdateCategory(data) {
    axios.put(`http://localhost:8000/api/classification/content/${data.id}`, {
        name: data.name,
        code: data.code
    }).then(() => {
        const idx = categories.value[data.level].findIndex(c => c.id === data.id)
        if (idx > -1) {
            categories.value[data.level][idx] = {...categories.value[data.level][idx], ...data}
        }
        editCategoryDialogVisible.value = false
    })
}

// 弹窗操作
function openAddProjectDialog() {
    newProjectName.value = ''
    addProjectDialogVisible.value = true
}

function openAddCategoryDialog(level) {
    currentLevel.value = level
    const parent = level > 1 ? selectedLevels.value[level - 1] : null

    categoryForm.value = {
        id: null,
        name: '',
        code: '',
        fullName: '',
        parent_id: parent?.id || null,
        level: level,
        dataId: selectedProject.value.id
    }

    editCategoryDialogVisible.value = true
    setTimeout(() => {
        updateFullName()
    }, 100)
}

function openEditCategoryDialog(item, level) {
    const parent = level > 1 ? selectedLevels.value[level - 1] : null

    categoryForm.value = {
        ...item,
        // fullName: generateFullName(level, parent),
        parent_id: parent?.id || null,
        level: level,
        dataId: selectedProject.value.id
    }
    categoryForm.value.fullName = generateFullName(level, parent)

    editingItem.value = item
    editingLevel.value = level
    editCategoryDialogVisible.value = true
}

function handleSubmitCategory() {
    if (categoryForm.value.id) {
        handleUpdateCategory(categoryForm.value)
    } else {
        handleAddCategory(categoryForm.value)
    }
}

function updateFullName() {
    let path = []
    let currentLevel = categoryForm.value.level - 1
    while (currentLevel > 0) {
        const item = selectedLevels.value[currentLevel]
        if (item) path.unshift(item.name)
        currentLevel--
    }

    path.push(categoryForm.value.name)

    if (selectedProject.value?.name) {
        categoryForm.value.fullName = `${path.join('/')}`
    } else {
        categoryForm.value.fullName = ''
    }
}

function generateFullName(level, parent) {

    if (level === 1) return categoryForm.value.name || ''

    let path = []
    for (let i = level - 1; i > 0; i--) {
        const upperLevel = selectedLevels.value[i]
        if (upperLevel) path.unshift(upperLevel.name)
    }
    return `${path.join('/')}/${categoryForm.value.name || ''}`
}

// 删除逻辑
const deleteTarget = ref({type: null, item: null, level: null, index: null})

function confirmDelete(type, item, index = null) {
    deleteTarget.value = {type, item, index}
    deleteConfirmVisible.value = true
}

function handleDeleteConfirmed() {
    const {type, item, index} = deleteTarget.value
    const itemId = item.id

    if (type === 'project') {
        axios.delete(`http://localhost:8000/api/classification/project/${itemId}`).then(() => {
            projects.value.splice(index, 1)
            if (selectedProject.value?.id === itemId) {
                selectedProject.value = null
                for (let i = 1; i <= 4; i++) {
                    categories.value[i] = []
                }
                selectedLevels.value = {
                    1: null,
                    2: null,
                    3: null
                }
            }
        })
    } else if (/^level\d+$/.test(type)) {
        const level = parseInt(type.replace('level', ''), 10)
        axios.delete(`http://localhost:8000/api/classification/content/${itemId}`).then(() => {
            categories.value[level] = categories.value[level].filter(c => c.id !== itemId)
            if (level < 4) {
                for (let i = level + 1; i <= 4; i++) {
                    categories.value[i] = []
                    if (i <= 3) selectedLevels.value[i] = null
                }
            }
        })
    }

    deleteConfirmVisible.value = false
}

// 选中联动
function handleSelectCategory(item, level) {
    selectedLevels.value[level] = item
    resetChildren(level)
    if (level < 4) {
        loadSubCategories(selectedProject.value.id, item.id)
    }
}

function isSelected(item, level) {
    return selectedLevels.value[level]?.id === item.id
}

onMounted(() => {
    fetchProjects()
})
</script>

<style scoped>
.classification-management {
    display: flex;
    padding: 20px;
}

.category-list {
    width: 200px;
    border-right: 1px solid #ebeef5;
    padding-right: 20px;
    margin-left: 170px;
}

.category-list h3 {
    font-size: 16px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.project-card {
    margin-bottom: 8px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-content .actions {
    display: none;
}

.project-card:hover .card-content .actions,
.project-card.active .card-content .actions {
    display: flex;
    gap: 8px;
}

.project-card:hover,
.project-card.active {
    background-color: #f0f9ff;
}

.content-panel {
    flex: 1;
    padding-left: 20px;
}

.level-group {
    display: flex;
    gap: 20px;
}

.level-item {
    flex: 1;
    border: 1px solid #ebeef5;
    padding: 10px;
    border-radius: 4px;
}

.level-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.level-title {
    font-weight: bold;
    font-size: 14px;
}

.category-table {
    width: 100%;
    border-collapse: collapse;
}

.category-table tr {
    height: 40px;
    transition: background-color 0.2s ease;
}

.category-table tr:nth-child(odd) {
    background-color: #f9f9f9;
}

.category-table tr:hover,
.category-table tr.active {
    background-color: #f0f9ff;
}

.category-table td {
    padding: 0 10px;
    border-bottom: 1px solid #eee;
}

:deep(.readonly-field .el-input__inner) {
    background-color: #dcdfe6 !important;
    cursor: not-allowed;
}

:deep(.readonly-field .el-input__suffix) {
    display: flex;
    align-items: center;
}

:deep(.readonly-field .el-input__suffix .el-icon) {
    margin-right: 5px;
}
</style>
