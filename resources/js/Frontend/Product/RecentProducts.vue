<template>
    <div class="content">
        <div class="content-heading pt-0 mb-3 border-0 d-flex justify-content-between">
            <h3 class="h3 mb-0">Produk Terbaru</h3>
        </div>
        <el-row :gutter="20">
            <el-col :lg="4" v-for="(d, i) in data" :key="i">
                <div class="product">
                    <div class="product-content">
                        <div class="product-img">
                            <a :href="route('product.show', { 'slug' : d.slug })">
                                <img :src="d.main_image" class="img-fluid"/>
                            </a>
                        </div>
                        <div class="product-info">
                            <div class="product-title">
                                <a :href="route('product.show', { 'slug' : d.slug })">
                                    {{ d.name }}
                                </a>
                            </div>
                            <div class="product-price">
                                {{ currency(d.sell_price) }}
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
export default {
    name : 'RecentProduct',
    components: {
    },
    data() {
        return {
            data : [],
            isLoading : false,
            limit : 12,
            page : 1,
            pageSize : 0,
            total : 0,
        }
    },
    async mounted(){
        await this.fetchData();
    },
    methods : {
        async fetchData(){
            var page = (page == undefined) ? 1 : page;
            try {
                this.isLoading = true;
                const response = await axios.get("/produk/data",{
                    params: {
                        page: page,
                        limit : this.limit,
                    }
                });
                if(response.status == 200){
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
    },
}
</script>