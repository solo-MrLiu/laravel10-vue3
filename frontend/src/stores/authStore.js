import { defineStore } from 'pinia'
import { ref } from 'vue'
import { login as loginApi } from '@/api/auth'

export const useAuthStore = defineStore('auth', () => {
    // 状态
    const user = ref(null)
    const token = ref(null)
    const isAuthenticated = ref(false)
    const loading = ref(false)
    const error = ref(null)

    // 获取存储的用户信息
    function restoreFromLocalStorage() {
        const storedUser = localStorage.getItem('auth_user')
        if (storedUser) {
            const parsedUser = JSON.parse(storedUser)
            user.value = parsedUser.user
            token.value = parsedUser.token
            isAuthenticated.value = true
        }
    }

    // 登录
    async function login(credentials) {
        loading.value = true
        error.value = null

        try {
            const response = await loginApi(credentials)
            const { user: userData, token: userToken } = response.data

            user.value = userData
            token.value = userToken
            isAuthenticated.value = true

            if (credentials.rememberMe) {
                localStorage.setItem('auth_user', JSON.stringify({
                    user: userData,
                    token: userToken
                }))
            }

            return userData
        } catch (err) {
            error.value = err.response?.data?.message || '登录失败'
            isAuthenticated.value = false
            throw err
        } finally {
            loading.value = false
        }
    }

    // 登出
    function logout() {
        user.value = null
        token.value = null
        isAuthenticated.value = false
        localStorage.removeItem('auth_user')
    }

    // 获取用户信息
    function getUser() {
        return user.value
    }

    // 获取token
    function getToken() {
        return token.value
    }

    // 检查是否认证
    function checkAuth() {
        return isAuthenticated.value
    }

    return {
        user,
        token,
        isAuthenticated,
        loading,
        error,
        restoreFromLocalStorage,
        login,
        logout,
        getUser,
        getToken,
        checkAuth
    }
})
