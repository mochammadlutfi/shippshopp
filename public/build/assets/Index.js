import{_ as C,A as x,j as m,k as f,l,p as e,v as c,$ as p,a0 as B,s as S,u as E,F as V,q as d,E as I,a3 as T,a4 as q,I as j,J as A}from"./id.js";import{E as P}from"./index23.js";import{E as $,a as z}from"./index8.js";import{E as N}from"./index24.js";import"./event.js";import"./isEqual.js";import"./_Uint8Array.js";import"./typescript.js";import"./index21.js";import"./index25.js";const U={setup(){},data(){return{product:[],var1:null,var2:null,selected:[],selectAll:!1,voucher:[],carts:[]}},computed:{totalProduct(){var t=0;return this.selected.forEach((s,h)=>{this.carts.forEach((n,a)=>{s===n.id&&(t+=n.qty)})}),t},totalOrder(){var t=0;return this.selected.forEach((s,h)=>{this.carts.forEach((n,a)=>{s===n.id&&(t+=n.harga*n.qty)})}),t}},async mounted(){await this.fetchData()},methods:{onSelectAll(t){t?(this.selected=[],this.carts.forEach((s,h)=>{this.selected.push(s.id)})):this.selected=[]},async fetchData(){try{this.isLoading=!0;const t=await axios.get("/cart/data",{});t.status==200&&(this.carts=t.data),this.isLoading=!1}catch(t){console.error(t)}},async updateCart(t,s){this.$inertia.form({id:t,qty:s}).post(this.route("cart.update"),{preserveScroll:!0})},checkout(){this.$inertia.form({cart:this.selected}).post(this.route("checkout.shipping"),{preserveScroll:!0})},removeSelected(){this.$swal.fire({icon:"warning",title:"Kamu Yakin?",text:`${this.selected.length} produk akan dihapus!`,showCancelButton:!0,cancelButtonText:"Tidak, Batal",confirmButtonText:"Ya, Hapus",confirmButtonColor:"#3f9ce8",cancelButtonColor:"#af1310"}).then(t=>{t.isConfirmed&&this.$inertia.post(this.route("cart.removeSelected"),{ids:this.selected},{preserveScroll:!0,resetOnSuccess:!1,onProgress:()=>{this.$swal.fire({title:"Tunggu Sebentar...",text:"",imageUrl:window._asset+"media/loading.gif",showConfirmButton:!1,allowOutsideClick:!1})},onSuccess:()=>(this.$swal.Close(),this.$swal.fire({icon:"success",title:"Berhasil",text:"Produk berhasil dihapus dari keranjang",showCancelButton:!1,showConfirmButton:!1})),onError:()=>{this.$swal.Close()}})})},remove(t){this.$swal.fire({icon:"warning",title:"Kamu Yakin?",text:"Semua produk yang dipilih akan dihapus!",showCancelButton:!0,cancelButtonText:"Tidak, Batal!",confirmButtonText:"Ya, Hapus!"}).then(s=>{s.isConfirmed&&this.$inertia.delete(this.route("cart.remove",t),{preserveScroll:!0,onSuccess:()=>(this.$swal.Close(),this.$swal.fire({icon:"success",title:"Berhasil",text:"Produk berhasil dihapus dari keranjang",showCancelButton:!1,showConfirmButton:!1}))})})}}},u=t=>(j("data-v-63305bfb"),t=t(),A(),t),D={class:"content"},O=u(()=>e("div",{class:"content-heading"},[e("h2",{class:"mb-0"},"Keranjang")],-1)),Y={class:"row"},H={class:"col-lg-8"},K={class:"block"},L={class:"block-header block-header-default"},F={class:"block-title"},R={class:"block-options"},J=u(()=>e("i",{class:"si si-trash"},null,-1)),M={class:"block-content py-0"},G={class:"custom-control custom-checkbox"},Q=["id","value"],W=["for"],X={class:"cart-product_img"},Z=["src"],ee={class:"fs-6"},te=["href"],se={class:"fw-semibold"},oe={class:"fw-bold"},le={class:"product_sub_total"},ae=["onClick"],ce=u(()=>e("i",{class:"si si-trash"},null,-1)),ne=[ce],ie={class:"col-lg-4"},re={class:"block block-bordered block-shadow block-rounded"},de={class:"block-content block-content-full"},ue=u(()=>e("h6",{class:"font-size-h5"},"Ringkasan belanja",-1)),he={class:"d-flex justify-content-between"},_e={class:"font-size-md font-w600"},me={class:"font-size-md"},fe=u(()=>e("hr",null,null,-1));function pe(t,s,h,n,a,i){const g=P,b=I,r=$,k=N,w=z,v=x("base-layout");return m(),f(v,null,{default:l(()=>[e("div",D,[O,e("div",Y,[e("div",H,[e("div",K,[e("div",L,[e("div",F,[c(g,{modelValue:a.selectAll,"onUpdate:modelValue":s[0]||(s[0]=o=>a.selectAll=o),onChange:i.onSelectAll},{default:l(()=>[p(" Pilih Semua ")]),_:1},8,["modelValue","onChange"])]),e("div",R,[a.selected.length?(m(),f(b,{key:0,type:"danger",onClick:i.removeSelected},{default:l(()=>[J,p(" Hapus ")]),_:1},8,["onClick"])):B("",!0)])]),e("div",M,[(m(!0),S(V,null,E(a.carts,(o,y)=>(m(),f(w,{gutter:10,class:"cart-item",key:y},{default:l(()=>[c(r,{lg:1,class:"cart-item-check"},{default:l(()=>[e("label",G,[T(e("input",{class:"custom-control-input",id:o.id,type:"checkbox",value:o.id,"onUpdate:modelValue":s[1]||(s[1]=_=>a.selected=_)},null,8,Q),[[q,a.selected]]),e("label",{class:"custom-control-label",for:o.id},null,8,W)])]),_:2},1024),c(r,{lg:2},{default:l(()=>[e("div",X,[e("img",{src:o.product.main_image,class:"img-fluid"},null,8,Z)])]),_:2},1024),c(r,{lg:10},{default:l(()=>[e("div",ee,[e("a",{href:t.route("product.show",{slug:o.product.slug})},[e("span",se,d(o.product.nama),1)],8,te)]),e("div",oe,d(t.currency(o.harga)),1)]),_:2},1024),c(r,{lg:4,class:"text-end my-auto"},{default:l(()=>[c(k,{modelValue:o.qty,"onUpdate:modelValue":_=>o.qty=_,min:1,size:"small",class:"w-100"},null,8,["modelValue","onUpdate:modelValue"])]),_:2},1024),c(r,{lg:6,class:"text-end my-auto"},{default:l(()=>[e("div",le,d(t.currency(o.harga*o.qty)),1)]),_:2},1024),c(r,{lg:1,class:"text-end my-auto"},{default:l(()=>[e("button",{class:"btn btn-danger btn-sm",onClick:_=>i.remove(o.id)},ne,8,ae)]),_:2},1024)]),_:2},1024))),128))])])]),e("div",ie,[e("div",re,[e("div",de,[ue,e("div",he,[e("div",_e,"Total Belanja ("+d(i.totalProduct)+" barang)",1),e("div",me,d(t.currency(i.totalOrder)),1)]),fe,c(b,{type:"primary",size:"large",class:"w-100",onClick:i.checkout,disabled:!a.selected.length},{default:l(()=>[p(" Checkout ")]),_:1},8,["onClick","disabled"])])])])])])]),_:1})}const Ee=C(U,[["render",pe],["__scopeId","data-v-63305bfb"]]);export{Ee as default};