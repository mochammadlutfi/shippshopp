<template>
    <div>
        <div class="content-heading d-flex justify-content-between mb-2 py-2">
            <span>Alamat Pengiriman</span>
            <div class="space-x-1">
                <el-button type="primary" plain @click.prevent="openModal" v-if="Object.keys(selected).length">
                    Pilih Alamat Lain
                </el-button>
            </div>
        </div>

        <div class="block block-bordered rounded mb-2" v-if="Object.keys(selected).length">
            <div class="block-header border-3x border-bottom p-3">
                <h3 class="block-title">{{ selected.name }}</h3>
            </div>
            <div class="block-content p-3" >
                <div class="content__top-info">
                    <span class="top-info__name">{{ selected.reciever }}</span>
                    <span class="top-info__phone">{{ selected.phone }}</span>
                </div>
                <div class="content__complete-address">
                    {{ selected.address }}
                </div>
                <div class="content__district">{{ selected.area_text }}</div>
            </div>
        </div>

        <div class="block block-bordered rounded mb-2"  v-else>
            <div class="block-content text-center p-3">
                <h3 class="font-weight-bold h4">Alamat Pengiriman Belum Ditambahkan</h3>
                <el-button type="primary" @click.prevent="createAddress">
                    <i class="fa fa-plus me-2"></i>Tambah Alamat
                </el-button>
            </div>
        </div>

        <el-dialog v-model="modalShow" class="address-list" :show-close="false">
            <template #header="{ titleId }">
                <div class="d-flex justify-content-between">
                    <h4 :id="titleId" class="fs-5 fw-bold my-auto">
                        Pilih Alamat Pengiriman
                    </h4>
                </div>
            </template>
            
            <div class="block block-rounded block-bordered mb-15" v-for="d in dataList" :key="d.id">
                <div class="block-content p-3">
                    <el-row>
                        <el-col :span="20">
                            <div class="mb-3">
                                    
                                <div class="font-size-md">
                                    <span class="font-w700">{{ d.reciever }} </span>({{ d.name }})
                                </div>
                                <div>
                                    {{ d.phone }}
                                </div>
                                <div class="content__complete-address">
                                    {{ d.address }}
                                </div>
                                <div class="content__district">{{ d.area_text }}</div>
                            </div>
                            
                            <el-button type="info" @click="edit(d)" size="small">
                                <i class="si si-note me-1"></i>
                                Ubah
                            </el-button>
                        </el-col>
                        <el-col :span="4" class="d-flex">
                            <el-button type="primary" @click="selectAddress(d)" class="my-auto" v-if="this.selected.id != d.id">
                                <i class="fa fa-check me-1"></i>
                                Pilih
                            </el-button>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </el-dialog>
        
        <el-dialog v-model="modalForm" class="address-form" :show-close="false">
            <template #header="{ titleId }">
                <div class="d-flex justify-content-between">
                    <h4 :id="titleId" class="fs-5 fw-bold my-auto">
                        {{ title }}
                    </h4>
                </div>
            </template>
            
            <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                        <el-form-item label="Alamat" :error="errors.phone">
                            <el-input v-model="form.address" type="textarea" :rows="2" placeholder="Masukan Alamat"/>
                        </el-form-item>
                        <el-form-item label="Nama Alamat" :error="errors.name">
                            <el-input v-model="form.name" type="text" placeholder="Masukan Nama Alamat"/>
                        </el-form-item>
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <el-form-item label="Nama Penerima" :error="errors.name">
                                    <el-input v-model="form.reciever" type="text" placeholder="Masukan Nama Penerima"/>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item label="No HP Penerima" :error="errors.phone">
                                    <el-input v-model="form.phone" type="text" placeholder="Masukan No Handphone"/>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-form-item label="Jadikan Alamat Utama?">
                            <el-radio-group v-model="form.is_main">
                                <el-radio :label="1" border>Ya</el-radio>
                                <el-radio :label="0" border>Tidak</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    <div class="text-end">
                        <el-button type="primary" plain @click="selectMap = true">
                            Kembali
                        </el-button>
                        <el-button type="primary" @click="submit">
                            Simpan
                        </el-button>
                    </div>
            </el-form>
            
            <!-- <template #footer>
                <span class="dialog-footer">
                    <el-button @click="modalForm = false">Cancel</el-button>
                    <el-button type="primary" @click="modalForm = false">
                        Confirm
                    </el-button>
                </span>
            </template> -->
        </el-dialog>
    </div>
</template>
<script>
import _ from 'lodash';
import { defineComponent, ref, computed, watch } from 'vue';
export default {
    name : 'ShippingAddress',
    setup() {
        const mapRef = ref();
        
        watch(mapRef, googleMap => {
            if (googleMap) {
                googleMap.$mapPromise.then(map=> {
                    
                })
            }
        });

        return {
            mapRef
        }
    },
    props : {
        modelValue : {
            type : Object,
            default : {}
        }
    },
    mounted(){
        this.center.lat = this.$page.props.location.lat;
        this.center.lng = this.$page.props.location.lng;
        this.marker.lat = this.$page.props.location.lat;
        this.marker.lng = this.$page.props.location.lng;
    },
    data(){
        return {
            title : 'Tambah Alamat Baru',
            selectMap : true,
            form : {
                id : null,
                name : null,
                reciever : null,
                phone : null,
                address : null,
                formatted_address :null,
                lat : null,
                lng : null,
                is_main : 0,
                distance : null,
            },
            dataList : [],
            errors : {},
            terms : null,
            modalShow: false,
            modalForm: false,
            editMode: false,
            selected : this.modelValue,
            locations : [],
            locationSelected : null,
            mapOptions : {
                mapTypeControl : false,
                streetViewControl : false,
                lang : "id",
            },
            center: { lat: 51.093048, lng: 6.84212 },
            marker: {
                lat: 51.093048,
                lng: 6.84212,
            },
        }
    },

    watch :{
        locationSelected(val){
            if(val){
                this.form.lat = val.lat;
                this.form.lng = val.lng;
                this.form.postal_code = val.postal_code;
                this.form.area_id = val.area_id;
                this.form.area_text = val.area_text;
            }else{
                this.form.postal_code = null;
                this.form.lat = null;
                this.form.lng = null;
                this.form.area_id = null;
                this.form.area_text = null;
            }
        },
        modelValue(val){
            if(Object.keys(val).length){
                this.selected = val;
            }
        }
    },

    methods :{
        openModal(){
            this.modalShow = true;
            this.fetchData();
        },
        selectAddress(v){
            this.selected = v;
            this.$emit('update:modelValue', v);
            this.modalShow = false;
        },
        async fetchData(){
            try {
                const response = await axios.get("/user/alamat/data",{
                    params: {
                        // page: page,
                    }
                });
                if(response.status == 200){
                    this.dataList = response.data;
                    // this.page = response.data.current_page;
                    // this.total = response.data.total;
                    // this.pageSize = response.data.per_page;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        createAddress(){
            this.modalForm = true;
        },
        closeForm(){
            this.reset();
            if(this.$root.window.mobile){
                
            }else{
                this.$bvModal.hide('modalCreateAddress');
            }
        },
        reset(){
            this.title = "Tambah Alamat Baru";
            this.form.id = null;
            this.form.name = null;
            this.form.reciever = null;
            this.form.phone = null;
            this.form.address = null;
            this.form.postal_code = null;
            this.form.area_id = null;
            this.form.area_text = null;
            this.form.lat = null;
            this.form.lng = null;
            

            this.modalForm = false;
            this.modalShow = false;

            this.errors = {};
            this.locationSelected = {};
            this.editMode = false;
        },
        onSearch(search, loading) {
            if(search.length) {
                loading(true);
                this.search(loading, search, this);
            }
        },
        submit: function () {
            let data = {};
            data = Object.assign(data, this.form)
            let url = this.editMode ? this.route("user.address.update") : this.route(
                "user.address.store");
            axios.post(url, data)
            .then((res) => {
                if(res.data.fail){
                    this.errors = res.data.errors;
                    this.$swal.close();
                }else{
                    this.$swal.close();
                    this.reset();
                    this.selected = res.data.data;
                    this.$emit('done', res.data.data);
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        html: `Alamat Baru Berhasil Ditambahkan!`,
                        timer: 1500
                    });
                }
            })
            .catch(function (error) {
                if (error.response) {
                    this.errors = error.response.data.errors;
                }
            });
        },
        search: _.debounce((loading, search, vm) => {

            let params = {
                keyword : search
            };

            axios.get(vm.route('shipper.sub_urban'), {
                params,
            })
            .then(function (response) {
                const resp = response.data;
                vm.locations = [];
                resp.data.forEach(val => {
                    vm.locations.push({
                        area_id : val.adm_level_5.id,
                        area_text : val.adm_level_2.name +', '+ val.adm_level_3.name +', '+ val.adm_level_4.name + ', ' + val.adm_level_5.name,
                        postal_code : val.postcodes[0],
                        lat : val.adm_level_5.geo_coord.lat,
                        lng : val.adm_level_5.geo_coord.lng,
                    });
                });
                loading(false);
            })
            .catch(function (error) {
                
            })
        }, 350),
        edit(data){
            this.reset();

            this.title = "Ubah Alamat";
            this.editMode = true;

            this.form.id = data.id;
            this.form.name = data.name;
            this.form.reciever = data.reciever;
            this.form.phone = data.phone;
            this.form.address = data.address;
            this.form.postal_code = data.postal_code;
            this.form.area_id = data.area_id;
            this.form.area_text = data.area_text;
            this.form.lat = data.lat;
            this.form.lng = data.lng;

            this.locationSelected = {
                area_id : data.area_id,
                area_text : data.area_text,
                postal_code : data.postal_code,
                lat : data.lat,
                lng : data.lng,
            }
            
            this.$bvModal.hide('addressList');
            if(this.$root.window.mobile){
                this.modalForm = true;
            }else{
                this.$bvModal.show('modalCreateAddress');
            }
        },
        setLatLng()
        {
            this.form.lat = this.center.lat;
            this.form.lng = this.center.lng;
        },
        removeLatLng()
        {
            this.form.lat = null;
            this.form.lng = null;
        },
        async onDragend(v)
        {
            try {
                this.isLoading = true;
                const response = await axios.post("/user/alamat/geocode",{
                    latlng: v.latLng.lat()+ ',' + v.latLng.lng(),
                });
                if(response.status == 200){
                    if(response.data.jarak <= 10000){
                        this.form.formatted_address = response.data.data;
                        this.form.distance = response.data.jarak;
                        this.center.lat = v.latLng.lat();
                        this.center.lng = v.latLng.lng();
                        this.form.lat = v.latLng.lat();
                        this.form.lng = v.latLng.lng();
                    }else{
                        ElMessage({
                            type: 'error',
                            message: 'Lokasi Tidak Terjangkau!',
                        });
                        this.center.lat = this.$page.props.location.lat;
                        this.center.lng = this.$page.props.location.lng;
                        this.marker.lat = this.$page.props.location.lat;
                        this.marker.lng = this.$page.props.location.lng;
                    }
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        setPlace(v){
            if (!v.geometry || !v.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }
            this.center = v.geometry.location;
            this.form.lat = this.center.lat;
            this.form.lng = this.center.lng;
            this.form.formatted_address = v.formatted_address;
        },
        async getStreetAddressFrom(lat, lng) {
            const vm = this;
            try {
                this.isLoading = true;
                const response = await axios.post("/user/alamat/geocode",{
                    latlng: lat+ ',' + lng,
                });
                if(response.status == 200){
                    if(response.data.jarak <= 10000){
                        vm.form.distance = response.data.jarak;
                        vm.form.formatted_address = response.data.data;
                    }else{
                        ElMessage({
                            type: 'error',
                            message: 'Lokasi Tidak Terjangkau!',
                        });
                    }
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        async submit(){
            const self = this;
            // const form = this.form;
            // const response = await axios.post("/user/alamat/json-store",{
            //     form
            // });
            // if(response.status == 200){
                
            // }
            
            await axios.post('/user/alamat/json-store', self.form).then(response => {

                const data = response.data;
                this.selected = data.result;
                this.$emit('update:modelValue', data.result);
                this.modalForm = false;
            }).catch(error => {
                const data = error.response.data;
                self.errors = data.result;
                self.loading = false;
                console.log(error);
            });
        }
    }
}
</script>

<style lang="scss">
.address-form{
    width:800px;
    border-radius: 10px;

    header{
        margin-right: 0px;
    }
}
.address-list{
    width:800px;
    border-radius: 10px;

    header{
        margin-right: 0px;
    }
}
</style>