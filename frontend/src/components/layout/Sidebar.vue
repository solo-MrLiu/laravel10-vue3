<template>
    <aside class="sidebar">
        <router-view></router-view>
    </aside>
</template>

<script>

</script>

<style scoped>
.sidebar {
    width: 200px;
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