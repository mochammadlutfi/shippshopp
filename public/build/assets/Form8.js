import{_ as w,A as g,j as V,k,l as a,p as t,v as e,q as v,$ as E,a2 as y,E as U}from"./id.js";import{E as x}from"./index20.js";import{E as M}from"./index21.js";import{E as P,a as S}from"./index22.js";import{a as j,E as B}from"./index32.js";import{E as A,a as N}from"./index8.js";import"./index7.js";import"./typescript.js";import"./event.js";import"./_Uint8Array.js";import"./_initCloneObject.js";import"./index30.js";import"./isEqual.js";import"./scroll.js";import"./debounce.js";import"./index33.js";const O={props:{data:{type:Object,default:{}},errors:Object},data(){return{title:"Tambah Pengguna Baru",loading:!1,form:{id:null,name:null,level:null,username:null,email:null,password:null,password_conf:null}}},created(){Object.keys(this.data).length&&this.setValue()},methods:{submit(){this.loading=!0;let i=this.$inertia.form(this.form),o=Object.keys(this.data).length?this.route("admin.user.update",{id:this.data.id}):this.route("admin.user.store");i.post(o,{preserveScroll:!0,onSuccess:()=>{x({type:"success",message:"Data Berhasil Disimpan!"})},onFinish:()=>{this.loading=!1}})},setValue(){this.title="Ubah Pengguna",this.form.id=this.data.id,this.form.name=this.data.name,this.form.username=this.data.username,this.form.level=this.data.level,this.form.email=this.data.email}}},F={class:"content"},C={class:"content-heading d-flex justify-content-between align-items-center pt-0"},D={class:"space-x-1"},I=t("i",{class:"fa fa-check me-2"},null,-1),K={class:"block block-rounded"},L={class:"block-content"};function T(i,o,r,q,l,u){const p=U,m=M,n=P,d=j,c=B,f=A,_=N,h=S,b=g("base-layout");return V(),k(b,null,{default:a(()=>[t("div",F,[e(h,{"label-width":"30%",onSubmit:y(u.submit,["prevent"]),"label-position":"left"},{default:a(()=>[t("div",C,[t("span",null,v(l.title),1),t("div",D,[e(p,{"native-type":"submit",type:"primary",loading:l.loading},{default:a(()=>[I,E(" Simpan ")]),_:1},8,["loading"])])]),t("div",K,[t("div",L,[e(_,{gutter:32},{default:a(()=>[e(f,{lg:16},{default:a(()=>[e(n,{class:"mb-4",label:"Nama Lengkap",error:r.errors.name},{default:a(()=>[e(m,{modelValue:l.form.name,"onUpdate:modelValue":o[0]||(o[0]=s=>l.form.name=s),placeholder:"Masukan Nama Lengkap"},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{class:"mb-4",label:"Username",error:r.errors.username},{default:a(()=>[e(m,{modelValue:l.form.username,"onUpdate:modelValue":o[1]||(o[1]=s=>l.form.username=s),placeholder:"Masukan Username"},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{class:"mb-4",label:"Alamat Email",error:r.errors.email},{default:a(()=>[e(m,{modelValue:l.form.email,"onUpdate:modelValue":o[2]||(o[2]=s=>l.form.email=s),placeholder:"Masukan Alamat Email"},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{class:"mb-4",label:"Hak Akses",error:r.errors.level},{default:a(()=>[e(c,{modelValue:l.form.level,"onUpdate:modelValue":o[3]||(o[3]=s=>l.form.level=s),class:"w-100",placeholder:"Pilih"},{default:a(()=>[e(d,{label:"Admin",value:"admin"}),e(d,{label:"Gudang",value:"gudang"})]),_:1},8,["modelValue"])]),_:1},8,["error"]),e(n,{class:"mb-4",label:"Password",error:r.errors.password},{default:a(()=>[e(m,{type:"password",modelValue:l.form.password,"onUpdate:modelValue":o[4]||(o[4]=s=>l.form.password=s),placeholder:"Masukan Password","show-password":""},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{class:"mb-4",label:"Konfirmasi Password",error:r.errors.password_conf},{default:a(()=>[e(m,{type:"password",modelValue:l.form.password_conf,"onUpdate:modelValue":o[5]||(o[5]=s=>l.form.password_conf=s),placeholder:"Masukan Konfirmasi Password","show-password":""},null,8,["modelValue"])]),_:1},8,["error"])]),_:1})]),_:1})])])]),_:1},8,["onSubmit"])])]),_:1})}const te=w(O,[["render",T]]);export{te as default};
