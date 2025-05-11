<template>
    <div class="bg-white rounded-xl p-5 card-shadow hover-lift">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">{{ title }}</p>
                <h3 class="text-2xl font-bold mt-1 mb-3">{{ count }}</h3>
                <p :class="getChangeClass()" class="text-xs flex items-center">
                    <i :class="getChangeIcon()" class="mr-1"></i> {{ change }} <span class="text-gray-500 ml-1">较上周</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-lg" :class="bgColor" :style="{ backgroundColor: bgColor }">
                <i :class="icon" :style="{ color: color }" class="text-xl"></i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        icon: String,
        title: String,
        count: String,
        change: String,
        color: String,
        bgColor: String
    },
    methods: {
        getChangeClass() {
            return this.change && this.change.includes('-') ? 'text-danger' : 'text-success';
        },
        getChangeIcon() {
            return this.change && this.change.includes('-') ? 'fa-solid fa-arrow-down' : 'fa-solid fa-arrow-up';
        }
    }
}
</script>

<style scoped>
/* 可根据需要添加额外样式 */
.card-shadow {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(22, 93, 255, 0.15);
}
</style>
