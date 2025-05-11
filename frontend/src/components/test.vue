<template>
    <div>
        <h2>注册弹窗测试页</h2>
        <RegisterComponent :visible="true" />
    </div>
</template>

<script setup>
import RegisterComponent from '@/components/auth/Register.vue'
</script>
