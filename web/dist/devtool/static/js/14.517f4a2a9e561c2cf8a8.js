webpackJsonp([14],{EzkW:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=s("B2YR"),r=s.n(a),n=s("GFpa"),o=s.n(n),i=s("RTBu"),l=s.n(i),c=s("PRez"),v=s("ZXMi"),u={name:"RpcRoutes",components:r()({VAlert:o.a},c,{VDataTable:l.a}),data:function(){return{search:"",pageOpts:[10,25,{text:"All",value:-1}],headers:[{text:this.$t("App.class"),align:"left",sortable:!1,value:"class"},{text:this.$t("App.method"),align:"left",value:"method"},{text:this.$t("App.serviceKey"),align:"left",value:"serviceKey"}],routes:[]}},created:function(){this.fetchList()},mounted:function(){},computed:{},methods:{fetchList:function(){var e=this;Object(v.m)().then(function(t){var s=t.data;console.log(s),e.routes=s})}}},d={render:function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("v-subheader",[s("h1",[e._v(e._s(e.$t(this.$route.name)))])]),e._v(" "),s("v-card",[s("v-card-title",{staticClass:"pt-1"},[s("v-spacer"),e._v(" "),s("v-text-field",{attrs:{"append-icon":"search",label:e.$t("App.search"),"single-line":"","hide-details":""},model:{value:e.search,callback:function(t){e.search=t},expression:"search"}})],1),e._v(" "),s("v-divider"),e._v(" "),s("v-data-table",{staticClass:"elevation-1",attrs:{headers:e.headers,items:e.routes,search:e.search,"rows-per-page-items":e.pageOpts,"rows-per-page-text":e.$t("App.rowsPerPage"),"disable-initial-sort":""},scopedSlots:e._u([{key:"items",fn:function(t){return[s("td",[e._v(e._s(t.item.class))]),e._v(" "),s("td",[e._v(e._s(t.item.method))]),e._v(" "),s("td",[s("span",{staticClass:"el-tag"},[e._v(e._s(t.item.serviceKey))])])]}}])},[s("template",{slot:"no-data"},[s("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[e._v("\n          Sorry, nothing to display here :(\n        ")])],1),e._v(" "),s("v-alert",{attrs:{slot:"no-results",value:!0,color:"error",icon:"warning"},slot:"no-results"},[e._v('\n        Your search for "'+e._s(e.search)+'" found no results.\n      ')])],2)],1)],1)},staticRenderFns:[]};var p=s("U5Ua")(u,d,!1,function(e){s("Z9cP")},"data-v-6e3beb59",null);t.default=p.exports},Z9cP:function(e,t){}});
//# sourceMappingURL=14.517f4a2a9e561c2cf8a8.js.map