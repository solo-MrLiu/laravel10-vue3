import {createRouter, createWebHistory} from 'vue-router';

import Auth from '../views/auth/Auth.vue';
import Home from '../views/home/Home.vue';
import Admin from '../views/admin/Admin.vue';

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
    {
        path: '/admin',
        name: 'admin',
        component: Admin,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/admin/user',
                name: 'User',
                component: () => import('../components/admin/UserManage.vue'),
                meta: {
                    requiresAuth: true,
                    keepalive: false
                }
            },
            {
                path: '/admin/role',
                name: 'Role',
                component: () => import('../components/admin/UserManagement.vue'),
                meta: {
                    requiresAuth: true,
                    keepalive: false
                }
            },
            {
                path: '/admin/classification',
                name: 'Classification',
                component: () => import('../components/admin/ClassificationSelector.vue'),
                meta: {
                    requiresAuth: true,
                    keepalive: false
                }
            },
        ]
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