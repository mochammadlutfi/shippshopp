<template>
    <base-layout>
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fs-4 fw-bold mb-0">Pesanan Saya</h3>
        </div>
        
        <div class="block block-bordered rounded mb-3">
            <ul class="nav nav-fill nav-tabs-capsule">
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'unpaid') ? true : false }"
                        :href="route('user.order.index', { status : 'unpaid' })">Belum Bayar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'pending') ? true : false }"
                        :href="route('user.order.index', { status : 'pending' })">Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'process') ? true : false }"
                        :href="route('user.order.index', { status : 'process' })">Dikemas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'shipped') ? true : false }"
                        :href="route('user.order.index', { status : 'shipped' })">Dikirim
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' :  (status == 'done') ? true : false }"
                        :href="route('user.order.index', { status : 'done' })">Selesai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' :  (status == 'cancel') ? true : false }"
                        :href="route('user.order.index', { status : 'cancel' })">Dibatalkan
                    </a>
                </li>
            </ul>
            <div class="block-content p-2">
                <el-row :gutter="12">
                    <el-col :lg="12">
                        <el-input v-model="searchTerms" class="w-75 m-2" placeholder="Cari Pesanan">
                            <template #prefix>
                                <i class="fa fa-search"></i>
                            </template>
                        </el-input>
                    </el-col>
                    <el-col :lg="12" class="d-flex float-end my-auto">
                        <div class="d-flex my-auto ms-auto">
                            <div class="my-auto px-3">
                                <span>{{ from }}-{{ to }}/{{ total }}</span>
                            </div>
                            <div class="pt-25 pl-0">
                                <el-button type="primary" size="small">
                                    <i class="fa fa-chevron-left fa-fw"></i>
                                </el-button>
                                <el-button type="primary" size="small" plain>
                                    <i class="fa fa-chevron-right fa-fw"></i>
                                </el-button>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </div>
    </div>
    </base-layout>
</template>

<script>
import moment from 'moment';
export default {
    data() {
        return {
            status : this.route().params.status == undefined ? 'unpaid' : this.route().params.status,
            data : [],
            isLoading : false,
            page : 1,
            pageSize : 0,
            total : 0,
            from : 0,
            to :  0,
            searchTerms : '',
        }
    },
    async mounted(){
        await this.fetchData();
    },
    methods : {
        async fetchData(page){
            var page = (page == undefined) ? 1 : page;
            try {
                this.isLoading = true;
                const response = await axios.get(this.route("user.order.data"),{
                    params: {
                        page: page,
                        search : this.searchTerms,
                        status : this.status
                    }
                });
                if(response.status == 200){
                    this.data = response.data.data;
                    this.page = response.data.current_page;
                    this.total = response.data.total;
                    this.pageSize = response.data.per_page;
                    this.from = response.data.from;
                    this.to = response.data.to;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        format_date(v){
            if (v) {
                moment.locale('id');
                return moment(String(v)).format('DD MMM YYYY')
            }
        },
        payment(d){
            snap.pay(d.payment_ref, {
                onSuccess: function(result){
                    this.updatePayment('paid', d.id);
                    ElMessage({
                        type: 'success',
                        message: 'Pembayaran Berhasil',
                    });
                    // window.location.href = vm.route('user.order.show', {id : d.id});
                },
                onPending: function(result){
                    ElMessage({
                        type: 'info',
                        message: 'Menunggu Pembayaran',
                    });
                    // window.location.href = vm.route('user.order.show', {id : d.id});
                },
                onError: function(result){
                    ElMessage({
                        type: 'error',
                        message: 'Pembayaran Gagal',
                    });
                    // window.location.href = vm.route('user.order.show', {id : d.id});
                },
                onClose: function(result){
                    // alert('asa');
                    console.log(result);
                    // snap.hide();
                }
            });
        },
        async updatePayment(status, id){
            try {
                this.isLoading = true;
                const response = await axios.post(this.route("user.order.state", {id : id}),{
                    status: status,
                });
                if(response.status == 200){
                    location.reload();
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        receive(id){
            ElMessageBox.alert('Pastikan barang yang diterima sesuai dengan pesanan!', 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Pesanan Sudah Diterima!',
                cancelButtonText: 'Batal!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.post(this.route('user.order.confirm', {id : id}), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.fetchData();
                        ElMessage({
                            type: 'success',
                            message: 'Pesanan Berhasil Diterima!',
                        });
                    },
                });
            });
        },
    },
}
</script>