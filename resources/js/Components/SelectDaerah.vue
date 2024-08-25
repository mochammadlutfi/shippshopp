<template>
    <el-select v-model="value" value-key="id" 
    class="w-100"
    filterable 
    :clearable="clearable"
    remote
    @change="selectChange"
    autocomplete="off"
    :disabled="isDisabled"
    :loading="isLoading"
    :multiple="multiple"
    :placeholder="placeholderGen">
        <el-option
            v-for="item in dataList"
            :key="item.id"
            :label="item.name"
            :value="item.id"
        />
    </el-select>
</template>

<script>
export default {
    name : 'SelectDaerah',
    data() {
        return {
            dataList : [],
            value : this.modelValue,
            isLoading : false,
        }
    },
    watch : {
        parent(value){
            this.value = null;
            if(value){
                this.fetchData();
            }
        },
        modelValue(v){
            this.value = v;
        }
    },
    props : {
        modelValue : {
            type : [String, Number, Array],
        },
        filter : {
            type : [String, Array],
        },
        type : {
            type : String,
            default : 'provinsi',
        },
        parent : {
            type : [String, Number],
        },
        hasParent : {
            type : Boolean,
            default : false,
        },
        disabled : {
            type : Boolean,
            default : false,
        },
        clearable : {
            type : Boolean,
            default : false,
        },
        multiple : {
            type : Boolean,
            default : false,
        },
        placeholder : {
            type : String,
            default : 'provinsi',
        }
    },
    computed :{
        placeholderGen(){
            if(this.placeholder != ""){
                if(this.type == 'provinsi'){
                    return 'Pilih Provinsi';
                }else if(this.type == 'kota'){
                    return 'Pilih Kota/Kabupaten';
                }else if(this.type == 'kecamatan'){
                    return 'Pilih Kecamatan';
                }else{
                    return 'Pilih Desa/Kelurahan';
                }
            }else{
                return this.placeholder;
            }
        },
        isDisabled(){
            if(this.disabled){
                return true;
            }else{
                if(this.hasParent){
                    if(this.parent){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return false;
                }
            }
        }
    },
    async mounted() {
        if(!this.hasParent){
            await this.fetchData();
        }else{
            if(this.parent){
                await this.fetchData();
            }
        }
    },
    methods :{
        async fetchData() {
            try {
                this.isLoading = true;
                const response = await axios.get(this.route("base.wilayah"),{
                    params: {
                        type: this.type,
                        parent : this.parent,
                        filter : this.filter,
                    }
                });
                if(response.status == 200){
                    this.dataList = [];
                    this.dataList = response.data;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        selectChange(v){
            this.$emit('update:modelValue', v);
        },
    }
}
</script>