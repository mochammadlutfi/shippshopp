<template>
    
    <el-config-provider namespace="ep" :locale="locale">
        <div id="page-container" class="main-content-boxed">
            <div id="main-container">
                <div class="content content-full">
                    <el-row justify="center" class="py-md-7 my-30">
                        <el-col :span="16">
                            <div class="block rounded block-fx-shadow">
                                <div class="block-content p-3">
                                    <el-row>
                                        <el-col :span="12" class="text-center">
                                            <img src="/images/logo/logo.png" class="img-fluid"/>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                                                <h1 class="h3 fw-bold mt-3 mb-2">Masuk Sekarang</h1>
                                                <el-form-item label="Username" :error="errors.username">
                                                    <el-input v-model="form.username" type="text"
                                                        placeholder="Masukan Username" />
                                                </el-form-item>
                                                <el-form-item label="Masukan password" :error="errors.password">
                                                    <el-input v-model="form.password" type="password"
                                                        placeholder="Masukan password" show-password />
                                                </el-form-item>
                                                <el-button native-type="submit" type="primary" class="w-100"
                                                    :loading="loading">Login Sekarang</el-button>
                                            </el-form>
                                        </el-col>
                                    </el-row>

                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
    </el-config-provider>
</template>

<script>
import id from 'element-plus/dist/locale/id.mjs';
export default {
    components: {
        ElConfigProvider,
    },
    setup() {
        return {
            zIndex: 3000,
            size: 'small',
            locale : id,
        }
    },
    props: {
        canResetPassword: Boolean,
        status: String,
        errors: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                'username': '',
                'password': '',
                'remember': false
            }),
            rules: {
                required: value => !!value || 'Required.',
                counter: value => value.length <= 20 || 'Max 20 characters',
                username: value => {
                    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    return pattern.test(value) || 'Invalid e-mail.'
                },
            },
            loading : false,
        }
    },

    methods: {
        submit() {
            this.loading = true;
            this.form.post(this.route('admin.login'), {
                onFinish: () => {
                    this.form.reset('password');
                    this.loading = false;
                },
            })
        }
    }
 }

</script>
