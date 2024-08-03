<template>
    <base-layout>
        <div class="content">
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Satuan Produk</span>
                <div class="space-x-1">
                    <el-button @click="showForm = true" type="primary" v-if="$page.props.user.level != 'kasir 2'">
                        <i class="fa fa-plus me-1"></i>
                        Tambah
                    </el-button>
                </div>
            </div>
            
            <div class="block block-rounded">
                <div class="block-content py-3">
                    <el-row justify="space-between">
                        <el-col :span="12">
                            <el-select v-model="limit" placeholder="Pilih" style="width: 115px" @change="fetchData(1)">
                                <el-option label="25" value="25"/>
                                <el-option label="50" value="50"/>
                                <el-option label="100" value="100"/>
                            </el-select>
                        </el-col>
                        <el-col :span="8">
                            <el-input
                                v-model="searchKeyword"
                                placeholder="Masukan kata kunci"
                                class="input-with-select"
                                clearable
                                >
                                <template #append>
                                    <el-button @click="fetchData(1)">
                                        <i class="fa fa-search"></i>
                                    </el-button>
                                </template>
                            </el-input>
                        </el-col>
                    </el-row>
                </div>
                <div class="block-content p-0">
                    <el-table :data="data" class="table-click" style="width: 100%" v-loading="isLoading" @sort-change="sortChange">
                        <el-table-column type="index"  :index="indexMethod" width="100" />
                        <el-table-column prop="name" label="Nama Satuan" sortable/>
                        <el-table-column prop="code" label="Kode" sortable/>
                        <el-table-column prop="base.name" label="Dasar Satuan" sortable/>
                        <el-table-column prop="operator" label="Operator" sortable/>
                        <el-table-column prop="operator_value" label="Nilai Operator" sortable/>
                        <el-table-column label="Aksi" align="center" width="180" v-if="$page.props.user.level != 'kasir 2'">
                            <template #default="scope">
                                <el-dropdown popper-class="dropdown-action" trigger="click">
                                    <el-button type="primary">
                                    Aksi <i class="fa fa-angle-down ms-1"></i>
                                    </el-button>
                                    <template #dropdown>
                                        <el-dropdown-menu>
                                            <el-dropdown-item class="d-flex align-items-center justify-content-between space-x-1" @click.prevent="edit(scope.row)">
                                                Ubah
                                                <i class="si fa-fw si-note"></i>
                                            </el-dropdown-item>
                                            <el-dropdown-item class="d-flex align-items-center justify-content-between space-x-1" @click.prevent="hapus(scope.row.id)">
                                                Hapus
                                                <i class="si fa-fw si-trash"></i>
                                            </el-dropdown-item>
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
                            <p class="mb-0">Menampilkan {{ from }} sampai {{ to }} dari {{ total }}</p>
                        </el-col>
                        <el-col :lg="12" class="text-end">
                            <el-pagination class="float-end" background layout="prev, pager, next"  :page-size="pageSize" :total="total" :current-page="page" @current-change="fetchData"/>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
        <el-dialog v-model="showForm" :title="formTitle" width="30%" class="rounded-2" :before-close="onCloseForm" :close-on-click-modal="false">
            <el-form :model="form" label-position="top" v-loading="loadingForm">
                <el-form-item label="Nama Satuan" :error="errors.name">
                    <el-input v-model="form.name" />
                </el-form-item>
                <el-form-item label="Kode Satuan">
                    <el-input v-model="form.code" />
                </el-form-item>
                <el-form-item label="Dasar Satuan">
                    <unit-select v-model="form.base_unit_id"></unit-select>
                </el-form-item>
                <template v-if="form.base_unit_id">
                    <el-form-item label="Operator" :error="errors.operator">
                        <el-select v-model="form.operator" class="w-100" placeholder="Pilih Operator">
                            <el-option key="*" label="Dikali (*)" value="*"/>
                            <el-option key="/" label="Dibagi (/)" value="/"/>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Nilai Operator" :error="errors.operator_value">
                        <el-input v-model="form.operator_value"/>
                    </el-form-item>
                </template>
                <div class="text-end">
                    <el-button @click="onCloseForm">
                        <i class="si si-close me-1"></i>
                        Batal
                    </el-button>
                    <el-button type="primary" @click="submit">
                        <i class="si si-check me-1"></i>
                        Simpan
                    </el-button>
                </div>
            </el-form>
        </el-dialog>
    </base-layout>
</template>

<script>
import UnitSelect from '@/Admin/Inventory/Unit/UnitSelect.vue';
import moment from 'moment';
export default {
    components : {
        UnitSelect
    },
    props: {
        errors : Object,
    },
    data(){
        return {
            selected : [],
            data : [],
            from : 0,
            to : 0,
            isLoading : true,
            page : 1,
            pageSize : 0,
            searchKey : 'name',
            searchKeyword : '',
            limit : 25,
            total : 0,
            showForm : false,
            sort : 'name',
            sortDir : 'ASC',
            formTitle: "Tambah Satuan",
            form : {
                id : null,
                name : null,
                code : null,
                operator : null,
                operator_value : null,
                base_unit_id : null,
            },
            editMode : false,
            loadingForm : false,
            loadingImport : false,
            showImport : false,
            importFile : null,
        } 
    },
    async created() {
        await this.fetchData();
    },
    methods :{
        indexMethod(index){
            const x = this.page == 1 ? 1 : (this.limit * (this.page - 1)) + 1;
            return x + index;
        },
        sortChange(data){
            this.sort = data.prop;
            this.sortDir = (data.order == 'ascending') ? 'ASC' : 'DESC';
            this.fetchData();
        },
        async fetchData(page) {
            var page = (page == undefined) ? 1 : page;
            try {
                this.isLoading = true;
                const response = await axios.get("/admin/unit/data",{
                    params: {
                        page: page,
                        limit : this.limit,
                        sort : this.sort,
                        sortDir : this.sortDir,
                        search : this.searchKeyword,
                        searchKey : this.searchKey,
                    }
                });
                if(response.status == 200){
                    this.from = response.data.from;
                    this.to = response.data.to;
                    this.data = response.data.data;
                    this.page = response.data.current_page;
                    this.total = response.data.total;
                    this.pageSize = response.data.per_page;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        format_date(value){
            if (value) {
                return moment(String(value)).format('DD MMMM YYYY')
            }
        },
        onCloseForm(){
            this.title = "Tambah Satuan";
            this.editMode = false;
            this.form.id = null;
            this.form.name = null;
            this.form.phone = null;
            this.showForm = false;
        },
        submit() {
            this.loadingForm = true;
            let form = this.$inertia.form(this.form);
            let url = this.editMode == true ? this.route('admin.inventory.unit.update', {id : this.form.id}) : this.route('admin.inventory.unit.store');
            form.post(url, {
                preserveScroll: true,
                onFinish:() => {
                    this.loadingForm = false;
                },
                onSuccess: () => {
                    ElMessage({
                        type: 'success',
                        message: 'Data Berhasil Disimpan!',
                    });
                    this.onCloseForm();
                    this.fetchData();
                },
            });
        },
        edit(data){
            this.formTitle = 'Ubah Satuan';
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.phone = data.phone;
            this.editMode = true;
            this.showForm = true;
        },
        hapus(id){
            ElMessageBox.alert('Data yang dihapus tidak bisa dikembalikan!', 'Peringatan', {
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
                type: 'warning',
            })
            .then(() => {
                this.$inertia.delete(this.route('admin.inventory.unit.delete', {id : id}), {
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
        onCloseImport(){
            this.showImport = false;
            this.importFile = null;
        },
    }
}
</script>