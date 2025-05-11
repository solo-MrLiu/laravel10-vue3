<template>
    <el-dialog
            v-model="dialogVisible"
            @update:visible="dialogVisible = $event"
            title="用户注册"
            :width="dialogWidth"
            :before-close="handleClose"
            custom-class="auth-dialog"
    >

        <template #default>
            <el-form
                    ref="formRef"
                    :model="formData"
                    :rules="rules"
                    label-width="80px"
            >
                <el-form-item label="手机号" prop="phone">
                    <el-input v-model="formData.phone" placeholder="请输入手机号" prefix-icon="ElIconPhone"/>
                </el-form-item>

                <el-form-item label="验证码" prop="code">
                    <el-input
                            v-model="formData.code"
                            placeholder="请输入验证码"
                            prefix-icon="ElIconVerificationCode"
                            style="width: 60%; margin-right: 10px"
                    />
                    <el-button
                            :disabled="countdown > 0"
                            @click="sendCode"
                            :loading="sendingCode"
                            class="get-code-btn"
                    >
                        {{ countdown > 0 ? `${countdown}s后重新发送` : '获取验证码' }}
                    </el-button>
                </el-form-item>

                <el-form-item label="密码" prop="password">
                    <el-input
                            v-model="formData.password"
                            type="password"
                            placeholder="请输入密码"
                            prefix-icon="ElIconLock"
                    />
                </el-form-item>

                <el-form-item label="确认密码" prop="confirmPassword">
                    <el-input
                            v-model="formData.confirmPassword"
                            type="password"
                            placeholder="请再次输入密码"
                            prefix-icon="ElIconLock"
                    />
                </el-form-item>
            </el-form>
        </template>
        <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleSubmit">注册</el-button>
      </span>
        </template>
    </el-dialog>
</template>

<script setup>
import {ref, watch, onMounted, onUnmounted} from 'vue'
import axios from 'axios'
import {ElMessage} from 'element-plus'

const props = defineProps({
    visible: Boolean,
    initialData: {
        type: Object,
        default: () => ({
            phone: '',
            code: '',
            password: '',
            confirmPassword: ''
        })
    }
})

const emit = defineEmits(['update:visible', 'submit'])

const dialogVisible = ref(false)
const formData = ref({...props.initialData})


// 👇 新增：定义 dialogWidth 响应式变量
const dialogWidth = ref('30%') // 默认桌面端宽度

// 监听窗口大小变化
const handleResize = () => {
    dialogWidth.value = window.innerWidth < 768 ? '90%' : '30%'
}

onMounted(() => {
    handleResize()
    window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
})
watch(() => props.visible, (newVal) => {
    dialogVisible.value = newVal
})

watch(() => dialogVisible.value, (newVal) => {
    emit('update:visible', newVal)
})

const rules = {
    phone: [
        {required: true, message: '请输入手机号', trigger: 'blur'},
        {pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号', trigger: 'blur'}
    ],
    code: [
        {required: true, message: '请输入验证码', trigger: 'blur'},
        {min: 6, max: 6, message: '验证码为6位数字', trigger: 'blur'}
    ],
    password: [
        {required: true, message: '请输入密码', trigger: 'blur'},
        {min: 6, max: 20, message: '密码长度在6-20个字符', trigger: 'blur'}
    ],
    confirmPassword: [
        {required: true, message: '请确认密码', trigger: 'blur'},
        {
            validator: (rule, value, callback) => {
                if (value !== formData.value.password) {
                    callback(new Error('两次输入的密码不一致'))
                } else {
                    callback()
                }
            }, trigger: 'blur'
        }
    ]
}

const formRef = ref(null)

// 验证码倒计时
const sendingCode = ref(false)
const countdown = ref(0)

// 同步 visible 控制
watch(
    () => props.visible,
    (newVal) => {
        dialogVisible.value = newVal
    }
)
watch(dialogVisible, (newVal) => {
    emit('update:visible', newVal)
})
// 发送验证码
const sendCode = () => {

    formRef.value.validateField('phone', async (error) => {


        if (!error) {
            return
        }

        sendingCode.value = true
        try {
            const res = await axios.post('http://localhost:8000/api/send-code', {
                phone: formData.value.phone
            })

            if (res.data.success) {
                ElMessage.success('验证码已发送')
                countdown.value = 60
                const timer = setInterval(() => {
                    countdown.value--
                    if (countdown.value <= 0) clearInterval(timer)
                }, 1000)
            } else {
                ElMessage.error(res.data.message || '验证码发送失败')
            }
        } catch (err) {
            ElMessage.error('网络异常，请稍后再试')
        } finally {
            sendingCode.value = false
        }
    })
}

// 提交注册
const handleSubmit = () => {
    formRef.value.validate(async (valid) => {
        if (valid) {
            try {
                const res = await axios.post('http://localhost:8000/api/register', {
                    phone: formData.value.phone,
                    code: formData.value.code,
                    password: formData.value.password,
                    password_confirmation : formData.value.confirmPassword
                })

                if (res.data.success) {
                    ElMessage.success('注册成功')
                    emit('submit', formData.value)
                    dialogVisible.value = false
                } else {
                    ElMessage.error(res.data.message || '注册失败')
                }
            } catch (err) {
                ElMessage.error('网络异常，请稍后再试')
            }
        } else {
            ElMessage.error('表单验证失败，请检查输入')
            return false
        }
    })
}

const handleClose = (done) => {
    formRef.value.resetFields()
    countdown.value = 0
    dialogVisible.value = false
    done()
}
</script>
