import axios from 'axios'

const apiClient = axios.create({
    baseURL: process.env.VUE_APP_API_URL, // 从环境变量获取API基础URL
    timeout: 10000, // 超时时间
})

// 登录接口
export const login = (credentials) => {
    return apiClient.post('/auth/login', credentials)
}

// 注册接口
export const register = (userData) => {
    return apiClient.post('/auth/register', userData)
}

// 发送验证码接口
export const sendVerificationCode = (phone) => {
    return apiClient.post('/auth/send-code', { phone })
}

// 重置密码接口
export const resetPassword = (data) => {
    return apiClient.post('/auth/reset-password', data)
}

// 社交登录接口
export const socialLogin = (provider, token) => {
    return apiClient.post(`/auth/${provider}`, { token })
}
