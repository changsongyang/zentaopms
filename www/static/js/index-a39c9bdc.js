import{j as r,d as c,J as u,bl as i,r as d,o as a,m as _,A as p,c as f,w as m,z as v,t as b,ad as w,f as g,F as h}from"./index.js";import{u as x}from"./chartEditStore-a437fc62.js";import"./plugin-f1daab42.js";import"./icon-2dfcbb24.js";const C=c({__name:"index",setup(k){const n=x();u();const o=i([{select:!0,title:"\u5168\u5C4F",event:()=>{var e;(e=window.fullscreen)==null||e.call(window)},className:"btn btn-full"},{select:!0,title:"\u5B58\u4E3A\u8349\u7A3F",event:()=>{var t;const e=n.getStorageInfo;(t=window.saveAsDraft)==null||t.call(window,e)},className:"btn btn-full"},{select:!0,title:"\u53D1\u5E03",event:()=>{var t;const e=n.getStorageInfo;(t=window.saveAsPublish)==null||t.call(window,e)},className:"btn btn-publish"}]);return(e,t)=>{const l=d("n-button");return a(!0),_(h,null,p(g(o),s=>(a(),f(l,{key:s.title,class:w(s.className),ghost:"",onClick:s.event},{default:m(()=>[v("span",null,b(s.title),1)]),_:2},1032,["class","onClick"]))),128)}}});var y=r(C,[["__scopeId","data-v-90aad402"]]);export{y as default};
