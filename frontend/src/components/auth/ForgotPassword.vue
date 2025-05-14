<template>
    <el-dialog
            v-model="dialogVisible"
            @update:visible="dialogVisible = $event"
            :title="'重置密码'"
            :width="dialogWidth"
            :before-close="handleClose"
            custom-class="auth-dialog"
    >
        <el-form ref="formRef" :model="formData" :rules="rules" label-width="80px">
            <!-- 手机号 -->
            <el-form-item label="手机号" prop="phone">
                <el-input v-model="formData.phone" placeholder="请输入手机号" prefix-icon="ElIconPhone" />
            </el-form-item>

            <!-- 验证码 -->
            <el-form-item label="验证码" prop="code">
                <div class="flex items-center">
                    <el-input
                            v-model="formData.code"
                            placeholder="请输入验证码"
                            prefix-icon="ElIconVerificationCode"
                            style="width: 60%"
                    />
                    <el-button
                            :disabled="countdown > 0"
                            @click="sendCode"
                            :loading="sendingCode"
                            class="get-code-btn ml-2"
                    >
                        {{ countdown > 0 ? `${countdown}s后重新发送` : '获取验证码' }}
                    </el-button>
                </div>
            </el-form-item>

            <!-- 新密码 -->
            <el-form-item label="新密码" prop="password">
                <el-input
                        v-model="formData.password"
                        type="password"
                        placeholder="请输入新密码"
                        prefix-icon="ElIconLock"
                />
            </el-form-item>
        </el-form>

        <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleSubmit">重置密码</el-button>
      </span>
        </template>
    </el-dialog>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { ElMessage } from 'element-plus'
import axios from "axios";

// Props
const props = defineProps({
    visible: {
        type: Boolean,
        default: false
    },
    initialData: {
        type: Object,
        default: () => ({
            phone: '',
            code: '',
            password: ''
        })
    }
})

// Emits
const emit = defineEmits(['update:visible', 'submit'])

// 响应式状态
const dialogVisible = ref(props.visible)
const formData = ref({ ...props.initialData })
const countdown = ref(0)
const sendingCode = ref(false)
const dialogWidth = ref('30%')

// 表单验证规则
const rules = {
    phone: [
        { required: true, message: '请输入手机号', trigger: 'blur' },
        { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号', trigger: 'blur' }
    ],
    code: [
        { required: true, message: '请输入验证码', trigger: 'blur' },
        { min: 6, max: 6, message: '验证码为6位数字', trigger: 'blur' }
    ],
    password: [
        { required: true, message: '请输入新密码', trigger: 'blur' },
        { min: 6, max: 20, message: '密码长度在6-20个字符', trigger: 'blur' }
    ]
}

// Refs
const formRef = ref(null)

// 监听 props.visible 并同步到本地状态
watch(
    () => props.visible,
    (newVal) => {
        dialogVisible.value = newVal
    }
)

// 同步内部状态回父组件
watch(
    () => dialogVisible.value,
    (newVal) => {
        emit('update:visible', newVal)
    }
)

// 处理窗口大小变化
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

// 提交处理
const handleSubmit = () => {
    formRef.value.validate(async (valid) => {
        if (valid) {
            try {
                const response = await axios.post('http://localhost:8000/api/reset-password', {
                    phone: formData.value.phone,
                    code: formData.value.code,
                    password: formData.value.password
                })

                if (response.data.success) {
                    ElMessage.success('密码重置成功，请使用新密码登录')
                    formRef.value.resetFields()
                    emit('submit', formData.value)
                    dialogVisible.value = false
                } else {
                    ElMessage.error(response.data.message || '密码重置失败')
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    ElMessage.error(error.response.data.message || '验证码错误或手机号未注册')
                } else if (error.response && error.response.status === 400) {
                    ElMessage.error(error.response.data.message || '无效或过期的重置链接')
                } else {
                    ElMessage.error('网络异常，请稍后再试')
                }
            }
        } else {
            ElMessage.error('表单验证失败，请检查输入')
            return false
        }
    })
}

// 关闭前确认
const handleClose = (done) => {
    if (formData.value.phone || formData.value.code || formData.value.password) {
        const confirmLeave = window.confirm('您有未提交的数据，确定要关闭吗？')
        if (confirmLeave) {
            formRef.value.resetFields()
            countdown.value = 0
            done()
        }
    } else {
        formRef.value.resetFields()
        countdown.value = 0
        done()
    }
}
</script>

<style scoped>
.get-code-btn {
    height: 40px;
    background-color: #6366f1;
    border-color: #6366f1;
    color: white;
    transition: all 0.3s ease;
}

.get-code-btn:hover:not(:disabled) {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

.get-code-btn:disabled {
    background-color: #e6e6e6;
    border-color: #e6e6e6;
    color: #999;
}
</style>
