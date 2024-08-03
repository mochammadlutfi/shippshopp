<template>
    <user-layout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fs-4 fw-bold mb-0">Retur Pesanan</h3>
            <div class="space-x-1">
                <a :href="route('user.return.create')" class="ep-button ep-button--primary">
                    <i class="fa fa-plus me-1"></i>
                    Ajukan Retur
                </a>
            </div>
        </div>
        
        
        <div class="block block-bordered rounded mb-3">
            <ul class="nav nav-tabs nav-tabs-alt nav-fill">
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'pending') ? true : false }"
                        :href="route('user.return.index', { status : 'pending' })">Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'confirm') ? true : false }"
                        :href="route('user.return.index', { status : 'confirm' })">Disetujui
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:class="{ 'active' : (status == 'reject') ? true : false }"
                        :href="route('user.return.index', { status : 'reject' })">Ditolak
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
                        <div class="d-flex float-end my-auto">
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
                                <!-- <button @click="prevPage" class="btn btn-alt-secondary mx-1" type="button"
                                    :disabled="checkPaginate('prev')">
                                    <i class="fa fa-chevron-left fa-fw"></i>
                                </button>
                                <button @click="nextPage" class="btn btn-alt-secondary mx-1" type="button"
                                    :disabled="checkPaginate('next')">
                                    <i class="fa fa-chevron-right fa-fw"></i>
                                </button> -->
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </div>
            
        <template v-if="data.length">
        <div class="block rounded block-bordered mb-2" v-for="d in data" :key="d.id">
            <div class="block-header border-3x border-bottom p-3">
                <h3 class="block-title">{{ format_date(d.created_at) }}</h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <el-tag class="ml-2" type="warning" v-if="d.status == 'pending'">Menunggu Konfirmasi</el-tag>
                        <el-tag class="ml-2" type="info" v-else-if="d.status == 'confirm'">Disetujui</el-tag>
                        <el-tag class="ml-2" type="danger" v-else-if="d.status == 'cancel'">Ditolak</el-tag>
                    </div>
                </div>
            </div>
            <div class="block-content p-3">
                <el-row :gutter="20">
                    <el-col :lg="18">
                        <el-row :gutter="20">
                            <el-col :lg="3">
                                <img :src="d.lines[0].product.main_image" class="img-fluid">
                            </el-col>
                            <el-col :lg="18" class="d-flex">
                                <div class="my-auto">
                                    <div class="fs-6 fw-bold">
                                        {{ d.lines[0].product.name }}
                                        <template v-if="d.lines[0].variant.name">
                                        - {{ d.lines[0].variant.name }}
                                        </template>
                                    </div>
                                    <div class="fs-sm fw-semibold">
                                        {{ d.lines[0].qty }}
                                    </div>
                                </div>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
            </div>
            <div class="block-content block-content-full block-content-sm text-right border-top">
                <a :href="route('user.return.show', { id : d.id} )" class="ep-button ep-button--primary is-plain">
                    Detail Retur
                </a>
            </div>
        </div>
        </template>
    </user-layout>
</template>


<script>
import moment from 'moment';
export default {
    data() {
        return {
            status : this.route().params.status == undefined ? 'pending' : this.route().params.status,
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
                const response = await axios.get(this.route("user.return.data"),{
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
    },
}
</script>