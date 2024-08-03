<template>
    <base-layout title="Home">
        <!-- <recent-products/> -->
        <div class="position-relative d-flex align-items-center">
            <div class="content content-full">
                <el-row>
                    <el-col :md="12" class="py-4 d-flex align-items-center">
                        <div class="text-center text-md-start">
                            <h1 class="text-header mb-3">
                                Let’s vaping together
                            </h1>
                            <p class="text-white fw-medium mb-4">
                                Penuhi keperluan vaping kamu hanya di shipp shopp vape store
                            </p>
                            <a class="ep-button ep-button--primary py-3 px-4" :href="route('product.index')">
                                <i class="fa fa-arrow-right opacity-50 me-1"></i> Cari Produk
                            </a>
                        </div>
                    </el-col>
                    <el-col :md="10" class="py-4 d-md-flex align-items-md-center justify-content-md-end">
                        <img class="img-fluid" src="/images/front.png" alt="Hero Promo">
                    </el-col>
                </el-row>
            </div>
        </div>

        <div class="content content-full">
            <div class="position-relative">
                <h2 class="fw-bold mb-4 text-center">
                    Produk <span class="text-primary">Kami</span>
                </h2>
            </div>
            <el-row :gutter="20" justify="center" class="mb-3">
                <el-col :md="6" v-for="(d,i) in category" :key="i">
                    <a class="block rounded" href="#">
                        <div class="block-content p-4 text-center">
                            <h2 class="mb-0 fs-lg">{{ d.nama }}</h2>
                        </div>
                    </a>
                </el-col>
            </el-row>
            <el-row :gutter="10">
                <el-col :md="4" v-for="(d, i) in data" :key="i">
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
        </div>

        
        <div class="content content-full">
            <div class="position-relative">
                <h2 class="fw-bold mb-4 text-center">
                    Lokasi <span class="text-primary">Kami</span>
                </h2>
            </div>
            <el-row :gutter="20" justify="center">
                <el-col :md="16">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0901393228382!2d106.5424828749906!3d-6.251853093736579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fd8ddf9ee793%3A0x671c246aea1ffd23!2sShipp%20Shopp%20Vape!5e0!3m2!1sen!2sid!4v1719943100082!5m2!1sen!2sid"
                        width="100%" height="400" class="rounded" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </el-col>
            </el-row>
        </div>
    </base-layout>
</template>
<style scoped>
.text-header{
    color: #FFFFFF;
    font-family: "Raleway", Sans-serif;
    font-size: 67px;
    font-weight: 700;
    line-height: 1.1em;
    letter-spacing: 0px;
}
</style>
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