var v=(_,a,s)=>new Promise((i,o)=>{var p=t=>{try{r(s.next(t))}catch(e){o(e)}},m=t=>{try{r(s.throw(t))}catch(e){o(e)}},r=t=>t.done?i(t.value):Promise.resolve(t.value).then(p,m);r((s=s.apply(_,a)).next())});import{M as F}from"./index-a1ed4785.js";import{_ as L,o as A}from"./index-88abecac.js";import{u as I,k as K,w as E,n as l,p as b,q as D,r as S,x as O}from"./useSync.hook-445a7635.js";import{p as R,d as M,q as N,a5 as H,R as V,ak as $,r as k,o as h,h as f,z as d,F as q,A as z,e as n,f as g,w as u,m as w,t as B,ad as U,cg as G,ch as J}from"./index.js";import{a as Y,b as j,l as P}from"./plugin-eb434dff.js";import"./icon-4abed280.js";import"./index-5923bd3c.js";import"./tables_list-180d3f1b.js";const Q={class:"go-content-charts-item-animation-patch"},W=["onDragstart","onDblclick"],X={class:"list-header"},Z={class:"list-center go-flex-center go-transition"},tt={class:"list-bottom"},et=M({__name:"index",props:{menuOptions:{type:Array,default:()=>[]}},setup(_){const a=I(),s=K(),i=N(),o=H(()=>s.getChartType),p=(t,e)=>{l(e.chartKey,b(e)),l(e.conKey,D(e)),t.dataTransfer.setData(G.DRAG_KEY,J(A(e,["image"]))),a.setEditCanvas(S.IS_CREATE,!0)},m=()=>{a.setEditCanvas(S.IS_CREATE,!1)},r=t=>v(this,null,function*(){try{Y(),l(t.chartKey,b(t)),l(t.conKey,D(t));let e=yield O(t);a.addComponentList(e,!1,!0),a.setTargetSelectChart(e.id),j()}catch(e){P(),window.$message.warning("\u56FE\u8868\u6B63\u5728\u7814\u53D1\u4E2D, \u656C\u8BF7\u671F\u5F85...")}});return V(()=>o.value,t=>{t===E.DOUBLE&&$(()=>{i.value.classList.add("miniAnimation")})}),(t,e)=>{const C=k("n-ellipsis"),x=k("n-text");return h(),f("div",Q,[d("div",{ref_key:"contentChartsItemBoxRef",ref:i,class:U(["go-content-charts-item-box",[o.value===g(E).DOUBLE?"double":"single"]])},[(h(!0),f(q,null,z(_.menuOptions,(c,T)=>(h(),f("div",{class:"item-box",key:T,draggable:"",onDragstart:y=>p(y,c),onDragend:m,onDblclick:y=>r(c)},[d("div",X,[n(g(F),{class:"list-header-control-btn",mini:!0,disabled:!0}),n(x,{class:"list-header-text",depth:"3"},{default:u(()=>[n(C,null,{default:u(()=>[w(B(c.title),1)]),_:2},1024)]),_:2},1024)]),d("div",Z,[n(g(L),{class:"list-img",chartConfig:c},null,8,["chartConfig"])]),d("div",tt,[n(x,{class:"list-bottom-text",depth:"3"},{default:u(()=>[n(C,{style:{"max-width":"90%"}},{default:u(()=>[w(B(c.title),1)]),_:2},1024)]),_:2},1024)])],40,W))),128))],2)])}}});var ut=R(et,[["__scopeId","data-v-3bb6960e"]]);export{ut as default};
