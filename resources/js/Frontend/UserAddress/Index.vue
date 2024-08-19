<template>
    <base-layout>
        <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fs-4 fw-bold mb-0">Buku Alamat</h3>
            <div class="space-x-1">
                <a :href="route('user.address.create')" class="ep-button ep-button--primary">
                    <i class="fa fa-plus me-1"></i>
                    Tambah Alamat
                </a>
            </div>
        </div>
        <el-row :gutter="20">
            <el-col :span="12" v-for="d in data" :key="d.id" class="mb-4">
        <div class="block rounded block-shadow block-bordered mb-2" >
            <div class="block-header border-3x border-bottom p-2">
                <h3 class="block-title">{{ d.name }}</h3>
                <span class="badge badge-primary p-1" v-if="d.is_main == 1">Alamat Utama</span>
            </div>
            <div class="block-content p-4">
                <div class="content__top-info">
                    <span class="top-info__name">{{ d.reciever }}</span>
                    <span class="top-info__phone">{{ d.phone }}</span>
                </div>
                <div class="content__complete-address">
                    {{ d.address }}
                </div>
            </div>
            <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                <a :href="route('user.address.edit', {id : d.id})" class="ep-button ep-button--primary">
                    <i class="si si-note me-1"></i>
                    Ubah
                </a>
                <el-button type="danger" @click.prevent="hapus(d.id)">
                    <i class="si si-trash me-1"></i>
                    Hapus
                </el-button>
                <button type="button" @click.prevent="setMainAddress(d.id)" class="btn btn-sm btn-primary" v-if="d.is_primary == 0">
                    Jadikan Alamat Utama
                </button>
            </div>
        </div>
            </el-col>
        </el-row>
        </div>
    </base-layout>
</template>

<script>
export default {
    data() {
        return {
            data : [],
            isLoading : false,
            // page : 1,
            pageSize : 0,
            total : 0,
        }
    },
    async mounted(){
        await this.fetchData();
    },
    methods : {
        async fetchData(){
            try {
                this.isLoading = true;
                const response = await axios.get("/user/alamat/data",{
                    params: {
                        // page: page,
                    }
                });
                if(response.status == 200){
                    this.data = response.data;
                    // this.page = response.data.current_page;
                    // this.total = response.data.total;
                    // this.pageSize = response.data.per_page;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        hapus(id){
            ElMessageBox.alert('Data yang dihapus tidak bisa dikembalikan!', 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.delete(this.route('user.address.delete', {id : id}), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.fetchData();
                        ElMessage({
                            type: 'success',
                            message: 'Data Berhasil Dihapus!',
                        });
                    },
                });
            });
        },
    },
}
</script>