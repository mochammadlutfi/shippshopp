import{_ as g,A as v,j as u,k as V,l as o,p as r,v as s,q as k,a3 as E,s as y,$ as S,a2 as w,E as x}from"./id.js";import{E as j}from"./index20.js";import{E as B}from"./index21.js";import{E as N,a as M}from"./index22.js";import{E as U,a as A}from"./index8.js";import{v as L}from"./directive.js";import"./index7.js";import"./typescript.js";import"./event.js";import"./_Uint8Array.js";import"./_initCloneObject.js";const O={props:{data:{type:Object,default:{}},errors:Object},data(){return{title:"Tambah Supplier Baru",loading:!1,form:{id:null,name:null,pic:null,phone:null,email:null,address:null},query:"",lines:[],lines_deleted:[]}},created(){Object.keys(this.data).length&&this.setValue()},methods:{submit(){this.loading=!0;let t=Object.assign(this.form,{lines:this.lines,lines_deleted:this.lines_deleted}),e=this.$inertia.form(t),a=Object.keys(this.data).length?this.route("admin.purchase.supplier.update",{id:this.data.id}):this.route("admin.purchase.supplier.store");e.post(a,{preserveScroll:!0,onSuccess:()=>{j({type:"success",message:"Data Berhasil Disimpan!"})},onFinish:()=>{this.loading=!1}})},selectProduct(t){if(this.lines.length>=1)if(this.lines.some(a=>a.variant_id===t.id))for(var e=0;e<this.lines.length;e++)this.lines[e].variant_id,t.id;else this.lines.push({id:null,variant_id:t.id,product_id:t.product_id,variant:t.name,product:t.product});else this.lines.push({id:null,variant_id:t.id,product_id:t.product_id,variant:t.name,product:t.product});this.$forceUpdate()},removeLine(t,e){e&&this.lines_deleted.push(e),this.lines.splice(t,1)},async findProduct(t){try{this.isLoading=!0;const e=await axios.get("/admin/produk/search",{params:{q:t}});if(e.status==200)return e.data;this.isLoading=!1}catch(e){console.error(e)}},setValue(){this.title="Ubah Supplier",this.form.id=this.data.id,this.form.name=this.data.nama,this.form.phone=this.data.hp,this.form.email=this.data.email,this.form.address=this.data.alamat}}},D={class:"content"},F={class:"content-heading d-flex justify-content-between align-items-center pt-0"},q=r("div",{class:"space-x-1"},null,-1),C={class:"block rounded block-bordered"},H={class:"block-content p-4"},I=r("i",{class:"fa fa-check me-1"},null,-1);function P(t,e,a,T,l,p){const n=B,d=N,m=U,c=A,h=x,_=M,f=v("base-layout"),b=L;return u(),V(f,null,{default:o(()=>[r("div",D,[s(_,{"label-width":"30%",onSubmit:w(p.submit,["prevent"]),"label-position":"top"},{default:o(()=>[r("div",F,[r("span",null,k(l.title),1),q]),E((u(),y("div",C,[r("div",H,[s(c,{gutter:32},{default:o(()=>[s(m,{lg:12},{default:o(()=>[s(d,{class:"mb-4",label:"Nama Supplier",error:a.errors.name},{default:o(()=>[s(n,{modelValue:l.form.name,"onUpdate:modelValue":e[0]||(e[0]=i=>l.form.name=i),placeholder:"Masukan Nama Supplier"},null,8,["modelValue"])]),_:1},8,["error"]),s(d,{class:"mb-4",label:"No Handphone",error:a.errors.phone},{default:o(()=>[s(n,{modelValue:l.form.phone,"onUpdate:modelValue":e[1]||(e[1]=i=>l.form.phone=i),placeholder:"Masukan No Handphone"},null,8,["modelValue"])]),_:1},8,["error"]),s(d,{class:"mb-4",label:"Alamat Email",error:a.errors.email},{default:o(()=>[s(n,{modelValue:l.form.email,"onUpdate:modelValue":e[2]||(e[2]=i=>l.form.email=i),placeholder:"Masukan Alamat Email"},null,8,["modelValue"])]),_:1},8,["error"])]),_:1}),s(m,{lg:12},{default:o(()=>[s(d,{class:"mb-4",label:"Alamat Lengkap",error:a.errors.address},{default:o(()=>[s(n,{modelValue:l.form.address,"onUpdate:modelValue":e[3]||(e[3]=i=>l.form.address=i),type:"textarea",rows:4,placeholder:"Masukan Alamat Email"},null,8,["modelValue"])]),_:1},8,["error"])]),_:1})]),_:1}),s(h,{"native-type":"submit",type:"primary"},{default:o(()=>[I,S(" Simpan ")]),_:1})])])),[[b,l.loading]])]),_:1},8,["onSubmit"])])]),_:1})}const ee=g(O,[["render",P]]);export{ee as default};
