import{_ as d,j as o,k as s,l as c,s as m,u,F as p}from"./id.js";import{a as h,E as f}from"./index26.js";import"./index21.js";import"./typescript.js";import"./event.js";import"./index30.js";import"./isEqual.js";import"./_Uint8Array.js";import"./scroll.js";import"./debounce.js";import"./index31.js";const _={name:"BrandSelect",data(){return{dataList:[],value:this.modelValue,isLoading:!1}},watch:{modelValue(e){this.value=e}},props:{modelValue:{type:[String,Number]}},computed:{},async mounted(){await this.fetchData()},methods:{async fetchData(){try{this.isLoading=!0;const e=await axios.get(this.route("admin.inventory.brand.data"),{});e.status==200&&(this.dataList=[],this.dataList=e.data),this.isLoading=!1}catch(e){console.error(e)}},selectChange(e){this.$emit("update:modelValue",e)}}};function g(e,l,L,v,a,r){const i=f,n=h;return o(),s(n,{modelValue:a.value,"onUpdate:modelValue":l[0]||(l[0]=t=>a.value=t),"value-key":"id",class:"w-100",filterable:"",clearable:"",remote:"",onChange:r.selectChange,autocomplete:"off",loading:a.isLoading,placeholder:"Pilih Merk"},{default:c(()=>[(o(!0),m(p,null,u(a.dataList,t=>(o(),s(i,{key:t.id,label:t.nama,value:t.id},null,8,["label","value"]))),128))]),_:1},8,["modelValue","onChange","loading"])}const F=d(_,[["render",g]]);export{F as default};
