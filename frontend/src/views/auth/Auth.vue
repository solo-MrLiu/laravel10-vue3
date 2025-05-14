<template>
    <div class="auth-container">
        <div class="auth-card">
            <LoginComponent
                    :initial-data="loginFormData"
                    :loading="loginLoading"
                    @submit="handleLogin"
                    @reset="resetLoginForm"
                    @social-login="handleSocialLogin"
                    @show-register="showRegisterDialog = true"
                    @show-forgot-password="showForgotPasswordDialog = true"
            />
        </div>

        <RegisterComponent
                :visible="showRegisterDialog"
                :initial-data="registerFormData"
                @update:visible="showRegisterDialog = $event"
                @submit="handleRegister"
        />

        <ForgotPasswordComponent
                :visible="showForgotPasswordDialog"
                :initial-data="forgotPasswordFormData"
                @update:visible="showForgotPasswordDialog = $event"
                @submit="handleResetPassword"
        />


    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import {ElMessage} from 'element-plus'
import LoginComponent from '@/components/auth/Login.vue'
import RegisterComponent from '@/components/auth/Register.vue'
import ForgotPasswordComponent from '@/components/auth/ForgotPassword.vue'




// 登录表单数据
const loginFormData = ref({
    phone: '',
    password: '',
    rememberMe: false
})

// 注册表单数据
const registerFormData = ref({
    phone: '',
    code: '',
    password: '',
    confirmPassword: ''
})

// 忘记密码表单数据
const forgotPasswordFormData = ref({
    phone: '',
    code: '',
    password: ''
})

// 状态管理
const showRegisterDialog = ref(false)
const showForgotPasswordDialog = ref(false)
const loginLoading = ref(false)



// 登录处理
const handleLogin = (data) => {

}

// 重置登录表单
const resetLoginForm = () => {
    loginFormData.value = {
        phone: '',
        password: '',
        rememberMe: false
    }
}

// 注册处理
const handleRegister = (data) => {
    console.log('注册表单数据：', data)
    ElMessage.success('注册成功，请登录')
    showRegisterDialog.value = false
    resetLoginForm()
}

// 重置密码处理
const handleResetPassword = (data) => {
    console.log('重置密码表单数据：', data)
    ElMessage.success('密码重置成功，请使用新密码登录')
    showForgotPasswordDialog.value = false
    resetLoginForm()
}


// 社交登录处理（模拟）
const handleSocialLogin = (type) => {
    ElMessage.info(`即将使用${type}账号登录`)
}
</script>
<style scoped>@import '@/assets/scss/base/_variables.scss';
@import '@/assets/scss/layouts/auth-container.scss';
@import '@/assets/scss/utils/form-elements.scss';
@import '@/assets/scss/utils/responsive.scss';
</style>