import{b3 as ye,n as L,aI as Ce,V as Ee,R as he,E as Be,bU as we,aF as Me,aG as Te,aB as Se,f as C,r as M,a as ke,aC as Y,ak as x,w as _,an as Ie,b9 as Ae,d as Re,A as T,j as c,k as g,l as p,a3 as U,v as E,p as m,H as r,W as ee,a2 as z,s as H,as as P,a0 as S,q as R,bt as D,G as Le,$ as K,av as q,aw as Oe,az as Ve,ab as le,aK as re,aM as ie,c1 as ne,au as $e,bq as ze,aL as se,aN as oe}from"./id.js";import{E as Pe}from"./index21.js";import{E as De,u as Fe,a as Ne,b as Ue}from"./index28.js";const He='a[href],button:not([disabled]),button:not([hidden]),:not([tabindex="-1"]),input:not([disabled]),input:not([type="hidden"]),select:not([disabled]),textarea:not([disabled])',Ke=e=>getComputedStyle(e).position==="fixed"?!1:e.offsetParent!==null,te=e=>Array.from(e.querySelectorAll(He)).filter(n=>qe(n)&&Ke(n)),qe=e=>{if(e.tabIndex>0||e.tabIndex===0&&e.getAttribute("tabIndex")!==null)return!0;if(e.disabled)return!1;switch(e.nodeName){case"A":return!!e.href&&e.rel!=="ignore";case"INPUT":return!(e.type==="hidden"||e.type==="file");case"BUTTON":case"SELECT":case"TEXTAREA":return!0;default:return!1}},je=e=>["",...ye].includes(e),j="_trap-focus-children",h=[],ae=e=>{if(h.length===0)return;const n=h[h.length-1][j];if(n.length>0&&e.code===Ce.tab){if(n.length===1){e.preventDefault(),document.activeElement!==n[0]&&n[0].focus();return}const a=e.shiftKey,i=e.target===n[0],l=e.target===n[n.length-1];i&&a&&(e.preventDefault(),n[n.length-1].focus()),l&&!a&&(e.preventDefault(),n[0].focus())}},Ge={beforeMount(e){e[j]=te(e),h.push(e),h.length<=1&&document.addEventListener("keydown",ae)},updated(e){L(()=>{e[j]=te(e)})},unmounted(){h.shift(),h.length===0&&document.removeEventListener("keydown",ae)}},Xe=he({name:"ElMessageBox",directives:{TrapFocus:Ge},components:{ElButton:Be,ElFocusTrap:we,ElInput:Pe,ElOverlay:De,ElIcon:Me,...Te},inheritAttrs:!1,props:{buttonSize:{type:String,validator:je},modal:{type:Boolean,default:!0},lockScroll:{type:Boolean,default:!0},showClose:{type:Boolean,default:!0},closeOnClickModal:{type:Boolean,default:!0},closeOnPressEscape:{type:Boolean,default:!0},closeOnHashChange:{type:Boolean,default:!0},center:Boolean,draggable:Boolean,overflow:Boolean,roundButton:{default:!1,type:Boolean},container:{type:String,default:"body"},boxType:{type:String,default:""}},emits:["vanish","action"],setup(e,{emit:n}){const{locale:a,zIndex:i,ns:l,size:o}=Se("message-box",C(()=>e.buttonSize)),{t:d}=a,{nextZIndex:f}=i,y=M(!1),s=ke({autofocus:!0,beforeClose:null,callback:null,cancelButtonText:"",cancelButtonClass:"",confirmButtonText:"",confirmButtonClass:"",customClass:"",customStyle:{},dangerouslyUseHTMLString:!1,distinguishCancelAndClose:!1,icon:"",inputPattern:null,inputPlaceholder:"",inputType:"text",inputValue:null,inputValidator:null,inputErrorMessage:"",message:null,modalFade:!0,modalClass:"",showCancelButton:!1,showConfirmButton:!0,type:"",title:void 0,showInput:!1,action:"",confirmButtonLoading:!1,cancelButtonLoading:!1,confirmButtonDisabled:!1,editorErrorMessage:"",validateError:!1,zIndex:f()}),F=C(()=>{const t=s.type;return{[l.bm("icon",t)]:t&&Y[t]}}),N=x(),u=x(),ue=C(()=>s.icon||Y[s.type]||""),de=C(()=>!!s.message),B=M(),G=M(),I=M(),V=M(),X=M(),ce=C(()=>s.confirmButtonClass);_(()=>s.inputValue,async t=>{await L(),e.boxType==="prompt"&&t!==null&&Z()},{immediate:!0}),_(()=>y.value,t=>{var v,w;t&&(e.boxType!=="prompt"&&(s.autofocus?I.value=(w=(v=X.value)==null?void 0:v.$el)!=null?w:B.value:I.value=B.value),s.zIndex=f()),e.boxType==="prompt"&&(t?L().then(()=>{var Q;V.value&&V.value.$el&&(s.autofocus?I.value=(Q=ge())!=null?Q:B.value:I.value=B.value)}):(s.editorErrorMessage="",s.validateError=!1))});const fe=C(()=>e.draggable),pe=C(()=>e.overflow);Fe(B,G,fe,pe),Ie(async()=>{await L(),e.closeOnHashChange&&window.addEventListener("hashchange",A)}),Ae(()=>{e.closeOnHashChange&&window.removeEventListener("hashchange",A)});function A(){y.value&&(y.value=!1,L(()=>{s.action&&n("action",s.action)}))}const W=()=>{e.closeOnClickModal&&$(s.distinguishCancelAndClose?"close":"cancel")},me=Ue(W),ve=t=>{if(s.inputType!=="textarea")return t.preventDefault(),$("confirm")},$=t=>{var v;e.boxType==="prompt"&&t==="confirm"&&!Z()||(s.action=t,s.beforeClose?(v=s.beforeClose)==null||v.call(s,t,s,A):A())},Z=()=>{if(e.boxType==="prompt"){const t=s.inputPattern;if(t&&!t.test(s.inputValue||""))return s.editorErrorMessage=s.inputErrorMessage||d("el.messagebox.error"),s.validateError=!0,!1;const v=s.inputValidator;if(typeof v=="function"){const w=v(s.inputValue);if(w===!1)return s.editorErrorMessage=s.inputErrorMessage||d("el.messagebox.error"),s.validateError=!0,!1;if(typeof w=="string")return s.editorErrorMessage=w,s.validateError=!0,!1}}return s.editorErrorMessage="",s.validateError=!1,!0},ge=()=>{const t=V.value.$refs;return t.input||t.textarea},J=()=>{$("close")},be=()=>{e.closeOnPressEscape&&J()};return e.lockScroll&&Ne(y),{...Re(s),ns:l,overlayEvent:me,visible:y,hasMessage:de,typeClass:F,contentId:N,inputId:u,btnSize:o,iconComponent:ue,confirmButtonClasses:ce,rootRef:B,focusStartRef:I,headerRef:G,inputRef:V,confirmRef:X,doClose:A,handleClose:J,onCloseRequested:be,handleWrapperClick:W,handleInputEnter:ve,handleAction:$,t:d}}}),We=["aria-label","aria-describedby"],Ze=["aria-label"],Je=["id"];function Qe(e,n,a,i,l,o){const d=T("el-icon"),f=T("close"),y=T("el-input"),s=T("el-button"),F=T("el-focus-trap"),N=T("el-overlay");return c(),g(Oe,{name:"fade-in-linear",onAfterLeave:n[11]||(n[11]=u=>e.$emit("vanish")),persisted:""},{default:p(()=>[U(E(N,{"z-index":e.zIndex,"overlay-class":[e.ns.is("message-box"),e.modalClass],mask:e.modal},{default:p(()=>[m("div",{role:"dialog","aria-label":e.title,"aria-modal":"true","aria-describedby":e.showInput?void 0:e.contentId,class:r(`${e.ns.namespace.value}-overlay-message-box`),onClick:n[8]||(n[8]=(...u)=>e.overlayEvent.onClick&&e.overlayEvent.onClick(...u)),onMousedown:n[9]||(n[9]=(...u)=>e.overlayEvent.onMousedown&&e.overlayEvent.onMousedown(...u)),onMouseup:n[10]||(n[10]=(...u)=>e.overlayEvent.onMouseup&&e.overlayEvent.onMouseup(...u))},[E(F,{loop:"",trapped:e.visible,"focus-trap-el":e.rootRef,"focus-start-el":e.focusStartRef,onReleaseRequested:e.onCloseRequested},{default:p(()=>[m("div",{ref:"rootRef",class:r([e.ns.b(),e.customClass,e.ns.is("draggable",e.draggable),{[e.ns.m("center")]:e.center}]),style:ee(e.customStyle),tabindex:"-1",onClick:n[7]||(n[7]=z(()=>{},["stop"]))},[e.title!==null&&e.title!==void 0?(c(),H("div",{key:0,ref:"headerRef",class:r([e.ns.e("header"),{"show-close":e.showClose}])},[m("div",{class:r(e.ns.e("title"))},[e.iconComponent&&e.center?(c(),g(d,{key:0,class:r([e.ns.e("status"),e.typeClass])},{default:p(()=>[(c(),g(P(e.iconComponent)))]),_:1},8,["class"])):S("v-if",!0),m("span",null,R(e.title),1)],2),e.showClose?(c(),H("button",{key:0,type:"button",class:r(e.ns.e("headerbtn")),"aria-label":e.t("el.messagebox.close"),onClick:n[0]||(n[0]=u=>e.handleAction(e.distinguishCancelAndClose?"close":"cancel")),onKeydown:n[1]||(n[1]=D(z(u=>e.handleAction(e.distinguishCancelAndClose?"close":"cancel"),["prevent"]),["enter"]))},[E(d,{class:r(e.ns.e("close"))},{default:p(()=>[E(f)]),_:1},8,["class"])],42,Ze)):S("v-if",!0)],2)):S("v-if",!0),m("div",{id:e.contentId,class:r(e.ns.e("content"))},[m("div",{class:r(e.ns.e("container"))},[e.iconComponent&&!e.center&&e.hasMessage?(c(),g(d,{key:0,class:r([e.ns.e("status"),e.typeClass])},{default:p(()=>[(c(),g(P(e.iconComponent)))]),_:1},8,["class"])):S("v-if",!0),e.hasMessage?(c(),H("div",{key:1,class:r(e.ns.e("message"))},[Le(e.$slots,"default",{},()=>[e.dangerouslyUseHTMLString?(c(),g(P(e.showInput?"label":"p"),{key:1,for:e.showInput?e.inputId:void 0,innerHTML:e.message},null,8,["for","innerHTML"])):(c(),g(P(e.showInput?"label":"p"),{key:0,for:e.showInput?e.inputId:void 0},{default:p(()=>[K(R(e.dangerouslyUseHTMLString?"":e.message),1)]),_:1},8,["for"]))])],2)):S("v-if",!0)],2),U(m("div",{class:r(e.ns.e("input"))},[E(y,{id:e.inputId,ref:"inputRef",modelValue:e.inputValue,"onUpdate:modelValue":n[2]||(n[2]=u=>e.inputValue=u),type:e.inputType,placeholder:e.inputPlaceholder,"aria-invalid":e.validateError,class:r({invalid:e.validateError}),onKeydown:D(e.handleInputEnter,["enter"])},null,8,["id","modelValue","type","placeholder","aria-invalid","class","onKeydown"]),m("div",{class:r(e.ns.e("errormsg")),style:ee({visibility:e.editorErrorMessage?"visible":"hidden"})},R(e.editorErrorMessage),7)],2),[[q,e.showInput]])],10,Je),m("div",{class:r(e.ns.e("btns"))},[e.showCancelButton?(c(),g(s,{key:0,loading:e.cancelButtonLoading,class:r([e.cancelButtonClass]),round:e.roundButton,size:e.btnSize,onClick:n[3]||(n[3]=u=>e.handleAction("cancel")),onKeydown:n[4]||(n[4]=D(z(u=>e.handleAction("cancel"),["prevent"]),["enter"]))},{default:p(()=>[K(R(e.cancelButtonText||e.t("el.messagebox.cancel")),1)]),_:1},8,["loading","class","round","size"])):S("v-if",!0),U(E(s,{ref:"confirmRef",type:"primary",loading:e.confirmButtonLoading,class:r([e.confirmButtonClasses]),round:e.roundButton,disabled:e.confirmButtonDisabled,size:e.btnSize,onClick:n[5]||(n[5]=u=>e.handleAction("confirm")),onKeydown:n[6]||(n[6]=D(z(u=>e.handleAction("confirm"),["prevent"]),["enter"]))},{default:p(()=>[K(R(e.confirmButtonText||e.t("el.messagebox.confirm")),1)]),_:1},8,["loading","class","round","disabled","size"]),[[q,e.showConfirmButton]])],2)],6)]),_:3},8,["trapped","focus-trap-el","focus-start-el","onReleaseRequested"])],42,We)]),_:3},8,["z-index","overlay-class","mask"]),[[q,e.visible]])]),_:3})}var Ye=Ee(Xe,[["render",Qe],["__file","index.vue"]]);const O=new Map,xe=e=>{let n=document.body;return e.appendTo&&(le(e.appendTo)&&(n=document.querySelector(e.appendTo)),oe(e.appendTo)&&(n=e.appendTo),oe(n)||(n=document.body)),n},_e=(e,n,a=null)=>{const i=E(Ye,e,se(e.message)||re(e.message)?{default:se(e.message)?e.message:()=>e.message}:null);return i.appContext=a,ie(i,n),xe(e).appendChild(n.firstElementChild),i.component},en=()=>document.createElement("div"),nn=(e,n)=>{const a=en();e.onVanish=()=>{ie(null,a),O.delete(l)},e.onAction=o=>{const d=O.get(l);let f;e.showInput?f={value:l.inputValue,action:o}:f=o,e.callback?e.callback(f,i.proxy):o==="cancel"||o==="close"?e.distinguishCancelAndClose&&o!=="cancel"?d.reject("close"):d.reject("cancel"):d.resolve(f)};const i=_e(e,a,n),l=i.proxy;for(const o in e)ne(e,o)&&!ne(l.$props,o)&&(l[o]=e[o]);return l.visible=!0,l};function k(e,n=null){if(!Ve)return Promise.reject();let a;return le(e)||re(e)?e={message:e}:a=e.callback,new Promise((i,l)=>{const o=nn(e,n??k._context);O.set(o,{options:e,callback:a,resolve:i,reject:l})})}const sn=["alert","confirm","prompt"],on={alert:{closeOnPressEscape:!1,closeOnClickModal:!1},confirm:{showCancelButton:!0},prompt:{showCancelButton:!0,showInput:!0}};sn.forEach(e=>{k[e]=tn(e)});function tn(e){return(n,a,i,l)=>{let o="";return $e(a)?(i=a,o=""):ze(a)?o="":o=a,k(Object.assign({title:o,message:n,type:"",...on[e]},i,{boxType:e}),l)}}k.close=()=>{O.forEach((e,n)=>{n.doClose()}),O.clear()};k._context=null;const b=k;b.install=e=>{b._context=e._context,e.config.globalProperties.$msgbox=b,e.config.globalProperties.$messageBox=b,e.config.globalProperties.$alert=b.alert,e.config.globalProperties.$confirm=b.confirm,e.config.globalProperties.$prompt=b.prompt};const un=b;export{un as E};
