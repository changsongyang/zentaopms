var f=Object.defineProperty,l=Object.defineProperties;var m=Object.getOwnPropertyDescriptors;var o=Object.getOwnPropertySymbols;var u=Object.prototype.hasOwnProperty,d=Object.prototype.propertyIsEnumerable;var p=(e,t,i)=>t in e?f(e,t,{enumerable:!0,configurable:!0,writable:!0,value:i}):e[t]=i,r=(e,t)=>{for(var i in t||(t={}))u.call(t,i)&&p(e,i,t[i]);if(o)for(var i of o(t))d.call(t,i)&&p(e,i,t[i]);return e},s=(e,t)=>l(e,m(t));import{ax as h,al as a}from"./index.js";import{e as c}from"./chartEditStore-08b7867a.js";import{c as n}from"./index-6bb56023.js";import"./plugin-8471cd7e.js";import"./icon-d59d56ff.js";import"./table_scrollboard-3667f4b7.js";/* empty css                                                                */import"./SettingItemBox-a90524c1.js";import"./CollapseItem-4a85a0e3.js";import"./useTargetData.hook-5df1ece2.js";const g={dataset:10*60,useEndDate:!1,endDate:new Date().getTime(),style:"\u65F6\u5206\u79D2",showDay:!1,flipperBgColor:"#16293E",flipperTextColor:"#4A9EF8FF",flipperWidth:30,flipperHeight:50,flipperRadius:5,flipperGap:10,flipperType:"down",flipperSpeed:450};class z extends c{constructor(){super(...arguments),this.key=n.key,this.attr=s(r({},h),{w:500,h:100,zIndex:-1}),this.chartConfig=a(n),this.option=a(g)}}export{z as default,g as option};
