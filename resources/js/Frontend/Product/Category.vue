<template>
    <base-layout title="Home">
        <!-- <recent-products/> -->
        <div class="content content-full">
            
            <div class="block block-rounded block-transparent bg-primary">
                <div class="block-content bg-black-25">
                    <div class="py-3 text-center">
                        <h1 class="h2 fw-bold text-white mb-2">Produk Kategori</h1>
                        <h2 class="h5 fw-medium text-white-75">{{ data.name }}</h2>
                    </div>
                </div>
            </div>
            <el-row :gutter="20">

                <el-col :lg="6">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Kategori</h3>
                        </div>
                        <div class="block-content">
                            <ul class="nav-main">
                                <li class="nav-main-item" v-for="(c, i) in category" :key="i">
                                    <a class="nav-main-link" :href="route('category', { category : c.slug })">
                                        <i class="nav-main-link-icon fa fa-angle-right"></i>
                                        <span class="nav-main-link-name">{{ c.name }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </el-col>

                <el-col :lg="18">
                    <div class="content-heading pt-0 mb-3 border-0 d-flex justify-content-between">
                        <h3 class="h3 mb-0">Produk Terbaru</h3>
                    </div>
                    <el-row :gutter="20">
                        <el-col :lg="5" v-for="(d, i) in product.data" :key="i">
                            <div class="product">
                                <div class="product-content">
                                    <div class="product-img">
                                        <!-- <a :href="route('product.show', { 'slug' : d.slug })"> -->
                                            <img :src="d.main_image" class="img-fluid"/>
                                        <!-- </a> -->
                                    </div>
                                    <div class="product-info">
                                        <div class="product-title">
                                            <!-- <a :href="route('product.show', { 'slug' : d.slug })"> -->
                                                {{ d.name }}
                                            <!-- </a> -->
                                        </div>
                                        <div class="product-price">
                                            {{ currency(d.sell_price) }}
                                        </div>
                                        <el-row :gutter="10">
                                            <el-col :lg="12">
                                                <a :href="route('product.show', { 'slug' : d.slug, 'grosir' : 0})" class="ep-button ep-button--primary ep-button--small w-100">Eceran</a>
                                            </el-col>
                                            <el-col :lg="12">
                                                <a :href="route('product.show', { 'slug' : d.slug, 'grosir' : 1})" class="ep-button ep-button--primary ep-button--small w-100 is-plain">Grosir</a>
                                            </el-col>
                                        </el-row>
                                    </div>
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </el-col>
            </el-row>
        </div>
    </base-layout>
</template>

<script>
export default {
    components: {
    },
    props : {
        data : {
            type : Object,
        },
        product : {
            type : Object,
        },
        category : {
            type : Array,
        }
    },
}
</script>