<template>
    <base-layout>
        <div class="content">
            <el-row justify="center">
                <el-col :span="16">
                
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fs-4 fw-bold mb-0">Detail Pesanan</h3>
        </div>
        <div class="block block-bordered rounded">
            <div class="block-header border-bottom border-2 py-2 px-3">
                <div class="block-title d-flex">
                    <div class="fw-bold">{{ data.nomor }}</div>
                    <div class="border-2 border-dark border-start fw-medium ms-2 ps-2">
                        {{ format_date(data.created_at) }}
                        <el-tag class="ml-2" type="danger" v-if="data.state == 'pending' && data.payment_status == 'unpaid'">Belum Bayar</el-tag>
                        <el-tag class="ml-2" type="warning" v-else-if="data.state == 'process'">Dikemas</el-tag>
                        <el-tag class="ml-2" type="info" v-else-if="data.state == 'shipping'">Dikirim</el-tag>
                        <el-tag class="ml-2" type="success" v-else-if="data.state == 'done'">Selesai</el-tag>
                        <el-tag class="ml-2" type="danger" v-else-if="data.state == 'cancel'">Batal</el-tag>
                    </div>
                </div>
                <div class="block-options">
                    
                    <a :href="`https://app.sandbox.midtrans.com/snap/v4/redirection/${data.payment_ref}`" class="ep-button ep-button--primary" v-if="data.payment_status == 'unpaid'">
                        Bayar Sekarang
                    </a>

                    <el-button type="primary" @click.prevent="confirm(data.id)" v-if="data.status == 'shipped'">
                    Terima Pesanan
                    </el-button>
                </div>
            </div>
            <div class="block-content p-3">
                <el-row>
                    <el-col :lg="12" v-if="data.shipping">
                        <h5 class="fw-semibold fs-6 mb-2">Info Pengiriman</h5>
                        <div class="fw-bold">{{ data.shipping.reciever }}</div>
                        <div class="">{{ data.shipping.phone }}</div>
                        <address>{{ data.shipping.address }}</address>
                    </el-col>
                </el-row>
            </div>
            <div class="block-content p-0">
                <div class="order-content" v-for="(d, i) in data.lines" :key="i">
                    <div class="order-items">
                        <div class="product-info">
                            <div class="product-img">
                                <img :src="d.product.main_image" class="img-fluid">
                            </div>
                            <div class="product-detail">
                                <div class="product_name">
                                    {{ d.product.nama }}
                                </div>
                                <div class="product_price">
                                    {{ d.qty }} x {{ currency(d.price) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-sum">
                        <p class="mb-0 font-w700">{{ currency(d.subtotal) }}</p>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-row">
                        <div class="col-title">
                            <span>Subtotal Produk</span>
                        </div>
                        <div class="col-val">
                            <div>{{ currency(data.total) }}</div>
                        </div>
                    </div>
                    <div class="order-row">
                        <div class="col-title">
                            <span>Total Belanja</span>
                        </div>
                        <div class="col-val">
                            <div>{{ currency(data.grand_total) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </el-col>
        </el-row>
        </div>
    </base-layout>
</template>

<style lang="scss">

.order-content {
    display: flex;
    border-top: 1px solid #333a40;

    .order-items  {
        -webkit-box-flex: 1;
        flex-grow: 1;
        width: calc(100% - 180px);

        .product-info {
            display: flex;
            width: 100%;
            padding: 10px;

            .product-img {
                flex-shrink: 0;
                margin-right: 16px;
                width: 50px;
                height: 50px;
                border-radius: 4px;
            }

            .product-detail{
                -webkit-box-flex: 1;
                flex-grow: 1;
            }
        }
    }

    .order-sum {
        display: inline-flex;
        -webkit-box-align: center;
        align-items: center;
        width: 180px;
        -webkit-box-pack: start;
        justify-content: flex-start;
        padding-left: 24px;
        border-left: 1px solid #333a40;
    }
}

.order-footer{
    border-top: 1px solid rgba(0,0,0,.09);

    .order-row {
        padding: 0 24px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        border-bottom: 1px dotted rgba(0,0,0,.09);

        .col-title  {
            padding: 13px 10px;
            font-size: 1rem;
        }

        .col-val  {    
            padding: 13px 0 13px 10px;
            width: 156px;
            border-left: 1px solid #333a40;
            justify-content: flex-end;
            word-wrap: break-word;
        }
    }
}
</style>
<script>
import moment from 'moment';
export default {
    props : {
        data : {
            type : Object,
            default : {}
        }
    },
    setup() {
        
    },
    methods :{
        format_date(v){
            if (v) {
                moment.locale('id');
                return moment(String(v)).format('DD MMMM YYYY')
            }
        },

        async doTest(){
            
            const form = this.$inertia.form({
                id : this.data.id,
            });

            form.post(this.route('user.order.test'), {
                preserveScroll: true,
            });
        },
        
        receive(id){
            ElMessageBox.alert('Pastikan barang yang diterima sesuai dengan pesanan!', 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Pesanan Sudah Diterima!',
                cancelButtonText: 'Batal!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.post(this.route('user.order.confirm', {id : id}), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.fetchData();
                        ElMessage({
                            type: 'success',
                            message: 'Pesanan Berhasil Diterima!',
                        });
                    },
                });
            });
        },
    }
}
</script>