<template>
    <base-layout>
        <div class="content">
            <div class="content-heading">
                <h2 class="mb-0">Keranjang</h2>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="block">
                        <div class="block-header block-header-default">
                            <div class="block-title">
                                <el-checkbox v-model="selectAll" @change="onSelectAll">
                                    Pilih Semua
                                </el-checkbox>
                            </div>
                            <div class="block-options">
                                <el-button type="danger" @click="removeSelected" v-if="selected.length">
                                    <i class="si si-trash"></i> Hapus
                                </el-button>
                            </div>
                        </div>
                        <div class="block-content py-0">
                            <el-row :gutter="10" class="cart-item" v-for="(value, index) in carts" :key="index">
                                <el-col :lg="1" class="cart-item-check">
                                    <label class="custom-control custom-checkbox">
                                        <input class="custom-control-input" :id="value.id" type="checkbox" :value="value.id" v-model="selected">
                                        <label class="custom-control-label" :for="value.id"></label>
                                    </label>
                                </el-col>
                                <el-col :lg="2">
                                    <div class="cart-product_img">
                                        <img :src="value.product.main_image" class="img-fluid">
                                    </div>
                                </el-col>
                                <el-col :lg="10">
                                    <div class="fs-6">
                                        <a :href="route('product.show', { slug: value.product.slug })">
                                            <span class="fw-semibold">{{ value.product.nama }}</span>
                                        </a>
                                    </div>
                                    <div class="fw-bold">
                                            {{ currency(value.harga) }}
                                    </div>
                                </el-col>
                                <el-col :lg="4" class="text-end my-auto">
                                    <el-input-number v-model="value.qty" :min="1" size="small" class="w-100"/>
                                </el-col>
                                <el-col :lg="6" class="text-end my-auto">
                                    <div class="product_sub_total">
                                        {{ currency(value.harga * value.qty) }}
                                    </div>
                                </el-col>
                                <el-col :lg="1" class="text-end my-auto">
                                    <button  class="btn btn-danger btn-sm" @click="remove(value.id)">
                                        <i class="si si-trash"></i>
                                    </button>
                                </el-col>
                            </el-row>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="block block-bordered block-shadow block-rounded">
                        <div class="block-content block-content-full">
                            <h6 class="font-size-h5">Ringkasan belanja</h6>
                            <div class="d-flex justify-content-between">
                                <div class="font-size-md font-w600">Total Belanja ({{ totalProduct }} barang)</div>
                                <div class="font-size-md">{{ currency(totalOrder) }}</div>
                            </div>
                            <hr/>
                            <el-button type="primary" size="large" class="w-100" @click="checkout" :disabled="!selected.length">
                                Checkout
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </base-layout>
</template>
<script>
export default {
    setup() {
        
    },
    data() {
        return {
            product: [],
            var1: null,
            var2: null,
            selected : [],
            selectAll : false,
            voucher : [],
            carts : [],
        }
    },
    computed :{
        totalProduct () {
            var qty = 0;
            this.selected.forEach((v1, i1) => {
                this.carts.forEach((v2, i2) => {
                    if(v1 === v2.id){
                        qty += v2.qty;
                    }
                });
            });
            return qty;
        },
        totalOrder () {
            var order = 0;
            this.selected.forEach((v1, i1) => {
                this.carts.forEach((v2, i2) => {
                    if(v1 === v2.id){
                        order += v2.harga * v2.qty;
                    }
                });
            });
            return order;
        }
    },
    async mounted(){
        await this.fetchData();
    },
    methods :{
        onSelectAll(v){
            if(v){
                this.selected = [];
                this.carts.forEach((value, index) => {
                    this.selected.push(value.id);
                });
            }else{
                this.selected = [];
            }
        },
        async fetchData(){
            try {
                this.isLoading = true;
                const response = await axios.get("/cart/data",{
                });
                if(response.status == 200){
                    this.carts = response.data;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        async updateCart(id, qty){
            let form = this.$inertia.form({
                id : id,
                qty : qty
            }); 
            form.post(this.route('cart.update'), {
                preserveScroll: true,
            });
        },
        checkout(){
            const form = this.$inertia.form({
                cart : this.selected,
            });

            form.post(this.route('checkout.shipping'), {
                preserveScroll: true,
            });
        },
        removeSelected(){
            this.$swal.fire({
                icon: 'warning',
                title: 'Kamu Yakin?',
                text: `${ this.selected.length } produk akan dihapus!`,
                showCancelButton: true,
                cancelButtonText : 'Tidak, Batal',
                confirmButtonText: "Ya, Hapus",
                confirmButtonColor: '#3f9ce8',
                cancelButtonColor: '#af1310',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.post(this.route('cart.removeSelected'), {
                            ids : this.selected,
                        }, {
                        preserveScroll: true,
                        resetOnSuccess: false,
                        onProgress: ()=> {
                            this.$swal.fire({
                                title: 'Tunggu Sebentar...',
                                text: '',
                                imageUrl: window._asset + 'media/loading.gif',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                        },
                        onSuccess: () => {
                            this.$swal.Close();
                            return this.$swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: "Produk berhasil dihapus dari keranjang",
                                showCancelButton: false,
                                showConfirmButton: false,
                            });
                        },
                        onError:() => {
                            this.$swal.Close();
                        }
                    })
                }
            })
        },
        remove(id){
            this.$swal.fire({
                icon: 'warning',
                title: 'Kamu Yakin?',
                text: `Semua produk yang dipilih akan dihapus!`,
                showCancelButton: true,
                cancelButtonText: 'Tidak, Batal!',
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(this.route('cart.remove', id), {
                        preserveScroll: true,
                        onSuccess: () => {
                            this.$swal.Close();
                            return this.$swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: "Produk berhasil dihapus dari keranjang",
                                showCancelButton: false,
                                showConfirmButton: false,
                            });
                        },
                    })
                }
            })
        }
    }
}
</script>


<style scoped>
.cart-header {
    border-bottom : 3px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    padding-bottom: 10px;
}
@media only screen and (max-width: 768px) {
    .content.cart-content {
        padding: 52px 12px 12px 12px !important;
    }

    .cart-item .product_info {
        padding: 20px;
    }

    .cart-floating {
        position: fixed;
        width: 100%;
        z-index: 10;
        box-shadow: none;
        max-width: 500px;
        left: initial;
        bottom: 0px;
        background: var(--N0,#FFFFFF);
        padding: 12px 16px;
        box-shadow: 0 1px 4px 0 #364d68bd;
    }

    .cart-floating .wrapper {
        display: block;
        width: 100%;
        bottom: 0px;
        background: var(--N0,#FFFFFF);
        color: rgba(0, 0, 0, 0.38);
    }

    .cart-floating .wrapper .action-wrapper {
        display: flex;
        width: 100%;
        -webkit-box-pack: justify;
        justify-content: space-between;
    }
    
    .cart-floating .total p{
        display: block;
        position: relative;
        font-weight: 400;
        font-family: "Open Sauce One", -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        font-size: 14px;
        line-height: 20px;
        color: rgba(49, 53, 59, 0.68);
        letter-spacing: 0.1px;
        text-decoration: initial;
        margin: 0px;
    }
}


.cart-content {
    padding : 0 !important;
}
.checkbox-row {
    width: 100%;
    box-shadow: rgb(0 0 0 / 5%) 0px 5px 1px -3px;
    background-color: white;
    max-width: 500px;
    z-index: 39;
    top: 51px;
    position: fixed;
    margin: 0px auto;
    transition: all 600ms cubic-bezier(0.63, 0.01, 0.29, 1) 0s;
    transform: translateY(0px);
}
.checkbox-row .cart-checkbox-head {
    padding-top: 15px;
    padding-bottom: 15px;
}
.checkbox-row .cart-checkbox-head {
    display: flex;
    flex-wrap: wrap;
    margin-left: -4px;
    margin-right: -4px;
    box-sizing: border-box;
    -webkit-box-align: center;
    align-items: center;
    padding-left: 16px;
    padding-right: 16px;
}


.cart-item{
    padding : 10px 0;
    border-bottom: 2px solid #eaeaea !important;
    margin: 0;
}
.cart-item:last-child {
    border-bottom: none !important;
}

.cart-item .product_sub_total{
    font-size: 1.1rem;
    font-weight: 700;
}
</style>

<style scoped lang="scss">
.cart-item {
    display : flex;
    
    .cart-item-check {
        margin-top: auto;
        margin-bottom: auto;
    }

    .cart-item-product  {
        display: flex;
        width: 60%;

        .cart-product_img img{
            width: 80px;
            height: 80px;
            border-radius: 1rem;
        }

        .cart-product_info {
            margin-top: auto;
            margin-bottom: auto;

            .cart-product_title {
                font-size: 1.15rem;
                font-weight: 500;
                a {
                    font-size: 14px;
                    color: rgba(49,53,59,0.96);
                    width: calc(100% - 8px);
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
            }
            .cart-product_price {
                font-size: 1rem;
                font-weight: 700;
            }
        }
    }
    .cart-item-qty {
        width : 115px;
        margin-top: auto;
        margin-bottom: auto;
        
    }
    .cart-item-subtotal {
        margin-top: auto;
        margin-bottom: auto;
        
    }
    .cart-item-remove {
        margin-top: auto;
        margin-bottom: auto;
        
    }
}
</style>