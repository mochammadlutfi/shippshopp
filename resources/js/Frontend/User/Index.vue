<template>
    <base-layout title="Profil">
        <el-row justify="center">
            <el-col :span="12">
                <div class="content">
                            
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fs-4 fw-bold mb-0">Ubah Profil</h3>
                        <div class="space-x-1">
                        </div>
                    </div>
                    <div class="block rounded">
                        <div class="block-content p-4">
                            <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                                <el-form-item label="Nama Lengkap" :error="errors.name">
                                    <el-input v-model="form.name" type="text" placeholder="Masukan Nama Lengkap"/>
                                </el-form-item>
                                <el-form-item label="Email" :error="errors.email">
                                    <el-input v-model="form.email" type="text" placeholder="Masukan Email"/>
                                </el-form-item>
                                <el-form-item label="No Handphone" :error="errors.phone">
                                    <el-input v-model="form.phone" type="text" placeholder="Masukan No Handphone"/>
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
        data : Object,
        errors: Object
    },
    data (){
        return {
            isLoading : false,
            form : {
                name : this.data.name,
                email : this.data.email,
                phone : this.data.phone
            }
        }
    },
    methods : {
        submit() {
            this.isLoading = true;
            let form = this.$inertia.form(this.form);
            form.post(this.route('user.update'), {
                preserveScroll: true,
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Profil berhasil diperbaharui!',
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