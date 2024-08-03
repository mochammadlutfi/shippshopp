<template>
    <base-layout>
        <div class="content">
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Pesanan Pembelian</span>
                <div class="space-x-1">
                    <a :href="route('admin.purchase.order.create')" class="ep-button ep-button--primary">
                        <i class="fa fa-plus me-1"></i>
                        Tambah
                    </a>
                    <el-button type="primary" @click.prevent="showModal = true">
                        <i class="fa fa-print me-1"></i>
                        Export
                    </el-button>
                </div>
            </div>
            
            <div class="block block-rounded bordered" v-loading="isLoading">
                <div class="block-content py-3">
                    <el-row justify="space-between">
                        <el-col :span="12">
                            <el-select v-model="params.limit" placeholder="Pilih" style="width: 115px" @change="fetchData(1)">
                                <el-option label="25" value="25"/>
                                <el-option label="50" value="50"/>
                                <el-option label="100" value="100"/>
                            </el-select>
                        </el-col>
                        <el-col :span="8">
                            <el-input
                                v-model="params.searchKeyword"
                                placeholder="Masukan kata kunci"
                                class="input-with-select"
                                clearable
                                >
                                <template #prefix>
                                    <i class="fa fa-search"></i>
                                </template>
                            </el-input>
                        </el-col>
                    </el-row>
                </div>
                <div class="block-content p-0">
                    <el-table :data="data" class="w-100"  
                    @sort-change="sortChange" header-cell-class-name="bg-primary text-dark">
                        <el-table-column type="index"  :index="indexMethod" width="100" />
                        <el-table-column prop="nomor" label="Nomor" sortable/>
                        <el-table-column prop="supplier.nama" label="Supplier" sortable/>
                        <el-table-column prop="state" label="Status" sortable>
                            <template #default="scope">
                                <el-tag type="warning" v-if="scope.row.state == 'draft'">
                                    Draft
                                </el-tag>
                                <el-tag type="success" v-else-if="scope.row.state == 'done'">
                                    Selesai
                                </el-tag>
                                <el-tag type="danger" v-else>Batal</el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="total" label="Total" sortable>
                            <template #default="scope">
                                {{ currency(scope.row.total) }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Aksi" align="center" width="180">
                            <template #default="scope">
                                <el-dropdown popper-class="dropdown-action" trigger="click">
                                    <el-button type="primary">
                                    Aksi <i class="fa fa-angle-down ms-1"></i>
                                    </el-button>
                                    <template #dropdown>
                                        <el-dropdown-menu>
                                            <el-dropdown-item>
                                                <a :href="route('admin.purchase.order.show', {id : scope.row.id})" class="w-100 d-flex align-items-center justify-content-between space-x-1">
                                                    Detail
                                                    <i class="si fa-fw si-eye"></i>
                                                </a>
                                            </el-dropdown-item>
                                            <template  v-if="$page.props.user.level != 'admin'">
                                            <el-dropdown-item>
                                                <a :href="route('admin.purchase.order.edit', {id : scope.row.id})" class="w-100 d-flex align-items-center justify-content-between space-x-1">
                                                Ubah
                                                <i class="si fa-fw si-note"></i>
                                            </a>
                                            </el-dropdown-item>
                                            <el-dropdown-item class="d-flex align-items-center justify-content-between space-x-1" @click.prevent="hapus(scope.row.id)">
                                                Hapus
                                                <i class="si fa-fw si-trash"></i>
                                            </el-dropdown-item>
                                            </template>
                                        </el-dropdown-menu>
                                    </template>
                                </el-dropdown>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
                <div class="block-content py-2" v-if="isLoading == false">
                    <el-row justify="space-between">
                        <el-col :lg="12">
                            <p class="mb-0">Menampilkan {{ params.from }} sampai {{ params.to }} dari {{ params.total }}</p>
                        </el-col>
                        <el-col :lg="12" class="text-end">
                            <el-pagination class="float-end" background layout="prev, pager, next"  :page-size="params.pageSize" :total="params.total" :current-page="params.page" @current-change="fetchData"/>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
        <el-dialog v-model="showModal" title="Export" width="500" :before-close="handleClose">
            
            <el-form label-width="30%" method="GET" :action="route('admin.purchase.order.report')" target="_blank" label-position="top">
                <el-form-item class="mb-4" label="Status">
                    <el-select v-model="form.status" name="status" class="w-100" placeholder="Pilih">
                        <el-option label="Semua" value="" />
                        <el-option label="Pending" value="draft" />
                        <el-option label="Selesai" value="done" />
                        <el-option label="Batal" value="cancel" />
                    </el-select>
                </el-form-item>
                <el-form-item class="mb-4" label="Periode">
                    <el-date-picker
                        name="date"
                        v-model="form.periode"
                        type="daterange"
                        range-separator="Sampai"
                        start-placeholder="Tanggal Mulai"
                        end-placeholder="Tanggal Selesai"
                    />
                </el-form-item>
                <div class="d-flex">
                    <div class="float-end">
                        <el-button @click="showModal = false">Batal</el-button>
                        <el-button type="primary" native-type="submit">
                            Download
                        </el-button>
                    </div>
                </div>
            </el-form>
        </el-dialog>
    </base-layout>
</template>

<script>
export default {
    data(){
        return {
            data : [],
            isLoading : true,
            params : {
                searchKey : 'name',
                searchKeyword : '',
                sort : 'id',
                sortDir : 'DESC',
                limit : 25,
                total : 0,
                page : 1,
                pageSize : 0,
            },
            showModal : false,
            form : {
                status : null,
                periode : null
            }
        } 
    },
    async created() {
        await this.fetchData();
    },
    methods :{
        async fetchData(page) {
            var page = (page == undefined) ? 1 : page;
            try {
                this.isLoading = true;
                const response = await axios.get(this.route("admin.purchase.order.data"),{
                    params: {
                        page: page,
                        limit : this.params.limit,
                        search : this.params.searchKeyword,
                        searchKey : this.params.searchKey,
                    }
                });
                if(response.status == 200){
                    this.data = response.data.data;
                    this.params.page = response.data.current_page;
                    this.params.total = response.data.total;
                    this.params.pageSize = response.data.per_page;
                    this.params.from = response.data.from;
                    this.params.to = response.data.to;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        format_date(value){
            if (value) {
                return moment(String(value)).format('DD MMM YYYY')
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
                this.$inertia.delete(this.route('admin.purchase.order.delete', {id : id}), {
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
    }
}
</script>