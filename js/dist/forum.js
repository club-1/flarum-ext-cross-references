/*! For license information please see forum.js.LICENSE.txt */
(()=>{var t={648:(t,e,r)=>{var n=r(288).default;function o(){"use strict";t.exports=o=function(){return e},t.exports.__esModule=!0,t.exports.default=t.exports;var e={},r=Object.prototype,i=r.hasOwnProperty,s=Object.defineProperty||function(t,e,r){t[e]=r.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",u=a.asyncIterator||"@@asyncIterator",f=a.toStringTag||"@@toStringTag";function l(t,e,r){return Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{l({},"")}catch(t){l=function(t,e,r){return t[e]=r}}function h(t,e,r,n){var o=e&&e.prototype instanceof m?e:m,i=Object.create(o.prototype),a=new k(n||[]);return s(i,"_invoke",{value:I(t,r,a)}),i}function p(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}e.wrap=h;var d={};function m(){}function v(){}function y(){}var g={};l(g,c,(function(){return this}));var b=Object.getPrototypeOf,w=b&&b(b(P([])));w&&w!==r&&i.call(w,c)&&(g=w);var x=y.prototype=m.prototype=Object.create(g);function _(t){["next","throw","return"].forEach((function(e){l(t,e,(function(t){return this._invoke(e,t)}))}))}function L(t,e){function r(o,s,a,c){var u=p(t[o],t,s);if("throw"!==u.type){var f=u.arg,l=f.value;return l&&"object"==n(l)&&i.call(l,"__await")?e.resolve(l.__await).then((function(t){r("next",t,a,c)}),(function(t){r("throw",t,a,c)})):e.resolve(l).then((function(t){f.value=t,a(f)}),(function(t){return r("throw",t,a,c)}))}c(u.arg)}var o;s(this,"_invoke",{value:function(t,n){function i(){return new e((function(e,o){r(t,n,e,o)}))}return o=o?o.then(i,i):i()}})}function I(t,e,r){var n="suspendedStart";return function(o,i){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw i;return{value:void 0,done:!0}}for(r.method=o,r.arg=i;;){var s=r.delegate;if(s){var a=E(s,r);if(a){if(a===d)continue;return a}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var c=p(t,e,r);if("normal"===c.type){if(n=r.done?"completed":"suspendedYield",c.arg===d)continue;return{value:c.arg,done:r.done}}"throw"===c.type&&(n="completed",r.method="throw",r.arg=c.arg)}}}function E(t,e){var r=e.method,n=t.iterator[r];if(void 0===n)return e.delegate=null,"throw"===r&&t.iterator.return&&(e.method="return",e.arg=void 0,E(t,e),"throw"===e.method)||"return"!==r&&(e.method="throw",e.arg=new TypeError("The iterator does not provide a '"+r+"' method")),d;var o=p(n,t.iterator,e.arg);if("throw"===o.type)return e.method="throw",e.arg=o.arg,e.delegate=null,d;var i=o.arg;return i?i.done?(e[t.resultName]=i.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=void 0),e.delegate=null,d):i:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,d)}function O(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function j(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function k(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(O,this),this.reset(!0)}function P(t){if(t){var e=t[c];if(e)return e.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var r=-1,n=function e(){for(;++r<t.length;)if(i.call(t,r))return e.value=t[r],e.done=!1,e;return e.value=void 0,e.done=!0,e};return n.next=n}}return{next:S}}function S(){return{value:void 0,done:!0}}return v.prototype=y,s(x,"constructor",{value:y,configurable:!0}),s(y,"constructor",{value:v,configurable:!0}),v.displayName=l(y,f,"GeneratorFunction"),e.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===v||"GeneratorFunction"===(e.displayName||e.name))},e.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,y):(t.__proto__=y,l(t,f,"GeneratorFunction")),t.prototype=Object.create(x),t},e.awrap=function(t){return{__await:t}},_(L.prototype),l(L.prototype,u,(function(){return this})),e.AsyncIterator=L,e.async=function(t,r,n,o,i){void 0===i&&(i=Promise);var s=new L(h(t,r,n,o),i);return e.isGeneratorFunction(r)?s:s.next().then((function(t){return t.done?t.value:s.next()}))},_(x),l(x,f,"Generator"),l(x,c,(function(){return this})),l(x,"toString",(function(){return"[object Generator]"})),e.keys=function(t){var e=Object(t),r=[];for(var n in e)r.push(n);return r.reverse(),function t(){for(;r.length;){var n=r.pop();if(n in e)return t.value=n,t.done=!1,t}return t.done=!0,t}},e.values=P,k.prototype={constructor:k,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(j),!t)for(var e in this)"t"===e.charAt(0)&&i.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function r(r,n){return s.type="throw",s.arg=t,e.next=r,n&&(e.method="next",e.arg=void 0),!!n}for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n],s=o.completion;if("root"===o.tryLoc)return r("end");if(o.tryLoc<=this.prev){var a=i.call(o,"catchLoc"),c=i.call(o,"finallyLoc");if(a&&c){if(this.prev<o.catchLoc)return r(o.catchLoc,!0);if(this.prev<o.finallyLoc)return r(o.finallyLoc)}else if(a){if(this.prev<o.catchLoc)return r(o.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<o.finallyLoc)return r(o.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var n=this.tryEntries[r];if(n.tryLoc<=this.prev&&i.call(n,"finallyLoc")&&this.prev<n.finallyLoc){var o=n;break}}o&&("break"===t||"continue"===t)&&o.tryLoc<=e&&e<=o.finallyLoc&&(o=null);var s=o?o.completion:{};return s.type=t,s.arg=e,o?(this.method="next",this.next=o.finallyLoc,d):this.complete(s)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),d},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),j(r),d}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;j(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,r){return this.delegate={iterator:P(t),resultName:e,nextLoc:r},"next"===this.method&&(this.arg=void 0),d}},e}t.exports=o,t.exports.__esModule=!0,t.exports.default=t.exports},288:t=>{function e(r){return t.exports=e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},t.exports.__esModule=!0,t.exports.default=t.exports,e(r)}t.exports=e,t.exports.__esModule=!0,t.exports.default=t.exports},357:(t,e,r)=>{var n=r(648)();t.exports=n;try{regeneratorRuntime=n}catch(t){"object"==typeof globalThis?globalThis.regeneratorRuntime=n:Function("r","regeneratorRuntime = r")(n)}}},e={};function r(n){var o=e[n];if(void 0!==o)return o.exports;var i=e[n]={exports:{}};return t[n](i,i.exports,r),i.exports}r.n=t=>{var e=t&&t.__esModule?()=>t.default:()=>t;return r.d(e,{a:e}),e},r.d=(t,e)=>{for(var n in e)r.o(e,n)&&!r.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},r.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),r.r=t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})};var n={};(()=>{"use strict";r.r(n),r.d(n,{filterCrossReferences:()=>A});const t=flarum.core.compat["common/extend"],e=flarum.core.compat["common/models/Discussion"];var o=r.n(e);const i=flarum.core.compat["forum/app"];var s=r.n(i);const a=flarum.core.compat["forum/components/CommentPost"];var c=r.n(a);const u=flarum.core.compat["forum/components/DiscussionHero"];var f=r.n(u);const l=flarum.core.compat["forum/components/DiscussionListItem"];var h=r.n(l);function p(t,e,r,n,o,i,s){try{var a=t[i](s),c=a.value}catch(t){return void r(t)}a.done?e(c):Promise.resolve(c).then(n,o)}var d=r(357),v=r.n(d),y=function(){function t(t){this.head=void 0,this.tail=void 0,this.data=new Map,this.capacity=void 0,this.capacity=t}var e=t.prototype;return e.get=function(t){return this.data.get(t)},e.set=function(t,e){if(this.data.size==this.capacity){var r=this.head;this.data.delete(r.key),this.head=r.next}var n={key:t};return this.head||(this.head=n),this.tail?(this.tail.next=n,this.tail=n):this.tail=n,this.data.set(t,e),this},t}();function g(t,e){return t+e}var b={};function w(){}b[o().name]="discussions";var x=function(){function t(){}return t.find=function(){var t,e=(t=v().mark((function t(e,r,n){var o,i,a,c=this;return v().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(void 0===n&&(n={}),o=g(e.name,r),1!=this.responseErrors.get(o)){t.next=4;break}return t.abrupt("return",null);case 4:if(!(i=this.inFlight.get(o))){t.next=9;break}return t.abrupt("return",i);case 9:return a=s().store.find(b[e.name],r,n,{errorHandler:w}).catch((function(){return c.responseErrors.set(o,!0),null})).finally((function(){return c.inFlight.delete(o)})),this.inFlight.set(o,a),t.abrupt("return",a);case 12:case"end":return t.stop()}}),t,this)})),function(){var e=this,r=arguments;return new Promise((function(n,o){var i=t.apply(e,r);function s(t){p(i,n,o,s,a,"next",t)}function a(t){p(i,n,o,s,a,"throw",t)}s(void 0)}))});return function(t,r,n){return e.apply(this,arguments)}}(),t}();function _(t,e){return _=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(t,e){return t.__proto__=e,t},_(t,e)}function L(t,e){t.prototype=Object.create(e.prototype),t.prototype.constructor=t,_(t,e)}x.responseErrors=new y(128),x.inFlight=new Map;const I=flarum.core.compat["common/Component"];var E=r.n(I),O=function(t){function e(){return t.apply(this,arguments)||this}return L(e,t),e.prototype.view=function(){return m("span",{class:"DiscussionId"},"#",this.attrs.discussionId)},e}(E());const j=flarum.core.compat["common/components/Link"];var k=r.n(j),P=function(t){function e(){for(var e,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(e=t.call.apply(t,[this].concat(n))||this).attrs=void 0,e.discussion=void 0,e}L(e,t);var r=e.prototype;return r.oninit=function(e){var r=this;t.prototype.oninit.call(this,e);var n=this.attrs.discussionId,i=s().store.getById("discussions",n);i||x.find(o(),n).then((function(t){t&&(r.discussion=t,m.redraw())})),i&&(this.discussion=i)},r.view=function(){var t,e=this.attrs.href,r=s().forum.attribute("showDiscussionId"),n=e&&/\/d\/[^\/]+\/[0-9]+/.test(e);return this.discussion?m(k(),{href:e||s().route("discussion",{id:this.attrs.discussionId}),class:"DiscussionLink"},null==(t=this.discussion)?void 0:t.title()," ",r&&m(O,{discussionId:this.attrs.discussionId})," ",n&&m(S,null)):m("span",{class:"DiscussionLink DiscussionUnknown"},s().translator.trans("club-1-cross-references.forum.unknown_discussion")," ",r&&m(O,{discussionId:this.attrs.discussionId})," ",n&&m(S,null))},e}(k()),S=function(t){function e(){return t.apply(this,arguments)||this}return L(e,t),e.prototype.view=function(){return m("span",{class:"DiscussionComment"},"(",s().translator.trans("club-1-cross-references.forum.comment"),")")},e}(E());const D=flarum.core.compat["forum/components/EventPost"];var F=function(t){function e(){for(var e,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(e=t.call.apply(t,[this].concat(n))||this).attrs=void 0,e}L(e,t),e.initAttrs=function(e){t.initAttrs.call(this,e),e.sourceIds=e.post.content()};var r=e.prototype;return r.icon=function(){return"fas fa-reply"},r.descriptionKey=function(){return 1==this.attrs.sourceIds.length?"club-1-cross-references.forum.post_stream.discussion_referenced_text":"club-1-cross-references.forum.post_stream.discussion_referenced_multiple_text"},r.descriptionData=function(){return 1==this.attrs.sourceIds.length?{source:m(P,{discussionId:this.attrs.sourceIds[0]})}:{sources:m("ul",null,this.attrs.sourceIds.map((function(t){return m("li",null,m(P,{discussionId:t}))})))}},e}(r.n(D)());function A(t){var e=t.getAttribute("id"),r=s().store.getById("discussions",e);if(!r)return x.find(o(),e).then((function(t){var e,r;t&&(r=null==(e=s().composer.fields)?void 0:e.content)&&(r("​"+r()),setTimeout((function(){return r(r().slice(1))}),50))})),!1;var n=r;t.setAttribute("title",n.title()),t.setAttribute("comment",s().translator.trans("club-1-cross-references.forum.comment"))}s().initializers.add("club-1-cross-references",(function(e){e.postComponents.discussionReferenced=F,function(){function e(){this.$(".Post-body a").map((function(){var t=this,e=this;if(e.protocol===document.location.protocol&&e.host===document.location.host){var r=e.pathname.match(/\/d\/([0-9]+)/);if(null!=r)if(s().forum.attribute("retrofitLinksInFrontend")&&e.text===e.href&&!e.classList.contains("DiscussionLink")){var n=r[1],o=document.createElement("span");m.mount(o,{view:function(){return m(P,{discussionId:n,href:e.href})}}),e.replaceWith(o)}else e.addEventListener("click",(function(e){m.route.set(t.getAttribute("href")),e.preventDefault()}))}}))}(0,t.extend)(c().prototype,"oncreate",e),(0,t.extend)(c().prototype,"onupdate",e)}(),(0,t.extend)(f().prototype,"items",(function(t){if(s().forum.attribute("showDiscussionId")){var e=this.attrs.discussion.id(),r=t.get("title");t.setContent("title",m("h2",[r.text," ",m(O,{discussionId:e})]))}})),(0,t.extend)(h().prototype,"infoItems",(function(t){if(s().forum.attribute("showDiscussionId")){var e=this.attrs.discussion.id();t.add("id",m(O,{discussionId:e}),90)}}))}))})(),module.exports=n})();
//# sourceMappingURL=forum.js.map