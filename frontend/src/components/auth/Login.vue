<template>
    <el-form
            ref="formRef"
            :model="formData"
            :rules="rules"
            class="login-form"
            label-width="80px"
    >
        <div class="form-header">
            <h3 class="form-title">用户登录</h3>
        </div>

        <el-form-item label="手机号" prop="phone">
            <el-input v-model="formData.phone" placeholder="请输入手机号" prefix-icon="ElIconPhone"/>
        </el-form-item>

        <el-form-item label="密码" prop="password">
            <el-input
                    v-model="formData.password"
                    type="password"
                    placeholder="请输入密码"
                    prefix-icon="ElIconLock"
            />
        </el-form-item>
        <!-- 验证码 -->
        <el-form-item label="验证码" prop="captcha">
            <div class="flex items-center">
                <el-input v-model="formData.captcha" placeholder="请输入验证码" style="width: 50%"/>
                <img :src="captchaImage" alt="验证码" @click="refreshCaptcha" class="ml-2 captcha-img"
                     style="cursor: pointer; height: 36px;"/>
            </div>
        </el-form-item>

        <div class="remember-and-forgot">
            <el-checkbox v-model="formData.rememberMe">记住我</el-checkbox>
            <el-link class="auth-link forgot-password" @click="$emit('show-forgot-password')">
                <i class="fa fa-lock mr-1"></i> 忘记密码？
            </el-link>
        </div>

        <div class="button-container">
            <el-button
                    type="primary"
                    @click="handleSubmit"
                    class="login-btn"
                    :loading="localLoading"
            >
                登录
            </el-button>
            <el-button @click="handleReset">重置</el-button>
        </div>

        <div class="register-container">
            <el-link class="auth-link register" @click="$emit('show-register')">
                <i class="fa fa-user-plus mr-1"></i> 注册账号
            </el-link>
        </div>

    </el-form>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios' // ✅ 引入 axios
import {ElMessage} from 'element-plus'
import router from "@/router/index.js";
import {getAuthData, setAuthData} from "@/utils/auth.js";

const props = defineProps({
    loading: Boolean,
    initialData: {
        type: Object,
        default: () => ({
            phone: '',
            password: '',
            captcha: '',
            rememberMe: false
        })
    }
})


const emit = defineEmits(['submit', 'reset', 'social-login', 'show-register', 'show-forgot-password'])

const formData = ref({...props.initialData})
const localLoading = ref(false) // ✅ 使用本地 loading 控制

// 验证码图片地址
const captchaImage = ref('')
const captchaKey = ref('')

// 获取验证码
const refreshCaptcha = async () => {
    try {
        const res = await axios.get('http://localhost:8000/api/captcha')
        captchaImage.value = res.data.image
        captchaKey.value = res.data.key
    } catch (err) {
        ElMessage.error('验证码加载失败')
    }
}

// 表单验证规则
const rules = {
    phone: [
        {required: true, message: '请输入手机号', trigger: 'blur'},
        {pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号', trigger: 'blur'}
    ],
    password: [
        {required: true, message: '请输入密码', trigger: 'blur'},
        {min: 6, max: 20, message: '密码长度在6-20个字符', trigger: 'blur'}
    ],
    captcha: [
        {required: true, message: '请输入验证码', trigger: 'blur'},
        {len: 4, message: '验证码为4位字符', trigger: 'blur'}
    ]
}

const formRef = ref(null)

// 提交登录表单
const handleSubmit = () => {
    formRef.value.validate(async (valid) => {
        if (valid) {
            localLoading.value = true // ✅ 修改本地变量

            try {
                const response = await axios.post('http://localhost:8000/api/login', {
                    phone: formData.value.phone,
                    password: formData.value.password,
                    captcha_key: captchaKey.value,
                    captcha_code: formData.value.captcha
                })
                ElMessage.success('登录成功')
                const {token, user} = response.data

                const userData = {
                    phone: formData.value.phone,
                    name: user.name,
                }
                if (formData.value.rememberMe) {
                    userData.password = formData.value.password
                }
                console.log('保存用户信息：', userData)
                // 统一保存 token + user
                setAuthData({token, user: userData}, formData.value.rememberMe)

                emit('submit', formData.value)
                await router.push('/home')
            } catch (error) {
                if (error.response?.status === 401) {
                    ElMessage.error('手机号或密码错误')
                } else if (error.response?.status === 422) {
                    ElMessage.error('验证码错误')
                } else {
                    ElMessage.error('网络异常，请稍后再试')
                }
            } finally {
                localLoading.value = false
            }
        } else {
            ElMessage.error('表单验证失败，请检查输入')
            return false
        }
    })
}
// 重置表单
const handleReset = () => {
    formRef.value.resetFields()           // 重置表单字段 + 校验状态
    formData.value.captcha = ''           // 手动清空验证码输入框
    refreshCaptcha()                      // 刷新验证码图片
    emit('reset')                         // 通知父组件执行额外清理
}

onMounted(() => {
    refreshCaptcha()
    const authData = getAuthData()
    if (authData) {
        formData.value.phone = authData.user.phone || ''
        formData.value.rememberMe = true

        // 如果是“记住我”，可以填充密码（可选）
        if (authData.user.password) {
            formData.value.password = authData.user.password
        }
        console.log('从本地存储获取的用户信息：', authData)
    }
})
</script>
