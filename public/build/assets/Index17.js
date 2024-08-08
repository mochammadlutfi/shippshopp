import{_ as z,A as U,j as _,k as g,l as a,p as s,v as e,$ as n,a2 as D,H as d,q as c,a0 as w,s as x,F as L,E as N,B as Y,x as F,y as I}from"./id.js";import{E as A}from"./index29.js";import{E as H}from"./index20.js";import{E as O}from"./index32.js";import{a as q,E as G}from"./index33.js";import{E as R,a as J}from"./index8.js";import{E as Q}from"./index21.js";import{E as W,a as X}from"./index30.js";import{E as Z}from"./index34.js";import{E as $,a as ee}from"./index22.js";import{E as ae}from"./index35.js";import{E as te}from"./index26.js";import"./index27.js";import"./scroll.js";import"./index7.js";import"./typescript.js";import"./isEqual.js";import"./_Uint8Array.js";import"./event.js";import"./debounce.js";import"./index31.js";import"./index23.js";import"./_initCloneObject.js";import"./index25.js";const se={props:{counter:{type:Object}},data(){return{data:[],isLoading:!0,status:this.route().params.status==null?"pending":this.route().params.status,params:{searchKey:"name",searchKeyword:"",sort:"id",sortDir:"DESC",limit:25,total:0,page:1,pageSize:0},showModal:!1,form:{status:null,periode:null}}},async created(){await this.fetchData()},methods:{async fetchData(l){var l=l??1;try{this.isLoading=!0;const i=await axios.get(this.route("admin.sale.order.data"),{params:{page:l,limit:this.params.limit,search:this.params.searchKeyword,searchKey:this.params.searchKey,status:this.status}});i.status==200&&(this.data=i.data.data,this.params.page=i.data.current_page,this.params.total=i.data.total,this.params.pageSize=i.data.per_page,this.params.from=i.data.from,this.params.to=i.data.to),this.isLoading=!1}catch(i){console.error(i)}},format_date(r){if(r)return moment(String(r)).format("DD MMM YYYY")},hapus(r){A.alert("Data yang dihapus tidak bisa dikembalikan!","Peringatan",{showCancelButton:!0,confirmButtonText:"Ya!",cancelButtonText:"Tidak!",type:"warning"}).then(()=>{this.$inertia.delete(this.route("admin.sale.order.delete",{id:r}),{preserveScroll:!0,onSuccess:()=>{this.fetchData(),H({type:"success",message:"Data Berhasil Dihapus!"})}})})},submit(){}}},oe={class:"content"},le={class:"content-heading d-flex justify-content-between align-items-center"},ne=s("span",null,"Pesanan Penjualan",-1),re={class:"space-x-1"},ie=s("i",{class:"fa fa-print me-1"},null,-1),de={class:"block block-bordered block-rounded mb-2"},pe={class:"nav nav-tabs nav-tabs-block nav-fill overflow-hidden"},ce={class:"nav-item"},ue=["href"],me={class:"nav-item"},_e=["href"],fe={class:"nav-item"},he=["href"],ge={class:"nav-item"},be=["href"],ye={class:"nav-item"},we=["href"],ve={class:"block-content py-3"},ke=s("i",{class:"fa fa-search"},null,-1),Ee={class:"block-content p-0"},De=s("i",{class:"fa fa-angle-down ms-1"},null,-1),xe=["href"],Ce=s("i",{class:"si fa-fw si-eye"},null,-1),Ve=["href"],Se=s("i",{class:"si fa-fw si-note"},null,-1),Be=s("i",{class:"si fa-fw si-trash"},null,-1),Me={key:0,class:"block-content py-2"},Pe={class:"mb-0"},Te={class:"d-flex"},je={class:"float-end"};function Ke(r,l,i,ze,t,b){const f=N,u=O,p=q,v=G,h=R,C=Q,k=J,m=W,y=Y,V=F,S=I,B=X,M=Z,E=$,P=ae,T=ee,j=te,K=U("base-layout");return _(),g(K,{title:"Penjualan"},{default:a(()=>[s("div",oe,[s("div",le,[ne,s("div",re,[e(f,{type:"primary",onClick:l[0]||(l[0]=D(o=>t.showModal=!0,["prevent"]))},{default:a(()=>[ie,n(" Export ")]),_:1})])]),s("div",de,[s("ul",pe,[s("li",ce,[s("a",{class:d(["nav-link",{active:t.status=="pending"}]),href:r.route("admin.sale.order.index",{status:"pending"})},[n(" Pending "),e(u,{type:"primary",round:"",class:d({"ep-tag--dark":t.status=="pending"})},{default:a(()=>[n(c(i.counter.pending),1)]),_:1},8,["class"])],10,ue)]),s("li",me,[s("a",{class:d(["nav-link",{active:t.status=="process"}]),href:r.route("admin.sale.order.index",{status:"process"})},[n("Diproses "),e(u,{type:"primary",round:"",class:d({"ep-tag--dark":t.status=="process"})},{default:a(()=>[n(c(i.counter.process),1)]),_:1},8,["class"])],10,_e)]),s("li",fe,[s("a",{class:d(["nav-link",{active:t.status=="shipped"}]),href:r.route("admin.sale.order.index",{status:"shipped"})},[n("Dikirim "),e(u,{type:"primary",round:"",class:d({"ep-tag--dark":t.status=="shipped"})},{default:a(()=>[n(c(i.counter.shipped),1)]),_:1},8,["class"])],10,he)]),s("li",ge,[s("a",{class:d(["nav-link",{active:t.status=="done"}]),href:r.route("admin.sale.order.index",{status:"done"})},[n("Selesai "),e(u,{type:"primary",round:"",class:d({"ep-tag--dark":t.status=="done"})},{default:a(()=>[n(c(i.counter.done),1)]),_:1},8,["class"])],10,be)]),s("li",ye,[s("a",{class:d(["nav-link",{active:t.status=="cancel"}]),href:r.route("admin.sale.order.index",{status:"cancel"})},[n("Dibatalkan "),e(u,{type:"primary",round:"",class:d({"ep-tag--dark":t.status=="cancel"})},{default:a(()=>[n(c(i.counter.cancel),1)]),_:1},8,["class"])],10,we)])]),s("div",ve,[e(k,{justify:"space-between"},{default:a(()=>[e(h,{span:12},{default:a(()=>[e(v,{modelValue:t.params.limit,"onUpdate:modelValue":l[1]||(l[1]=o=>t.params.limit=o),placeholder:"Pilih",style:{width:"115px"},onChange:l[2]||(l[2]=o=>b.fetchData(1))},{default:a(()=>[e(p,{label:"25",value:"25"}),e(p,{label:"50",value:"50"}),e(p,{label:"100",value:"100"})]),_:1},8,["modelValue"])]),_:1}),e(h,{span:8},{default:a(()=>[e(C,{modelValue:t.params.searchKeyword,"onUpdate:modelValue":l[3]||(l[3]=o=>t.params.searchKeyword=o),placeholder:"Masukan kata kunci",class:"input-with-select",clearable:""},{prefix:a(()=>[ke]),_:1},8,["modelValue"])]),_:1})]),_:1})]),s("div",Ee,[e(B,{data:t.data,class:"w-100",onSortChange:r.sortChange,"header-cell-class-name":"bg-primary text-dark"},{default:a(()=>[e(m,{type:"index",width:"100"}),e(m,{prop:"nomor",label:"Nomor",sortable:""}),e(m,{prop:"customer.name",label:"Pelanggan",sortable:""}),e(m,{prop:"status_pembayaran",label:"Status Pembayaran",sortable:""},{default:a(o=>[o.row.payment_status=="unpaid"?(_(),g(u,{key:0,type:"danger"},{default:a(()=>[n(" Belum Bayar ")]),_:1})):o.row.payment_status=="pending"?(_(),g(u,{key:1,type:"warning"},{default:a(()=>[n(" Pending ")]),_:1})):o.row.payment_status=="paid"?(_(),g(u,{key:2,type:"success"},{default:a(()=>[n(" Lunas ")]),_:1})):w("",!0)]),_:1}),e(m,{prop:"total",label:"Total",sortable:""},{default:a(o=>[n(c(r.currency(o.row.total)),1)]),_:1}),e(m,{label:"Aksi",align:"center",width:"180"},{default:a(o=>[e(S,{"popper-class":"dropdown-action",trigger:"click"},{dropdown:a(()=>[e(V,null,{default:a(()=>[e(y,null,{default:a(()=>[s("a",{href:r.route("admin.sale.order.show",{id:o.row.id}),class:"w-100 d-flex align-items-center justify-content-between space-x-1"},[n(" Detail "),Ce],8,xe)]),_:2},1024),r.$page.props.user.level!="admin"?(_(),x(L,{key:0},[e(y,null,{default:a(()=>[s("a",{href:r.route("admin.sale.order.edit",{id:o.row.id}),class:"w-100 d-flex align-items-center justify-content-between space-x-1"},[n(" Ubah "),Se],8,Ve)]),_:2},1024),e(y,{class:"d-flex align-items-center justify-content-between space-x-1",onClick:D(Ue=>b.hapus(o.row.id),["prevent"])},{default:a(()=>[n(" Hapus "),Be]),_:2},1032,["onClick"])],64)):w("",!0)]),_:2},1024)]),default:a(()=>[e(f,{type:"primary"},{default:a(()=>[n(" Aksi "),De]),_:1})]),_:2},1024)]),_:1})]),_:1},8,["data","onSortChange"])]),t.isLoading==!1?(_(),x("div",Me,[e(k,{justify:"space-between"},{default:a(()=>[e(h,{lg:12},{default:a(()=>[s("p",Pe,"Menampilkan "+c(t.params.from)+" sampai "+c(t.params.to)+" dari "+c(t.params.total),1)]),_:1}),e(h,{lg:12,class:"text-end"},{default:a(()=>[e(M,{class:"float-end",background:"",layout:"prev, pager, next","page-size":t.params.pageSize,total:t.params.total,"current-page":t.params.page,onCurrentChange:b.fetchData},null,8,["page-size","total","current-page","onCurrentChange"])]),_:1})]),_:1})])):w("",!0)])]),e(j,{modelValue:t.showModal,"onUpdate:modelValue":l[7]||(l[7]=o=>t.showModal=o),title:"Export",width:"500","before-close":r.handleClose},{default:a(()=>[e(T,{"label-width":"30%",method:"GET",action:r.route("admin.sale.order.report"),target:"_blank","label-position":"top"},{default:a(()=>[e(E,{class:"mb-4",label:"Status"},{default:a(()=>[e(v,{modelValue:t.form.status,"onUpdate:modelValue":l[4]||(l[4]=o=>t.form.status=o),name:"status",class:"w-100",placeholder:"Pilih"},{default:a(()=>[e(p,{label:"Semua",value:""}),e(p,{label:"Pending",value:"pending"}),e(p,{label:"Diproses",value:"process"}),e(p,{label:"Dikirim",value:"shipped"}),e(p,{label:"Selesai",value:"done"}),e(p,{label:"Batal",value:"cancel"})]),_:1},8,["modelValue"])]),_:1}),e(E,{class:"mb-4",label:"Periode"},{default:a(()=>[e(P,{name:"date",modelValue:t.form.periode,"onUpdate:modelValue":l[5]||(l[5]=o=>t.form.periode=o),type:"daterange","range-separator":"Sampai","start-placeholder":"Tanggal Mulai","end-placeholder":"Tanggal Selesai"},null,8,["modelValue"])]),_:1}),s("div",Te,[s("div",je,[e(f,{onClick:l[6]||(l[6]=o=>t.showModal=!1)},{default:a(()=>[n("Batal")]),_:1}),e(f,{type:"primary","native-type":"submit"},{default:a(()=>[n(" Download ")]),_:1})])])]),_:1},8,["action"])]),_:1},8,["modelValue","before-close"])]),_:1})}const ra=z(se,[["render",Ke]]);export{ra as default};
