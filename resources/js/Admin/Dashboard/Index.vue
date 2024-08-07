<template>
    <base-layout>
        <div class="content">
            <el-row :gutter="20">
                <el-col :span="6">
                    <a class="block block-rounded block-link-shadow text-end" href="javascript:void(0)">
                        <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                            <div class="item item-circle bg-primary">
                                <i class="si si-users fa-2x text-black"></i>
                            </div>
                            <div class="text-end">
                                <div class="fs-3 fw-semibold">{{ stats.customer }}</div>
                                <div class="fs-sm fw-semibold text-uppercase text-muted">Pelanggan</div>
                            </div>
                        </div>
                    </a>
                </el-col>
                <el-col :span="6">
                    <a class="block block-rounded block-link-shadow text-end" href="javascript:void(0)">
                        <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                            <div class="item item-circle bg-primary">
                                <i class="fa fa-boxes-stacked fa-2x text-black"></i>
                            </div>
                            <div class="text-end">
                                <div class="fs-3 fw-semibold">{{ stats.product }}</div>
                                <div class="fs-sm fw-semibold text-uppercase text-muted">Produk</div>
                            </div>
                        </div>
                    </a>
                </el-col>
                <el-col :span="6">
                    <a class="block block-rounded block-link-shadow text-end" href="javascript:void(0)">
                        <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                            <div class="item item-circle bg-primary">
                                <i class="fa fa-bag-shopping fa-2x text-black"></i>
                            </div>
                            <div class="text-end">
                                <div class="fs-3 fw-semibold">{{ stats.sales }}</div>
                                <div class="fs-sm fw-semibold text-uppercase text-muted">Penjualan</div>
                            </div>
                        </div>
                    </a>
                </el-col>
                <el-col :span="6">
                    <a class="block block-rounded block-link-shadow text-end" href="javascript:void(0)">
                        <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                            <div class="item item-circle bg-primary">
                                <i class="fa fa-bell fa-2x text-black"></i>
                            </div>
                            <div class="text-end">
                                <div class="fs-3 fw-semibold">{{ stats.order }}</div>
                                <div class="fs-sm fw-semibold text-uppercase text-muted">Pesanan</div>
                            </div>
                        </div>
                    </a>
                </el-col>
            </el-row>

            <el-row :gutter="20">
                <el-col :span="12">
                    <h2 class="content-heading">Pesanan Terakhir</h2>
                    <div class="block rounded block-bordered">
                        <div class="block-content p-0">
                            <el-table :data="orders" class="rounded w-100">
                                <el-table-column prop="nomor" label="Nama"/>
                                <el-table-column prop="customer.name" label="Pelanggan"/>
                                <el-table-column label="Total">
                                    <template #default="scope">
                                        {{ currency(scope.row.total) }}
                                    </template>
                                </el-table-column>
                                <el-table-column label="Status">
                                    <template #default="scope">
                                        <el-tag type="warning" v-if="scope.row.state == 'pending'">
                                            Pending
                                        </el-tag>
                                        <el-tag type="warning" v-else-if="scope.row.state == 'process'">
                                            Diproses
                                        </el-tag>
                                        <el-tag type="info" v-else-if="scope.row.state == 'shipped'">
                                            Dikirim
                                        </el-tag>
                                        <el-tag type="success" v-else-if="scope.row.state == 'done'">
                                            Selesai
                                        </el-tag>
                                        <el-tag type="danger" v-else-if="scope.row.state == 'cancel'">
                                            Batal
                                        </el-tag>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </el-col>
                <el-col :span="12">
                    <h2 class="content-heading">Stok Kosong</h2>
                    <div class="block rounded block-bordered">
                        <div class="block-content p-0">
                            <el-table :data="products" class="rounded w-100">
                                <el-table-column label="Produk">
                                    <template #default="scope">
                                        {{ scope.row.nama }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="stock" label="Stok Sekarang" width="120" align="center"/>
                                <el-table-column label="Stok Minimum" width="120" align="center">
                                    <template #default>
                                        5
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
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
        stats : {
            type : Object,
        },
        data : {
            type : Object,
        }
    },
    data() {
        return {
            orders : [],
            products : [],
        }
    },
    mounted(){
        this.fetchOrder();
        this.fetchProduct();
    },
    methods :{
        async fetchOrder(){
            try {
                this.isLoading = true;
                const response = await axios.get("/admin/penjualan/data",{
                    params: {
                        page: 1,
                        limit : 10,
                    }
                });
                if(response.status == 200){
                    this.orders = response.data.data;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchProduct(){
            try {
                this.isLoading = true;
                const response = await axios.get("/admin/produk/stock",{
                    params: {
                        limit : 10,
                    }
                });
                if(response.status == 200){
                    this.products = response.data;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>