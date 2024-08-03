<template>
    <base-layout :title="data.name">
        <div class="content">
            <el-row justify="center">
                <el-col :lg="18">
                    <div class="block rounded">
                        <div class="block-content p-4">
                            <el-row :gutter="20">
                                <el-col :lg="12">
                                    <flicking ref="flicking0" :options="{ bounce: 30 }" :plugins="plugins">
                                        <template v-if="data.image.length">
                                            <template  v-for="(v, i) in data.image" >
                                                <div class="flicking-panel thumb has-background-primary" v-if="v.path != null" :key="i">
                                                    <img class="thumb-image" :src="v.path" />
                                                </div>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <div class="flicking-panel has-background-primary">
                                                <img class="panel-image img-fluid" src="/media/placeholder/product.png" />
                                            </div>
                                        </template>
                                    </flicking>
                                    <flicking class="product-img-nav" ref="flicking1" :options="optionNav">
                                        <template v-if="data.image.length">
                                            <template  v-for="(v, i) in data.image" >
                                                <div class="flicking-panel thumb has-background-primary" v-if="v.path != null" :key="i">
                                                    <img class="thumb-image" :src="v.path" />
                                                </div>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <div class="flicking-panel thumb has-background-primary">
                                                <img class="thumb-image" src="/media/placeholder/product.png" />
                                            </div>
                                        </template>
                                    </flicking>
                                </el-col>
                                <el-col :lg="12">
                                    <div class="product-info">
                                        <h2 class="product-title">
                                            {{ data.nama }}
                                        </h2>
                                        <div class="">
                                            Stock Produk {{ data.stok }}
                                        </div>
                                        <div class="product-price mb-4">
                                            <div class="product-price_final">
                                                {{ currency(data.harga_jual) }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <template v-if="data.variant.length > 1">
                                        <div class="mb-4" v-if="data.has_variant">
                                            <el-radio-group v-model="form.variant_id">
                                                <template v-for="(v, i) in data.variant" :key="i">
                                                    <el-radio :label="v.id" border class="me-2" @change="setVariantPrice(v)" v-if="(grosir == 1 && i >= 1)" :disabled="isDisable(v.stock)">
                                                        {{ v.name }}
                                                    </el-radio>
                                                    <el-radio :label="v.id" border class="me-2" @change="setVariantPrice(v)" v-else-if="grosir == 0 && i == 0" :disabled="isDisable(v.stock)">
                                                        {{ v.name }}
                                                    </el-radio>
                                                </template>
                                            </el-radio-group>
                                        </div>
                                    </template> -->
                                    <div class="mb-4">
                                        <el-input-number v-model="form.qty" :min="1" size="large" />
                                    </div>
                                    <el-button type="primary" size="large" @click.prevent="addCart" :disabled="isDisable(data.stok)">
                                        <i class="fa fa-cart-plus me-2"></i>
                                        Tambah Keranjang
                                    </el-button>
                                </el-col>
                            </el-row>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </base-layout>
</template>

<style scoped>
.flicking-panel{
    height: auto !important;
}
.product-img-detail{
    display: flex;
    align-items: flex-start;
    margin-right: 30px;
}

.product-img-nav .thumb{
    width: 91px;
    flex: 0 0 91px;
    margin-right: 10px;
    cursor : pointer;
}

.product-action{
    --color-shadow: 0 1px 4px 0 rgba(141,150,170,0.4);
    display: block;
    overflow: hidden;
    background-color: var(--NN50,#FFFFFF);
    padding: 10px 0px;
    transition-property: background-color, border-color, box-shadow;
    transition-duration: 120ms;
    transition-timing-function: cubic-bezier(0.2, 0.64, 0.21, 1);
    position: fixed;
    width: 100%;
    max-width: 500px;
    height: 60px;
    bottom: 0px;
    z-index: 2;
    border-radius: 0px;
    box-shadow: rgb(49 53 59 / 12%) 0px -1px 6px;
    margin: 0px auto !important;
}

.product-action .product-action-wrap{
    width: 100%;
    height: 40px;
    display: flex;
    flex-direction: row;
    -webkit-box-align: center;
    align-items: center;
}

@media only screen and (max-width: 768px) {
    .product_detail{
        padding: 0px !important;
    }

    .product_detail .search-header{
        display: flex;
        flex: 2;
    }

    .product_detail .flicking-panel {
        padding: 0 !important;
    }

    .product-info{
        background-color: #fff;
        border-radius: 0 0 16px 16px;
        position: relative;
        padding: 12px 16px;
    }

    .product-info .product-title {
        font-size: 1.2rem;
        margin-bottom: 4px;
    }

    .product-info .product-price .product-price_final {
        font-size: 1.8rem !important;
        font-weight: 600;
    }
}
.flicking-panel {
    width: 100%;
    height: 100%;
    display: flex;
    border-radius: 5px;
    /* margin-right: 10px; */
    /* margin-bottom: 10px; */
    align-items: flex-end;
    /* padding: 30px 20px; */
    box-sizing: border-box;
    position: relative;
    border: 1px solid #e7ecf0;
}

.flicking-panel img{
    border-radius: 5px;
}

.flicking-panel .thumb {
    border-radius: 3px;
    border: 1px solid #e7ecf0;
}

.flicking-viewport {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    overflow: hidden;
}

.flicking-camera {
    width: 100%;
    height: 100%;
    display: flex;
    position: relative;
    flex-direction: row;
    z-index: 1;
}

.panel-image {
    top: -100%;
    bottom: -100%;
    width: 100%;
    margin: auto;
}

.flicking-panel.thumb.active {
    border: 1px solid #3f9ce8;
}

.flicking-panel .thumb-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.btn-wishlist, .btn-share{
    background-color: transparent;
    background-image: none;
    border-color: #dee5ea;
    font-size: 1.2rem;
    color : #4c5664;
}

.btn-wishlist:hover{
    color: #f65a75;
    background-color: rgb(255 241 242/1);
    border-color: rgb(255 241 242/0);
}

.btn-wishlist.active{
    color: #f65a75;
    background-color: rgb(255 241 242/1);
    border-color: rgb(255 241 242/0);
}

.btn-wishlist svg{
    transform: scale(2);
}

.btn-wishlist:hover svg{
    fill: currentColor;
}

.btn-share:hover{
    color: #3f9ce8;
    background-color: #eff6ff;
}

</style>
<script>

import Flicking from "@egjs/vue3-flicking";
import { Arrow, Pagination, Sync  } from "@egjs/flicking-plugins";
import "@egjs/flicking-plugins/dist/arrow.css";
import "@egjs/flicking-plugins/dist/pagination.css";
import { useCartStore } from '@/Stores/cartStore.js';
export default {
    components : {
        Flicking
    },
    data (){
        return {
            cart : useCartStore(),
            options : {
                align: "prev",
                defaultIndex: 1,
                circular: true,
            },
            plugins : [],
            optionNav : { 
                bound: true, 
                bounce: 30, 
                moveType: 'freeScroll',
                align: 'prev' 
            },
            stock : 0,
            form: {
                product_id : this.data.id,
                qty : 1,
                price : this.data.harga_jual
            }
        }
    },
    props : {
        data : Object,
    },
    mounted(){
        this.plugins = [new Sync({
            type: "index",
            synchronizedFlickingOptions: [
                {
                    flicking: this.$refs.flicking0,
                    isSlidable: true
                },
                {
                    flicking: this.$refs.flicking1,
                    isClickable: true,
                    activeClass: "active"
                }
            ]
        })];

        
        // this.variant_id = this.data.variant[0].id;
        // this.calculateStock();
    },
    methods : {
        isDisable(stok){
            if(stok > 0){
                return false;
            }else{
                return true;
            }
        },
        calculateStock()
        {
            let stock = 0;
            this.data.variant.forEach((v, i) => {
                if(this.data.variant.length > 1){
                    if(this.grosir == 1 && i >= 1){
                        stock = parseInt(stock) + parseInt(v.stock);
                    }else if(this.grosir == 0 && i == 0){
                        stock = parseInt(stock) + parseInt(v.stock);
                    }
                }else{
                    stock = parseInt(stock) + parseInt(v.stock);
                }
            });
            this.stock = stock;
        },
        setVariantPrice(v){
            this.form.price = v.sell_price;
            this.stock = v.stock;
        },
        addCart() {
            if(this.$page.props.user){
                let form = this.$inertia.form(this.form);
                form.post(this.route('cart.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        ElMessage({
                            type: 'success',
                            message: 'Produk berhasil Ditambahkan Ke Keranjang!',
                        });
                        this.cart.fetchCart();
                    },
                });
            }else{
                window.location = this.route('login');
            }
        },
        onPaused() {
        console.log("## OnPaused")
        },
        onPlayed() {
        console.log("## OnPlayed")
        },
    }
}
</script>