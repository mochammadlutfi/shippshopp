<template>
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="space-x-2 d-flex align-items-center">
                <!-- Logo -->
                <a :href="route('home')">
                    <img src="/images/logo/logo.png" height="70"/>
                </a>
                <!-- END Logo -->
            </div>
            <!-- END Left Section -->
            <div class="d-none d-lg-block">
                <!-- Header Navigation -->
                <!-- Desktop Navigation, mobile navigation can be found in #sidebar -->
                <!-- <ul class="nav-main nav-main-horizontal nav-main-hover">
                    <li class="nav-main-item">
                        <a class="nav-main-link" :href="route('home')">
                            <span class="nav-main-link-name">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" :href="route('about')">
                            <span class="nav-main-link-name">Tentang</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" :href="route('product.index')">
                            <span class="nav-main-link-name">Produk</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" :href="route('tutorial')">
                            <span class="nav-main-link-name">Panduan</span>
                        </a>
                    </li>
                </ul> -->
                <!-- END Header Navigation -->
            </div>
            <!-- Right Section -->
            <div class="space-x-1 d-flex">
                <cart-header class="me-3"/>

                <template v-if="$page.props.user">
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
                                <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1"
                                 :href="route('user.profil')">
                                    <span class="fs-sm fw-500">Profil</span>
                                    <i class="fa fa-fw fa-user opacity-25"></i>
                                </a>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" 
                                :href="route('user.order.index')">
                                    <span class="fs-sm fw-500">Pesanan Saya</span>
                                    <i class="fa fa-fw fa-lock opacity-25"></i>
                                </a>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" 
                                :href="route('user.address.index')">
                                    <span class="fs-sm fw-500">Buku Alamat</span>
                                    <i class="si fa-fw si-map opacity-25"></i>
                                </a>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" 
                                :href="route('user.password')">
                                    <span class="fs-sm fw-500">Password</span>
                                    <i class="fa fa-fw fa-lock opacity-25"></i>
                                </a>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <Link :href="route('logout')" method="post" as="button" type="button" class="btn-logout dropdown-item d-flex align-items-center justify-content-between space-x-1">
                                    <span class="fs-sm fw-500">Keluar</span>
                                    <i class="fa fa-fw fa-sign-out-alt opacity-25"></i>
                                </Link>
                            </el-dropdown-item>
                        </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </template>    
                <template v-else>
                    <a class="ep-button ep-button--primary my-md-auto d-none d-lg-block"
                        :href="route('register')">
                        Daftar 
                    </a>
                    <a class="ep-button ep-button--primary is-plain my-md-auto d-none d-lg-block"
                        :href="route('login')"> 
                        Masuk 
                    </a>
                </template>
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->
    </header>
</template>

<style>
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
import CartHeader from '../Cart/CartHeader.vue';
import { Link } from '@inertiajs/vue3';

export default {
    name : "BaseHeader",
    components : {
        Link,
        CartHeader
    },
    methods:{
        doLogout(){
            alert('sa')
        }
    }
}
</script>
