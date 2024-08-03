<template>
    <base-layout>
        <div class="content">
            <el-form label-width="30%" @submit.prevent="onSubmit" label-position="top">
                <div class="content-heading d-flex justify-content-between align-items-center pt-0">
                    <span>{{ title }}</span>
                    <div class="space-x-1">
                    </div>
                </div>
                <div class="block rounded" v-loading=loading>
                    <div class="block-content p-4">
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <el-form-item class="mb-4" label="Nama Produk" label-width="180px" :error="errors.name">
                                    <el-input v-model="form.name" placeholder="Masukan Nama Produk"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Kategori" label-width="180px" :error="errors.category_id">
                                    <category-select v-model="form.category_id"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Merk" label-width="180px" :error="errors.brand_id">
                                    <brand-select v-model="form.brand_id"/>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item class="mb-4" label="SKu" label-width="180px" :error="errors.sku">
                                    <el-input v-model="form.sku" placeholder="Masukan SKU Produk"/>
                                </el-form-item>
                                <el-form-item class="mb-4" label="Harga Jual" label-width="180px" :error="errors.sell_price">
                                    <el-input
                                        v-model="form.sell_price"
                                        placeholder="Masukan Harga Jual"
                                        :formatter="(value) => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                        :parser="(value) => value.replace(/^Rp\s+|(\,)/gi, '')"
                                    />
                                </el-form-item>
                                <el-form-item class="mb-4" label="Harga Beli" label-width="180px" :error="errors.purchase_price">
                                    <el-input
                                        v-model="form.purchase_price"
                                        placeholder="Masukan Harga Beli"
                                        :formatter="(value) => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                        :parser="(value) => value.replace(/^Rp\s+|(\,)/gi, '')"
                                    />
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-form-item class="mb-4" label="Deskripsi" label-width="180px">
                            <el-input v-model="form.description" type="textarea" :rows="3" placeholder="Masukan Deskripsi"/>
                        </el-form-item>
                        <el-form-item class="mb-4" label="Foto Produk" label-width="180px">
                            <el-upload action="#" v-model:file-list="imageRef" 
                            :on-remove="handleRemove" list-type="picture-card" :auto-upload="false">
                                <i class="fa fa-plus"></i>
                            </el-upload>
                        </el-form-item>
                        <el-button native-type="submit">
                            <i class="fa fa-check me-2"></i> Simpan
                        </el-button>
                    </div>
                </div>
            </el-form>
        </div>
    </base-layout>
</template>

<style>
#variant .ep-form-item__content{
    margin : 0 !important;
}
</style>
<script setup>
import CategorySelect from '../Category/CategorySelect.vue';
import BrandSelect from '../Brand/BrandSelect.vue';
import { ref, onMounted } from 'vue';
import { ElMessage } from 'element-plus';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    },
    errors: Object,
})

const title = ref('Tambah Produk Baru');
const loading = ref(false);
const form = ref({
    id: null,
    name: null,
    description: null,
    category_id: null,
    brand_id: null,
    sku: null,
    has_variant: false,
    sell_price: "",
    sell_unit_id: null,
    purchase_price: "",
    purchase_unit_id: null,
    image: null,
});
const imageRef = ref([]);
const imageDeleted = ref([]);
const lines = ref([]);

const setValue = () => {
    title.value = 'Ubah Produk';

    form.value.id = props.data.id;
    form.value.name = props.data.nama;
    form.value.description = props.data.deskripsi;
    form.value.category_id = props.data.kategori_id;
    form.value.brand_id = props.data.brand_id;
    form.value.sku = props.data.sku;
    form.value.sell_price = props.data.harga_jual;
    form.value.purchase_price = props.data.harga_beli;

    // imageRef.value = ;
    if(props.data.image.length){
        props.data.image.forEach(v => {
            imageRef.value.push({
                name : v.nama,
                url : v.path
            })
        });
    }
    // form.has_variant = (props.data.has_variant == "1") ? true : false;
    // if (props.data.has_variant == "1") {
    //     props.data.variant.forEach(v => {
    //         variant.value.push({
    //             id: v.id,
    //             name: v.name,
    //             sku: v.sku,
    //             sell_price: v.sell_price,
    //             sell_unit_id: v.sell_unit_id,
    //             purchase_price: v.purchase_price,
    //             purchase_unit_id: v.purchase_unit_id,
    //             image: v.image
    //         });
    //     });
    // } else {
    //     form.sku = props.data.variant[0].sku;
    //     form.sell_price = props.data.variant[0].sell_price;
    //     form.sell_unit_id = props.data.variant[0].sell_unit_id;
    //     form.purchase_price = props.data.variant[0].purchase_price;
    //     form.purchase_unit_id = props.data.variant[0].purchase_unit_id;
    //     form.image = props.data.variant[0].image;
    // }
};

const handleRemove = (uploadFile, uploadFiles) => {
//   console.log(uploadFile)
  imageDeleted.value.push(uploadFile);
}

const onSubmit = () => {
    console.log(imageRef.value);
    loading.value = true;
    let data = Object.assign(form.value, {
        image: imageRef.value,
        imageDeleted: imageDeleted.value,
    });

    const formData = useForm(data);
    let url = (Object.keys(props.data).length) ?
        route('admin.inventory.product.update', {
            id: props.data.id
        }) :
        route('admin.inventory.product.store');
    formData.post(url, {
        preserveScroll: true,
        onSuccess: () => {
            ElMessage({
                type: 'success',
                message: 'Data Berhasil Disimpan!',
            });
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const setVariantTable = (v) => {
    if (v) {
        variant.value.push({
            id: null,
            name: null,
            sku: null,
            sell_price: "",
            sell_unit_id: null,
            purchase_price: "",
            purchase_unit_id: null,
            image: null
        });
    } else {
        variant.value = [];
    }
};

const addVariant = () => {
    variant.value.push({
        id: null,
        name: null,
        sku: null,
        sell_price: "",
        sell_unit_id: null,
        purchase_price: "",
        purchase_unit_id: null,
        image: null
    });
};

const removeVariant = (index) => {
    variant.value.splice(index, 1);
};

onMounted(() => {
    if (Object.keys(props.data).length) {
        setValue();
    }
});
</script>