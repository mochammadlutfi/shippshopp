<template>
    <el-select v-model="value" value-key="id" 
    class="w-100"
    filterable 
    clearable 
    remote
    @change="selectChange"
    autocomplete="off"
    :loading="isLoading"
    placeholder="Pilih Pesanan">
        <el-option
            v-for="item in dataList"
            :key="item.id"
            :label="item.nomor"
            :value="item.id"
        />
    </el-select>
</template>

<script>
export default {
    name : 'SelectOrder',
    data() {
        return {
            dataList : [],
            value : this.modelValue,
            isLoading : false,
        }
    },
    watch : {
        modelValue(v){
            this.value = v;
        }
    },
    props : {
        modelValue : {
            type : [String, Number],
        },
    },
    computed :{
    },
    async mounted() {
        await this.fetchData();
    },
    methods :{
        async fetchData() {
            try {
                this.isLoading = true;
                const response = await axios.get(this.route("user.order.data"),{
                    params: {
                        status : 'done'
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