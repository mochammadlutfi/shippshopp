import{_ as v,j as f,k as _,l as a,s as L,u as P,F as S,A as y,p as m,v as e,q as x,a2 as A,$ as h,E as N}from"./id.js";import{a as D,E as U}from"./index26.js";import{E as B}from"./index20.js";import{E as C,a as j}from"./index22.js";import{E as F,a as K}from"./index8.js";import{E as M}from"./index21.js";import{E as O,a as G}from"./index29.js";import"./index30.js";import"./isEqual.js";import"./_Uint8Array.js";import"./event.js";import"./scroll.js";import"./debounce.js";import"./index31.js";import"./index7.js";import"./typescript.js";import"./_initCloneObject.js";const R={name:"SelectDaerah",data(){return{dataList:[],value:this.modelValue,isLoading:!1}},watch:{parent(r){this.value=null,r&&this.fetchData()},modelValue(r){this.value=r}},props:{modelValue:{type:[String,Number,Array]},filter:{type:[String,Array]},type:{type:String,default:"provinsi"},parent:{type:[String,Number]},hasParent:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1},clearable:{type:Boolean,default:!1},multiple:{type:Boolean,default:!1},placeholder:{type:String,default:"provinsi"}},computed:{placeholderGen(){return this.placeholder!=""?this.type=="provinsi"?"Pilih Provinsi":this.type=="kota"?"Pilih Kota/Kabupaten":this.type=="kecamatan"?"Pilih Kecamatan":"Pilih Desa/Kelurahan":this.placeholder},isDisabled(){return this.disabled?!0:this.hasParent?!this.parent:!1}},async mounted(){this.hasParent?this.parent&&await this.fetchData():await this.fetchData()},methods:{async fetchData(){try{this.isLoading=!0;const r=await axios.get(this.route("base.wilayah"),{params:{type:this.type,parent:this.parent,filter:this.filter}});r.status==200&&(this.dataList=[],this.dataList=r.data),this.isLoading=!1}catch(r){console.error(r)}},selectChange(r){this.$emit("update:modelValue",r)}}};function T(r,l,i,g,t,d){const u=U,n=D;return f(),_(n,{modelValue:t.value,"onUpdate:modelValue":l[0]||(l[0]=s=>t.value=s),"value-key":"id",class:"w-100",filterable:"",clearable:i.clearable,remote:"",onChange:d.selectChange,autocomplete:"off",disabled:d.isDisabled,loading:t.isLoading,multiple:i.multiple,placeholder:d.placeholderGen},{default:a(()=>[(f(!0),L(S,null,P(t.dataList,s=>(f(),_(u,{key:s.id,label:s.name,value:s.id},null,8,["label","value"]))),128))]),_:1},8,["modelValue","clearable","onChange","disabled","loading","multiple","placeholder"])}const H=v(R,[["render",T]]),I={components:{SelectDaerah:H},setup(){},props:{data:Object,errors:Object},data(){return{title:"Tambah Alamat",isLoading:!1,form:{id:null,name:null,reciever:null,city_id:null,province_id:null,phone:null,address:null,is_main:0},center:{lat:51.093048,lng:6.84212},marker:{lat:51.093048,lng:6.84212}}},mounted(){Object.keys(this.data).length&&this.setValue()},methods:{setValue(){this.title="Ubah Alamat",this.form.id=this.data.id,this.form.name=this.data.name,this.form.reciever=this.data.reciever,this.form.phone=this.data.phone,this.form.address=this.data.address,this.form.is_main=this.data.is_main,this.form.province_id=this.data.province_id,this.form.city_id=this.data.city_id},submit(){this.isLoading=!0;let r=this.$inertia.form(this.form),l=this.form.id?route("user.address.update",{id:this.form.id}):route("user.address.store");r.post(l,{preserveScroll:!0,onSuccess:()=>{B({type:"success",message:"Alamat berhasil disimpan!"})},onFinish:()=>{this.isLoading=!1}})}}},q={class:"content"},J={class:"d-flex justify-content-between align-items-center mb-3"},Y={class:"fs-4 fw-bold mb-0"},z={class:"block rounded"},Q={class:"block-content p-4"};function W(r,l,i,g,t,d){const u=y("select-daerah"),n=C,s=F,c=K,p=M,b=O,V=G,k=N,E=j,w=y("base-layout");return f(),_(w,null,{default:a(()=>[m("div",q,[e(c,{justify:"center"},{default:a(()=>[e(s,{span:16},{default:a(()=>[m("div",J,[m("h3",Y,x(t.title),1)]),m("div",z,[m("div",Q,[e(E,{"label-position":"top","label-width":"100%",onSubmit:A(d.submit,["prevent"])},{default:a(()=>[e(c,{gutter:20},{default:a(()=>[e(s,{span:12},{default:a(()=>[e(n,{label:"Provinsi",error:i.errors.province_id},{default:a(()=>[e(u,{type:"provinsi",modelValue:t.form.province_id,"onUpdate:modelValue":l[0]||(l[0]=o=>t.form.province_id=o)},null,8,["modelValue"])]),_:1},8,["error"])]),_:1}),e(s,{span:12},{default:a(()=>[e(n,{label:"Kota/Kabupaten",error:i.errors.city_id},{default:a(()=>[e(u,{type:"kota",modelValue:t.form.city_id,"onUpdate:modelValue":l[1]||(l[1]=o=>t.form.city_id=o),hasParent:"",parent:t.form.province_id},null,8,["modelValue","parent"])]),_:1},8,["error"])]),_:1})]),_:1}),e(n,{label:"Alamat Lengkap",error:i.errors.phone},{default:a(()=>[e(p,{modelValue:t.form.address,"onUpdate:modelValue":l[2]||(l[2]=o=>t.form.address=o),type:"textarea",rows:2,placeholder:"Masukan Alamat"},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{label:"Nama Alamat",error:i.errors.name},{default:a(()=>[e(p,{modelValue:t.form.name,"onUpdate:modelValue":l[3]||(l[3]=o=>t.form.name=o),type:"text",placeholder:"Masukan Nama Alamat"},null,8,["modelValue"])]),_:1},8,["error"]),e(c,{gutter:20},{default:a(()=>[e(s,{span:12},{default:a(()=>[e(n,{label:"Nama Penerima",error:i.errors.name},{default:a(()=>[e(p,{modelValue:t.form.reciever,"onUpdate:modelValue":l[4]||(l[4]=o=>t.form.reciever=o),type:"text",placeholder:"Masukan Nama Penerima"},null,8,["modelValue"])]),_:1},8,["error"])]),_:1}),e(s,{span:12},{default:a(()=>[e(n,{label:"No HP Penerima",error:i.errors.phone},{default:a(()=>[e(p,{modelValue:t.form.phone,"onUpdate:modelValue":l[5]||(l[5]=o=>t.form.phone=o),type:"text",placeholder:"Masukan No Handphone"},null,8,["modelValue"])]),_:1},8,["error"])]),_:1})]),_:1}),e(n,{label:"Jadikan Alamat Utama?"},{default:a(()=>[e(V,{modelValue:t.form.is_main,"onUpdate:modelValue":l[6]||(l[6]=o=>t.form.is_main=o)},{default:a(()=>[e(b,{label:1,border:""},{default:a(()=>[h("Ya")]),_:1}),e(b,{label:0,border:""},{default:a(()=>[h("Tidak")]),_:1})]),_:1},8,["modelValue"])]),_:1}),e(k,{"native-type":"submit",type:"primary",class:"w-100",loading:t.isLoading},{default:a(()=>[h(" Simpan ")]),_:1},8,["loading"])]),_:1},8,["onSubmit"])])])]),_:1})]),_:1})])]),_:1})}const ce=v(I,[["render",W]]);export{ce as default};
