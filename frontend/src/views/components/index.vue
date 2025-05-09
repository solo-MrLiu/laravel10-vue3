<template>
    <div id="app">
        <Navbar />
        <HeroSection />
        <CustomerLogos />
        <FeaturesSection />
        <!-- 可按需添加更多组件 -->
    </div>
</template>

<script setup>
import Navbar from './Navbar.vue'
import HeroSection from './HeroSection.vue'
import CustomerLogos from './CustomerLogos.vue'
import FeaturesSection from './FeaturesSection.vue'
</script>

<style scoped>
/* 全局样式可以放在这里 */
body {
    font-family: 'Inter', sans-serif;
    color: #1D2129;
    background-color: #F2F3F5;
}
/* 引入 Tailwind CSS 和 Font Awesome */
/*@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');*/
@tailwind base;
@tailwind components;
@tailwind utilities;

/* 自定义全局样式 */
@layer base {
    :root {
        --primary: #165DFF;
        --secondary: #00B42A;
        --accent: #FF7D00;
        --dark: #1D2129;
        --light: #F2F3F5;
    }
    body {
        font-family: 'Inter', sans-serif;
    }
}
</style>