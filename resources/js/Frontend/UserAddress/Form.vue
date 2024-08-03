<template>
    <user-layout>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fs-4 fw-bold mb-0">{{ title }}</h3>
        </div>
        <div class="block rounded">
            <div class="block-content p-4">
                <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                    <!-- <template v-if="!form.lat && !form.lng"> -->
                    <div class="ep-input mb-3">
                        <div class="ep-input__wrapper">
                            <GMapAutocomplete
                                class="ep-input__inner"
                                placeholder="Cari Lokasi"
                                @place_changed="setPlace"
                                >
                            </GMapAutocomplete>
                        </div>
                    </div>
                    <GMapMap :center="center"
                    :options="mapOptions"
                    :zoom="14"
                    ref="mapRef"
                    map-type-id="roadmap"
                    class="w-100 mb-4" 
                    style="height:300px;">
                        <GMapMarker :position="center" :draggable="true" @dragend="onDragend"/>
                    </GMapMap>
                    <el-form-item label="Alamat Berdasarkan Titik" :error="errors.phone">
                        <el-input v-model="form.formatted_address" type="textarea" :rows="2" :readonly="true"/>
                    </el-form-item>
                        <el-form-item label="Alamat Lengkap" :error="errors.phone">
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
                        <el-button native-type="submit" type="primary" class="w-100" :loading="isLoading">
                            Simpan
                        </el-button>
                    <!-- </template> -->
                </el-form>
            </div>
        </div>
    </user-layout>
</template>

<script>
import { defineComponent, ref, computed, watch } from 'vue';

export default {
    components : {

    },
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
        data : Object,
        errors: Object
    },
    data() {
        return {
            title : 'Tambah Alamat',
            isLoading : false,
            map: null,
            searchQuery: null,
            mapOptions : {
                mapTypeControl : false,
                streetViewControl : false,
                lang : "id",
            },
            // center : null,
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
        this.center.lat = this.$page.props.location.lat;
        this.center.lng = this.$page.props.location.lng;
        this.marker.lat = this.$page.props.location.lat;
        this.marker.lng = this.$page.props.location.lng;
        // const success = (position) => {
        //     this.center.lat = position.coords.latitude;
        //     this.center.lng = position.coords.longitude;
        //     this.form.lat = this.center.lat;
        //     this.form.lng = this.center.lng;

        //     this.getStreetAddressFrom(position.coords.latitude, position.coords.longitude);
        // };

        // const error = (err) => {
        //     console.log(error)
        // };

        // navigator.geolocation.getCurrentPosition(success, error);
    },
    methods : {
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
                        this.center.lat = v.latLng.lat();
                        this.center.lng = v.latLng.lng();
                        this.form.lat = v.latLng.lat();
                        this.form.lng = v.latLng.lng();
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
            // this.getStreetAddressFrom(v.latLng.lat(), v.latLng.lng());
            // console.log(v.latLng.lat());
        },
        setPlace(v){
            // console.log(v);
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
            try {
                this.isLoading = true;
                const response = await axios.post("/user/alamat/geocode",{
                    latlng: lat+ ',' + lng,
                });
                if(response.status == 200){
                    if(response.data.jarak <= 10000){
                        this.form.formatted_address = response.data.data;
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
        submit() {
            this.isLoading = true;
            let form = this.$inertia.form(this.form);
            form.post("/user/alamat/store", {
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