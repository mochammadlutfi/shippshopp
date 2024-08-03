<template>
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="space-x-1">
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="space-x-1">
                <!-- User Dropdown -->
                <el-dropdown trigger="click" popper-class="dropdown-user">
                    <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user d-sm-none"></i>
                        <span class="d-none d-sm-inline-block ms-2">{{ $page.props.user.name }}</span>
                        <i class="fa fa-angle-down opacity-50 ms-1"></i>
                    </button>
                    <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item>
                            <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="#">
                                <span class="fs-sm fw-500">Profil</span>
                                <i class="fa fa-fw fa-user opacity-25"></i>
                            </a>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="#">
                                <span class="fs-sm fw-500">Password</span>
                                <i class="fa fa-fw fa-lock opacity-25"></i>
                            </a>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <Link :href="route('admin.logout')" method="post" as="button" type="button" class="btn-logout dropdown-item d-flex align-items-center justify-content-between space-x-1">
                                <span class="fs-sm fw-500">Keluar</span>
                                <i class="fa fa-fw fa-sign-out-alt opacity-25"></i>
                            </Link>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                    </template>
                </el-dropdown>
                <!-- END User Dropdown -->
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->
    </header>
</template>

<style scoped>
.btn-logout {
    display: inline-block;
    text-align: start;
    user-select: none;
    background: transparent;
    border: none;
    padding: 0;
    width: 100%;
}
</style>

<script>
import { Link } from '@inertiajs/vue3';
export default {
    name : 'BaseHeader',
    components : {
        Link,
    },
    setup() {
        
    },
}
</script>