<template>
    <div class="login-container">
        <el-card class="login-card">
            <h2 class="login-title">登录</h2>
            <el-form ref="loginForm" :model="loginData" :rules="loginRules" label-width="80px">
                <el-form-item label="用户名" prop="username">
                    <el-input v-model="loginData.username" placeholder="请输入用户名">
                        <template #prefix>
                            <el-icon>
                                <User/>
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="loginData.password" placeholder="请输入密码" type="password">
                        <template #prefix>
                            <el-icon>
                                <Lock/>
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item>
                    <el-checkbox v-model="loginData.rememberMe">记住我</el-checkbox>
                </el-form-item>
                <el-form-item class="button-group">
                    <el-button type="primary" @click="handleLogin">
                        登录
                        <template #suffix>
                            <el-icon>
                                <ArrowRight/>
                            </el-icon>
                        </template>
                    </el-button>
                    <el-button @click="resetForm">重置</el-button>
                </el-form-item>
            </el-form>
            <div class="register-forgot">
                <el-link type="primary" @click="goToRegister">注册</el-link>
                <el-link type="primary" @click="goToForgotPassword">忘记密码</el-link>
            </div>
        </el-card>
    </div>
</template>

<script setup>
import {ref,onMounted } from 'vue';
import {ElMessage} from 'element-plus';
import axios from 'axios';
import {useRouter} from 'vue-router';
import {User, Lock, ArrowRight} from '@element-plus/icons-vue';

const router = useRouter();
const loginData = ref({
    username: '',
    password: '',
    rememberMe: false
});
onMounted(() => {
    const userInfo = localStorage.getItem('userInfo');
    if (userInfo) {
        const { username ,password} = JSON.parse(userInfo);
        loginData.value.username = username;
        loginData.value.password = password;
        loginData.value.rememberMe = true;
    }
});

const loginRules = {
    username: [
        {required: true, message: '请输入用户名', trigger: 'blur'}
    ],
    password: [
        {required: true, message: '请输入密码', trigger: 'blur'}
    ]
};

const loginForm = ref();

const handleLogin = async () => {
    const valid = await loginForm.value.validate();
    if (!valid) {
        return;
    }

    try {
        const response = await axios.post('http://127.0.0.1:8000/api/login', loginData.value);
        if (response.status === 200) {
            if (loginData.value.rememberMe) {
                localStorage.setItem('userInfo', JSON.stringify({
                    username: loginData.value.username,
                    password: loginData.value.password,
                    access_token: response.data.access_token
                }));
            }
            // 这里可以添加登录成功后的路由跳转逻辑
            ElMessage.success('登录成功');
            await router.push('/home');
        }
    } catch (error) {
        ElMessage.error('登录失败，请检查用户名和密码');
    }
};

const resetForm = () => {
    loginData.value.username = '';
    loginData.value.password = '';
    loginData.value.rememberMe = false;
    loginForm.value.resetFields();
};

const goToRegister = () => {
    router.push('/register');
};

const goToForgotPassword = () => {
    router.push('/forgot-password');
};
</script>

<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #8e2de2, #4a00e0);
    padding: 16px;
}

.login-card {
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

.login-title {
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

.button-group {
    display: flex;
    justify-content: space-between;
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

.el-checkbox {
    margin-bottom: 16px;
}

.register-forgot {
    margin-top: 16px;
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.el-link {
    font-size: 14px;
    color: #409eff;

    &:hover {
        color: #66b1ff;
        text-decoration: underline;
    }
}
</style>