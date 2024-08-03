<template>
    <user-layout>
        <el-form label-width="30%" @submit.prevent="submit" label-position="top">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fs-4 fw-bold mb-0">Pengajuan Retur</h3>
                <div class="space-x-1">
                    <button class="ep-button ep-button--primary">
                        <i class="fa fa-check me-1"></i>
                        Kirim
                    </button>
                </div>
            </div>
            <div class="block rounded block-bordered">
                <div class="block-content p-3">
                    <el-form-item label="Pilih Pesanan" :error="errors.order_id">
                        <select-order v-model="form.order_id" @change="fetchData"/>
                    </el-form-item>

                    
                    <el-table :data="lines" border class="mb-4" id="variant">
                        <el-table-column label="Produk">
                            <template #default="scope">
                                <div>{{ scope.row.product }}
                                    <template v-if="scope.row.product">
                                        - {{ scope.row.variant }}
                                    </template>
                                </div>
                                <div>{{ scope.row.sku }}</div>
                            </template>
                        </el-table-column>
                        <el-table-column label="Qty Order" width="90px">
                            <template #default="scope">
                                {{  scope.row.qty_order }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Qty Retur" width="175px">
                            <template #default="scope">
                                <el-input-number v-model="scope.row.qty" :min="1" :max="scope.row.qty_order"/>
                            </template>
                        </el-table-column>
                        <el-table-column label="Alasan">
                            <template #default="scope">
                                <el-input v-model="scope.row.reason"/>
                            </template>
                        </el-table-column>
                        <el-table-column width="60px" align="center">
                            <template #default="scope">
                                <el-button type="danger" size="small" @click="removeLine(scope.$index, scope.row.id)">
                                    <i class="fa fa-times"></i>    
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </el-form>
    </user-layout>
</template>

<script>
import SelectOrder from '../UserOrder/SelectOrder.vue';
export default {
    components :{
        SelectOrder
    },
    props : {
        errors : {
            type : Object,
        }
    },
    data(){
        return {
            form : {
                order_id : null,
            },
            lines : [],
        };
    },
    setup() {
        
    },
    methods :{
        async fetchData()
        {
            try {
                this.isLoading = true;
                const response = await axios.get(this.route("user.order.data"),{
                    params: {
                        id : this.form.order_id,
                    }
                });
                if(response.status == 200){
                    // this.dataList = [];
                    // this.dataList = response.data;
                    console.log(response.data);
                    response.data.lines.forEach((v, i) => {
                        this.lines.push({
                            'id' : null,
                            'line_id' : v.id,
                            'product' : v.product.name,
                            'variant' : v.variant.name,
                            'variant_id' : v.variant_id,
                            'product_id' : v.product_id,
                            'qty_order' : v.qty,
                            'qty' : v.qty,
                            'reason' : '',
                        });
                    });
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        removeLine(index, id)
        {
            if(id){
                this.lines_deleted.push(id);
            }
            this.lines.splice(index, 1);
            this.$forceUpdate();
        },
        submit() {
            this.loading = true;
            let data = Object.assign(this.form, {
                    lines : this.lines
                }
            );
            let form = this.$inertia.form(data);
            let url = this.route('user.return.store');
            form.post(url, {
                preserveScroll: true,
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Data Berhasil Disimpan!',
                    });
                },
                onFinish:() => {
                    this.loading = false;
                },
            });
        },
    }
}
</script>