import{h}from"./moment.js";import{_ as v,A as b,j as w,k,l as r,p as e,H as i,v as n,q as u,$ as x,E}from"./id.js";import{E as d}from"./index20.js";import{E as B}from"./index29.js";import{E as P}from"./index21.js";import{E as D,a as S}from"./index8.js";import{E as C,a as T}from"./index30.js";import"./index7.js";import"./typescript.js";import"./index27.js";import"./scroll.js";import"./event.js";import"./index31.js";import"./_Uint8Array.js";import"./isEqual.js";import"./debounce.js";import"./index23.js";import"./_initCloneObject.js";const M={data(){return{status:this.route().params.status==null?"unpaid":this.route().params.status,data:[],isLoading:!1,page:1,pageSize:0,total:0,from:0,to:0,searchTerms:""}},async mounted(){await this.fetchData()},methods:{async fetchData(s){var s=s??1;try{this.isLoading=!0;const a=await axios.get(this.route("user.order.data"),{params:{page:s,search:this.searchTerms,status:this.status}});a.status==200&&(this.data=a.data.data,this.page=a.data.current_page,this.total=a.data.total,this.pageSize=a.data.per_page,this.from=a.data.from,this.to=a.data.to),this.isLoading=!1}catch(a){console.error(a)}},format_date(t){if(t)return h.locale("id"),h(String(t)).format("DD MMM YYYY")},payment(t){snap.pay(t.payment_ref,{onSuccess:function(s){this.updatePayment("paid",t.id),d({type:"success",message:"Pembayaran Berhasil"})},onPending:function(s){d({type:"info",message:"Menunggu Pembayaran"})},onError:function(s){d({type:"error",message:"Pembayaran Gagal"})},onClose:function(s){console.log(s)}})},async updatePayment(t,s){try{this.isLoading=!0,(await axios.post(this.route("user.order.state",{id:s}),{status:t})).status==200&&location.reload(),this.isLoading=!1}catch(a){console.error(a)}},receive(t){B.alert("Pastikan barang yang diterima sesuai dengan pesanan!","Peringatan",{showCancelButton:!0,confirmButtonText:"Pesanan Sudah Diterima!",cancelButtonText:"Batal!",type:"warning"}).then(()=>{this.$inertia.post(this.route("user.order.confirm",{id:t}),{preserveScroll:!0,onSuccess:()=>{this.fetchData(),d({type:"success",message:"Pesanan Berhasil Diterima!"})}})})}}},V={class:"content"},z=e("div",{class:"d-flex justify-content-between align-items-center mb-4"},[e("h3",{class:"fs-4 fw-bold mb-0"},"Pesanan Saya")],-1),L={class:"block block-bordered rounded mb-3"},N={class:"nav nav-fill nav-tabs-capsule"},Y={class:"nav-item"},j=["href"],A={class:"nav-item"},I=["href"],q={class:"nav-item"},G=["href"],H={class:"nav-item"},R=["href"],U={class:"nav-item"},F=["href"],J={class:"nav-item"},K=["href"],O={class:"block-content p-2"},Q=e("i",{class:"fa fa-search"},null,-1),W={class:"d-flex my-auto ms-auto"},X={class:"my-auto px-3"},Z={class:"pt-25 pl-0"},$=e("i",{class:"fa fa-chevron-left fa-fw"},null,-1),ee=e("i",{class:"fa fa-chevron-right fa-fw"},null,-1),te=["href"];function se(t,s,a,ae,o,oe){const f=P,p=D,m=E,_=S,c=C,g=T,y=b("base-layout");return w(),k(y,null,{default:r(()=>[e("div",V,[z,e("div",L,[e("ul",N,[e("li",Y,[e("a",{class:i(["nav-link",{active:o.status=="unpaid"}]),href:t.route("user.order.index",{status:"unpaid"})},"Belum Bayar ",10,j)]),e("li",A,[e("a",{class:i(["nav-link",{active:o.status=="pending"}]),href:t.route("user.order.index",{status:"pending"})},"Pending ",10,I)]),e("li",q,[e("a",{class:i(["nav-link",{active:o.status=="process"}]),href:t.route("user.order.index",{status:"process"})},"Dikemas ",10,G)]),e("li",H,[e("a",{class:i(["nav-link",{active:o.status=="shipped"}]),href:t.route("user.order.index",{status:"shipped"})},"Dikirim ",10,R)]),e("li",U,[e("a",{class:i(["nav-link",{active:o.status=="done"}]),href:t.route("user.order.index",{status:"done"})},"Selesai ",10,F)]),e("li",J,[e("a",{class:i(["nav-link",{active:o.status=="cancel"}]),href:t.route("user.order.index",{status:"cancel"})},"Dibatalkan ",10,K)])]),e("div",O,[n(_,{gutter:12},{default:r(()=>[n(p,{lg:12},{default:r(()=>[n(f,{modelValue:o.searchTerms,"onUpdate:modelValue":s[0]||(s[0]=l=>o.searchTerms=l),class:"w-75 m-2",placeholder:"Cari Pesanan"},{prefix:r(()=>[Q]),_:1},8,["modelValue"])]),_:1}),n(p,{lg:12,class:"d-flex float-end my-auto"},{default:r(()=>[e("div",W,[e("div",X,[e("span",null,u(o.from)+"-"+u(o.to)+"/"+u(o.total),1)]),e("div",Z,[n(m,{type:"primary",size:"small"},{default:r(()=>[$]),_:1}),n(m,{type:"primary",size:"small",plain:""},{default:r(()=>[ee]),_:1})])])]),_:1})]),_:1})]),n(g,{data:o.data,class:"w-100",onSortChange:t.sortChange,"header-cell-class-name":"bg-dark text-white"},{default:r(()=>[n(c,{type:"index",width:"100"}),n(c,{prop:"nomor",label:"Nomor",sortable:""}),n(c,{prop:"total",label:"Total",sortable:""},{default:r(l=>[x(u(t.currency(l.row.total)),1)]),_:1}),n(c,{label:"Aksi",align:"center",width:"180"},{default:r(l=>[e("a",{href:t.route("user.order.show",{id:l.row.id}),class:"ep-button ep-button--primary"}," Detail ",8,te)]),_:1})]),_:1},8,["data","onSortChange"])])])]),_:1})}const xe=v(M,[["render",se]]);export{xe as default};
