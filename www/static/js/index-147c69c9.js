import{L as Q}from"./index-961888e8.js";import{b as U,d as I,aa as J,n as W,ae as E,af as $,r as n,o,E as _,w as e,f as u,u as p,c as R,q as X,g as s,t as H,Q as b,p as V,F as M,$ as P,j as k,Y as L,a8 as O,ag as z,a7 as w,ah as Z,ai as ee,e as x}from"./index-5427279b.js";import{i as N}from"./icon-d6196121.js";const ue=s("\u7CFB\u7EDF\u8BBE\u7F6E"),te=I({__name:"index",props:{modelShow:Boolean},emits:["update:modelShow"],setup(g,{emit:r}){const{HelpOutlineIcon:C,CloseIcon:A}=N.ionicons5,l=J(),h=W([{key:E.ASIDE_ALL_COLLAPSED,value:l.getAsideAllCollapsed,type:"switch",name:"\u83DC\u5355\u6298\u53E0",desc:"\u9996\u9875\u83DC\u5355\u6298\u53E0\u65F6\u9690\u85CF\u81F3\u754C\u9762\u5916"},{key:E.HIDE_PACKAGE_ONE_CATEGORY,value:l.getHidePackageOneCategory,type:"switch",name:"\u9690\u85CF\u5206\u7C7B",desc:"\u5DE5\u4F5C\u7A7A\u95F4\u8868\u5355\u5206\u7C7B\u53EA\u6709\u5355\u9879\u65F6\u9690\u85CF"},{key:E.CHANGE_LANG_RELOAD,value:l.getChangeLangReload,type:"switch",name:"\u5207\u6362\u8BED\u8A00",desc:"\u5207\u6362\u8BED\u8A00\u91CD\u65B0\u52A0\u8F7D\u9875\u9762",tip:"\u82E5\u9047\u5230\u90E8\u5206\u533A\u57DF\u8BED\u8A00\u5207\u6362\u5931\u8D25\uFF0C\u5219\u5EFA\u8BAE\u5F00\u542F"},{key:"divider1",type:"divider",name:"",desc:"",value:""},{key:E.CHART_TOOLS_STATUS_HIDE,value:l.getChartToolsStatusHide,type:"switch",name:"\u9690\u85CF\u5DE5\u5177\u680F",desc:"\u9F20\u6807\u79FB\u5165\u65F6\uFF0C\u4F1A\u5C55\u793A\u5207\u6362\u5230\u5C55\u5F00\u6A21\u5F0F"},{key:E.CHART_TOOLS_STATUS,value:l.getChartToolsStatus,type:"select",name:"\u5DE5\u5177\u680F\u5C55\u793A",desc:"\u5DE5\u4F5C\u7A7A\u95F4\u5DE5\u5177\u680F\u5C55\u793A\u65B9\u5F0F",options:[{label:"\u4FA7\u8FB9\u680F",value:$.ASIDE},{label:"\u5E95\u90E8 Dock",value:$.DOCK}]},{key:"divider0",type:"divider",name:"",desc:"",value:""},{key:E.CHART_MOVE_DISTANCE,value:l.getChartMoveDistance,type:"number",name:"\u79FB\u52A8\u8DDD\u79BB",min:1,step:1,suffix:"px",desc:"\u5DE5\u4F5C\u7A7A\u95F4\u65B9\u5411\u952E\u63A7\u5236\u79FB\u52A8\u8DDD\u79BB"},{key:E.CHART_ALIGN_RANGE,value:l.getChartAlignRange,type:"number",name:"\u5438\u9644\u8DDD\u79BB",min:10,step:2,suffix:"px",desc:"\u5DE5\u4F5C\u7A7A\u95F4\u79FB\u52A8\u56FE\u8868\u65F6\u7684\u5438\u9644\u8DDD\u79BB"}]),m=()=>{r("update:modelShow",!1)},f=(B,a)=>{l.setItem(a.key,a.value)};return(B,a)=>{const d=n("n-h3"),y=n("n-icon"),i=n("n-space"),D=n("n-divider"),F=n("n-text"),v=n("n-switch"),T=n("n-input-number"),S=n("n-select"),j=n("n-tooltip"),K=n("n-list-item"),Y=n("n-list"),q=n("n-modal");return o(),_(q,{show:g.modelShow,"onUpdate:show":a[0]||(a[0]=t=>P(modelShow)?modelShow.value=t:null),onAfterLeave:m},{default:e(()=>[u(Y,{bordered:"",class:"go-system-setting"},{header:e(()=>[u(i,{justify:"space-between"},{default:e(()=>[u(d,{class:"go-mb-0"},{default:e(()=>[ue]),_:1}),u(y,{size:"20",class:"go-cursor-pointer",onClick:m},{default:e(()=>[u(p(A))]),_:1})]),_:1})]),default:e(()=>[(o(!0),R(M,null,X(h,t=>(o(),_(K,{key:t.key},{default:e(()=>[t.type==="divider"?(o(),_(D,{key:0,style:{margin:"0"}})):(o(),_(i,{key:1,size:40},{default:e(()=>[u(i,null,{default:e(()=>[u(F,{class:"item-left"},{default:e(()=>[s(H(t.name),1)]),_:2},1024),t.type==="switch"?(o(),_(v,{key:0,value:t.value,"onUpdate:value":[c=>t.value=c,c=>f(c,t)],size:"small"},null,8,["value","onUpdate:value"])):t.type==="number"?(o(),_(T,{key:1,value:t.value,"onUpdate:value":[c=>t.value=c,c=>f(c,t)],class:"input-num-width",size:"small",step:t.step||null,suffix:t.suffix||null,min:t.min||0},null,8,["value","onUpdate:value","step","suffix","min"])):t.type==="select"?(o(),_(S,{key:2,class:"select-min-width",value:t.value,"onUpdate:value":[c=>t.value=c,c=>f(c,t)],size:"small",options:t.options},null,8,["value","onUpdate:value","options"])):b("",!0)]),_:2},1024),u(i,null,{default:e(()=>[u(F,{class:"item-right"},{default:e(()=>[s(H(t.desc),1)]),_:2},1024),t.tip?(o(),_(j,{key:0,trigger:"hover"},{trigger:e(()=>[u(y,{size:"21"},{default:e(()=>[u(p(C))]),_:1})]),default:e(()=>[V("span",null,H(t.tip),1)]),_:2},1024)):b("",!0)]),_:2},1024)]),_:2},1024))]),_:2},1024))),128))]),_:1})]),_:1},8,["show"])}}});var ne=U(te,[["__scopeId","data-v-5508f055"]]);const oe=s("\u5173\u4E8E\u6211\u4EEC"),se=s("\u7248\u6743\u58F0\u660E\uFF1A"),le=s(" GoView \u7248\u6743\u5C5E\u4E8E "),ae=s("https://gitee.com/MTrun/go-view"),_e=s(" \u9879\u76EE\u4F5C\u8005 "),de=s("\u534F\u8BAE\u5907\u6CE8\uFF1A"),ce=s(" \u8BF7\u9075\u5B88\u5F00\u6E90 MIT \u534F\u8BAE\uFF0C\u4EE5\u4E0A\u58F0\u660E "),re=s("\u4E0D\u53EF\u5220\u9664"),ie=s("\uFF0C\u5426\u5219\u89C6\u4F5C\u4FB5\u6743\u884C\u4E3A\uFF0C\u540E\u679C\u81EA\u8D1F\uFF01 "),pe=s("\u5546\u4E1A\u6388\u6743\uFF1A"),me=s(" \u82E5\u4E0D\u60F3\u4FDD\u7559\u7248\u6743\u58F0\u660E\uFF0C\u8BF7\u901A\u8FC7\u4ED3\u5E93/\u4EA4\u6D41\u7FA4 \u8054\u7CFB\u9879\u76EE\u4F5C\u8005\uFF0C\u8FDB\u884C\u6388\u6743 "),fe=I({__name:"index",props:{modelShow:Boolean},emits:["update:modelShow"],setup(g,{emit:r}){const{HelpOutlineIcon:C,CloseIcon:A}=N.ionicons5,l=()=>{r("update:modelShow",!1)};return(h,m)=>{const f=n("n-h3"),B=n("n-icon"),a=n("n-space"),d=n("n-text"),y=n("n-a"),i=n("n-list-item"),D=n("n-list"),F=n("n-modal");return o(),_(F,{show:g.modelShow,"onUpdate:show":m[0]||(m[0]=v=>P(modelShow)?modelShow.value=v:null),onAfterLeave:l},{default:e(()=>[u(D,{bordered:"",class:"go-system-info"},{header:e(()=>[u(a,{justify:"space-between"},{default:e(()=>[u(f,{class:"go-mb-0"},{default:e(()=>[oe]),_:1}),u(B,{size:"20",class:"go-cursor-pointer",onClick:l},{default:e(()=>[u(p(A))]),_:1})]),_:1})]),default:e(()=>[u(i,null,{default:e(()=>[u(a,{class:"go-my-2",size:20},{default:e(()=>[u(d,{class:"item-left"},{default:e(()=>[se]),_:1}),u(d,null,{default:e(()=>[le,u(y,{href:"https://gitee.com/MTrun/go-view",target:"_blank"},{default:e(()=>[ae]),_:1}),_e]),_:1})]),_:1})]),_:1}),u(i,null,{default:e(()=>[u(a,{class:"go-my-2",size:20},{default:e(()=>[u(d,{class:"item-left"},{default:e(()=>[de]),_:1}),u(d,null,{default:e(()=>[ce,u(d,{type:"error"},{default:e(()=>[re]),_:1}),ie]),_:1})]),_:1})]),_:1}),u(i,null,{default:e(()=>[u(a,{class:"go-mt-2",size:20},{default:e(()=>[u(d,{class:"item-left"},{default:e(()=>[pe]),_:1}),u(d,null,{default:e(()=>[me]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["show"])}}});var Fe=U(fe,[["__scopeId","data-v-d1898d18"]]),G="/static/png/person-3d7a1ea6.png";const ve={class:"user-info-box"},Ee=I({__name:"index",setup(g){const{ChatboxEllipsesIcon:r,PersonIcon:C,LogOutOutlineIcon:A,SettingsSharpIcon:l}=N.ionicons5,h=k(!1),m=k(!1),f=k(!1),a=k([{label:"\u6211\u7684\u4FE1\u606F",key:"info",type:"render",render:()=>w("div",{style:"display: flex; align-items: center; padding: 8px 12px;"},[w(z,{round:!0,style:"margin-right: 12px;",src:G}),w("div",null,[w("div",null,[w(Z,{depth:2},{default:()=>"\u5954\u8DD1\u7684\u9762\u6761"})])])])},{type:"divider",key:"d1"},{label:L("global.sys_set"),key:"sysSet",icon:O(l)},{label:L("global.contact"),key:"contact",icon:O(r)},{type:"divider",key:"d3"},{label:L("global.logout"),key:"logout",icon:O(A)}]),d=F=>{f.value=!0},y=()=>{m.value=!0},i=()=>{h.value=!0},D=F=>{switch(F){case"contact":i();break;case"sysSet":y();break;case"logout":ee();break}};return(F,v)=>{const T=n("n-dropdown");return o(),R(M,null,[u(T,{trigger:"hover",onSelect:D,"show-arrow":!0,options:a.value},{default:e(()=>[V("div",ve,[f.value?(o(),_(p(C),{key:0})):b("",!0),f.value?b("",!0):(o(),_(p(z),{key:1,round:"","object-fit":"cover",size:"medium",src:p(G),onError:d},null,8,["src"]))])]),_:1},8,["options"]),u(p(ne),{modelShow:m.value,"onUpdate:modelShow":v[0]||(v[0]=S=>m.value=S)},null,8,["modelShow"]),u(p(Fe),{modelShow:h.value,"onUpdate:modelShow":v[1]||(v[1]=S=>h.value=S)},null,8,["modelShow"])],64)}}});var he=U(Ee,[["__scopeId","data-v-1e8d9d16"]]);const Ae=I({__name:"index",setup(g){return(r,C)=>(o(),_(p(Q),null,{left:e(()=>[x(r.$slots,"left")]),center:e(()=>[x(r.$slots,"center")]),"ri-left":e(()=>[x(r.$slots,"ri-left")]),"ri-right":e(()=>[u(p(he)),x(r.$slots,"ri-right")]),_:3}))}});export{ne as G,Ae as _};
