import{r as t,h as s,F as e}from"./p-b9d115dd.js";const i=class{constructor(s){t(this,s),this.editor=void 0,this.forceUpdateCounter=void 0,this.toggleFullscreen=null,this.showCharCount=!0}render(){const t=this.editor.storage.characterCount.characters();return s(e,{key:"55e412fff5b12b148cc56065e37fca603af14d90"},this.showCharCount?s("span",null,t," character",t>1?"s":"","."):s("div",null),this.toggleFullscreen&&s("button",{key:"d44396418729ae598910d6d8c221a0fd9292070c",id:"fullscreen-button",onClick:this.toggleFullscreen},"Toggle Fullscreen"))}};export{i as zen_editor_footer}