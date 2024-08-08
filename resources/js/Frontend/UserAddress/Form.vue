<template>
    <base-layout>
        <div class="content">
            <el-row justify="center">
                <el-col :span="16">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fs-4 fw-bold mb-0">{{ title }}</h3>
                    </div>
                    <div class="block rounded">
                        <div class="block-content p-4">
                            <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                                <el-form-item label="Alamat Lengkap" :error="errors.phone">
                                    <el-input v-model="form.address" type="textarea" :rows="2"
                                        placeholder="Masukan Alamat" />
                                </el-form-item>
                                <el-form-item label="Nama Alamat" :error="errors.name">
                                    <el-input v-model="form.name" type="text" placeholder="Masukan Nama Alamat" />
                                </el-form-item>
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="Nama Penerima" :error="errors.name">
                                            <el-input v-model="form.reciever" type="text"
                                                placeholder="Masukan Nama Penerima" />
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="No HP Penerima" :error="errors.phone">
                                            <el-input v-model="form.phone" type="text"
                                                placeholder="Masukan No Handphone" />
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-form-item label="Jadikan Alamat Utama?">
                                    <el-radio-group v-model="form.is_main">
                                        <el-radio :label="1" border>Ya</el-radio>
                                        <el-radio :label="0" border>Tidak</el-radio>
                                    </el-radio-group>
                                </el-form-item>
                                <el-button native-type="submit" type="primary" class="w-100" :loading="isLoading">
                                    Simpan
                                </el-button>
                                <!-- </template> -->
                            </el-form>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </base-layout>
</template>

<script>
import { defineComponent, ref, computed, watch } from 'vue';

export default {
    components : {

    },
    setup() {

    },
    props : {
        data : Object,
        errors: Object
    },
    data() {
        return {
            title : 'Tambah Alamat',
            isLoading : false,
            form : {
                id : null,
                name : null,
                reciever : null,
                phone : null,
                address : null,
                is_main : 0,
            },
            center: { 
                lat: 51.093048, 
                lng: 6.84212 
            },
            marker: {
                lat: 51.093048,
                lng: 6.84212,
            },
        }
    },
    mounted() {

        if(Object.keys(this.data).length){
            this.setValue();
        }
    },
    methods : {

        setValue(){
            this.title = 'Ubah Alamat';

            this.form.id = this.data.id;
            this.form.name = this.data.name;
            this.form.reciever = this.data.reciever;
            this.form.phone = this.data.phone;
            this.form.address = this.data.address;
            this.form.is_main = this.data.is_main;
        },
        submit() {
            this.isLoading = true;
            let form = this.$inertia.form(this.form);

            let url = (this.data.id) ? route('user.address.update', {id : this.data.id}) : route('user.address.store')

            form.post(url, {
                preserveScroll: true,
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Alamat berhasil disimpan!',
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