<template>
    <base-layout>
        
    <div class="content">
            <div class="content-heading d-flex justify-content-between align-items-center">
                <div class="my-auto">
                    Detail Pesanan Pembelian
                </div>
                <div class="float-end" v-if="data.state == 'draft'">
                    <el-button type="primary" @click.prevent="modalConfirm = true;">
                        <i class="fa fa-check me-2"></i>
                        Konfirmasi
                    </el-button>
                    <el-button type="primary" plain @click.prevent="updateState('cancel')">
                        <i class="fa fa-close me-2"></i>
                        Batal
                    </el-button>
                </div>
            </div>

            <div class="block block-bordered rounded">
                <div class="block-content p-4">
                    <h3 class="fs-3 fw-bold">{{ data.nomor }}</h3>

                    <el-descriptions column="2">
                        <el-descriptions-item label="Supplier">
                            {{ data.supplier.name }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Sales">
                            {{ data.supplier.pic }}
                        </el-descriptions-item>
                        <el-descriptions-item label="No Handphone">
                            {{ data.supplier.phone }}
                        </el-descriptions-item>
                        <el-descriptions-item label="No Faktur Supplier" v-if="data.state == 'done'">
                            {{ data.ref }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Tanggal Pembelian">
                            {{ formatDate(data.date) }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Tanggal Terima" v-if="data.state == 'done'">
                            {{ formatDate(data.date_received) }}
                        </el-descriptions-item>
                    </el-descriptions>

                    
                    <el-table :data="data.lines" border id="variant" class="mb-2">
                        <el-table-column label="Produk">
                            <template #default="scope">
                                <div>{{ scope.row.product.name }}
                                    <template v-if="scope.row.variant.name">
                                        - {{ scope.row.variant.name }}
                                    </template>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column label="Harga">
                            <template #default="scope">
                                {{ currency(scope.row.price) }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Stok">
                            <template #default="scope">
                                {{ scope.row.variant.stock }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="qty" label="Qty Dipesan"/>
                        <el-table-column prop="qty_receipt" label="Qty Diterima"/>
                        <el-table-column label="Satuan">
                            <template #default="scope">
                                {{ scope.row.unit.name }} ({{ scope.row.unit.code }})
                            </template>
                        </el-table-column>
                        <el-table-column label="Total">
                            <template #default="scope">
                                {{ currency(scope.row.subtotal) }}
                            </template>
                        </el-table-column>
                    </el-table>

                    <el-row justify="end">
                        <el-col :lg="12" class="float-end">
                            <div class="d-flex float-end justify-content-end w-75 fs-4">
                                <div class="">Total</div>
                                <div class="text-end w-50">{{ currency(data.total) }}</div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
        <el-dialog v-model="modalConfirm" title="Konfirmasi Pembelian Produk?" width="800px">
            <el-form @submit.prevent="submit" label-position="top">
                <el-row :gutter="32">
                    <el-col :lg="12">
                        <el-form-item class="mb-4" label="Faktur Supplier">
                            <el-input v-model="form.ref"/>
                        </el-form-item>
                    </el-col>
                    <el-col :lg="12">
                        <el-form-item class="mb-4" label="Tanggal" :error="errors.date">
                            <el-date-picker
                                v-model="form.date"
                                type="date"
                                class="w-100"
                                placeholder="Pilih Tanggal"
                                format="DD MMMM YYYY"
                                value-format="YYYY-MM-DD"
                                :disabled-date="disabledDate"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-table :data="lines" border id="variant" class="mb-2" style="width: 100%" >
                    <el-table-column label="Produk" width="210">
                        <template #default="scope">
                            <div>{{ scope.row.product }}
                                <template v-if="scope.row.variant">
                                    - {{ scope.row.variant }}
                                </template>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Stok" width="62">
                        <template #default="scope">
                            {{ scope.row.stock }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Harga Beli" width="150">
                        <template #default="scope">
                            <el-input
                                v-model="scope.row.price"
                                :formatter="(value) => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                :parser="(value) => value.replace(/^Rp\s+|(\,)/gi, '')"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column label="Satuan" width="110">
                        <template #default="scope">
                            {{ scope.row.unit }} ({{ scope.row.unit_code }})
                        </template>
                    </el-table-column>
                    <el-table-column prop="qty" label="Qty Dipesan" width="110"/>
                    <el-table-column label="Qty Diterima" width="140">
                        <template #default="scope">
                            <el-input-number v-model="scope.row.qty_receipt" :min="0" class="w-100"/>
                        </template>
                    </el-table-column>
                </el-table>
                
                <div class="border-top pt-2 text-end">
                    <el-button @click="modalConfirm = false">Batal</el-button>
                    <el-button native-type="submit" type="primary">
                        <i class="fa fa-check me-2"></i>
                        Simpan
                    </el-button>
                </div>
            </el-form>
        </el-dialog>
    </base-layout>
</template>

<script>
import moment from 'moment';
export default {
    props : {
        data : {
            type : Object,
            default : {}
        },
        errors : Object,
    },
    data() {
        return {
            modalConfirm : false,
            form : {
                date : moment().format("YYYY-MM-DD"),
                ref : null,
            },
            lines : [],
        };
    },
    mounted(){
        this.setInitValue();
    },
    methods :{
        formatDate(date){
            moment.locale('id');
            return moment(date).locale('id').format('DD MMMM YYYY');
        },
        disabledDate(time){
            var date = new Date();
            date.setDate(date.getDate() - 1);
            return time < date;
        },
        setInitValue(){
            this.data.lines.forEach((v, i) => {
                this.lines.push({
                    'id' : v.id,
                    'product' : v.product.name,
                    'variant' : v.variant.name,
                    'variant_id' : v.variant_id,
                    'product_id' : v.product_id,
                    'price' : v.price,
                    'qty' : v.qty,
                    'stock' :  v.variant.stock,
                    'qty_receipt' : v.qty,
                    'unit_id' : v.unit_id,
                    'unit' : v.unit.name,
                    'unit_code' : v.unit.code,
                    'subtotal' : v.subtotal,
                });
            });
        },
        submit() {
            this.loading = true;
            let data = {
                'ref' : this.form.ref,
                'date' : this.form.date,
                'state' : 'done',
                'lines' : this.lines,
            };
            let form = this.$inertia.form(data);
            let url = this.route('admin.purchase.order.state', {id : this.data.id});
            form.post(url, {
                preserveScroll: true,
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Data Berhasil Disimpan!',
                    });
                    this.modalConfirm = false;
                },
                onFinish:() => {
                    this.loading = false;
                },
            });
        },
        updateState(state){
            let msg = (state == 'done') ? 'Konfirmasi Status Pembelian!' : 'Batalkan Pembelian?';
            ElMessageBox.alert(msg, 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.post(this.route('admin.purchase.order.state', {id : this.data.id}), {
                    state : state,
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