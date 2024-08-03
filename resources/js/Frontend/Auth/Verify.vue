<template>
    <base-layout title="Masuk">
        <div class="content content-full">
            <el-row justify="center" >
                <el-col :md="10" :lg="10" class="align-items-center">
                    <div class="block rounded block-bordered">
                        <div class="block-content p-3">
                            <div class="text-center">
                                <h2 class="fs-4 text-center mb-3">Verifikasi Email</h2>
                                <h3 class="fs-5 text-center">Verifikasi email kamu untuk menyelesaikan pendaftaran</h3>
                                <i class="fa-5x si si-envelope"></i>
                            </div>
                            <div class="font-size-h5">Hai {{ $page.props.user.name }}!</div>
                            <p class="nice-copy mb-2">Kami sudah mengirim email ke <b>{{ $page.props.user.email }}</b> berserta link untuk verifikasi akun kamu.
                                Apabila tidak ditemukan, periksa folder spam email<br>
                            </p>
                            <!-- <Link :href="route('verification.resend')" method="post" as="button" class="btn btn-primary btn-block">
                                <span class="font-size-sm font-w500">Kirim Ulang Email</span>
                            </Link> -->
                            <el-button type="primary" class="w-100" @click="resentEmail">
                                Kirim Ulang Email
                            </el-button>
                        </div>
                    </div>
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
                email: '',
                password: '',
                remember: false
            }
        }
    },
    methods :{
        resentEmail() {
            this.isLoading = true;
            // let form = this.$inertia.form(this.form);
            // form.post(this.route('login'), {
            //     preserveScroll: true,
            //     onSuccess: () => {
            //         ElMessage({
            //             type: 'success',
            //             message: 'Login Berhasil!',
            //         });
            //     },
            //     onFinish:() => {
            //         this.isLoading = false;
            //     },
            // });
            this.$inertia.visit(this.route('verification.send'),{
                method: 'post',
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Email Berhasil Terkirim!',
                    });
                },
            });
        }
    }
}
</script>