var Y=(e,t,s)=>new Promise((r,n)=>{var l=f=>{try{h(s.next(f))}catch(b){n(b)}},x=f=>{try{h(s.throw(f))}catch(b){n(b)}},h=f=>f.done?r(f.value):Promise.resolve(f.value).then(l,x);h((s=s.apply(e,t)).next())});import{d as H,q as g,R as z,c7 as ue,X as _e,o as c,h as p,c8 as de,c9 as ee,ca as ve,cb as pe,a as me,cc as ge,cd as fe,ce as he,cf as ye,al as Ce,a5 as be,y as ke,r as L,c as I,a9 as m,z as d,e as O,f as o,w as $,F as M,A as F,t as E,p as te,cg as Oe,ch as Se,H as Ee,I as Ie,u as xe,a3 as De,aH as Le,a1 as w,bI as Te,aY as Ae,ad as we}from"./index.js";import{C as Ne}from"./index-5923bd3c.js";import{m as Re,l as Pe,u as $e,n as j,p as ze,q as Me,r as J,k as ne,s as Fe,P as N,v as R}from"./useSync.hook-445a7635.js";import{i as ae}from"./icon-4abed280.js";const Ue={class:"list-img",alt:"\u56FE\u8868\u56FE\u7247"},X=H({__name:"index",props:{chartConfig:{type:Object,required:!0}},setup(e){const t=e,s=g(""),r=()=>Y(this,null,function*(){s.value=yield Re(t.chartConfig)});return z(()=>t.chartConfig.key,()=>r(),{immediate:!0}),(n,l)=>{const x=ue("lazy");return _e((c(),p("img",Ue,null,512)),[[x,s.value]])}}});function Be(e){var t=e==null?0:e.length;return t?e[t-1]:void 0}var He=Be;function Ke(e,t,s){var r=-1,n=e.length;t<0&&(t=-t>n?0:n+t),s=s>n?n:s,s<0&&(s+=n),n=t>s?0:s-t>>>0,t>>>=0;for(var l=Array(n);++r<n;)l[r]=e[r+t];return l}var Ge=Ke,Ve=de,qe=Ge;function Ye(e,t){return t.length<2?e:Ve(e,qe(t,0,-1))}var je=Ye,Je=ee,Xe=He,Qe=je,We=ve;function Ze(e,t){return t=Je(t,e),e=Qe(e,t),e==null||delete e[We(Xe(t))]}var et=Ze,tt=pe;function nt(e){return tt(e)?void 0:e}var at=nt,st=me,ot=ye,lt=et,ct=ee,it=ge,rt=at,ut=fe,_t=he,dt=1,vt=2,pt=4,mt=ut(function(e,t){var s={};if(e==null)return s;var r=!1;t=st(t,function(l){return l=ct(l,e),r||(r=l.length>1),l}),it(e,_t(e),s),r&&(s=ot(s,dt|vt|pt,rt));for(var n=t.length;n--;)lt(s,t[n]);return s}),gt=mt;const ft=e=>(Ee("data-v-4c2515b6"),e=e(),Ie(),e),ht={class:"go-chart-common"},yt={key:0,class:"charts"},Ct={class:"tree-top"},bt=ft(()=>d("span",null,"\u6240\u6709\u5206\u7EC4",-1)),kt={class:"tree-body"},Ot={class:"chapter chapter-first"},St=["title"],Et={class:"label-count"},It=["onDragstart"],xt=["title"],Dt={key:1,class:"chapter chapter-last"},Lt=["title"],Tt={class:"label-count"},At=["onDragstart"],wt=["title"],Nt={key:1,class:"no-charts"},Rt={class:"chart-content-list"},Pt=H({__name:"index",props:{selectOptions:{type:Object,default:()=>{}},menu:{type:String,default:()=>{}}},setup(e){const t=Pe(()=>Ce(()=>import("./index-3aca9379.js"),["static/js/index-3aca9379.js","static/css/index-6b9ef97d.css","static/js/index-a1ed4785.js","static/css/index-0905aa8c.css","static/js/index.js","static/css/index-d2bdc124.css","static/js/icon-4abed280.js","static/js/useSync.hook-445a7635.js","static/css/useSync.hook-4ce673a6.css","static/js/plugin-eb434dff.js","static/js/tables_list-180d3f1b.js","static/js/index-5923bd3c.js","static/css/index-2c7157c7.css"])),{ChevronUpOutlineIcon:s,ChevronDownOutlineIcon:r}=ae.ionicons5,n=$e(),l=e,x=g(n.getDimensions),h=g(n.getDimension),f=g(n.getScopeList),b=g(n.getScope);z(()=>n.getDimensions,()=>{x.value=n.getDimensions,h.value=n.getDimension}),z(()=>n.getScopeList,()=>{f.value=n.getScopeList,b.value=n.getScope});const T=be(()=>{const i=n.getTreeData,{chart:a,pivot:y,metric:D}=i;return a&&l.menu=="Charts"?a[h.value]:y&&l.menu=="Tables"?y[h.value]:D&&l.menu=="Metrics"?D[b.value]:[]}),A=g(!1),k=g([]),se=()=>{A.value=!A.value;for(const i in T.value){k.value[T.value[i].id]=A.value;for(const a in T.value[i].child)k.value[T.value[i].child[a].id]=A.value}},K=i=>{k.value[i]=!k.value[i]};let _=ke({menuOptions:[],selectOptions:{},categorys:{all:[]},categoryNames:{all:"\u6240\u6709"},categorysNum:0,saveSelectOptions:{}});const U=g(),oe=i=>{for(const a in i){_.selectOptions=i[a];break}};z(()=>l.selectOptions,i=>{if(_.categorysNum=0,!!i){i.list.forEach(a=>{const y=_.categorys[a.category];_.categorys[a.category]=y&&y.length?[...y,a]:[a],_.categoryNames[a.category]=a.categoryName,_.categorys.all.push(a)});for(const a in _.categorys)_.categorysNum+=1,_.menuOptions.push({key:a,label:_.categoryNames[a]});oe(_.categorys),U.value=_.menuOptions[0].key}},{immediate:!0});const le=i=>{_.selectOptions=_.categorys[i]},G=(i,a)=>{j(a.chartKey,ze(a)),j(a.conKey,Me(a)),i.dataTransfer.setData(Oe.DRAG_KEY,Se(gt(a,["image"]))),n.setEditCanvas(J.IS_CREATE,!0)},V=()=>{n.setEditCanvas(J.IS_CREATE,!1)};return(i,a)=>{const y=L("n-select"),D=L("n-icon"),q=L("n-scrollbar"),ce=L("n-menu");return c(),p("div",ht,[e.menu=="Charts"||e.menu=="Tables"||e.menu=="Metrics"?(c(),p("div",yt,[e.menu=="Charts"||e.menu=="Tables"?(c(),I(y,{key:0,class:"dimension-btn",value:h.value,"onUpdate:value":a[0]||(a[0]=v=>h.value=v),options:x.value},null,8,["value","options"])):m("",!0),e.menu=="Metrics"?(c(),I(y,{key:1,class:"dimension-btn",value:b.value,"onUpdate:value":a[1]||(a[1]=v=>b.value=v),options:f.value},null,8,["value","options"])):m("",!0),d("div",Ct,[bt,O(D,{size:"16",component:A.value?o(s):o(r),onClick:se},null,8,["component"])]),d("div",kt,[O(q,null,{default:$(()=>[(c(!0),p(M,null,F(T.value,(v,Xt)=>(c(),p("div",{key:v.id,class:"tree-item"},[d("div",null,[d("div",Ot,[O(D,{size:"16",component:k.value[v.id]?o(s):o(r),onClick:u=>K(v.id)},null,8,["component","onClick"]),d("label",{title:v.title,class:"text-nowrap label-title"},E(v.title),9,St),d("label",Et,E(v.count),1)]),v.child&&k.value[v.id]?(c(!0),p(M,{key:0},F(v.child,u=>(c(),p("div",{class:"tree-child",key:u.id},[u.type!="chapter"?(c(),p("div",{key:0,class:"item-box",draggable:"true",onDragstart:C=>G(C,u.chartConfig),onDragend:V},[u.chartConfig.image?(c(),I(o(X),{key:0,class:"list-img",chartConfig:u.chartConfig},null,8,["chartConfig"])):m("",!0),d("span",{title:u.title},E(u.title),9,xt)],40,It)):m("",!0),u.type=="chapter"?(c(),p("div",Dt,[O(D,{size:"16",component:k.value[u.id]?o(s):o(r),onClick:C=>K(u.id)},null,8,["component","onClick"]),d("label",{title:u.title,class:"text-nowrap"},E(u.title),9,Lt),d("label",Tt,E(u.child.length),1)])):m("",!0),u.child&&k.value[u.id]?(c(!0),p(M,{key:2},F(u.child,(C,ie)=>(c(),p("div",{class:"tree-child",key:ie},[C.type!="chapter"?(c(),p("div",{key:0,class:"item-box",draggable:"true",onDragstart:re=>G(re,C.chartConfig),onDragend:V},[C.chartConfig.image?(c(),I(o(X),{key:0,class:"list-img",chartConfig:C.chartConfig},null,8,["chartConfig"])):m("",!0),d("p",{title:C.title,class:"text-nowrap"},E(C.title),9,wt)],40,At)):m("",!0)]))),128)):m("",!0)]))),128)):m("",!0)])]))),128))]),_:1})])])):m("",!0),e.menu!="Charts"&&e.menu!="Tables"&&e.menu!="Metrics"?(c(),p("div",Nt,[O(ce,{class:"chart-menu-width",value:U.value,"onUpdate:value":[a[2]||(a[2]=v=>U.value=v),le],options:o(_).menuOptions,"icon-size":16,indent:18},null,8,["value","options"]),d("div",Rt,[O(q,null,{default:$(()=>[O(o(t),{menuOptions:o(_).selectOptions},null,8,["menuOptions"])]),_:1})])])):m("",!0)])}}});var $t=te(Pt,[["__scopeId","data-v-4c2515b6"]]);const zt=xe(),Mt=g(zt.getAppTheme);ne();const{getCharts:Ft}=De(ne()),Ut=Le({id:"usePackagesStore",state:()=>({packagesList:Object.freeze(Fe)}),getters:{getPackagesList(){return this.packagesList}}}),{TableSplitIcon:Bt,RoadmapIcon:Ht,SpellCheckIcon:Kt,GraphicalDataFlowIcon:Q}=ae.carbon,{getPackagesList:W}=Ut(),S=[],Z={[N.CHARTS]:{icon:w(Ht),label:R.CHARTS},[N.INFORMATIONS]:{icon:w(Kt),label:R.INFORMATIONS},[N.TABLES]:{icon:w(Bt),label:R.TABLES},[N.DECORATES]:{icon:w(Q),label:R.DECORATES},[N.METRICS]:{icon:w(Q),label:R.METRICS}},Gt=()=>{for(const e in W)S.push({key:e,icon:Z[e].icon,label:Z[e].label,list:W[e]})};Gt();S[0].key;const P=g(S[0].key),B=g(S[0]),Vt=e=>{for(const t in S)S[t].key==e&&(B.value=S[t])};const qt={class:"menu-width-box"},Yt={class:"menu-component-box"},jt=H({__name:"index",setup(e){return Te(t=>({"7563b37f":o(Mt)})),(t,s)=>{const r=L("n-tab-pane"),n=L("n-tabs");return c(),I(o(Ne),{class:we(["go-content-charts",{scoped:!o(Ft)}]),backIcon:!1},{default:$(()=>[d("aside",null,[d("div",qt,[O(n,{value:o(P),"onUpdate:value":[s[0]||(s[0]=l=>Ae(P)?P.value=l:null),o(Vt)],class:"tabs-box",size:"small",type:"segment"},{default:$(()=>[(c(!0),p(M,null,F(o(S),l=>(c(),I(r,{key:l.key,name:l.key,size:"small","display-directive":"show:lazy"},{tab:$(()=>[d("span",null,E(l.label),1)]),_:2},1032,["name"]))),128))]),_:1},8,["value","onUpdate:value"]),d("div",Yt,[o(B)?(c(),I(o($t),{selectOptions:o(B),menu:o(P),key:o(P)},null,8,["selectOptions","menu"])):m("",!0)])])])]),_:1},8,["class"])}}});var Jt=te(jt,[["__scopeId","data-v-7ab8059c"]]),nn=Object.freeze(Object.defineProperty({__proto__:null,default:Jt},Symbol.toStringTag,{value:"Module"}));export{X as _,nn as i,gt as o};
