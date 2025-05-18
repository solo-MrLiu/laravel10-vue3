<template>
    <div class="member-list-container">
        <!-- 搜索和操作按钮 -->
        <div class="search-and-actions">
            <el-input
                    placeholder="快速查询"
                    v-model="searchQuery"
                    @input="handleSearch"
                    style="width: 240px;"
            >
                <template #prefix>
                    <el-icon>
                        <Search/>
                    </el-icon>
                </template>
            </el-input>

            <div class="action-buttons">
                <el-button type="primary" @click="addUser">新建用户
                    <el-icon>
                        <UserFilled/>
                    </el-icon>
                </el-button>
                <el-dropdown @command="handleMoreCommand">
                    <el-button type="primary">
                        更多
                        <el-icon>
                            <ArrowDown/>
                        </el-icon>
                    </el-button>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item command="importData">导入</el-dropdown-item>
                            <el-dropdown-item command="showColumns">显示列</el-dropdown-item>
                            <el-dropdown-item command="transferJob">离职继任</el-dropdown-item>
                            <el-dropdown-item command="batchOperate">批量操作</el-dropdown-item>
                            <el-dropdown-item command="batchDelete">批量删除</el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </div>
        </div>

        <!-- 新建用户弹窗 -->
        <el-dialog title="新建用户" v-model="dialogVisible" width="50%">
            <el-form :model="newUserForm" label-width="100px">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="姓名">
                            <el-input v-model="newUserForm.name"/>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="用户名">
                            <el-input v-model="newUserForm.username"/>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="邮箱">
                            <el-input v-model="newUserForm.email"/>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="电话">
                            <el-input v-model="newUserForm.phone"/>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="角色">
                            <el-select v-model="newUserForm.role" placeholder="请选择角色">
                                <el-option label="管理员" value="admin"/>
                                <el-option label="普通用户" value="user"/>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="是否禁用">
                            <el-switch v-model="newUserForm.disabled"/>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>

            <template #footer>
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="submitNewUser">提交</el-button>
            </template>
        </el-dialog>

        <!-- 表格 -->
        <el-table :data="filteredMembers" @selection-change="handleSelectionChange">
            <el-table-column type="selection"></el-table-column>
            <el-table-column prop="name" label="姓名"></el-table-column>
            <el-table-column prop="position" label="职务"></el-table-column>
            <el-table-column prop="department" label="部门"></el-table-column>
            <el-table-column prop="phone" label="工作电话"></el-table-column>
            <el-table-column prop="email" label="邮箱"></el-table-column>
            <el-table-column prop="username" label="用户名"></el-table-column>
            <el-table-column prop="role" label="角色"></el-table-column>
            <el-table-column prop="disabled" label="是否禁用"></el-table-column>
        </el-table>
    </div>
</template>


<script>



export default {
    props: {
        departmentId: {
            type: [String, Number],
            required: true,
        },
    },
    handleselectdata(){
      return{
          selectedRows: [],
      }
    },
    data() {
        return {
            searchQuery: '',
            dialogVisible: false,
            newUserForm: {
                name: '',
                username: '',
                email: '',
                phone: '',
                role: '',
                disabled: false,
            },
            members: [
                {
                    id: 1,
                    name: '测试',
                    position: '',
                    department: 'RB示例部门',
                    phone: '',
                    email: '',
                    username: '123456',
                    role: 'RB示例角色',
                    disabled: '否'
                },
                {
                    id: 2,
                    name: 'RB示例用户',
                    position: '',
                    department: 'RB示例部门',
                    phone: '',
                    email: '',
                    username: 'rebuild',
                    role: 'RB示例角色',
                    disabled: '否'
                },
                {
                    id: 3,
                    name: '超级管理员',
                    position: '',
                    department: '总部',
                    phone: '',
                    email: '',
                    username: 'admin',
                    role: '管理员',
                    disabled: '否'
                },
            ],
        };
    },
    computed: {
        filteredMembers() {
            return this.members.filter(member =>
                member.name.includes(this.searchQuery) ||
                member.department.includes(this.searchQuery) ||
                member.username.includes(this.searchQuery)
            );
        },
    },
    methods: {
        handleSearch() {
        },
        addUser() {
            this.dialogVisible = true;
        },
        submitNewUser() {
            // 提交新建用户逻辑
            console.log('提交新用户:', this.newUserForm);
            this.dialogVisible = false;
        },
        handleMoreCommand(command) {
            switch (command) {
                case 'importData':
                    this.importData();
                    break;
                case 'showColumns':
                    this.showColumns();
                    break;
                case 'transferJob':
                    this.transferJob();
                    break;
                case 'batchOperate':
                    this.batchOperate();
                    break;
                case 'batchDelete':
                    this.batchDelete();
                    break;
                default:
                    console.warn('未知命令:', command);
            }
        },
        importData() {
            console.log('Import data');
        },
        showColumns() {
            console.log('Show columns');
        },
        transferJob() {
            console.log('Transfer job');
        },
        batchOperate() {
            console.log('Batch operate');
        },
        batchDelete() {
            console.log('Batch delete');
        },
        handleSelectionChange(selection) {
            this.selectedRows = selection;
            console.log('当前选中行:', this.selectedRows);
        },
    },
};

</script>
<style scoped>
.member-list-container {
    padding: 10px;
}

.search-and-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}
</style>
