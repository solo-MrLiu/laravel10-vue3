<template>
    <div class="register-container">
        <el-card class="register-card">
            <h2 class="register-title">注册</h2>
            <el-form ref="registerForm" :model="registerData" :rules="registerRules" label-width="80px">
                <el-form-item label="用户名" prop="username">
                    <el-input v-model="registerData.username" placeholder="请输入用户名">
                        <template #prefix>
                            <el-icon><User /></el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="registerData.email" placeholder="请输入邮箱">
                        <template #prefix>
                            <el-icon><Message /></el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="registerData.password" placeholder="请输入密码" type="password">
                        <template #prefix>
                            <el-icon><Lock /></el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="确认密码" prop="password_confirmation ">
                    <el-input v-model="registerData.password_confirmation " placeholder="请再次输入密码" type="password">
                        <template #prefix>
                            <el-icon><Lock /></el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="handleRegister">注册</el-button>
                    <el-button @click="resetForm">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { ElMessage } from 'element-plus';
import axios from 'axios';
import { User, Message, Lock } from '@element-plus/icons-vue';
import router from "../router/index.js";

const registerData = ref({
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
});

// 先定义验证函数
const validatePassword = (rule, value, callback) => {
    if (value === registerData.value.password) {
        callback();
    } else {
        callback(new Error('两次输入的密码不一致'));
    }
};

const registerRules = {
    username: [
        { required: true, message: '请输入用户名', trigger: 'blur' }
    ],
    email: [
        { required: true, message: '请输入邮箱', trigger: 'blur' },
        { type: 'email', message: '请输入正确的邮箱格式', trigger: ['blur', 'change'] }
    ],
    password: [
        { required: true, message: '请输入密码', trigger: 'blur' },
        { min: 6, message: '密码长度至少为6位', trigger: 'blur' }
    ],
    password_confirmation : [
        { required: true, message: '请确认密码', trigger: 'blur' },
        { validator: validatePassword, trigger: 'blur' }
    ]
};

const registerForm = ref();

const handleRegister = async () => {
    const valid = await registerForm.value.validate();
    if (!valid) {
        return;
    }
    try {
        const response = await axios.post('http://127.0.0.1:8000/api/register', registerData.value);
        if (response.status === 200) {
            ElMessage.success('注册成功');
            // 这里可以添加注册成功后的路由跳转逻辑，例如跳转到登录页
            await router.push('/login');
        } else {
            ElMessage.error('注册失败，请检查输入信息');
        }
    } catch (error) {
        console.log(error)
        ElMessage.error('注册失败，请检查网络或联系管理员');
    }
};

const resetForm = () => {
    registerData.value.username = '';
    registerData.value.email = '';
    registerData.value.password = '';
    registerData.value.password_confirmation  = '';
    registerForm.value.resetFields();
};
</script>

<style scoped>
.register-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #8e2de2, #4a00e0);
    padding: 16px;
}

.register-card {
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: fade-in 0.5s ease-in-out;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.register-title {
    text-align: center;
    margin-bottom: 24px;
    color: #333;
    font-size: 28px;
    font-weight: 600;
}

.el-form {
    width: 100%;
}

.el-form-item {
    margin-bottom: 16px;
}

.el-form-item__label {
    font-weight: 500;
    font-size: 16px;
    color: #555;
}

.el-input {
    width: 100%;
    font-size: 16px;
    border-color: #dcdfe6;
    &:focus {
        border-color: #409eff;
        box-shadow: 0 0 0 2px rgba(64, 158, 255, 0.2);
    }
}

.el-button {
    font-size: 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.el-button--primary {
    background-color: #409eff;
    border-color: #409eff;
    &:hover {
        background-color: #66b1ff;
        border-color: #66b1ff;
        box-shadow: 0 2px 4px rgba(64, 158, 255, 0.3);
    }
}
</style>