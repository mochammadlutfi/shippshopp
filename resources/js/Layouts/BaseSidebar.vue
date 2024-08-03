<template>

    <div id="sidebar">
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="d-flex justify-content-center p-3">
                <!-- Logo -->
                <div class="text-center">
                    <a :href="route('admin.dashboard')" class="mx-auto">
                        <img src="/images/logo/logo.png" class="w-50"/>
                    </a>
                </div>
                <!-- END Logo -->
            </div>
            <!-- END Side Header -->

            <!-- Sidebar Scrolling -->

            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side content-side-full">
                    <base-navigation :nodes="$page.props.menu"></base-navigation>
                </div>
                <!-- END Side Navigation -->
            </div>
            <!-- END Sidebar Scrolling -->
        </div>
    </div>

</template>

<script>
import BaseNavigation from './BaseNavigation.vue';
import simplebar from 'simplebar-vue';
export default {
    name : 'BaseSidebar',
    components : {
        BaseNavigation,
        simplebar
    },
}
</script>

<style>

</style>