(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[43],{115:function(e,t,c){"use strict";var a=c(15),n=c.n(a),r=c(0),o=c(154),l=c(5),s=c.n(l);c(219);const i=e=>({thousandSeparator:e.thousandSeparator,decimalSeparator:e.decimalSeparator,decimalScale:e.minorUnit,fixedDecimalScale:!0,prefix:e.prefix,suffix:e.suffix,isNumericString:!0});t.a=e=>{let{className:t,value:c,currency:a,onValueChange:l,displayType:u="text",...m}=e;const p="string"==typeof c?parseInt(c,10):c;if(!Number.isFinite(p))return null;const b=p/10**a.minorUnit;if(!Number.isFinite(b))return null;const d=s()("wc-block-formatted-money-amount","wc-block-components-formatted-money-amount",t),f={...m,...i(a),value:void 0,currency:void 0,onValueChange:void 0},j=l?e=>{const t=+e.value*10**a.minorUnit;l(t)}:()=>{};return Object(r.createElement)(o.a,n()({className:d,displayType:u},f,{value:b,onValueChange:j}))}},219:function(e,t){},351:function(e,t){},412:function(e,t,c){"use strict";var a=c(0),n=c(1),r=c(5),o=c.n(r),l=c(115),s=c(11),i=c(41),u=c(2),m=c(42);c(351),t.a=e=>{let{currency:t,values:c,className:r}=e;const p=Object(u.getSetting)("taxesEnabled",!0)&&Object(u.getSetting)("displayCartPricesIncludingTax",!1),{total_price:b,total_tax:d,tax_lines:f}=c,{receiveCart:j,...O}=Object(i.a)(),x=Object(s.__experimentalApplyCheckoutFilter)({filterName:"totalLabel",defaultValue:Object(n.__)("Total","woocommerce"),extensions:O.extensions,arg:{cart:O}}),g=parseInt(d,10),v=f&&f.length>0?Object(n.sprintf)(
/* translators: %s is a list of tax rates */
Object(n.__)("Including %s","woocommerce"),f.map(e=>{let{name:c,price:a}=e;return`${Object(m.formatPrice)(a,t)} ${c}`}).join(", ")):Object(n.__)("Including <TaxAmount/> in taxes","woocommerce");return Object(a.createElement)(s.TotalsItem,{className:o()("wc-block-components-totals-footer-item",r),currency:t,label:x,value:parseInt(b,10),description:p&&0!==g&&Object(a.createElement)("p",{className:"wc-block-components-totals-footer-item-tax"},Object(a.createInterpolateElement)(v,{TaxAmount:Object(a.createElement)(l.a,{className:"wc-block-components-totals-footer-item-tax-value",currency:t,value:g})}))})}},489:function(e,t,c){"use strict";c.r(t);var a=c(0),n=c(412),r=c(42),o=c(41),l=c(11);const s=()=>{const{extensions:e,receiveCart:t,...c}=Object(o.a)(),n={extensions:e,cart:c,context:"woocommerce/checkout"};return Object(a.createElement)(l.ExperimentalOrderMeta.Slot,n)};t.default=e=>{let{children:t,className:c=""}=e;const{cartTotals:l}=Object(o.a)(),i=Object(r.getCurrencyFromPriceResponse)(l);return Object(a.createElement)("div",{className:c},t,Object(a.createElement)("div",{className:"wc-block-components-totals-wrapper"},Object(a.createElement)(n.a,{currency:i,values:l})),Object(a.createElement)(s,null))}}}]);