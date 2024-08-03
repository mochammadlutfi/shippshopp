<template>
    <base-layout :title="title">
        <div class="content">
            <el-form label-width="30%" @submit.prevent="submit" label-position="top">
                <div class="content-heading d-flex justify-content-between align-items-center pt-0">
                    <span>{{ title }}</span>
                    <div class="space-x-1">
                        <el-button native-type="submit" type="primary" :loading="loading">
                            <i class="fa fa-check me-2"></i>
                            Simpan
                        </el-button>
                    </div>
                </div>
                <div class="block block-rounded">
                    <div class="block-content p-4">
                        <el-row :gutter="32">
                            <el-col :lg="12">
                                <el-form-item class="mb-4" label="Pelanggan" :error="errors.name">
                                    <customer-select v-model="form.customer_id"/>
                                </el-form-item>
                            </el-col>
                            <el-col :lg="12">
                                <el-form-item class="mb-4" label="Tanggal" :error="errors.email">
                                    <el-date-picker
                                        v-model="form.date"
                                        type="date"
                                        class="w-100"
                                        placeholder="Pilih Tanggal"
                                        format="DD MMMM YYYY"
                                        value-format="YYYY-MM-DD"
                                    />
                                </el-form-item>
                            </el-col>
                        </el-row>  
                        
                        <el-autocomplete
                            v-model="query"
                            :fetch-suggestions="findProduct"
                            clearable
                            :trigger-on-focus="false"
                            class="inline-input w-50 mb-3"
                            placeholder="Cari Produk"
                            @select="selectProduct"
                        >
                            <template #default="{ item }">
                                <div>{{ item.product }}
                                    <template v-if="item.product">
                                        - {{ item.name }}
                                    </template>
                                </div>
                                <div>{{ item.sku }}</div>
                            </template>
                            <template #prefix>
                                <span>
                                    <i class="fa fa-search"></i>
                                </span>
                            </template>
                        </el-autocomplete>
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
                            <el-table-column label="Harga">
                                <template #default="scope">
                                    {{ currency(scope.row.price) }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Qty">
                                <template #default="scope">
                                    <el-input-number v-model="scope.row.qty" :min="1"  @change="calculateTotal"/>
                                </template>
                            </el-table-column>
                            <el-table-column label="Total">
                                <template #default="scope">
                                    {{ currency(scope.row.price * scope.row.qty) }}
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

                        <el-row justify="end">
                            <el-col :lg="12" class="float-end">
                                <div class="d-flex float-end justify-content-end w-75 fs-4">
                                    <div class="">Total</div>
                                    <div class="text-end w-50">{{ currency(form.total) }}</div>
                                </div>
                            </el-col>
                        </el-row>
                    </div>
                </div>
            </el-form>
        </div>
    </base-layout>
</template>

<script>
import CustomerSelect from '../Customer/CustomerSelect.vue';
export default {
    components :{
        CustomerSelect
    },
    props : {
        data : {
            type : Object,
            default : {}
        },
        errors : Object,
    },
    data() {
        return {
            title : 'Tambah Penjualan',
            query : "",
            loading : false,
            form : {
                id : null,
                nomor : null,
                customer_id : null,
                date : null,
                total : 0,
            },
            lines : [],
            lines_deleted : []
        }
    },
    created(){
        if(Object.keys(this.data).length){
            this.setValue();
        }
    },
    methods : {
         selectProduct(data)
        {
            if(this.lines.length >= 1){
                if(this.lines.some(detail => detail.variant_id === data.id)){
                    for (var i = 0; i < this.lines.length; i++) {
                        if (this.lines[i].variant_id === data.id) {
                            this.lines[i].qty++;
                        }
                    }
                } else {
                    this.lines.push({
                        id : null,
                        variant_id : data.id,
                        product_id : data.product_id,
                        variant : data.name,
                        product : data.product,
                        qty : 1,
                        price : data.sell_price,
                        unit_id : data.sell_unit_id,
                    });
                }
            }else{
                this.lines.push({
                    id : null,
                    variant_id : data.id,
                    product_id : data.product_id,
                    variant : data.name,
                    product : data.product,
                    qty : 1,
                    price : data.sell_price,
                    unit_id : data.sell_unit_id,
                });
            }
            this.calculateTotal();
            this.$forceUpdate();
        },
        async findProduct(v)
        {
            try {
                this.isLoading = true;
                const response = await axios.get("/admin/produk/search",{
                    params: {
                        q : v,
                    }
                });
                if(response.status == 200){
                    return response.data;
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
            this.calculateTotal();
            this.$forceUpdate();
        },
        submit() {
            this.loading = true;
            let data = Object.assign(this.form, {
                    lines : this.lines,
                    lines_deleted : this.lines_deleted,
                }
            );
            let form = this.$inertia.form(data);
            let url = (Object.keys(this.data).length) ? this.route('admin.sale.order.update', {id : this.data.id}) : this.route('admin.sale.order.store');
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
        setValue(){
            this.title = 'Ubah Penjualan';

            this.form.id = this.data.id;
            this.form.nomor = this.data.nomor;
            this.form.customer_id = this.data.customer_id;
            this.form.date = this.data.date;
            this.form.total = this.data.total;

            this.data.lines.forEach((v, i) => {
                this.lines.push({
                    'id' : v.id,
                    'product' : v.product.name,
                    'variant' : v.variant.name,
                    'variant_id' : v.variant_id,
                    'product_id' : v.product_id,
                    'price' : v.price,
                    'qty' : v.qty,
                    'unit_id' : v.unit_id,
                    'subtotal' : v.subtotal,
                });
            });
        },
        calculateTotal()
        {
            let total = 0;
            for (var i = 0; i < this.lines.length; i++) {
                this.lines[i].subtotal = this.lines[i].qty * this.lines[i].price;

                total += this.lines[i].subtotal;
            }
            this.form.total = total;
        }
    }
}
</script>