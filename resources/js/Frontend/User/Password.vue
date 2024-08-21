<template>
    <base-layout title="Ubah Password">
        <el-row justify="center">
            <el-col :span="12">
                <div class="content">
                            
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fs-4 fw-bold mb-0">Ubah Password</h3>
                        <div class="space-x-1">
                        </div>
                    </div>
                    <div class="block rounded">
                        <div class="block-content p-4">
                            <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                                <el-form-item label="Password Sekarang" :error="errors.password_old">
                                    <el-input v-model="form.password_old" type="password"/>
                                </el-form-item>
                                <el-form-item label="Password Baru" :error="errors.password_new">
                                    <el-input v-model="form.password_new" type="password"/>
                                </el-form-item>
                                <el-form-item label="Konfirmasi Password Baru" :error="errors.password_confirmation">
                                    <el-input v-model="form.password_confirmation" type="password"/>
                                </el-form-item>

                                <el-button native-type="submit" type="primary" class="w-100" :loading="isLoading">
                                    Simpan
                                </el-button>
                            </el-form>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
    </base-layout>
</template>
<script>
export default {
    props : {
        errors: Object
    },
    data (){
        return {
            isLoading : false,
            form : {
                password_old : null,
                password_new : null,
                password_confirmation : null
            }
        }
    },
    methods : {
        submit() {
            this.isLoading = true;
            let form = this.$inertia.form(this.form);
            form.post(this.route('user.password'), {
                preserveScroll: true,
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Password berhasil diperbaharui!',
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