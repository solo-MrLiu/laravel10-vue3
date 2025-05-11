import {createRouter, createWebHistory} from 'vue-router';

import Auth from '../views/auth/Auth.vue';

const routes = [

    {
        path: '/',
        name: 'login',
        component: Auth
    },




];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

// 路由守卫：验证登录状态
router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !localStorage.getItem('access_token')) {
        alert('请先登录！');
        next('/login');
    } else {
        next();
    }
});

export default router;