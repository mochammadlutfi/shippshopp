<template>
    <base-layout title="Home">
        <!-- <recent-products/> -->
        <div class="content content-full">
            
            <div class="py-3 text-center">
                <h1 class="h2 fw-bold text-white mb-2">Kategori Produk</h1>
                <h2 class="h5 fw-medium text-white-75">{{ data.name }}</h2>
            </div>
            <el-row :gutter="20">

                <el-col :lg="6">
                    <h3 class="fs-3 text-white mb-3">Daftar Kategori</h3>
                    <a :href="route('category', { category : c.slug })" class="block block-rounded block-bordered" v-for="(c, i) in category" :key="i">
                        <div class="block-content p-4">
                            <h3 class="mb-0">{{ c.nama }}</h3>
                        </div>
                    </a>
                </el-col>

                <el-col :lg="18">
                    <div class="content-heading pt-0 mb-3 border-0 d-flex justify-content-between">
                        <h3 class="fs-3 mb-0">Produk Terbaru</h3>
                    </div>
                    <el-row :gutter="20"> 
                        <el-col :lg="6" v-for="(d, i) in product.data" :key="i">
                            <div class="product">
                                <div class="product-content"> 
                                    <div class="product-img">
                                        <a :href="route('product.show', { 'slug' : d.slug })">
                                            <img :src="d.main_image" class="img-fluid"/>
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="product-title">
                                            <a :href="route('product.show', { 'slug' : d.slug })">
                                                {{ d.nama }}
                                            </a>
                                        </div>
                                        <div class="product-price">
                                            {{ currency(d.harga_jual) }}
                                        </div>
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