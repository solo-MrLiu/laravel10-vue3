<template>
    <aside class="sidebar">
        <ul>
            <li><a href="#" @click="toggleMenu('system')">系统设置</a></li>
            <ul v-if="activeMenu==='system'">
                <li><a href="#">通用配置</a></li>
                <li><a href="#">服务集成</a></li>
                <li><a href="#">API密钥</a></li>
            </ul>
            <li><a href="#" @click="toggleMenu('user')">用户管理</a></li>
            <ul v-if="activeMenu==='user'">
                <li><a href="#">部门用户</a></li>
                <li><a href="#">角色权限</a></li>
                <li><a href="#">团队</a></li>
            </ul>
            <li><a href="#">业务管理</a></li>
            <li><a href="#">报表管理</a></li>
            <li><a href="#">项目管理</a></li>
            <li><a href="#">文件管理</a></li>
            <li><a href="#">日志管理</a></li>
        </ul>
    </aside>
</template>

<script>
export default {
    data() {
        return {
            activeMenu: null
        };
    },
    methods: {
        toggleMenu(menu) {
            this.activeMenu = this.activeMenu === menu ? null : menu;
        }
    }
};
</script>

<style scoped>
.sidebar {
    width: 150px;
    background-color: #f4f4f4;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 45px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar li {
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

.sidebar a {
    text-decoration: none;
    color: #333;
}
</style>