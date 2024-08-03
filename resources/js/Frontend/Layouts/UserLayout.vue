<template>
    <el-config-provider namespace="ep" :locale="locale">
        
        <Head>
            <title>{{ title }}</title>
            <meta name="description" content="Kang Ebod, Makhfudz Solaeman">
        </Head>
        <div id="page-container" :class="classContainer">
            <base-header/>
            <!-- Main Container -->
            <div id="main-container">
                <div class="content">
                    <el-row :gutter="30">
                        <el-col :lg="6">
                            <div class="block rounded">
                                <div class="block-content p-2">
                                    <ul class="nav-main nav-main-landing">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" :class="(route().current('user.profil')) ? 'active' : ''" :href="route('user.profil')">
                                                <i class="nav-main-link-icon fa fa-user"></i>
                                                <span class="nav-main-link-name">Profil</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" :class="(route().current('user.order.*')) ? 'active' : ''" :href="route('user.order.index')">
                                                <i class="nav-main-link-icon si si-notebook"></i>
                                                <span class="nav-main-link-name">Pesanan Saya</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" :class="(route().current('user.address.*')) ? 'active' : ''" :href="route('user.address.index')">
                                                <i class="nav-main-link-icon si si-map"></i>
                                                <span class="nav-main-link-name">Buku Alamat</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" :class="(route().current('user.return.*')) ? 'active' : ''" :href="route('user.return.index')">
                                                <i class="nav-main-link-icon si si-action-undo"></i>
                                                <span class="nav-main-link-name">Retur Pesanan</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </el-col>
                        <el-col :lg="18">
                            <slot />
                        </el-col>
                    </el-row>
                </div>
            </div>
            <!-- END Main Container -->
        </div>
    </el-config-provider>
</template>
  
<style scoped>
.nav-main-link {
    min-height: 3rem;
}
.nav-main-link.active {
    border-left : 3px solid var(--ep-color-primary);
}

</style>
<script>
    import BaseHeader from './BaseHeader.vue';
    import { Head } from '@inertiajs/vue3';
    import id from 'element-plus/dist/locale/id.mjs'
    export default {
        name: 'UserLayout',
        components: {
            Head,
            ElConfigProvider,
            BaseHeader
        },
        props: {
            title : {
                type : String,
                default : ""
            }
        },
        data(){
            return {
                sidebar : true,
                limitPosition: 500,
                scrolled: false,
                lastPosition: 0,
            }
        },
        computed : {  
            classContainer() {
                return {
                    'landing' : true,
                    'dark-mode' : true,
                    'side-scroll': true,
                    'main-content-boxed': true,
                    'side-trans-enabled': true,
                    'page-header-fixed': this.scrolled,
                    'sidebar-o-xs': this.sidebar,
                    'page-header-dark' : true,
                }
            },
        },
        beforeMount() {
            window.addEventListener("scroll", this.handleScroll);
        },
        methods : {
            
            handleScroll() {
            if (100 < window.scrollY) {
                this.scrolled = true;
                // move up!
            }

            if (100 > window.scrollY) {
                this.scrolled = false;
                // move down
            }

            this.lastPosition = window.scrollY;
            },
        },
        setup() {
            return {zIndex: 3000, size: 'small', locale: id}
        }
    }
</script>