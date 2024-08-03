<template>
    <base-layout>
        <div class="content">
            <el-form label-width="30%" @submit.prevent="submit" label-position="top">
                <div class="content-heading d-flex justify-content-between align-items-center pt-0">
                    <span>{{ title }}</span>
                </div>
                <div class="block block-rounded">
                    <div class="block-content">
                        <el-row :gutter="32">
                            <el-col :lg="16">
                                <el-form-item class="mb-4" label="Nama Pelanggan" :error="errors.name">
                                    <el-input v-model="form.name" placeholder="Masukan Nama Pelanggan"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="No Handphone" :error="errors.phone">
                                    <el-input v-model="form.phone" placeholder="Masukan No Handphone"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Alamat Email" :error="errors.email">
                                    <el-input v-model="form.email" placeholder="Masukan Alamat Email"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Password" :error="errors.password">
                                    <el-input type="password" v-model="form.password" placeholder="Masukan Password"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Konfirmasi Password" :error="errors.password_conf">
                                    <el-input type="password" v-model="form.password_conf" placeholder="Masukan Konfirmasi Password"/>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-button native-type="submit" type="primary" :loading="loading">
                            <i class="fa fa-check me-2"></i>
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
            title : 'Tambah Pelanggan Baru',
            loading : false,
            form : {
                id : null,
                name : null,
                pic : null,
                phone : null,
                email : null,
                address : null,
            }
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
            let form = this.$inertia.form(this.form);
            let url = (Object.keys(this.data).length) ? this.route('admin.sale.customer.update', {id : this.data.id}) : this.route('admin.sale.customer.store');
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
            this.title = 'Ubah Pelanggan';

            this.form.id = this.data.id;
            this.form.name = this.data.name;
            this.form.pic = this.data.pic;
            this.form.phone = this.data.phone;
            this.form.email = this.data.email;
            this.form.address = this.data.address;
        },  
    }
}
</script>