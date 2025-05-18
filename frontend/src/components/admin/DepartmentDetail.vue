<template>
    <div>
        <h3>部门 ID: {{ departmentId }}</h3>
        <p>这里是部门的详细信息。</p>
    </div>
</template>

<script>export default {
    name: 'DepartmentDetail',
    props: {
        departmentId: {
            type: [String, Number],
            required: true,
        },
    },
};
</script>