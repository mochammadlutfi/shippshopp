import O from"./UnitSelect.js";import{h as L}from"./moment.js";import{_ as z,A as D,j as d,k as _,l as a,p as r,$ as u,a0 as b,v as e,a3 as E,a2 as V,s as x,q as w,F as A,E as P,B as Y,x as q,y as H}from"./id.js";import{E as S}from"./index20.js";import{E as R}from"./index29.js";import{a as G,E as J}from"./index32.js";import{E as Q,a as W}from"./index8.js";import{E as X}from"./index21.js";import{E as Z,a as $}from"./index31.js";import{E as ee}from"./index34.js";import{E as te,a as oe}from"./index22.js";import{E as ae}from"./index26.js";import{v as le}from"./directive.js";import"./typescript.js";import"./event.js";import"./index30.js";import"./isEqual.js";import"./_Uint8Array.js";import"./scroll.js";import"./debounce.js";import"./index33.js";import"./index7.js";import"./index27.js";import"./index23.js";import"./_initCloneObject.js";const se={components:{UnitSelect:O},props:{errors:Object},data(){return{selected:[],data:[],from:0,to:0,isLoading:!0,page:1,pageSize:0,searchKey:"name",searchKeyword:"",limit:25,total:0,showForm:!1,sort:"name",sortDir:"ASC",formTitle:"Tambah Satuan",form:{id:null,name:null,code:null,operator:null,operator_value:null,base_unit_id:null},editMode:!1,loadingForm:!1,loadingImport:!1,showImport:!1,importFile:null}},async created(){await this.fetchData()},methods:{indexMethod(s){return(this.page==1?1:this.limit*(this.page-1)+1)+s},sortChange(s){this.sort=s.prop,this.sortDir=s.order=="ascending"?"ASC":"DESC",this.fetchData()},async fetchData(t){var t=t??1;try{this.isLoading=!0;const n=await axios.get("/admin/unit/data",{params:{page:t,limit:this.limit,sort:this.sort,sortDir:this.sortDir,search:this.searchKeyword,searchKey:this.searchKey}});n.status==200&&(this.from=n.data.from,this.to=n.data.to,this.data=n.data.data,this.page=n.data.current_page,this.total=n.data.total,this.pageSize=n.data.per_page),this.isLoading=!1}catch(n){console.error(n)}},format_date(s){if(s)return L(String(s)).format("DD MMMM YYYY")},onCloseForm(){this.title="Tambah Satuan",this.editMode=!1,this.form.id=null,this.form.name=null,this.form.phone=null,this.showForm=!1},submit(){this.loadingForm=!0;let s=this.$inertia.form(this.form),t=this.editMode==!0?this.route("admin.inventory.unit.update",{id:this.form.id}):this.route("admin.inventory.unit.store");s.post(t,{preserveScroll:!0,onFinish:()=>{this.loadingForm=!1},onSuccess:()=>{S({type:"success",message:"Data Berhasil Disimpan!"}),this.onCloseForm(),this.fetchData()}})},edit(s){this.formTitle="Ubah Satuan",this.form.id=s.id,this.form.name=s.name,this.form.phone=s.phone,this.editMode=!0,this.showForm=!0},hapus(s){R.alert("Data yang dihapus tidak bisa dikembalikan!","Peringatan",{showCancelButton:!0,confirmButtonText:"Ya!",cancelButtonText:"Tidak!",type:"warning"}).then(()=>{this.$inertia.delete(this.route("admin.inventory.unit.delete",{id:s}),{preserveScroll:!0,onSuccess:()=>{this.fetchData(),S({type:"success",message:"Data Berhasil Dihapus!"})}})})},onCloseImport(){this.showImport=!1,this.importFile=null}}},re={class:"content"},ne={class:"content-heading d-flex justify-content-between align-items-center"},ie=r("span",null,"Satuan Produk",-1),me={class:"space-x-1"},de=r("i",{class:"fa fa-plus me-1"},null,-1),ue={class:"block block-rounded"},pe={class:"block-content py-3"},ce=r("i",{class:"fa fa-search"},null,-1),fe={class:"block-content p-0"},_e=r("i",{class:"fa fa-angle-down ms-1"},null,-1),he=r("i",{class:"si fa-fw si-note"},null,-1),ge=r("i",{class:"si fa-fw si-trash"},null,-1),be={key:0,class:"block-content py-2"},we={class:"mb-0"},ke={class:"text-end"},ye=r("i",{class:"si si-close me-1"},null,-1),ve=r("i",{class:"si si-check me-1"},null,-1);function Ce(s,t,n,De,o,i){const p=P,c=G,k=J,h=Q,g=X,y=W,m=Z,v=Y,F=q,M=H,B=$,T=ee,f=te,U=D("unit-select"),K=oe,I=ae,N=D("base-layout"),C=le;return d(),_(N,null,{default:a(()=>[r("div",re,[r("div",ne,[ie,r("div",me,[s.$page.props.user.level!="kasir 2"?(d(),_(p,{key:0,onClick:t[0]||(t[0]=l=>o.showForm=!0),type:"primary"},{default:a(()=>[de,u(" Tambah ")]),_:1})):b("",!0)])]),r("div",ue,[r("div",pe,[e(y,{justify:"space-between"},{default:a(()=>[e(h,{span:12},{default:a(()=>[e(k,{modelValue:o.limit,"onUpdate:modelValue":t[1]||(t[1]=l=>o.limit=l),placeholder:"Pilih",style:{width:"115px"},onChange:t[2]||(t[2]=l=>i.fetchData(1))},{default:a(()=>[e(c,{label:"25",value:"25"}),e(c,{label:"50",value:"50"}),e(c,{label:"100",value:"100"})]),_:1},8,["modelValue"])]),_:1}),e(h,{span:8},{default:a(()=>[e(g,{modelValue:o.searchKeyword,"onUpdate:modelValue":t[4]||(t[4]=l=>o.searchKeyword=l),placeholder:"Masukan kata kunci",class:"input-with-select",clearable:""},{append:a(()=>[e(p,{onClick:t[3]||(t[3]=l=>i.fetchData(1))},{default:a(()=>[ce]),_:1})]),_:1},8,["modelValue"])]),_:1})]),_:1})]),r("div",fe,[E((d(),_(B,{data:o.data,class:"table-click",style:{width:"100%"},onSortChange:i.sortChange},{default:a(()=>[e(m,{type:"index",index:i.indexMethod,width:"100"},null,8,["index"]),e(m,{prop:"name",label:"Nama Satuan",sortable:""}),e(m,{prop:"code",label:"Kode",sortable:""}),e(m,{prop:"base.name",label:"Dasar Satuan",sortable:""}),e(m,{prop:"operator",label:"Operator",sortable:""}),e(m,{prop:"operator_value",label:"Nilai Operator",sortable:""}),s.$page.props.user.level!="kasir 2"?(d(),_(m,{key:0,label:"Aksi",align:"center",width:"180"},{default:a(l=>[e(M,{"popper-class":"dropdown-action",trigger:"click"},{dropdown:a(()=>[e(F,null,{default:a(()=>[e(v,{class:"d-flex align-items-center justify-content-between space-x-1",onClick:V(j=>i.edit(l.row),["prevent"])},{default:a(()=>[u(" Ubah "),he]),_:2},1032,["onClick"]),e(v,{class:"d-flex align-items-center justify-content-between space-x-1",onClick:V(j=>i.hapus(l.row.id),["prevent"])},{default:a(()=>[u(" Hapus "),ge]),_:2},1032,["onClick"])]),_:2},1024)]),default:a(()=>[e(p,{type:"primary"},{default:a(()=>[u(" Aksi "),_e]),_:1})]),_:2},1024)]),_:1})):b("",!0)]),_:1},8,["data","onSortChange"])),[[C,o.isLoading]])]),o.isLoading==!1?(d(),x("div",be,[e(y,{justify:"space-between"},{default:a(()=>[e(h,{lg:12},{default:a(()=>[r("p",we,"Menampilkan "+w(o.from)+" sampai "+w(o.to)+" dari "+w(o.total),1)]),_:1}),e(h,{lg:12,class:"text-end"},{default:a(()=>[e(T,{class:"float-end",background:"",layout:"prev, pager, next","page-size":o.pageSize,total:o.total,"current-page":o.page,onCurrentChange:i.fetchData},null,8,["page-size","total","current-page","onCurrentChange"])]),_:1})]),_:1})])):b("",!0)])]),e(I,{modelValue:o.showForm,"onUpdate:modelValue":t[10]||(t[10]=l=>o.showForm=l),title:o.formTitle,width:"30%",class:"rounded-2","before-close":i.onCloseForm,"close-on-click-modal":!1},{default:a(()=>[E((d(),_(K,{model:o.form,"label-position":"top"},{default:a(()=>[e(f,{label:"Nama Satuan",error:n.errors.name},{default:a(()=>[e(g,{modelValue:o.form.name,"onUpdate:modelValue":t[5]||(t[5]=l=>o.form.name=l)},null,8,["modelValue"])]),_:1},8,["error"]),e(f,{label:"Kode Satuan"},{default:a(()=>[e(g,{modelValue:o.form.code,"onUpdate:modelValue":t[6]||(t[6]=l=>o.form.code=l)},null,8,["modelValue"])]),_:1}),e(f,{label:"Dasar Satuan"},{default:a(()=>[e(U,{modelValue:o.form.base_unit_id,"onUpdate:modelValue":t[7]||(t[7]=l=>o.form.base_unit_id=l)},null,8,["modelValue"])]),_:1}),o.form.base_unit_id?(d(),x(A,{key:0},[e(f,{label:"Operator",error:n.errors.operator},{default:a(()=>[e(k,{modelValue:o.form.operator,"onUpdate:modelValue":t[8]||(t[8]=l=>o.form.operator=l),class:"w-100",placeholder:"Pilih Operator"},{default:a(()=>[e(c,{key:"*",label:"Dikali (*)",value:"*"}),e(c,{key:"/",label:"Dibagi (/)",value:"/"})]),_:1},8,["modelValue"])]),_:1},8,["error"]),e(f,{label:"Nilai Operator",error:n.errors.operator_value},{default:a(()=>[e(g,{modelValue:o.form.operator_value,"onUpdate:modelValue":t[9]||(t[9]=l=>o.form.operator_value=l)},null,8,["modelValue"])]),_:1},8,["error"])],64)):b("",!0),r("div",ke,[e(p,{onClick:i.onCloseForm},{default:a(()=>[ye,u(" Batal ")]),_:1},8,["onClick"]),e(p,{type:"primary",onClick:i.submit},{default:a(()=>[ve,u(" Simpan ")]),_:1},8,["onClick"])])]),_:1},8,["model"])),[[C,o.loadingForm]])]),_:1},8,["modelValue","title","before-close"])]),_:1})}const We=z(se,[["render",Ce]]);export{We as default};