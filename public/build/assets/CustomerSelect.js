import{_ as u,j as o,k as s,l as m,s as c,u as d,F as p}from"./id.js";import{E as h,a as f}from"./index33.js";import"./index21.js";import"./typescript.js";import"./event.js";import"./index32.js";import"./isEqual.js";import"./_Uint8Array.js";import"./scroll.js";import"./debounce.js";import"./index31.js";const _={name:"SupplierSelect",data(){return{dataList:[],value:this.modelValue,isLoading:!1}},watch:{modelValue(e){this.value=e}},props:{modelValue:{type:[String,Number]}},computed:{},async mounted(){await this.fetchData()},methods:{async fetchData(){try{this.isLoading=!0;const e=await axios.get(this.route("admin.sale.customer.data"),{});e.status==200&&(this.dataList=[],this.dataList=e.data),this.isLoading=!1}catch(e){console.error(e)}},selectChange(e){this.$emit("update:modelValue",e)}}};function g(e,l,L,v,a,i){const r=f,n=h;return o(),s(n,{modelValue:a.value,"onUpdate:modelValue":l[0]||(l[0]=t=>a.value=t),"value-key":"id",class:"w-100",filterable:"",clearable:"",remote:"",onChange:i.selectChange,autocomplete:"off",loading:a.isLoading,placeholder:"Pilih Konsumen"},{default:m(()=>[(o(!0),c(p,null,d(a.dataList,t=>(o(),s(r,{key:t.id,label:t.name,value:t.id},null,8,["label","value"]))),128))]),_:1},8,["modelValue","onChange","loading"])}const F=u(_,[["render",g]]);export{F as default};
