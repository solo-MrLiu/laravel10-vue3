import {createRouter, createWebHistory} from 'vue-router';

import Auth from '../views/auth/Auth.vue';
import Home from '../views/home/Home.vue';

const routes = [

    {
        path: '/',
        name: 'login',
        component: Auth
    },
    {
        path: '/home',
        name: 'home',
        component: Home,
        meta: {
            requiresAuth: true
        }

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
        next('/');
    } else {
        next();
    }
});

export default router;