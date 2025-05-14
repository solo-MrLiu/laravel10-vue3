// src/utils/auth.js

const AUTH_TOKEN_KEY = 'auth_data' // 统一命名，包含 user 和 token

export const setAuthData = (data, remember = false) => {
    const storage = remember ? localStorage : sessionStorage
    storage.setItem(AUTH_TOKEN_KEY, JSON.stringify(data))
}

export const getAuthData = () => {
    const localData = localStorage.getItem(AUTH_TOKEN_KEY)
    const sessionData = sessionStorage.getItem(AUTH_TOKEN_KEY)

    return localData ? JSON.parse(localData) : sessionData ? JSON.parse(sessionData) : null
}

export const clearAuthData = () => {
    localStorage.removeItem(AUTH_TOKEN_KEY)
    sessionStorage.removeItem(AUTH_TOKEN_KEY)
}