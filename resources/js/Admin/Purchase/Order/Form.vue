<template>
    <base-layout>
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
                                <el-form-item class="mb-4" label="Supplier" :error="errors.name">
                                    <supplier-select v-model="form.supplier_id" @change="getDetail"/>
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
                                        :disabled-date="disabledDate"
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
                                <div>{{ item.nama }}</div>
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
                                    <div>{{ scope.row.product }}</div>
                                    <div>{{ scope.row.sku }}</div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="stock" label="Stok"/>
                            <el-table-column label="Harga Beli" width="100">
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
import SupplierSelect from '../Supplier/SupplierSelect.vue';
import moment from 'moment';
export default {
    components :{
        SupplierSelect
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
            title : 'Tambah Pesanan Pembelian',
            query : "",
            loading : false,
            form : {
                id : null,
                nomor : null,
                supplier_id : null,
                date : moment().format("YYYY-MM-DD"),
                total : 0,
            },
            supplier : null,
            lines : [],
            lines_deleted : []
        }
    },
    mounted(){

        if(Object.keys(this.data).length){
            this.setValue();
        }
    },
    methods : {
        disabledDate(time){
            var date = new Date();
            date.setDate(date.getDate() - 1);
            return time < date;
        },
        async getDetail(v)
        {
            try {
                this.isLoading = true;
                const response = await axios.get("/admin/supplier/produk",{
                    params: {
                        id : v,
                    }
                });
                if(response.status == 200){
                    const data = response.data;
                    // console.log(data);
                    this.lines = [];
                    this.form.sales = data.pic;
                    data.lines.forEach((v, i) => {
                        this.lines.push({
                            'id' : v.id,
                            'product' : v.product.name,
                            'variant' : v.variant.name,
                            'stock' : v.variant.stock,
                            'variant_id' : v.variant_id,
                            'product_id' : v.product_id,
                            'unit' : v.variant.purchase_unit.name,
                            'price' : v.variant.purchase_price,
                            'qty' : 1,
                            'unit_id' : v.variant.purchase_unit_id,
                            'subtotal' : v.variant.purchase_price,
                        });
                    });
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
         selectProduct(data)
        {
            if(this.lines.length >= 1){
                if(this.lines.some(detail => detail.product_id === data.id)){
                    for (var i = 0; i < this.lines.length; i++) {
                        if (this.lines[i].product_id === data.id) {
                            this.lines[i].qty++;
                        }
                    }
                } else {
                    console.log(data);
                    this.lines.push({
                        id : null,
                        product_id : data.id,
                        stock : data.stok,
                        product : data.nama,
                        sku : data.sku,
                        qty : 1,
                        price : data.harga_beli
                    });
                }
            }else{
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
            let url = (Object.keys(this.data).length) ? this.route('admin.purchase.order.update', {id : this.data.id}) : this.route('admin.purchase.order.store');
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
            this.title = 'Ubah Pesanan Pembelian';

            this.form.id = this.data.id;
            this.form.nomor = this.data.nomor;
            this.form.supplier_id = this.data.supplier_id;
            this.form.date = this.data.date;
            this.form.total = this.data.total;
            this.form.sales = this.data.supplier.pic;

            this.data.lines.forEach((v, i) => {
                this.lines.push({
                    'id' : v.id,
                    'product' : v.product.name,
                    'variant' : v.variant.name,
                    'variant_id' : v.variant_id,
                    'product_id' : v.product_id,
                    'price' : v.price,
                    'qty' : v.qty,
                    'unit' : v.variant.purchase_unit.name,
                    'unit_id' : v.purchase_unit_id,
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