import{_ as w,A as b,j as k,k as g,l as s,p as l,v as e,$ as i,a2 as V,E}from"./id.js";import{E as y}from"./index20.js";import{E as v}from"./index21.js";import{E as x,a as M}from"./index22.js";import{E as L,a as N}from"./index8.js";import"./index7.js";import"./typescript.js";import"./event.js";import"./_Uint8Array.js";import"./_initCloneObject.js";const S={props:{errors:Object},data(){return{isLoading:!1,form:{name:"",email:"",phone:"",password:"",password_conf:""}}},methods:{submit(){this.isLoading=!0,this.$inertia.form(this.form).post(this.route("register"),{preserveScroll:!0,onSuccess:()=>{y({type:"success",message:"Pendaftaran Berhasil!"})},onFinish:()=>{this.isLoading=!1}})}}},B={class:"content content-full"},U={class:"text-center"},j=l("h2",{class:"font-weight-bold mb-0"},"Daftar",-1),C=["href"],D={class:"block block-rounded block-fx-shadow"},F={class:"block-content block-content-full"};function P(d,r,t,H,o,p){const n=v,m=x,u=E,f=M,c=L,_=N,h=b("base-layout");return k(),g(h,{title:"Daftar"},{default:s(()=>[l("div",B,[e(_,{justify:"center"},{default:s(()=>[e(c,{md:10,lg:10,class:"align-items-center"},{default:s(()=>[l("div",U,[j,l("p",null,[i("Sudah punya akun? "),l("a",{href:d.route("login")},"Daftar",8,C),i(" di sini")])]),e(f,{"label-position":"top","label-width":"100%",onSubmit:V(p.submit,["prevent"])},{default:s(()=>[l("div",D,[l("div",F,[e(m,{label:"Nama Lengkap",error:t.errors.name},{default:s(()=>[e(n,{modelValue:o.form.name,"onUpdate:modelValue":r[0]||(r[0]=a=>o.form.name=a),type:"text",placeholder:"Masukan Nama Lengkap"},null,8,["modelValue"])]),_:1},8,["error"]),e(m,{label:"Email",error:t.errors.email},{default:s(()=>[e(n,{modelValue:o.form.email,"onUpdate:modelValue":r[1]||(r[1]=a=>o.form.email=a),type:"text",placeholder:"Masukan Email"},null,8,["modelValue"])]),_:1},8,["error"]),e(m,{label:"No Handphone",error:t.errors.phone},{default:s(()=>[e(n,{modelValue:o.form.phone,"onUpdate:modelValue":r[2]||(r[2]=a=>o.form.phone=a),type:"text",placeholder:"Masukan No Handphone"},null,8,["modelValue"])]),_:1},8,["error"]),e(m,{label:"Masukan password",error:t.errors.password},{default:s(()=>[e(n,{modelValue:o.form.password,"onUpdate:modelValue":r[3]||(r[3]=a=>o.form.password=a),type:"password",placeholder:"Masukan Password","show-password":""},null,8,["modelValue"])]),_:1},8,["error"]),e(m,{label:"Masukan password",error:t.errors.password_conf},{default:s(()=>[e(n,{modelValue:o.form.password_conf,"onUpdate:modelValue":r[4]||(r[4]=a=>o.form.password_conf=a),type:"password",placeholder:"Masukan Konfirmasi Password","show-password":""},null,8,["modelValue"])]),_:1},8,["error"]),e(u,{"native-type":"submit",type:"primary",class:"w-100",loading:o.isLoading},{default:s(()=>[i("Login Sekarang")]),_:1},8,["loading"])])])]),_:1},8,["onSubmit"])]),_:1})]),_:1})])]),_:1})}const Q=w(S,[["render",P]]);export{Q as default};