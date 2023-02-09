/*! For license information please see forum.js.LICENSE.txt */
(()=>{var t={648:(t,r,e)=>{var n=e(288).default;function o(){"use strict";t.exports=o=function(){return r},t.exports.__esModule=!0,t.exports.default=t.exports;var r={},e=Object.prototype,i=e.hasOwnProperty,s=Object.defineProperty||function(t,r,e){t[r]=e.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",u=a.asyncIterator||"@@asyncIterator",f=a.toStringTag||"@@toStringTag";function l(t,r,e){return Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}),t[r]}try{l({},"")}catch(t){l=function(t,r,e){return t[r]=e}}function h(t,r,e,n){var o=r&&r.prototype instanceof m?r:m,i=Object.create(o.prototype),a=new S(n||[]);return s(i,"_invoke",{value:E(t,e,a)}),i}function p(t,r,e){try{return{type:"normal",arg:t.call(r,e)}}catch(t){return{type:"throw",arg:t}}}r.wrap=h;var d={};function m(){}function v(){}function y(){}var g={};l(g,c,(function(){return this}));var b=Object.getPrototypeOf,w=b&&b(b(P([])));w&&w!==e&&i.call(w,c)&&(g=w);var x=y.prototype=m.prototype=Object.create(g);function _(t){["next","throw","return"].forEach((function(r){l(t,r,(function(t){return this._invoke(r,t)}))}))}function L(t,r){function e(o,s,a,c){var u=p(t[o],t,s);if("throw"!==u.type){var f=u.arg,l=f.value;return l&&"object"==n(l)&&i.call(l,"__await")?r.resolve(l.__await).then((function(t){e("next",t,a,c)}),(function(t){e("throw",t,a,c)})):r.resolve(l).then((function(t){f.value=t,a(f)}),(function(t){return e("throw",t,a,c)}))}c(u.arg)}var o;s(this,"_invoke",{value:function(t,n){function i(){return new r((function(r,o){e(t,n,r,o)}))}return o=o?o.then(i,i):i()}})}function E(t,r,e){var n="suspendedStart";return function(o,i){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw i;return{value:void 0,done:!0}}for(e.method=o,e.arg=i;;){var s=e.delegate;if(s){var a=I(s,e);if(a){if(a===d)continue;return a}}if("next"===e.method)e.sent=e._sent=e.arg;else if("throw"===e.method){if("suspendedStart"===n)throw n="completed",e.arg;e.dispatchException(e.arg)}else"return"===e.method&&e.abrupt("return",e.arg);n="executing";var c=p(t,r,e);if("normal"===c.type){if(n=e.done?"completed":"suspendedYield",c.arg===d)continue;return{value:c.arg,done:e.done}}"throw"===c.type&&(n="completed",e.method="throw",e.arg=c.arg)}}}function I(t,r){var e=r.method,n=t.iterator[e];if(void 0===n)return r.delegate=null,"throw"===e&&t.iterator.return&&(r.method="return",r.arg=void 0,I(t,r),"throw"===r.method)||"return"!==e&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+e+"' method")),d;var o=p(n,t.iterator,r.arg);if("throw"===o.type)return r.method="throw",r.arg=o.arg,r.delegate=null,d;var i=o.arg;return i?i.done?(r[t.resultName]=i.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=void 0),r.delegate=null,d):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,d)}function O(t){var r={tryLoc:t[0]};1 in t&&(r.catchLoc=t[1]),2 in t&&(r.finallyLoc=t[2],r.afterLoc=t[3]),this.tryEntries.push(r)}function j(t){var r=t.completion||{};r.type="normal",delete r.arg,t.completion=r}function S(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(O,this),this.reset(!0)}function P(t){if(t){var r=t[c];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var e=-1,n=function r(){for(;++e<t.length;)if(i.call(t,e))return r.value=t[e],r.done=!1,r;return r.value=void 0,r.done=!0,r};return n.next=n}}return{next:k}}function k(){return{value:void 0,done:!0}}return v.prototype=y,s(x,"constructor",{value:y,configurable:!0}),s(y,"constructor",{value:v,configurable:!0}),v.displayName=l(y,f,"GeneratorFunction"),r.isGeneratorFunction=function(t){var r="function"==typeof t&&t.constructor;return!!r&&(r===v||"GeneratorFunction"===(r.displayName||r.name))},r.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,y):(t.__proto__=y,l(t,f,"GeneratorFunction")),t.prototype=Object.create(x),t},r.awrap=function(t){return{__await:t}},_(L.prototype),l(L.prototype,u,(function(){return this})),r.AsyncIterator=L,r.async=function(t,e,n,o,i){void 0===i&&(i=Promise);var s=new L(h(t,e,n,o),i);return r.isGeneratorFunction(e)?s:s.next().then((function(t){return t.done?t.value:s.next()}))},_(x),l(x,f,"Generator"),l(x,c,(function(){return this})),l(x,"toString",(function(){return"[object Generator]"})),r.keys=function(t){var r=Object(t),e=[];for(var n in r)e.push(n);return e.reverse(),function t(){for(;e.length;){var n=e.pop();if(n in r)return t.value=n,t.done=!1,t}return t.done=!0,t}},r.values=P,S.prototype={constructor:S,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(j),!t)for(var r in this)"t"===r.charAt(0)&&i.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function e(e,n){return s.type="throw",s.arg=t,r.next=e,n&&(r.method="next",r.arg=void 0),!!n}for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n],s=o.completion;if("root"===o.tryLoc)return e("end");if(o.tryLoc<=this.prev){var a=i.call(o,"catchLoc"),c=i.call(o,"finallyLoc");if(a&&c){if(this.prev<o.catchLoc)return e(o.catchLoc,!0);if(this.prev<o.finallyLoc)return e(o.finallyLoc)}else if(a){if(this.prev<o.catchLoc)return e(o.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<o.finallyLoc)return e(o.finallyLoc)}}}},abrupt:function(t,r){for(var e=this.tryEntries.length-1;e>=0;--e){var n=this.tryEntries[e];if(n.tryLoc<=this.prev&&i.call(n,"finallyLoc")&&this.prev<n.finallyLoc){var o=n;break}}o&&("break"===t||"continue"===t)&&o.tryLoc<=r&&r<=o.finallyLoc&&(o=null);var s=o?o.completion:{};return s.type=t,s.arg=r,o?(this.method="next",this.next=o.finallyLoc,d):this.complete(s)},complete:function(t,r){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&r&&(this.next=r),d},finish:function(t){for(var r=this.tryEntries.length-1;r>=0;--r){var e=this.tryEntries[r];if(e.finallyLoc===t)return this.complete(e.completion,e.afterLoc),j(e),d}},catch:function(t){for(var r=this.tryEntries.length-1;r>=0;--r){var e=this.tryEntries[r];if(e.tryLoc===t){var n=e.completion;if("throw"===n.type){var o=n.arg;j(e)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,e){return this.delegate={iterator:P(t),resultName:r,nextLoc:e},"next"===this.method&&(this.arg=void 0),d}},r}t.exports=o,t.exports.__esModule=!0,t.exports.default=t.exports},288:t=>{function r(e){return t.exports=r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},t.exports.__esModule=!0,t.exports.default=t.exports,r(e)}t.exports=r,t.exports.__esModule=!0,t.exports.default=t.exports},357:(t,r,e)=>{var n=e(648)();t.exports=n;try{regeneratorRuntime=n}catch(t){"object"==typeof globalThis?globalThis.regeneratorRuntime=n:Function("r","regeneratorRuntime = r")(n)}}},r={};function e(n){var o=r[n];if(void 0!==o)return o.exports;var i=r[n]={exports:{}};return t[n](i,i.exports,e),i.exports}e.n=t=>{var r=t&&t.__esModule?()=>t.default:()=>t;return e.d(r,{a:r}),r},e.d=(t,r)=>{for(var n in r)e.o(r,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:r[n]})},e.o=(t,r)=>Object.prototype.hasOwnProperty.call(t,r),e.r=t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})};var n={};(()=>{"use strict";e.r(n),e.d(n,{filterCrossReferences:()=>F});const t=flarum.core.compat["common/extend"],r=flarum.core.compat["common/models/Discussion"];var o=e.n(r);const i=flarum.core.compat["forum/app"];var s=e.n(i);const a=flarum.core.compat["forum/components/CommentPost"];var c=e.n(a);const u=flarum.core.compat["forum/components/DiscussionHero"];var f=e.n(u);const l=flarum.core.compat["forum/components/DiscussionListItem"];var h=e.n(l);function p(t,r,e,n,o,i,s){try{var a=t[i](s),c=a.value}catch(t){return void e(t)}a.done?r(c):Promise.resolve(c).then(n,o)}var d=e(357),v=e.n(d),y=function(){function t(t){this.head=void 0,this.tail=void 0,this.data=new Map,this.capacity=void 0,this.capacity=t}var r=t.prototype;return r.get=function(t){return this.data.get(t)},r.set=function(t,r){if(this.data.size==this.capacity){var e=this.head;this.data.delete(e.key),this.head=e.next}var n={key:t};return this.head||(this.head=n),this.tail?(this.tail.next=n,this.tail=n):this.tail=n,this.data.set(t,r),this},t}();function g(t,r){return t+r}var b={};function w(){}b[o().name]="discussions";var x=function(){function t(){}return t.find=function(){var t,r=(t=v().mark((function t(r,e,n){var o,i,a,c=this;return v().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(void 0===n&&(n={}),o=g(r.name,e),1!=this.responseErrors.get(o)){t.next=4;break}return t.abrupt("return",null);case 4:if(!(i=this.inFlight.get(o))){t.next=9;break}return t.abrupt("return",i);case 9:return a=s().store.find(b[r.name],e,n,{errorHandler:w}).catch((function(){return c.responseErrors.set(o,!0),null})).finally((function(){return c.inFlight.delete(o)})),this.inFlight.set(o,a),t.abrupt("return",a);case 12:case"end":return t.stop()}}),t,this)})),function(){var r=this,e=arguments;return new Promise((function(n,o){var i=t.apply(r,e);function s(t){p(i,n,o,s,a,"next",t)}function a(t){p(i,n,o,s,a,"throw",t)}s(void 0)}))});return function(t,e,n){return r.apply(this,arguments)}}(),t}();function _(t,r){return _=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(t,r){return t.__proto__=r,t},_(t,r)}function L(t,r){t.prototype=Object.create(r.prototype),t.prototype.constructor=t,_(t,r)}x.responseErrors=new y(128),x.inFlight=new Map;const E=flarum.core.compat["common/Component"];var I=e.n(E),O=function(t){function r(){return t.apply(this,arguments)||this}return L(r,t),r.prototype.view=function(){return m("span",{class:"DiscussionId"},"#",this.attrs.discussionId)},r}(I());const j=flarum.core.compat["common/components/Link"];var S=e.n(j),P=function(t){function r(){for(var r,e=arguments.length,n=new Array(e),o=0;o<e;o++)n[o]=arguments[o];return(r=t.call.apply(t,[this].concat(n))||this).attrs=void 0,r.discussion=void 0,r}L(r,t);var e=r.prototype;return e.oninit=function(r){var e=this;t.prototype.oninit.call(this,r);var n,i=this.attrs.discussionId,a=s().store.getById("discussions",i);a||x.find(o(),i).then((function(t){t&&(e.discussion=t,m.redraw())})),this.discussion=a||(n=i,{id:function(t){function r(){return t.apply(this,arguments)}return r.toString=function(){return t.toString()},r}((function(){return n})),title:function(){return s().translator.trans("club-1-cross-references.forum.unknown_discussion").toString()}})},e.view=function(){var t,r=this.attrs.href,e=s().forum.attribute("showDiscussionId"),n=r&&/\/d\/[^\/]+\/[0-9]+/.test(r);return m(S(),{href:r||s().route("discussion",{id:this.attrs.discussionId}),class:"DiscussionLink"},null==(t=this.discussion)?void 0:t.title()," ",e&&m(O,{discussionId:this.attrs.discussionId})," ",n&&m(k,null))},r}(S()),k=function(t){function r(){return t.apply(this,arguments)||this}return L(r,t),r.prototype.view=function(){return m("span",{class:"DiscussionComment"},"(",s().translator.trans("club-1-cross-references.forum.comment"),")")},r}(I());const D=flarum.core.compat["forum/components/EventPost"];var A=function(t){function r(){for(var r,e=arguments.length,n=new Array(e),o=0;o<e;o++)n[o]=arguments[o];return(r=t.call.apply(t,[this].concat(n))||this).attrs=void 0,r}L(r,t),r.initAttrs=function(r){t.initAttrs.call(this,r),r.sourceIds=r.post.content()};var e=r.prototype;return e.icon=function(){return"fas fa-reply"},e.descriptionKey=function(){return"club-1-cross-references.forum.post_stream.discussion_referenced_text"},e.descriptionData=function(){return 1==this.attrs.sourceIds.length?{source:m(P,{discussionId:this.attrs.sourceIds[0]})}:{source:m("ul",null,this.attrs.sourceIds.map((function(t){return m("li",null,m(P,{discussionId:t}))})))}},r}(e.n(D)());function F(t){var r=t.getAttribute("id"),e=s().store.getById("discussions",r);if(!e)return x.find(o(),r).then((function(t){var r,e;t&&(e=null==(r=s().composer.fields)?void 0:r.content)&&(e("​"+e()),setTimeout((function(){return e(e().slice(1))}),50))})),!1;var n=e;t.setAttribute("title",n.title()),t.setAttribute("comment",s().translator.trans("club-1-cross-references.forum.comment"))}s().initializers.add("club-1-cross-references",(function(r){r.postComponents.discussionReferenced=A,function(){function r(){this.$(".Post-body a").map((function(){var t=this,r=this;if(r.protocol===document.location.protocol&&r.host===document.location.host){var e=r.pathname.match(/\/d\/([0-9]+)/);if(null!=e)if(r.text===r.href){var n=e[1],o=document.createElement("span");m.mount(o,{view:function(){return m(P,{discussionId:n,href:r.href})}}),r.replaceWith(o)}else r.addEventListener("click",(function(r){m.route.set(t.getAttribute("href")),r.preventDefault()}))}}))}(0,t.extend)(c().prototype,"oncreate",r),(0,t.extend)(c().prototype,"onupdate",r)}(),(0,t.extend)(f().prototype,"items",(function(t){if(s().forum.attribute("showDiscussionId")){var r=this.attrs.discussion.id(),e=t.get("title");t.setContent("title",m("h2",[e.text," ",m(O,{discussionId:r})]))}})),(0,t.extend)(h().prototype,"infoItems",(function(t){if(s().forum.attribute("showDiscussionId")){var r=this.attrs.discussion.id();t.add("id",m(O,{discussionId:r}),90)}}))}))})(),module.exports=n})();
//# sourceMappingURL=forum.js.map