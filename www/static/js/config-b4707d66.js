var d=Object.defineProperty,l=Object.defineProperties;var p=Object.getOwnPropertyDescriptors;var r=Object.getOwnPropertySymbols;var g=Object.prototype.hasOwnProperty,h=Object.prototype.propertyIsEnumerable;var e=(o,t,i)=>t in o?d(o,t,{enumerable:!0,configurable:!0,writable:!0,value:i}):o[t]=i,n=(o,t)=>{for(var i in t||(t={}))g.call(t,i)&&e(o,i,t[i]);if(r)for(var i of r(t))h.call(t,i)&&e(o,i,t[i]);return o},f=(o,t)=>l(o,p(t));import{al as a,ax as m}from"./index.js";import{e as c}from"./chartEditStore-a5e1adf8.js";import{H as s}from"./index-a2cac8c2.js";import"./plugin-f1a298df.js";import"./icon-5d4a52b7.js";import"./table_scrollboard-e30c6082.js";import"./SizeSetting.vue_vue_type_style_index_0_scoped_true_lang-00b020a9.js";import"./useTargetData.hook-1a6eea49.js";const u={text:"",icon:"",textSize:30,textColor:"#ffffff",textWeight:"bold",placement:"left-top",distance:8,hint:"\u8FD9\u662F\u63D0\u793A\u6587\u672C",width:0,height:0,paddingX:16,paddingY:8,borderWidth:1,borderStyle:"solid",borderColor:"#1a77a5",borderRadius:6,color:"#ffffff",textAlign:"left",fontWeight:"normal",backgroundColor:"rgba(89, 196, 230, .2)",fontSize:24};class w extends c{constructor(){super(...arguments),this.key=s.key,this.chartConfig=a(s),this.option=a(u),this.attr=f(n({},m),{w:36,h:36,zIndex:1})}}export{w as default,u as option};
