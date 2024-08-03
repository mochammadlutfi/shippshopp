<template>
    <base-layout title="Detail Penjualan">
        
        <div class="content">
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Detail Retur</span>
                <div class="space-x-1">
                    <el-button type="primary" v-if="data.status == 'pending'" @click.prevent="updateState('confirm')">
                        <i class="fa fa-check me-1"></i>
                        Konfirmasi Retur
                    </el-button>
                    <el-button type="primary" plain v-if="data.status == 'pending'" @click.prevent="updateState('reject')">
                        <i class="fa fa-times me-1"></i>
                        Tolak Retur
                    </el-button>
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
                                <el-col :lg="8">Tanggal</el-col>
                                <el-col :lg="16">
                                    <div class="fw-semibold">{{ data.date }}</div>
                                </el-col>
                            </el-row>
                            <el-row class="mb-2" :gutter="10">
                                <el-col :lg="8">Status Retur</el-col>
                                <el-col :lg="16">
                                    <el-tag type="warning" v-if="data.status == 'pending'">
                                        Pending
                                    </el-tag>
                                    <el-tag type="success" v-else-if="data.status == 'confirm'">
                                        Disetujui
                                    </el-tag>
                                    <el-tag type="danger" v-else-if="data.status == 'reject'">
                                        Ditolak
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
                                <div>{{ scope.row.product.name }}
                                    <template v-if="scope.row.variant.name">
                                        - {{ scope.row.variant.name }}
                                    </template>
                                </div>
                                <div>{{ scope.row.variant.sku }}</div>
                            </template>
                        </el-table-column>
                        <el-table-column prop="qty_order" label="Qty Order" width="100"/>
                        <el-table-column prop="qty" label="Qty Retur" width="100"/>
                        <el-table-column label="Alasan" width="120">
                            <template #default="scope">
                                {{ scope.row.reason }}
                            </template>
                        </el-table-column>
                    </el-table>
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
            if(v == 'confirm'){
                note = "Terima pengajuan retur?";
            }else{
                note = "Tolak pengajuan retur?";
            }

            ElMessageBox.alert(note, 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.post(this.route('admin.sale.return.state',{id: this.data.id}), {
                    state : v,
                }, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.fetchData();
                        ElMessage({
                            type: 'success',
                            message: 'Data Berhasil Diperbaharui!',
                        });
                    },
                });
            });
        }
    }
}
</script>