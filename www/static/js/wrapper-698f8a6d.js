var te=Object.defineProperty,ne=Object.defineProperties;var oe=Object.getOwnPropertyDescriptors;var L=Object.getOwnPropertySymbols;var se=Object.prototype.hasOwnProperty,ie=Object.prototype.propertyIsEnumerable;var b=(t,e,n)=>e in t?te(t,e,{enumerable:!0,configurable:!0,writable:!0,value:n}):t[e]=n,u=(t,e)=>{for(var n in e||(e={}))se.call(e,n)&&b(t,n,e[n]);if(L)for(var n of L(e))ie.call(e,n)&&b(t,n,e[n]);return t},k=(t,e)=>ne(t,oe(e));var R=(t,e,n)=>new Promise((s,o)=>{var a=r=>{try{d(n.next(r))}catch(l){o(l)}},i=r=>{try{d(n.throw(r))}catch(l){o(l)}},d=r=>r.done?s(r.value):Promise.resolve(r.value).then(a,i);d((n=n.apply(t,e)).next())});import{g as H}from"./storage-c4cf734b.js";import{aM as S,aE as v,aN as re,c as x,d as C,o as h,e as w,s as D,ao as F,u as c,aO as B,V as y,aP as E,aQ as G,aR as M,G as _,aS as U,aT as V,Y as q,F as j,R as m,n as A,l as p,aU as g,ad as f,aV as ae,q as ce,g as I,U as O,aW as T,aX as de,S as le}from"./index-ed17d07a.js";import{c as ue,u as he,C as we}from"./chartEditStore-066de5d6.js";import{u as pe,f as ge}from"./index-6be04ae3.js";import"./plugin-15ab4596.js";import"./icon-c5f6d281.js";import"./table_scrollboard-3d700369.js";import"./GlobalSetting.vue_vue_type_style_index_0_scoped_true_lang-e23277d4.js";import"./useTargetData.hook-796669c2.js";const ve=(t,e,n,s)=>{const o=t,a=e,i={width:1,height:1},d=parseFloat((o/a).toFixed(5)),r=()=>{const ee=parseFloat((window.innerWidth/window.innerHeight).toFixed(5));n&&(ee>d?(i.width=parseFloat((window.innerHeight*d/o).toFixed(5)),i.height=parseFloat((window.innerHeight/a).toFixed(5)),n.style.transform=`scale(${i.width}, ${i.height})`):(i.height=parseFloat((window.innerWidth/d/a).toFixed(5)),i.width=parseFloat((window.innerWidth/o).toFixed(5)),n.style.transform=`scale(${i.width}, ${i.height})`),s&&s(i))},l=S(()=>{r()},200);return{calcRate:r,windowResize:()=>{window.addEventListener("resize",l)},unWindowResize:()=>{window.removeEventListener("resize",l)}}},fe=(t,e,n,s)=>{const o=t,a=e,i={width:1,height:1},d=parseFloat((o/a).toFixed(5)),r=()=>{n&&(i.height=parseFloat((window.innerWidth/d/a).toFixed(5)),i.width=parseFloat((window.innerWidth/o).toFixed(5)),n.style.transform=`scale(${i.width}, ${i.height})`,s&&s(i))},l=S(()=>{r()},200);return{calcRate:r,windowResize:()=>{window.addEventListener("resize",l)},unWindowResize:()=>{window.removeEventListener("resize",l)}}},ye=(t,e,n,s)=>{const o=t,a=e,i={height:1,width:1},d=parseFloat((o/a).toFixed(5)),r=()=>{n&&(i.width=parseFloat((window.innerHeight*d/o).toFixed(5)),i.height=parseFloat((window.innerHeight/a).toFixed(5)),n.style.transform=`scale(${i.width}, ${i.height})`,s&&s(i))},l=S(()=>{r()},200);return{calcRate:r,windowResize:()=>{window.addEventListener("resize",l)},unWindowResize:()=>{window.removeEventListener("resize",l)}}},_e=(t,e,n,s)=>{const o={width:1,height:1},a=()=>{n&&(o.width=parseFloat((window.innerWidth/t).toFixed(5)),o.height=parseFloat((window.innerHeight/e).toFixed(5)),n.style.transform=`scale(${o.width}, ${o.height})`,s&&s(o))},i=S(()=>{a()},200);return{calcRate:a,windowResize:()=>{window.addEventListener("resize",i)},unWindowResize:()=>{window.removeEventListener("resize",i)}}},K={},W={echarts:re},X=t=>{if(!t.events)return{};const e={};for(const o in t.events.baseEvent){const a=t.events.baseEvent[o];a&&(e[o]=me(a))}const n=t.events.advancedEvents||{},s={[v.VNODE_BEFORE_MOUNT](o){K[t.id]=o.component;const a=(n[v.VNODE_BEFORE_MOUNT]||"").trim();P(a,o)},[v.VNODE_MOUNTED](o){const a=(n[v.VNODE_MOUNTED]||"").trim();P(a,o)}};return u(u({},e),s)};function me(t){try{return new Function(`
      return (
        async function(mouseEvent){
          ${t}
        }
      )`)()}catch(e){console.error(e)}}function P(t,e){try{Function(`
      "use strict";
      return (
        async function(e, components, node_modules){
          const {${Object.keys(W).join()}} = node_modules;
          ${t}
        }
      )`)().bind(e==null?void 0:e.component)(e,K,W)}catch(n){console.error(n)}}const Y=(t,e)=>({zIndex:e+1,left:`${t.x}px`,top:`${t.y}px`}),Q=(t,e)=>({width:`${e?e*t.w:t.w}px`,height:`${e?e*t.h:t.h}px`}),J=t=>({display:t.hide?"none":"block"}),Z=t=>{const e={};return t&&t.overFlowHidden&&(e.overflow="hidden"),e},Se=t=>{const e=t.selectColor?{background:t.background}:{background:`url(${t.backgroundImage}) center center / cover no-repeat !important`};return u({position:"relative",width:t.width?`${t.width||100}px`:"100%",height:t.height?`${t.height}px`:"100%"},e)};const Ce=C({__name:"index",props:{groupData:{type:Object,required:!0},themeSetting:{type:Object,required:!0},themeColor:{type:Object,required:!0},groupIndex:{type:Number,required:!0}},setup(t){return(e,n)=>(h(!0),w(j,null,D(t.groupData.groupList,s=>(h(),w("div",{class:F(["chart-item",c(B)(s.styles.animations)]),key:s.id,style:y(u(u(u(u(u(u({},c(Y)(s.attr,t.groupIndex)),c(E)(s.styles)),c(G)(s.styles)),c(J)(s.status)),c(Z)(s.preview)),c(M)(s.styles)))},[(h(),_(q(s.chartConfig.chartKey),U({id:s.id,chartConfig:s,themeSetting:t.themeSetting,themeColor:t.themeColor,style:u({},c(Q)(s.attr))},V(c(X)(s))),null,16,["id","chartConfig","themeSetting","themeColor","style"]))],6))),128))}});var Re=x(Ce,[["__scopeId","data-v-78cb40c3"]]);const xe=C({__name:"index",props:{localStorageInfo:{type:Object,required:!0}},setup(t){const e=t,{initDataPond:n,clearMittDataPondMap:s}=pe(),o=m(()=>e.localStorageInfo.editCanvasConfig.chartThemeSetting),a=m(()=>{const i=e.localStorageInfo.editCanvasConfig.chartThemeColor;return ue[i]});return s(),A(()=>{n(e.localStorageInfo.requestGlobalConfig)}),(i,d)=>(h(!0),w(j,null,D(t.localStorageInfo.componentList,(r,l)=>(h(),w("div",{class:F(["chart-item",c(B)(r.styles.animations)]),key:r.id,style:y(u(u(u(u(u(u({},c(Y)(r.attr,l)),c(E)(r.styles)),c(G)(r.styles)),c(J)(r.status)),c(Z)(r.preview)),c(M)(r.styles)))},[r.isGroup?(h(),_(c(Re),{key:0,groupData:r,groupIndex:l,themeSetting:c(o),themeColor:c(a)},null,8,["groupData","groupIndex","themeSetting","themeColor"])):(h(),_(q(r.chartConfig.chartKey),U({key:1,id:r.id,chartConfig:r,themeSetting:c(o),themeColor:c(a),style:u({},c(Q)(r.attr))},V(c(X)(r))),null,16,["id","chartConfig","themeSetting","themeColor","style"]))],6))),128))}});var N=x(xe,[["__scopeId","data-v-8e1419e2"]]);const Fe=t=>{const e=p(!1),n=setInterval(()=>{if(window.$vue.component){clearInterval(n);const s=o=>{window.$vue.component(o.chartConfig.chartKey)||window.$vue.component(o.chartConfig.chartKey,ge(o.chartConfig))};t.componentList.forEach(o=>R(void 0,null,function*(){o.isGroup?o.groupList.forEach(a=>{s(a)}):s(o)})),e.value=!0}},200);return{show:e}},Ee=t=>{const e=p(),n=p(),s=p(t.editCanvasConfig.width),o=p(t.editCanvasConfig.height);return A(()=>{switch(t.editCanvasConfig.previewScaleType){case g.FIT:(()=>{const{calcRate:a,windowResize:i,unWindowResize:d}=ve(s.value,o.value,n.value);a(),i(),f(()=>{d()})})();break;case g.SCROLL_Y:(()=>{const{calcRate:a,windowResize:i,unWindowResize:d}=fe(s.value,o.value,n.value,r=>{const l=e.value;l.style.width=`${s.value*r.width}px`,l.style.height=`${o.value*r.height}px`});a(),i(),f(()=>{d()})})();break;case g.SCROLL_X:(()=>{const{calcRate:a,windowResize:i,unWindowResize:d}=ye(s.value,o.value,n.value,r=>{const l=e.value;l.style.width=`${s.value*r.width}px`,l.style.height=`${o.value*r.height}px`});a(),i(),f(()=>{d()})})();break;case g.FULL:(()=>{const{calcRate:a,windowResize:i,unWindowResize:d}=_e(s.value,o.value,n.value);a(),i(),f(()=>{d()})})();break}}),{entityRef:e,previewRef:n}},ze=t=>{const e=he();e.requestGlobalConfig=t[we.REQUEST_GLOBAL_CONFIG]};const $e=C({__name:"index",setup(t){const e=H();ae(`\u9884\u89C8-${e.editCanvasConfig.projectName}`);const n=m(()=>u(u({},Se(e.editCanvasConfig)),E(e.editCanvasConfig))),s=m(()=>{const d=e.editCanvasConfig.previewScaleType;return d===g.SCROLL_Y||d===g.SCROLL_X});ze(e);const{entityRef:o,previewRef:a}=Ee(e),{show:i}=Fe(e);return(d,r)=>(h(),w("div",{class:F(`go-preview ${c(e).editCanvasConfig.previewScaleType}`)},[c(s)?(h(),w("div",{key:0,ref_key:"entityRef",ref:o,class:"go-preview-entity"},[ce("div",{ref_key:"previewRef",ref:a,class:"go-preview-scale"},[c(i)?(h(),w("div",{key:0,style:y(c(n))},[I(c(N),{localStorageInfo:c(e)},null,8,["localStorageInfo"])],4)):O("",!0)],512)],512)):(h(),w("div",{key:1,ref_key:"previewRef",ref:a,class:"go-preview-scale"},[c(i)?(h(),w("div",{key:0,style:y(c(n))},[I(c(N),{localStorageInfo:c(e)},null,8,["localStorageInfo"])],4)):O("",!0)],512))],2))}});var Le=x($e,[["__scopeId","data-v-2e814e32"]]);const Be=C({__name:"wrapper",setup(t){let e=p(Date.now());return[T.JSON,T.CHART].forEach(n=>{!window.opener||window.opener.addEventListener(n,s=>R(this,null,function*(){const o=yield H();de(le.GO_CHART_STORAGE_LIST,[k(u({},s.detail),{id:o.id})]),e.value=Date.now()}))}),(n,s)=>(h(),_(Le,{key:c(e)}))}});export{Be as default};
