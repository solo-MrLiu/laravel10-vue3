<template>
    <aside class="nav-menu">
        <ul class="menu-list">
            <li v-for="(item, index) in menuItems" :key="item.title" class="menu-item">
                <a
                        href="#"
                        @click.prevent="handleClick(item)"
                        :class="{ active: isActive(item) }"
                        class="menu-link"
                >
                    {{ item.title }}
                </a>

                <!-- 子菜单 -->
                <ul
                        v-if="item.children && item.children.length > 0"
                        v-show="expanded === item.title"
                        class="submenu"
                >
                    <li v-for="child in item.children" :key="child.title" class="submenu-item">
                        <router-link :to="child.path" class="submenu-link">{{ child.title }}</router-link>
                    </li>
                </ul>

                <!-- 分隔线 -->
                <div v-if="index < menuItems.length - 1" class="divider"></div>
            </li>
        </ul>
    </aside>
</template>

<script>export default {
    name: 'NavMenu',
    data() {
        return {
            menuItems: [],
            expanded: null,
            loading: true
        };
    },
    mounted() {
        this.fetchMenuData();
    },
    watch: {
        '$route': {
            immediate: true,
            handler(newRoute) {
                for (const item of this.menuItems) {
                    if (
                        item.children &&
                        item.children.some(child => newRoute.path.startsWith(child.path))
                    ) {
                        this.expanded = item.title;
                        break;
                    }
                }
            }
        }
    },
    methods: {
        async fetchMenuData() {
            try {
                const res = {
                    data: [
                        {
                            title: '系统设置',
                            children: [
                                {title: '通用配置', path: '/settings/general'},
                                {title: '服务集成', path: '/settings/services'}
                            ]
                        },
                        {
                            title: '用户管理',
                            children: [
                                {title: '部门用户', path: '/admin/user'},
                                {title: '角色权限', path: '/admin/role'},
                            ]
                        },
                        {
                            title: '业务管理',
                            children: [
                                {title: '分类数据', path: '/admin/classification'},
                                {title: '业务实体', path: '/admin/entity'},
                                {title: '审批流程', path: '/admin/role'},
                                {title: '触发器', path: '/admin/role'},
                            ]
                        },
                        {
                            title: '商城管理',
                            children: [
                                {title: '商城装修', path: '/admin/user'},
                                {title: '产品管理', path: '/admin/role'},
                                {title: '价格管理', path: '/admin/role'},
                                {title: '会员管理', path: '/admin/role'},
                                {title: '充值管理', path: '/admin/role'},
                                {title: '优惠券管理', path: '/admin/role'},
                                {title: '促销管理', path: '/admin/role'},
                            ]
                        },                        {
                            title: '产品追溯',
                            children: [
                                {title: '生产线管理', path: '/admin/user'},
                                {title: '生成追溯码', path: '/admin/role'},
                                {title: '入库扫码', path: '/admin/role'},
                                {title: '出库扫码', path: '/admin/role'},
                                {title: '反向追溯', path: '/admin/role'},
                                {title: '异常预警', path: '/admin/role'},
                            ]
                        },
                        {
                            title: '报表管理',
                            path: '/projects'
                        },
                        {
                            title: '项目管理',
                            path: '/projects'
                        },
                        {
                            title: '文件管理',
                            path: '/projects'
                        },
                        {
                            title: '日志管理',
                            path: '/projects'
                        },
                    ]
                };

                this.menuItems = res.data;
            } catch (err) {
                console.error('获取菜单失败:', err);
            } finally {
                this.loading = false;
            }
        },
        handleClick(item) {
            if (item.children && item.children.length > 0) {
                this.expanded = this.expanded === item.title ? null : item.title;
            } else if (item.path) {
                this.$router.push(item.path);
            }
        },
        isActive(item) {
            if (item.path) {
                return this.$route.path.startsWith(item.path);
            }
            return false;
        }
    }
};
</script>

<style scoped>
.nav-menu {
    width: 170px;
    height: 100vh;
    background-color: #f4f4f4;
    position: fixed;
    left: 0;
    top: 45px;
}

.menu-list {
    list-style: none;
    margin: 0;
    padding: 0;
    width: 150px;
}

.menu-item {
    position: relative;
    padding-bottom: 5px; /* 留出空间给 divider */
}

.menu-link {
    display: block;
    padding: 5px 5px 5px 20px;
    color: #333;
    text-decoration: none;
    cursor: pointer;
    width: 100%;
    font-size: 14px;
}

.menu-link:hover,
.menu-link.active {
    background-color: #dcdfe6;
    color: blue;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

/* 子菜单样式 */
.submenu {
    list-style: none;
    margin: 5px 0 0 10px;
    padding-left: 10px;
    border-left: 1px solid #ccc;
}

.submenu-item {
    margin-bottom: 5px;
}

.submenu-link {
    display: block;
    padding: 8px 10px;
    color: #555;
    text-decoration: none;
    border-radius: 4px;
    font-size: 12px;
}

.submenu-link.router-link-active {
    background-color: #c9cfd8;
    color: blue;
}

/* 分隔线样式 */
.divider {
    height: 1px;
    background-color: #ddd;
    margin: 2px 2px 2px 0;
}
</style>