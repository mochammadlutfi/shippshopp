<template>
    <el-config-provider namespace="ep" :locale="locale">
        
        <Head>
            <title>{{ title }}</title>
            <meta name="description" content="Shipp Shopp Vape">
        </Head>
        <div id="page-container" :class="classContainer">
            <base-header/>
            <!-- Main Container -->
            <div id="main-container">
                <slot />
            </div>
            <!-- END Main Container -->
        </div>
    </el-config-provider>
</template>
  
<style scoped>
</style>
<script>
    import BaseHeader from './BaseHeader.vue';
    import { Head } from '@inertiajs/vue3';
    import id from 'element-plus/dist/locale/id.mjs'
    export default {
        name: 'BaseLayout',
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
                    // 'page-header-glass' : this.title == 'Home' ? true : false
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