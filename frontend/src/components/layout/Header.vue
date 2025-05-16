<template>
    <header class="header">
        <!--        桌面端顶部栏-->
        <!-- 左侧公司名称 -->
            <div class="left-content flex items-center ">
                <img src="@/assets/vue.svg" alt="Logo" class="w-10 h-auto mx-auto">
                <span class="text-white  font-bold">Your Company Name</span>
            </div>
            <div class="flex items-center space-x-6  right-content">
                <div class="flex items-center space-x-4">
                    <!-- 通知按钮 -->
                    <button class="relative p-2 text-gray-500 hover:text-primary transition-colors">
                        <i class="fa-solid fa-bell text-xl text-white"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full"></span>
                    </button>
                    <!-- 用户信息区域（带下拉菜单） -->
                    <div class="flex items-center space-x-2 relative cursor-pointer"
                         @mouseenter="showDropdown = true"
                         @mouseleave="showDropdown = false"
                    >
                        <img
                                :src="currentUser.avatar"
                                alt="用户头像"
                                class="w-8 h-8 rounded-full object-cover border-2 border-primary"
                        />
                        <span class="font-medium text-white">{{ currentUser.name }}</span>
                        <!-- 下拉菜单 -->
                        <transition name="fade">
                            <div
                                    v-show="showDropdown"
                                    class="absolute right-0 top-10 mt-2 w-40 bg-white rounded-md shadow-lg z-50 py-1 origin-top-right transform opacity-100 scale-100 transition-all"
                            >
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">个人信息</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">修改密码</a>
                                <!-- 管理员专属菜单 -->
                                <a
                                        v-if="isUserAdmin"
                                        href="#"
                                        @click.prevent="handleAdminAccess"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    进入管理后台
                                </a>
                                <a href="#" @click.prevent="logout"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">退出登录</a>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
    </header>
</template>


<script setup>
import {computed, ref} from "vue";
import {useRouter} from 'vue-router'
import {clearAuthData, getAuthData} from '@/utils/auth'

const router = useRouter()
const showDropdown = ref(false)

// 从 localStorage 获取用户信息
const storedData = getAuthData()

const currentUser = ref(storedData ? storedData.user : {
    name: '游客',
    avatar: 'https://picsum.photos/id/1005/200/200',
    role: 'admin'
})
// 判断是否是管理员
const isUserAdmin = computed(()=>true)//(() => currentUser.role === 'admin')//调试
// 密码验证函数
const handleAdminAccess = () => {
    const enteredPassword = prompt('请输入管理员密码：')
    if (enteredPassword === '123789') {
        // 跳转到管理后台路由（需提前在 router 中定义）
        router.push('/admin')
    } else {
        alert('密码错误！')
    }
}
//退出登录
const logout = () => {

    // 从 localStorage 中移除用户信息
    clearAuthData()
    // 跳转到登录页
    router.push('/')
}
</script>


<style scoped>
.header {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    height: 45px;
    display: flex;
    align-items: center; /* 确保内容垂直居中 */
    justify-content: space-between; /* 两端对齐 */
}
.left-content, .right-content {
    display: flex;
    align-items: center;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>