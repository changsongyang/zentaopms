import{k as m}from"./useSync.hook-445a7635.js";import{d as i,u as _,q as s,a5 as d,R as f,r as a,o as h,c as g,w as v,z as r,e as C}from"./index.js";import"./plugin-eb434dff.js";import"./icon-4abed280.js";import"./tables_list-180d3f1b.js";const x=r("span",null," \u62FC\u547D\u52A0\u8F7D\u4E2D... ",-1),F=i({__name:"index",setup(w){const n=m(),c=_(),o=s(!1),t=s(0),l=d(()=>c.getAppTheme);return f(()=>n.getPercentage,e=>{if(e===0){setTimeout(()=>{t.value=e,o.value=!1},500);return}t.value=e,o.value=e>0}),(e,y)=>{const u=a("n-progress"),p=a("n-modal");return h(),g(p,{show:o.value,"close-on-esc":!1,"transform-origin":"center"},{default:v(()=>[r("div",null,[x,C(u,{type:"line",color:l.value,percentage:t.value,style:{width:"300px"}},null,8,["color","percentage"])])]),_:1},8,["show"])}}});export{F as default};
