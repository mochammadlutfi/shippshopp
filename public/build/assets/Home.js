import{_ as v,A as y,j as d,k as r,l as t,p as e,v as a,$ as p,s as m,u as f,F as h,q as _,I as w,J as x}from"./id.js";import{E as b,a as k}from"./index8.js";import"./typescript.js";const j={components:{},props:{data:{type:Object},category:{type:Array}}},c=s=>(w("data-v-cfd87280"),s=s(),x(),s),S={class:"position-relative d-flex align-items-center"},I={class:"content content-full"},B={class:"text-center text-md-start"},C=c(()=>e("h1",{class:"text-header mb-3"}," Let’s vaping together ",-1)),E=c(()=>e("p",{class:"text-white fw-medium mb-4"}," Penuhi keperluan vaping kamu hanya di shipp shopp vape store ",-1)),H=["href"],P=c(()=>e("i",{class:"fa fa-arrow-right opacity-50 me-1"},null,-1)),V=c(()=>e("img",{class:"img-fluid",src:"/images/front.png",alt:"Hero Promo"},null,-1)),A={class:"content content-full"},L=c(()=>e("div",{class:"position-relative"},[e("h2",{class:"fw-bold mb-4 text-center"},[p(" Produk "),e("span",{class:"text-primary"},"Kami")])],-1)),N={class:"block rounded",href:"#"},$={class:"block-content p-4 text-center"},F={class:"mb-0 fs-lg"},K={class:"product"},q={class:"product-content"},z={class:"product-img"},D=["href"],J=["src"],O={class:"product-info"},R={class:"product-title"},T=["href"],G={class:"product-price"},M={class:"content content-full"},Q=c(()=>e("div",{class:"position-relative"},[e("h2",{class:"fw-bold mb-4 text-center"},[p(" Lokasi "),e("span",{class:"text-primary"},"Kami")])],-1)),U=c(()=>e("iframe",{src:"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0901393228382!2d106.5424828749906!3d-6.251853093736579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fd8ddf9ee793%3A0x671c246aea1ffd23!2sShipp%20Shopp%20Vape!5e0!3m2!1sen!2sid!4v1719943100082!5m2!1sen!2sid",width:"100%",height:"400",class:"rounded",allowfullscreen:"",loading:"lazy",referrerpolicy:"no-referrer-when-downgrade"},null,-1));function W(s,X,u,Y,Z,ee){const n=b,i=k,g=y("base-layout");return d(),r(g,{title:"Home"},{default:t(()=>[e("div",S,[e("div",I,[a(i,null,{default:t(()=>[a(n,{md:12,class:"py-4 d-flex align-items-center"},{default:t(()=>[e("div",B,[C,E,e("a",{class:"ep-button ep-button--primary py-3 px-4",href:s.route("product.index")},[P,p(" Cari Produk ")],8,H)])]),_:1}),a(n,{md:10,class:"py-4 d-md-flex align-items-md-center justify-content-md-end"},{default:t(()=>[V]),_:1})]),_:1})])]),e("div",A,[L,a(i,{gutter:20,justify:"center",class:"mb-3"},{default:t(()=>[(d(!0),m(h,null,f(u.category,(o,l)=>(d(),r(n,{md:6,key:l},{default:t(()=>[e("a",N,[e("div",$,[e("h2",F,_(o.nama),1)])])]),_:2},1024))),128))]),_:1}),a(i,{gutter:10},{default:t(()=>[(d(!0),m(h,null,f(u.data,(o,l)=>(d(),r(n,{md:4,key:l},{default:t(()=>[e("div",K,[e("div",q,[e("div",z,[e("a",{href:s.route("product.show",{slug:o.slug})},[e("img",{src:o.main_image,class:"img-fluid"},null,8,J)],8,D)]),e("div",O,[e("div",R,[e("a",{href:s.route("product.show",{slug:o.slug})},_(o.nama),9,T)]),e("div",G,_(s.currency(o.harga_jual)),1)])])])]),_:2},1024))),128))]),_:1})]),e("div",M,[Q,a(i,{gutter:20,justify:"center"},{default:t(()=>[a(n,{md:16},{default:t(()=>[U]),_:1})]),_:1})])]),_:1})}const ae=v(j,[["render",W],["__scopeId","data-v-cfd87280"]]);export{ae as default};
