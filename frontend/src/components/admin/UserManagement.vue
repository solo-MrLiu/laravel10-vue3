<template>
    <div class="main-content">
        <el-table :data="users" style="width: 100%">
            <el-table-column prop="id" label="ID"></el-table-column>
            <el-table-column prop="name" label="姓名"></el-table-column>
            <el-table-column prop="email" label="邮箱"></el-table-column>
            <el-table-column label="操作">
                <template #default="{row}">
                    <el-button type="primary" @click="editUser(row)">编辑</el-button>
                    <el-button type="danger" @click="deleteUser(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog :title="dialogTitle" :visible.sync="dialogVisible">
            <el-form ref="userFormRef" :model="currentUser" :rules="rules">
                <el-form-item label="姓名" prop="name">
                    <el-input v-model="currentUser.name"></el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="currentUser.email"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password" v-if="isCreate">
                    <el-input type="password" v-model="currentUser.password"></el-input>
                </el-form-item>
            </el-form>
            <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisible = false">取消</el-button>
          <el-button type="primary" @click="saveUser">保存</el-button>
        </span>
            </template>
        </el-dialog>
        <el-button type="primary" @click="createUser">新建用户</el-button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import axios from 'axios'

const users = ref([])
const dialogVisible = ref(false)
const dialogTitle = ref('')
const currentUser = ref({})
const isCreate = ref(false)
const userFormRef = ref(null)

const rules = {
    name: [
        { required: true, message: '请输入姓名', trigger: 'blur' }
    ],
    email: [
        { required: true, message: '请输入邮箱', trigger: 'blur' },
        { type: 'email', message: '请输入正确的邮箱格式', trigger: ['blur', 'change'] }
    ],
    password: [
        { required: true, message: '请输入密码', trigger: 'blur' }
    ]
}

const fetchUsers = async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/users')
        users.value = response.data
    } catch (error) {
        ElMessage.error('获取用户列表失败')
    }
}

const createUser = () => {
    isCreate.value = true
    dialogTitle.value = '新建用户'
    currentUser.value = {}
    dialogVisible.value = true
}

const editUser = (user) => {
    isCreate.value = false
    dialogTitle.value = '编辑用户'
    currentUser.value = {...user }
    dialogVisible.value = true
}

const saveUser = async () => {
    const valid = await userFormRef.value.validate()
    if (!valid) {
        return
    }
    try {
        if (isCreate.value) {
            await axios.post('/api/users', currentUser.value)
            ElMessage.success('用户创建成功')
        } else {
            await axios.put(`/api/users/${currentUser.value.id}`, currentUser.value)
            ElMessage.success('用户更新成功')
        }
        dialogVisible.value = false
        fetchUsers()
    } catch (error) {
        ElMessage.error('保存用户失败')
    }
}

const deleteUser = async (user) => {
    await ElMessageBox.confirm('确定要删除该用户吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(async () => {
        try {
            await axios.delete(`/api/users/${user.id}`)
            ElMessage.success('用户删除成功')
            fetchUsers()
        } catch (error) {
            ElMessage.error('删除用户失败')
        }
    }).catch(() => {
        ElMessage.info('已取消删除')
    })
}

onMounted(() => {
    fetchUsers()
})
</script>

<style scoped>
.main-content {
    margin-left: 170px; /* 避开侧边栏 */
    padding: 10px;
}
</style>