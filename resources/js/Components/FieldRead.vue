<template>
    <!-- <div class="field-read">
        <div class="label">{{ label }}</div>
        <div class="value">{{ value }}</div>
    </div> -->
    <el-row :gutter="10">
        <el-col :lg="6">
            <label class="col-form-label">{{ label }}</label>
        </el-col>
        <el-col :lg="14">
            <div class="form-control-plaintext">: {{ value }}</div>
        </el-col>
    </el-row>
    <!-- <div class="row g-0">
        <div class="col-lg-8">
            <div class="form-control-plaintext">: {{ value }}</div>
        </div>
    </div> -->
</template>
<style scoped>
    .field-read {
        display: flex;
    }
</style>
<script>
export default {
    name : 'FieldRead',
    props:{
        label : {
            type : String,
        },
        value : {
            type : String
        }
    },
    setup() {
        
    },
}
</script>