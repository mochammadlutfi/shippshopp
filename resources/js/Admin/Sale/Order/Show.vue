<template>
    <base-layout title="Detail Penjualan">
        
        <div class="content">
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Detail Penjualan</span>
                <div class="space-x-1">
                    <template v-if="data.payment_status == 'paid'">
                        <el-button type="primary" @click.prevent="updateState('process')" v-if="data.state == 'pending'">
                            <i class="fa fa-check me-1"></i>
                            Konfirmasi Pesanan
                        </el-button>
                        <el-button type="primary" @click.prevent="updateState('shipped')" v-else-if="data.state == 'process'">
                            <i class="fa fa-check me-1"></i>
                            Konfirmasi Pengiriman
                        </el-button>
                    </template>
                </div>
            </div>
            <div class="block rounded block-bordered">
                <div class="block-content p-4">
                    <el-row :gutter="20" justify="space-between">
                        <el-col :lg="12">
                            <h2 class="fw-bold fs-5 mb-2">Informasi Dasar</h2>
                            <el-row class="mb-2" :gutter="10">
                                <el-col :lg="8">Nomor</el-col>
                                <el-col :lg="16">
                                    <div class="fw-semibold">{{ data.nomor }}</div>
                                </el-col>
                            </el-row>
                            <el-row class="mb-2" :gutter="10">
                                <el-col :lg="8">Tanggal Pesan</el-col>
                                <el-col :lg="16">
                                    <div class="fw-semibold">{{ data.date }}</div>
                                </el-col>
                            </el-row>
                            <el-row class="mb-2" :gutter="10">
                                <el-col :lg="8">Status Pemesanan</el-col>
                                <el-col :lg="16">
                                    <el-tag type="warning" v-if="data.state == 'pending'">
                                        Pending
                                    </el-tag>
                                    <el-tag type="warning" v-else-if="data.state == 'process'">
                                        Diproses
                                    </el-tag>
                                    <el-tag type="info" v-else-if="data.state == 'shipped'">
                                        Dikirim
                                    </el-tag>
                                    <el-tag type="success" v-else-if="data.state == 'done'">
                                        Selesai
                                    </el-tag>
                                    <el-tag type="danger" v-else-if="data.state == 'cancel'">
                                        Batal
                                    </el-tag>
                                </el-col>
                            </el-row>
                            <el-row class="mb-2" :gutter="10">
                                <el-col :lg="8">Status Pembayaran</el-col>
                                <el-col :lg="16">
                                    <el-tag type="danger" v-if="data.payment_status == 'unpaid'">
                                            Belum Bayar
                                        </el-tag>
                                        <el-tag type="warning" v-else-if="data.payment_status == 'pending'">
                                            Pending
                                        </el-tag>
                                        <el-tag type="success" v-else-if="data.payment_status == 'paid'">
                                            Lunas
                                        </el-tag>
                                </el-col>
                            </el-row>
                        </el-col>
                        <el-col :lg="12">
                            <h2 class="fw-bold fs-5 mb-2">Informasi Pelanggan</h2>
                            <el-row class="mb-2" :gutter="10">
                                <el-col :lg="8">Nama</el-col>
                                <el-col :lg="16">
                                    <div class="fw-semibold">{{ data.customer.name }}</div>
                                </el-col>
                            </el-row> 
                            <el-row class="mb-2" :gutter="10" v-if="data.shipping">
                                <el-col :lg="8">Alamat Pengiriman</el-col>
                                <el-col :lg="16">
                                    <b>{{ data.shipping.reciever }}</b><br>
                                    {{ data.shipping.phone }}<br>
                                    {{ data.shipping.address }}<br>
                                </el-col>
                            </el-row>
                        </el-col>
                    </el-row>
                </div>
            </div>
            <div class="block rounded">
                <div class="block-content p-0">
                    <el-table :data="data.lines" border id="variant" class="mb-2 rounded fs-6">
                        <el-table-column label="Produk">
                            <template #default="scope">
                                <div>{{ scope.row.product.nama }}
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column label="Harga" width="150">
                            <template #default="scope">
                                {{ currency(scope.row.harga) }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="qty" label="Qty" width="100"/>
                        <el-table-column label="Total" width="200">
                            <template #default="scope">
                                {{ currency(scope.row.subtotal) }}
                            </template>
                        </el-table-column>
                    </el-table>

                    <el-row justify="end" class="p-3">
                        <el-col :lg="8" class="float-end">
                            <div class="d-flex float-end justify-content-end w-75 mb-2">
                                <div class="fs-6 fw-semibold">Total</div>
                                <div class="fs-6 fw-bold text-end w-50">{{ currency(data.total) }}</div>
                            </div>
                            <!-- <div class="d-flex float-end justify-content-end w-75 mb-2">
                                <div class="fs-6 fw-semibold">Biaya Kirim</div>
                                <div class="fs-6 fw-bold text-end w-50">{{ currency(data.shipping_cost) }}</div>
                            </div> -->
                            <div class="d-flex float-end justify-content-end w-75 pt-2 mb-2 border-top border-2">
                                <div class="fs-5 fw-semibold">Total Belanja</div>
                                <div class="fs-5 fw-bold text-end w-50">{{ currency(data.grand_total) }}</div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
    </base-layout>
</template>

<script>
export default {
    props : {
        data : {
            type : Object,
            default : {}
        }
    },
    setup() {
        
    },
    methods : {
        updateState(v){
            var note = '';
            if(v == 'process'){
                note = "Ubah status pesanan menjadi diproses?";
            }else{
                note = "Ubah status pesanan menjadi dikirim?";
            }

            ElMessageBox.alert(note, 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.post(this.route('admin.sale.order.state',{id: this.data.id}), {
                    state : v,
                }, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.fetchData();
                        ElMessage({
                            type: 'success',
                            message: 'Data Berhasil Dihapus!',
                        });
                    },
                });
            });
        }
    }
}
</script>