<template>
    <el-dropdown popper-class="rounded" trigger="click" placement="bottom-end">
        <span class="el-dropdown-link">
            <el-badge :value="cart.count">
                <el-button type="primary" class="text-white" link>
                    <i class="fa fa-2x fa-bag-shopping fa-cart"></i>
                </el-button>
            </el-badge>
        </span>
        <template #dropdown>
            <el-dropdown-menu class="cart-dropdown" v-if="cart.count">
                <div class="border-bottom d-flex justify-content-between px-3 py-2 rounded-top">
                    <h5 class="dropdown-header text-uppercase">Keranjang ({{ cart.count }})</h5>
                    <div class="space-x-1">
                        <a :href="route('cart.index')" class="fs-6 fw-bold">Lihat Keranjang</a>
                    </div>
                </div>
                <div class="cart-list">
                    <div class="cart-item" v-for="(item, index) in cart.items" :key="`cart-${index}`">
                        <div class="cart-image">
                            <img :src="item.product.main_image" :alt="item.product.name"/>
                        </div>
                        <div class="cart-info">
                            <div class="pdp-cart-name">{{ item.product.name }}</div>
                            <div class="pdp-cart-qty">
                                {{ item.qty }} Barang x {{ currency(item.harga) }}
                            </div>
                        </div>
                        <div class="cart-subtotal">
                            {{ currency(item.harga * item.qty) }}
                        </div>
                    </div>
                </div>
            </el-dropdown-menu>
            <el-dropdown-menu v-else>
                <div class="py-5 px-3 text-center">
                    <img src="/images/placeholders/cart_is_empty.png" class="w-50"/>
                    <div>
                        <div class="fs-6 fw-semibold mt-3">
                            Yah keranjang belanjaanmu kosong!
                        </div>
                    </div>
                </div>
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script>
import { useCartStore } from '@/Stores/cartStore.js';
export default {
    name : "CartHeader",
    data(){
        return {
            cart : useCartStore()
        }
    },
    async mounted(){
        await this.cart.fetchCart();
    },
    methods : {
    }
}
</script>

<style scoped>

</style>