import{_ as v,A as y,j as d,k as l,l as t,p as e,v as a,$ as u,s as m,u as f,F as h,q as _,I as b,J as w}from"./id.js";import{E as x,a as k}from"./index8.js";import"./typescript.js";const j={components:{},props:{data:{type:Object},category:{type:Array}}},c=s=>(b("data-v-fefdd35a"),s=s(),w(),s),I={class:"position-relative d-flex align-items-center"},B={class:"content content-full"},C={class:"text-center text-md-start"},E=c(()=>e("h1",{class:"text-header mb-3"}," Let’s vaping together ",-1)),H=c(()=>e("p",{class:"text-white fw-medium mb-4"}," Penuhi keperluan vaping kamu hanya di shipp shopp vape store ",-1)),P=["href"],S=c(()=>e("i",{class:"fa fa-arrow-right opacity-50 me-1"},null,-1)),A=c(()=>e("img",{class:"img-fluid",src:"/images/front.png",alt:"Hero Promo"},null,-1)),L={class:"content content-full"},N=c(()=>e("div",{class:"position-relative"},[e("h2",{class:"fw-bold mb-4 text-center"},[u(" Produk "),e("span",{class:"text-primary"},"Kami")])],-1)),V=["href"],$={class:"block-content p-4 text-center"},F={class:"mb-0 fs-lg"},K={class:"product"},q={class:"product-content"},z={class:"product-img"},D=["href"],J=["src"],O={class:"product-info"},R={class:"product-title"},T=["href"],G={class:"product-price"},M={class:"content content-full"},Q=c(()=>e("div",{class:"position-relative"},[e("h2",{class:"fw-bold mb-4 text-center"},[u(" Lokasi "),e("span",{class:"text-primary"},"Kami")])],-1)),U=c(()=>e("iframe",{src:"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1854135679187!2d106.5800059100494!3d-6.239276693722938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fd66dbceff3b%3A0x88d2608299c8215!2sshippshoppvape%20binong%20permai!5e0!3m2!1sen!2sid!4v1724054540565!5m2!1sen!2sid",width:"100%",height:"400",class:"rounded",allowfullscreen:"",loading:"lazy",referrerpolicy:"no-referrer-when-downgrade"},null,-1));function W(s,X,p,Y,Z,ee){const n=x,i=k,g=y("base-layout");return d(),l(g,{title:"Home"},{default:t(()=>[e("div",I,[e("div",B,[a(i,null,{default:t(()=>[a(n,{md:12,class:"py-4 d-flex align-items-center"},{default:t(()=>[e("div",C,[E,H,e("a",{class:"ep-button ep-button--primary py-3 px-4",href:s.route("product.index")},[S,u(" Cari Produk ")],8,P)])]),_:1}),a(n,{md:10,class:"py-4 d-md-flex align-items-md-center justify-content-md-end"},{default:t(()=>[A]),_:1})]),_:1})])]),e("div",L,[N,a(i,{gutter:20,justify:"center",class:"mb-3"},{default:t(()=>[(d(!0),m(h,null,f(p.category,(o,r)=>(d(),l(n,{md:6,key:r},{default:t(()=>[e("a",{class:"block rounded",href:s.route("category",o.slug)},[e("div",$,[e("h2",F,_(o.nama),1)])],8,V)]),_:2},1024))),128))]),_:1}),a(i,{gutter:10},{default:t(()=>[(d(!0),m(h,null,f(p.data,(o,r)=>(d(),l(n,{md:4,key:r},{default:t(()=>[e("div",K,[e("div",q,[e("div",z,[e("a",{href:s.route("product.show",{slug:o.slug})},[e("img",{src:o.main_image,class:"img-fluid"},null,8,J)],8,D)]),e("div",O,[e("div",R,[e("a",{href:s.route("product.show",{slug:o.slug})},_(o.nama),9,T)]),e("div",G,_(s.currency(o.harga_jual)),1)])])])]),_:2},1024))),128))]),_:1})]),e("div",M,[Q,a(i,{gutter:20,justify:"center"},{default:t(()=>[a(n,{md:16},{default:t(()=>[U]),_:1})]),_:1})])]),_:1})}const ae=v(j,[["render",W],["__scopeId","data-v-fefdd35a"]]);export{ae as default};
