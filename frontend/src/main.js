import {createApp} from 'vue'
import './assets/scss/main.scss'
import App from './App.vue'
import router from "./router";
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import axios from 'axios';

// 设置 axios 的响应拦截器
axios.interceptors.response.use(
    // 如果响应正常，直接返回响应数据
    response => response,

    // 如果发生错误
    error => {
        // 检查错误对象是否包含响应，并且响应状态码是否为 401（未授权）
        if (error.response && error.response.status === 401) {
            // 如果是 401 错误，跳转到登录页面
            router.push('/login');
        }

        // 其他错误或非 401 错误，继续抛出以便在调用处处理
        return Promise.reject(error);
    }
);


const app = createApp(App);
app.use(router);
app.use(ElementPlus);
app.mount('#app');