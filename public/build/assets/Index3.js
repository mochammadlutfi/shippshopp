import{_ as c,A as _,j as h,k as b,l as r,p as m,v as e,$ as V,a2 as E,E as g}from"./id.js";import{E as k}from"./index20.js";import{E as x}from"./index21.js";import{E as y,a as v}from"./index22.js";import"./index7.js";import"./typescript.js";import"./event.js";import"./_Uint8Array.js";import"./_initCloneObject.js";const N={props:{data:Object,errors:Object},data(){return{isLoading:!1,form:{name:this.data.name,email:this.data.email,phone:this.data.phone}}},methods:{submit(){this.isLoading=!0,this.$inertia.form(this.form).post(this.route("user.update"),{preserveScroll:!0,onSuccess:()=>{k({type:"success",message:"Profil berhasil diperbaharui!"})},onFinish:()=>{this.isLoading=!1}})}}},L={class:"block rounded"},M={class:"block-content p-4"};function S(i,t,l,w,o,p){const s=x,n=y,u=g,d=v,f=_("user-layout");return h(),b(f,{title:"Profil"},{default:r(()=>[m("div",L,[m("div",M,[e(d,{"label-position":"top","label-width":"100%",onSubmit:E(p.submit,["prevent"])},{default:r(()=>[e(n,{label:"Nama Lengkap",error:l.errors.name},{default:r(()=>[e(s,{modelValue:o.form.name,"onUpdate:modelValue":t[0]||(t[0]=a=>o.form.name=a),type:"text",placeholder:"Masukan Nama Lengkap"},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{label:"Email",error:l.errors.email},{default:r(()=>[e(s,{modelValue:o.form.email,"onUpdate:modelValue":t[1]||(t[1]=a=>o.form.email=a),type:"text",placeholder:"Masukan Email"},null,8,["modelValue"])]),_:1},8,["error"]),e(n,{label:"No Handphone",error:l.errors.phone},{default:r(()=>[e(s,{modelValue:o.form.phone,"onUpdate:modelValue":t[2]||(t[2]=a=>o.form.phone=a),type:"text",placeholder:"Masukan No Handphone"},null,8,["modelValue"])]),_:1},8,["error"]),e(u,{"native-type":"submit",type:"primary",class:"w-100",loading:o.isLoading},{default:r(()=>[V(" Simpan ")]),_:1},8,["loading"])]),_:1},8,["onSubmit"])])])]),_:1})}const A=c(N,[["render",S]]);export{A as default};