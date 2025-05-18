<template>
    <div class="user-management-container">
        <!-- 左侧部门树 -->
        <div class="department-tree">
            <el-tree
                    :data="departmentTreeData"
                    node-key="id"
                    default-expand-all
            >
                <template #default="{ node, data }">
          <span class="custom-tree-node">
            <span>{{ node.label }}</span>
            <el-popover
                    v-if="data.editable"
                    placement="right"
                    :width="160"
                    trigger="click"
                    content=""
            >
              <template #reference>
                  <MoreFilled style="width: 1em; height: 1em; cursor: pointer;" />
              </template>
              <div>
                <el-button link @click="editDepartment(data)">编辑</el-button>
                <el-button link @click="addDepartment(data)">添加</el-button>
                <el-button link @click="disableDepartment(data)">禁用</el-button>
              </div>
            </el-popover>
          </span>
                </template>
            </el-tree>
        </div>

        <!-- 右侧内容区域 -->
        <div class="content-area">
            <el-tabs v-model="activeTab" type="card">
                <el-tab-pane label="人员信息" name="members">
                    <member-list v-if="selectedDepartmentId" :department-id="selectedDepartmentId"/>
                </el-tab-pane>
                <el-tab-pane label="部门详情" name="details">
                    <department-detail v-if="selectedDepartmentId" :department-id="selectedDepartmentId"/>
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import MemberList from './MemberList.vue'
import DepartmentDetail from './DepartmentDetail.vue'

// 当前激活页签
const activeTab = ref('members')

// 选中部门 ID
const selectedDepartmentId = ref('data')

// 部门树数据
const departmentTreeData = ref([
    {
        id: 0,
        label: '全部部门',
        editable: true,
        children: [
            {
                id: 1,
                label: '技术部',
                editable: true,
                children: [
                    {id: 2, label: '前端组', editable: true},
                    {id: 3, label: '后端组', editable: true},
                ],
            },
            {
                id: 4,
                label: '产品部',
                editable: true,
                children: [],
            },
        ],
    },
])

// 方法定义
function handleDepartmentClick(node) {
    selectedDepartmentId.value = node.id
}

function editDepartment(data) {
    console.log('Edit department:', data)
}

function addDepartment(data) {
    console.log('Add department under:', data)
}

function disableDepartment(data) {
    console.log('Disable department:', data)
}
</script>

<style scoped>
.user-management-container {
    display: flex;
}

.department-tree {
    margin-left: 170px;
    width: 200px;
    border-right: 1px solid #e4e4e4;
    padding: 10px;
}

.content-area {
    flex: 1;
    padding: 20px;
}

.custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
}

.el-popover {
    text-align: center;
}

.el-popover .el-button {
    display: block;
    width: 100%;
    margin: 5px 0;
}


</style>
