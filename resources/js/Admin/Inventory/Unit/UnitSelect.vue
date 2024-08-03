<template>
    <el-select v-model="value" value-key="id" 
    class="w-100"
    filterable 
    clearable 
    remote
    @change="selectChange"
    autocomplete="off"
    :loading="isLoading"
    :placeholder="placeholder">
        <el-option
            v-for="item in dataList"
            :key="item.id"
            :label="item.name + ' ('+ item.code +')'"
            :value="item.id"
        />
    </el-select>
</template>

<script>
export default {
    name : 'UnitSelect',
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
            type : [String,Number]
        },
        placeholder : {
            type : String,
            default : "",
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
                const response = await axios.get(this.route("admin.inventory.unit.data"),{
                    params : {
                        base : this.base
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