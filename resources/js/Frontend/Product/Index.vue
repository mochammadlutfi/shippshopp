<template>
    <base-layout title="Produk">
        <div class="content content-full">
            
            <el-row :gutter="20" justify="center">
                <el-col :lg="18">
                    <div class="content-heading pt-0 mb-3 border-0 d-flex justify-content-between">
                        <h3 class="h3 mb-0">Produk Terbaru</h3>
                    </div>
                    <el-row :gutter="20">
                        <el-col :lg="6" v-for="(d, i) in data.data" :key="i">
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
        // RecentProducts,
    },
    props : {
        data : {
            type : Object,
        },
        category : {
            type : Array,
        }
    },
}
</script>