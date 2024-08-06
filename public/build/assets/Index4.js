import{_ as h,A as m,j as a,k as b,l as c,p as t,$ as i,s as o,u as f,F as k,q as n,a0 as r,v as y,a2 as l,E as v}from"./id.js";const g={data(){return{data:[],isLoading:!1,pageSize:0,total:0}},async mounted(){await this.fetchData()},methods:{async fetchData(){try{this.isLoading=!0;const e=await axios.get("/user/alamat/data",{params:{}});e.status==200&&(this.data=e.data),this.isLoading=!1}catch(e){console.error(e)}}}},w={class:"d-flex justify-content-between align-items-center mb-4"},C=t("h3",{class:"fs-4 fw-bold mb-0"},"Buku Alamat",-1),x={class:"space-x-1"},A=["href"],B=t("i",{class:"fa fa-plus me-1"},null,-1),$={class:"block-header border-3x border-bottom p-2"},L={class:"block-title"},N={key:0,class:"badge badge-primary p-1"},V={class:"block-content p-4"},D={class:"content__top-info"},E={class:"top-info__name"},U={class:"top-info__phone"},j={class:"content__complete-address"},z={class:"block-content block-content-full block-content-sm bg-body-light font-size-sm"},F=["href"],M=t("i",{class:"si si-note me-1"},null,-1),S=t("i",{class:"si si-trash me-1"},null,-1),T=["onClick"];function q(e,H,I,J,d,G){const _=v,p=m("user-layout");return a(),b(p,null,{default:c(()=>[t("div",w,[C,t("div",x,[t("a",{href:e.route("user.address.create"),class:"ep-button ep-button--primary"},[B,i(" Tambah Alamat ")],8,A)])]),(a(!0),o(k,null,f(d.data,s=>(a(),o("div",{class:"block rounded block-shadow block-bordered mb-2",key:s.id},[t("div",$,[t("h3",L,n(s.name),1),s.is_main==1?(a(),o("span",N,"Alamat Utama")):r("",!0)]),t("div",V,[t("div",D,[t("span",E,n(s.reciever),1),t("span",U,n(s.phone),1)]),t("div",j,n(s.address),1)]),t("div",z,[t("a",{href:e.route("user.address.edit",{id:s.id}),class:"ep-button ep-button--info"},[M,i(" Ubah ")],8,F),y(_,{type:"danger",onClick:l(u=>e.destroy(s.id),["prevent"]),plain:""},{default:c(()=>[S,i(" Hapus ")]),_:2},1032,["onClick"]),s.is_primary==0?(a(),o("button",{key:0,type:"button",onClick:l(u=>e.setMainAddress(s.id),["prevent"]),class:"btn btn-sm btn-primary"}," Jadikan Alamat Utama ",8,T)):r("",!0)])]))),128))]),_:1})}const O=h(g,[["render",q]]);export{O as default};
