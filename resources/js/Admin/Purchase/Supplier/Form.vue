<template>
    <base-layout>
        <div class="content">
            <el-form label-width="30%" @submit.prevent="submit" label-position="top">
                <div class="content-heading d-flex justify-content-between align-items-center pt-0">
                    <span>{{ title }}</span>
                    <div class="space-x-1">
                    </div>
                </div>
                <div class="block rounded block-bordered" v-loading="loading">
                    <div class="block-content p-4">
                        <el-row :gutter="32">
                            <el-col :lg="12">
                                <el-form-item class="mb-4" label="Nama Supplier" :error="errors.name">
                                    <el-input v-model="form.name" placeholder="Masukan Nama Supplier"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="No Handphone" :error="errors.phone">
                                    <el-input v-model="form.phone" placeholder="Masukan No Handphone"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Alamat Email" :error="errors.email">
                                    <el-input v-model="form.email" placeholder="Masukan Alamat Email"/>
                                </el-form-item>
                            </el-col>
                            <el-col :lg="12">
                                <el-form-item class="mb-4" label="Alamat Lengkap" :error="errors.address">
                                    <el-input v-model="form.address" type="textarea" :rows="4" placeholder="Masukan Alamat Email"/>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-button native-type="submit" type="primary">
                            <i class="fa fa-check me-1"></i>
                            Simpan
                        </el-button>
                    </div>
                </div>
            </el-form>
        </div>
    </base-layout>
</template>

<script>
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
            title : 'Tambah Supplier Baru',
            loading : false,
            form : {
                id : null,
                name : null,
                pic : null,
                phone : null,
                email : null,
                address : null,
            },
            query : '',
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
        submit() {
            this.loading = true;
            let data = Object.assign(this.form, {
                    lines : this.lines,
                    lines_deleted : this.lines_deleted,
                }
            );
            let form = this.$inertia.form(data);
            let url = (Object.keys(this.data).length) ? this.route('admin.purchase.supplier.update', {id : this.data.id}) : this.route('admin.purchase.supplier.store');
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
         selectProduct(data)
        {
            if(this.lines.length >= 1){
                if(this.lines.some(detail => detail.variant_id === data.id)){
                    for (var i = 0; i < this.lines.length; i++) {
                        if (this.lines[i].variant_id === data.id) {
                            
                        }
                    }
                } else {
                    this.lines.push({
                        id : null,
                        variant_id : data.id,
                        product_id : data.product_id,
                        variant : data.name,
                        product : data.product,
                    });
                }
            }else{
                this.lines.push({
                    id : null,
                    variant_id : data.id,
                    product_id : data.product_id,
                    variant : data.name,
                    product : data.product,
                });
            }
            this.$forceUpdate();
        },
        removeLine(index, id)
        {
            if(id){
                this.lines_deleted.push(id);
            }
            this.lines.splice(index, 1);
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
        setValue(){
            this.title = 'Ubah Supplier';

            this.form.id = this.data.id;
            this.form.name = this.data.name;
            this.form.pic = this.data.pic;
            this.form.phone = this.data.phone;
            this.form.email = this.data.email;
            this.form.address = this.data.address;

            
            this.data.lines.forEach((v, i) => {
                this.lines.push({
                    'id' : v.id,
                    'product' : v.product.name,
                    'variant' : v.variant.name,
                    'variant_id' : v.variant_id,
                    'product_id' : v.product_id,
                    'supplier_id' : v.supplier_id,
                });
            });
        },  
    }
}
</script>