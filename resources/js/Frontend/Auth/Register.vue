<template>
    <base-layout title="Daftar">
        <div class="content content-full">
            <el-row justify="center" >
                <el-col :md="10" :lg="10" class="align-items-center">
                    <div class="text-center">
                        <h2 class="font-weight-bold mb-0">Daftar</h2>
                        <p>Sudah punya akun? <a :href="route('login')">Daftar</a> di sini</p>
                    </div>
                    <el-form
                        label-position="top"
                        label-width="100%"
                        @submit.prevent="submit"
                    >
                        <div class="block block-rounded block-fx-shadow">
                            <!-- Database section -->
                            <div class="block-content block-content-full">
                                <el-form-item label="Nama Lengkap" :error="errors.name">
                                    <el-input v-model="form.name" type="text" placeholder="Masukan Nama Lengkap"/>
                                </el-form-item>
                                <el-form-item label="Email" :error="errors.email">
                                    <el-input v-model="form.email" type="text" placeholder="Masukan Email"/>
                                </el-form-item>
                                <el-form-item label="No Handphone" :error="errors.phone">
                                    <el-input v-model="form.phone" type="text" placeholder="Masukan No Handphone"/>
                                </el-form-item>
                                <el-form-item label="Masukan password" :error="errors.password">
                                    <el-input v-model="form.password" type="password" placeholder="Masukan Password" show-password/>
                                </el-form-item>
                                <el-form-item label="Masukan password" :error="errors.password_conf">
                                    <el-input v-model="form.password_conf" type="password" placeholder="Masukan Konfirmasi Password" show-password/>
                                </el-form-item>
                                <el-button native-type="submit" type="primary" class="w-100" :loading="isLoading">Login Sekarang</el-button>
                            </div>
                        </div>
                    </el-form>
                </el-col>
            </el-row>
        </div>
    </base-layout>
</template>

<script>
export default {
    props :{
        errors : Object,
    },
    data() {
        return {
            isLoading : false,
            form : {
                name : '',
                email: '',
                phone : '',
                password: '',
                password_conf : '',
            }
        }
    },
    methods :{
        submit() {
            this.isLoading = true;
            let form = this.$inertia.form(this.form);
            form.post(this.route('register'), {
                preserveScroll: true,
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Pendaftaran Berhasil!',
                    });
                },
                onFinish:() => {
                    this.isLoading = false;
                },
            });
        }
    }
}
</script>