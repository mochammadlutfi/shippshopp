<template>
    <base-layout>
        <div class="content">
            <el-row :gutter="20">
                <el-col :lg="16">
                    <shipping-address v-model="address"/>
                    <div class="content-heading pt-0 mb-0 border-0 font-size-md">
                        Pesanan Kamu
                    </div>
                    <div class="block block-bordered rounded">
                        <div class="block-content p-3">
                            <template v-for="(d, i) in cart">
                                <el-row :key="d.variant_id" v-if="i == 0 || !showAll" class="mb-2" :gutter="10">
                                    <el-col :lg="2">
                                        <img :src="d.product.main_image" class="img-fluid">
                                    </el-col>
                                    <el-col :lg="14" class="my-auto">
                                        <div class="fs-5 fw-bold">{{ d.product.name }}</div>
                                        <div class="fs-sm fw-semibold">{{ currency(d.harga) }} x {{ d.qty }} barang</div>
                                    </el-col>
                                    <el-col :lg="8" class="my-auto">
                                        <div class="fs-5 fw-bold text-end">
                                            {{ currency(d.harga * d.qty) }}
                                        </div>
                                    </el-col>
                                </el-row>
                            </template>
                        </div>
                        <div class="block-content p-3 text-center border-top border-bottom border-2" @click="showAll = !showAll" v-if="cart.length > 1">
                            <template v-if="showAll">
                                <span class="fs-6 fw-semibold">Tampilkan Semua</span>
                                <i class="fa fa-angle-small-down"></i>
                            </template>
                            <template v-else>
                                <span class="fs-6 fw-semibold">Tampilkan Lebih Sedikit</span>
                                <i class="fa fa-angle-small-up"></i>
                            </template>
                        </div>
                    </div>
                </el-col>
                <el-col :lg="8">
                    <div class="content-heading d-flex justify-content-between mb-2 py-2">
                        Rincian Belanja
                    </div>
                    <div class="block block-bordered rounded">
                        <div class="block-content block-content-full">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="fs-6">Total Harga ({{ cart.length }} barang)</div>
                                <div class="fs-6 fw-bold">{{ currency(totalHarga) }}</div>
                            </div>
                        </div>
                        <div class="block-content p-4 border-top border-2x">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="fs-5">Total Belanja</div>
                                <div class="fs-5 fw-bold">{{ currency(totalHarga) }}</div>
                            </div>
                            <el-button type="primary" size="large" class="w-100" @click="payment">
                                Lanjut Ke Pembayaran
                            </el-button>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </base-layout>
</template>
<script>
import ShippingAddress from './ShippingAddress.vue';
export default {
    components: {
        ShippingAddress
    },
    props : {
        cart : Array,
        errors : Object,
    },
    data() {
        return {
            product: [],
            var1: null,
            var2: null,
            selected : [],
            selectAll : false,
            address : {},
            showAll : true,
        }
    },
    computed :{
        totalProduct () {
            var qty = 0;
            this.selected.forEach((value, index) => {
                qty += value.qty;
            });
            return qty;
        },
        totalHarga () {
            var total = 0;
            this.cart.forEach((value, index) => {
                total += value.harga * value.qty;
            });
            return total;
        }
    },
    watch : {
        selectAll: function(val){
            this.selected = [];
            if(val){
                this.$page.props.cart.forEach((value, index) => {
                    this.selected.push(value)
                });
            }
        },
        address(v){
            if(Object.keys(v).length){
                if(v.distance){
                    let km = (v.distance - 5000) / 1000;
                    this.shippingCost = km * 6000;
                }
            }
        }
    },
    mounted(){
        this.getAddress();
    },
    methods :{
        async payment(){
            const form = this.$inertia.form({
                lines : this.cart,
                address_id : this.address.id,
                shipping_cost : 0,
                total : this.totalHarga
            });

            form.post(this.route('checkout.store'), {
                preserveScroll: true,
                    onSuccess: () => {
                },
            });
        },
        // async payment(){
        //     try {
        //         const vm = this;
        //         this.isLoading = true;
        //         const response = await axios.post(this.route('checkout.store'),{
        //             lines : this.cart,
        //             address_id : this.address.id,
        //             shipping_cost : this.shippingCost,
        //             total : this.totalHarga
        //         });
        //         if(response.status == 200){
        //             const data = response.data;
                    
        //         }
        //         this.isLoading = false;
        //     } catch (error) {
        //         console.error(error);
        //     }
        // },
        async getAddress(){
            try {
                this.isLoading = true;
                const response = await axios.get("/user/alamat/data",{
                    params : {
                        main : 1
                    }
                });
                if(response.status == 200){
                    this.address = response.data;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        async updatePayment(status, id){
            try {
                this.isLoading = true;
                const response = await axios.post(this.route("user.order.state", {id : id}),{
                    status: status,
                });
                if(response.status == 200){
                    // location.reload();
                    window.location.href = this.route('user.order.show', {id : id});
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<style>
.cart-item{
    padding : 10px 0;
    border-bottom: 2px solid #eaeaea !important;
    margin: 0;
}

.product-cart .cart-item:last-child {
    border-bottom: none !important;
}

.cart-item .product-img{
    max-width: 100px;
}
.cart-item img{
    width: 80px;
    height: 80px;
    border-radius: 1rem;
}

.cart-item .product_info .product_name{
    font-size: 1.15rem;
    font-weight: 500;
}

.cart-item .product_info .product_price{
    font-size: 1rem;
    font-weight: 700;
}

.cart-item .product_sub_total{
    font-size: 1.1rem;
    font-weight: 700;
}


.section-label {
    font-size: 18px;
    margin-bottom: 20px;
    line-height: 20px;
}

ul.payment-method-list {
    margin: 16px 0px;
    padding: 0px;
    list-style: none;
}

ul.payment-method-list li.list_item {
    display: flex;
    position: relative;
    padding: 16px 16px 16px 0px;
    margin-left: 16px;
    border-bottom: 0.5px solid rgb(224, 224, 224);
    -webkit-box-align: center;
    align-items: center;
}

ul.payment-method-list li.list_item .payment-wrap{
    display: flex;
    -webkit-box-pack: start;
    justify-content: flex-start;
    -webkit-box-align: center;
    align-items: center;
    flex: 1 1 0%;
    min-height: 38px;
    max-width: 100%;
    position: relative;
    cursor: pointer;
}

.payment-wrap .payment__logo {
    width: 52px;
    padding-right: 12px;
    text-align: center;
    line-height: 0;
}

.payment-wrap .payment__logo img {
    width: auto;
    height: auto;
    min-width: 32px;
    max-width: 100%;
}

.payment-wrap .payment__content {
    display: flex;
    flex: 1 1 0%;
    flex-direction: column;
    padding-right: 8px;
    text-overflow: ellipsis;
    overflow: hidden;
}

.payment-wrap .payment__content .payment__text{
    display: flex;
    -webkit-box-pack: start;
    justify-content: flex-start;
    -webkit-box-align: center;
    align-items: center;
}


.payment-wrap .payment__select {
    max-width: 50px;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
}

.payment-wrap .payment__icon {
    padding: 0px;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    min-width: 8px;
    min-height: 13px;
    color: rgb(66, 181, 73);
    font-size: 12px;
    line-height: 16px;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 168px;
    opacity: 1;
}

</style>