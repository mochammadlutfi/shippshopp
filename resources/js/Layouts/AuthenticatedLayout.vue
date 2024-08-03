<template>
    <el-config-provider namespace="ep" :locale="locale">
        
        <el-container id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header main-content-boxed">
            <Head>
                <title>{{ title }}</title>
                <meta name="description" content="Your page description">
            </Head>
            <base-sidebar/>

            <base-header/>
            
            <!-- <el-header>Header</el-header> -->

            <el-main id="main-container">
                <slot />
            </el-main>

        </el-container>
    </el-config-provider>
</template>
  
<script>
    import { Head } from '@inertiajs/vue3'
//   import { ElConfigProvider } from 'element-plus';
    import id from 'element-plus/dist/locale/id.mjs'
    import BaseHeader from './BaseHeader.vue';
    import BaseSidebar from './BaseSidebar.vue';
    export default {
        name : 'AuthenticatedLayout',
        components: {
            Head,
            BaseHeader,
            BaseSidebar,
            ElConfigProvider,
        },
        props : {
            title : {
                type : String,
                default : ""
            }
        },
        setup() {
            return {
                zIndex: 3000,
                size: 'small',
                locale : id,
            }
        },
    }
  </script>