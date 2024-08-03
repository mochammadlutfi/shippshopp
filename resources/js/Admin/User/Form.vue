<template>
    <base-layout>
        <div class="content">
            <el-form label-width="30%" @submit.prevent="submit" label-position="left">
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
                    <div class="block-content">
                        <el-row :gutter="32">
                            <el-col :lg="16">
                                <el-form-item class="mb-4" label="Nama Lengkap" :error="errors.name">
                                    <el-input v-model="form.name" placeholder="Masukan Nama Lengkap"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Username" :error="errors.username">
                                    <el-input v-model="form.username" placeholder="Masukan Username"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Alamat Email" :error="errors.email">
                                    <el-input v-model="form.email" placeholder="Masukan Alamat Email"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Hak Akses" :error="errors.level">
                                    <el-select v-model="form.level" class="w-100" placeholder="Pilih">
                                        <el-option label="Admin" value="admin" />
                                        <el-option label="Gudang" value="gudang" />
                                    </el-select>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Password" :error="errors.password">
                                    <el-input type="password" v-model="form.password" placeholder="Masukan Password" show-password/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Konfirmasi Password" :error="errors.password_conf">
                                    <el-input type="password" v-model="form.password_conf" placeholder="Masukan Konfirmasi Password" show-password/>
                                </el-form-item>
                            </el-col>
                        </el-row>
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
            title : 'Tambah Pengguna Baru',
            loading : false,
            form : {
                id : null,
                name : null,
                level : null,
                username : null,
                email : null,
                password : null,
                password_conf : null,
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
            let url = (Object.keys(this.data).length) ? this.route('admin.user.update', {id : this.data.id}) : this.route('admin.user.store');
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
            this.title = 'Ubah Pengguna';

            this.form.id = this.data.id;
            this.form.name = this.data.name;
            this.form.username = this.data.username;
            this.form.level = this.data.level;
            this.form.email = this.data.email;
        },  
    }
}
</script>