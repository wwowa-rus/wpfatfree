(function($){$.fn.sortableLists=function(options){var jQBody=$('body').css('position','relative'),defaults={currElClass:'',placeholderClass:'',placeholderCss:{'position':'relative','padding':0},hintClass:'',hintCss:{'display':'none','position':'relative','padding':0},hintWrapperClass:'',hintWrapperCss:{},baseClass:'',baseCss:{'position':'absolute','top':0-parseInt(jQBody.css('margin-top')),'left':0-parseInt(jQBody.css('margin-left')),'margin':0,'padding':0,'z-index':2500},opener:{active:!1,open:'',close:'',openerCss:{'float':'left','display':'inline-block','background-position':'center center','background-repeat':'no-repeat'},openerClass:''},listSelector:'ul',listsClass:'',listsCss:{},insertZone:50,insertZonePlus:!1,scroll:20,ignoreClass:'',isAllowed:function(cEl,hint,target){return!0},onDragStart:function(e,cEl){return!0},onChange:function(cEl){return!0},complete:function(cEl){return!0}},setting=$.extend(!0,{},defaults,options),base=$('<'+setting.listSelector+' />').prependTo(jQBody).attr('id','sortableListsBase').css(setting.baseCss).addClass(setting.listsClass+' '+setting.baseClass),placeholder=$('<li />').attr('id','sortableListsPlaceholder').css(setting.placeholderCss).addClass(setting.placeholderClass),hint=$('<li />').attr('id','sortableListsHint').css(setting.hintCss).addClass(setting.hintClass),hintWrapper=$('<'+setting.listSelector+' />').attr('id','sortableListsHintWrapper').addClass(setting.listsClass+' '+setting.hintWrapperClass).css(setting.listsCss).css(setting.hintWrapperCss),opener=$('<span />').addClass('sortableListsOpener '+setting.opener.openerClass).css(setting.opener.openerCss).on('mousedown',function(e){var li=$(this).closest('li');if(li.hasClass('sortableListsClosed')){open(li)}else{close(li)}
return!1});if(setting.opener.as=='class'){opener.addClass(setting.opener.close)}else if(setting.opener.as=='html'){opener.html(setting.opener.close)}else{opener.css('background-image','url('+setting.opener.close+')');console.error('jQuerySortableLists opener as background image is deprecated. In version 2.0.0 it will be removed. Use html instead please.')}
var state={isDragged:!1,isRelEFP:null,oEl:null,rootEl:null,cEl:null,upScroll:!1,downScroll:!1,pX:0,pY:0,cX:0,cY:0,isAllowed:!0,e:{pageX:0,pageY:0,clientX:0,clientY:0},doc:$(document),win:$(window)};if(setting.opener.active){if(!setting.opener.open)throw 'Opener.open value is not defined. It should be valid url, html or css class.';if(!setting.opener.close)throw 'Opener.close value is not defined. It should be valid url, html or css class.';$(this).find('li').each(function(){var li=$(this);if(li.children(setting.listSelector).length){opener.clone(!0).prependTo(li.children('div').first());if(!li.hasClass('sortableListsOpen')){close(li)}else{open(li)}}})}
return this.on('mousedown',function(e){var target=$(e.target);if(state.isDragged!==!1||(setting.ignoreClass&&target.hasClass(setting.ignoreClass)))return;e.preventDefault();var el=target.closest('li'),rEl=$(this);if(el[0]){setting.onDragStart(e,el);startDrag(e,el,rEl)}});function startDrag(e,el,rEl){state.isDragged=!0;var elMT=parseInt(el.css('margin-top')),elMB=parseInt(el.css('margin-bottom')),elML=parseInt(el.css('margin-left')),elMR=parseInt(el.css('margin-right')),elXY=el.offset(),elIH=el.innerHeight();state.rootEl={el:rEl,offset:rEl.offset(),rootElClass:rEl.attr('class')};state.cEl={el:el,mT:elMT,mL:elML,mB:elMB,mR:elMR,offset:elXY};state.cEl.xyOffsetDiff={X:e.pageX-state.cEl.offset.left,Y:e.pageY-state.cEl.offset.top};state.cEl.el.addClass('sortableListsCurrent'+' '+setting.currElClass);el.before(placeholder);var placeholderNode=state.placeholderNode=$('#sortableListsPlaceholder');el.css({'width':el.width(),'position':'absolute','top':elXY.top-elMT,'left':elXY.left-elML}).prependTo(base);placeholderNode.css({'display':'block','height':elIH});hint.css('height',elIH);state.doc.on('mousemove',dragging).on('mouseup',endDrag)}
function dragging(e){if(state.isDragged){var cEl=state.cEl,doc=state.doc,win=state.win;if(!e.pageX){setEventPos(e)}
if(doc.scrollTop()>state.rootEl.offset.top-10&&e.clientY<50){if(!state.upScroll)
{setScrollUp(e)}else{e.pageY=e.pageY-setting.scroll;$('html, body').each(function(i){$(this).scrollTop($(this).scrollTop()-setting.scroll)});setCursorPos(e)}}
else if(doc.scrollTop()+win.height()<state.rootEl.offset.top+state.rootEl.el.outerHeight(!1)+10&&win.height()-e.clientY<50){if(!state.downScroll){setScrollDown(e)}else{e.pageY=e.pageY+setting.scroll;$('html, body').each(function(i){$(this).scrollTop($(this).scrollTop()+setting.scroll)});setCursorPos(e)}}else{scrollStop(state)}
state.oElOld=state.oEl;cEl.el[0].style.visibility='hidden';state.oEl=oEl=elFromPoint(e.pageX,e.pageY);cEl.el[0].style.visibility='visible';showHint(e,state);setCElPos(e,state)}}
function endDrag(e){var cEl=state.cEl,hintNode=$('#sortableListsHint',state.rootEl.el),hintStyle=hint[0].style,targetEl=null,isHintTarget=!1,hintWrapperNode=$('#sortableListsHintWrapper');if(hintStyle.display=='block'&&hintNode.length&&state.isAllowed){targetEl=hintNode;isHintTarget=!0}else{targetEl=state.placeholderNode;isHintTarget=!1}
offset=targetEl.offset();cEl.el.animate({left:offset.left-state.cEl.mL,top:offset.top-state.cEl.mT},250,function()
{tidyCurrEl(cEl);targetEl.after(cEl.el[0]);targetEl[0].style.display='none';hintStyle.display='none';hintNode.remove();hintWrapperNode.removeAttr('id').removeClass(setting.hintWrapperClass);if(hintWrapperNode.length){hintWrapperNode.prev('div').append(opener.clone(!0))}
if(isHintTarget){state.placeholderNode.slideUp(150,function(){state.placeholderNode.remove();tidyEmptyLists();setting.onChange(cEl.el);setting.complete(cEl.el);state.isDragged=!1})}else{state.placeholderNode.remove();tidyEmptyLists();setting.complete(cEl.el);state.isDragged=!1}});scrollStop(state);state.doc.unbind("mousemove",dragging).unbind("mouseup",endDrag)}
function setScrollUp(e){if(state.upScroll)return;state.upScroll=setInterval(function(){state.doc.trigger('mousemove')},50)}
function setScrollDown(e){if(state.downScroll)return;state.downScroll=setInterval(function(){state.doc.trigger('mousemove')},50)}
function setCursorPos(e){state.pY=e.pageY;state.pX=e.pageX;state.cY=e.clientY;state.cX=e.clientX}
function setEventPos(e){e.pageY=state.pY;e.pageX=state.pX;e.clientY=state.cY;e.clientX=state.cX}
function scrollStop(state){clearInterval(state.upScroll);clearInterval(state.downScroll);state.upScroll=state.downScroll=!1}
function setCElPos(e,state){var cEl=state.cEl;cEl.el.css({'top':e.pageY-cEl.xyOffsetDiff.Y-cEl.mT,'left':e.pageX-cEl.xyOffsetDiff.X-cEl.mL})}
function elFromPoint(x,y){if(!document.elementFromPoint)return null;var isRelEFP=state.isRelEFP;if(isRelEFP===null){var s,res;if((s=state.doc.scrollTop())>0){isRelEFP=((res=document.elementFromPoint(0,s+$(window).height()-1))==null||res.tagName.toUpperCase()=='HTML')}
if((s=state.doc.scrollLeft())>0){isRelEFP=((res=document.elementFromPoint(s+$(window).width()-1,0))==null||res.tagName.toUpperCase()=='HTML')}}
if(isRelEFP){x-=state.doc.scrollLeft();y-=state.doc.scrollTop()}
var el=$(document.elementFromPoint(x,y));if(!state.rootEl.el.find(el).length)
{return null}else if(el.is('#sortableListsPlaceholder')||el.is('#sortableListsHint'))
{return null}else if(!el.is('li'))
{el=el.closest('li');return el[0]?el:null}else if(el.is('li'))
{return el}}
function showHint(e,state){var oEl=state.oEl;if(!oEl||!state.oElOld)return;var oElH=oEl.outerHeight(!1),relY=e.pageY-oEl.offset().top;if(setting.insertZonePlus){if(14>relY)
{showOnTopPlus(e,oEl,7>relY)}else if(oElH-14<relY)
{showOnBottomPlus(e,oEl,oElH-7<relY)}}else{if(5>relY)
{showOnTop(e,oEl)}else if(oElH-5<relY)
{showOnBottom(e,oEl)}}}
function showOnTop(e,oEl){if($('#sortableListsHintWrapper',state.rootEl.el).length){hint.unwrap()}
if(e.pageX-oEl.offset().left<setting.insertZone){if(oEl.prev('#sortableListsPlaceholder').length){hint.css('display','none');return}
oEl.before(hint)}
else{var children=oEl.children(),list=oEl.children(setting.listSelector).first();if(list.children().first().is('#sortableListsPlaceholder')){hint.css('display','none');return}
if(!list.length){children.first().after(hint);hint.wrap(hintWrapper)}else{list.prepend(hint)}
if(state.oEl){open(oEl)}}
hint.css('display','block');state.isAllowed=setting.isAllowed(state.cEl.el,hint,hint.parents('li').first())}
function showOnTopPlus(e,oEl,outside){if($('#sortableListsHintWrapper',state.rootEl.el).length){hint.unwrap()}
if(!outside&&e.pageX-oEl.offset().left>setting.insertZone){var children=oEl.children(),list=oEl.children(setting.listSelector).first();if(list.children().first().is('#sortableListsPlaceholder')){hint.css('display','none');return}
if(!list.length){children.first().after(hint);hint.wrap(hintWrapper)}else{list.prepend(hint)}
if(state.oEl){open(oEl)}}
else{if(oEl.prev('#sortableListsPlaceholder').length){hint.css('display','none');return}
oEl.before(hint)}
hint.css('display','block');state.isAllowed=setting.isAllowed(state.cEl.el,hint,hint.parents('li').first())}
function showOnBottom(e,oEl){if($('#sortableListsHintWrapper',state.rootEl.el).length){hint.unwrap()}
if(e.pageX-oEl.offset().left<setting.insertZone){if(oEl.next('#sortableListsPlaceholder').length){hint.css('display','none');return}
oEl.after(hint)}
else{var children=oEl.children(),list=oEl.children(setting.listSelector).last();if(list.children().last().is('#sortableListsPlaceholder')){hint.css('display','none');return}
if(list.length){children.last().append(hint)}else{oEl.append(hint);hint.wrap(hintWrapper)}
if(state.oEl){open(oEl)}}
hint.css('display','block');state.isAllowed=setting.isAllowed(state.cEl.el,hint,hint.parents('li').first())}
function showOnBottomPlus(e,oEl,outside){if($('#sortableListsHintWrapper',state.rootEl.el).length){hint.unwrap()}
if(!outside&&e.pageX-oEl.offset().left>setting.insertZone){var children=oEl.children(),list=oEl.children(setting.listSelector).last();if(list.children().last().is('#sortableListsPlaceholder')){hint.css('display','none');return}
if(list.length){children.last().append(hint)}else{oEl.append(hint);hint.wrap(hintWrapper)}
if(state.oEl){open(oEl)}}
else{if(oEl.next('#sortableListsPlaceholder').length){hint.css('display','none');return}
oEl.after(hint)}
hint.css('display','block');state.isAllowed=setting.isAllowed(state.cEl.el,hint,hint.parents('li').first())}
function open(li){li.removeClass('sortableListsClosed').addClass('sortableListsOpen');li.children(setting.listSelector).css('display','block');var opener=li.children('div').children('.sortableListsOpener').first();if(setting.opener.as=='html'){opener.html(setting.opener.close)}else if(setting.opener.as=='class'){opener.addClass(setting.opener.close).removeClass(setting.opener.open)}else{opener.css('background-image','url('+setting.opener.close+')')}}
function close(li){li.removeClass('sortableListsOpen').addClass('sortableListsClosed');li.children(setting.listSelector).css('display','none');var opener=li.children('div').children('.sortableListsOpener').first();if(setting.opener.as=='html'){opener.html(setting.opener.open)}else if(setting.opener.as=='class'){opener.addClass(setting.opener.open).removeClass(setting.opener.close)}else{opener.css('background-image','url('+setting.opener.open+')')}}
function tidyCurrEl(cEl){var cElStyle=cEl.el[0].style;cEl.el.removeClass(setting.currElClass+' '+'sortableListsCurrent');cElStyle.top='0';cElStyle.left='0';cElStyle.position='relative';cElStyle.width='auto'}
function tidyEmptyLists(){$(setting.listSelector,state.rootEl.el).each(function(i){if(!$(this).children().length){$(this).prev('div').children('.sortableListsOpener').first().remove();$(this).remove()}})}};$.fn.sortableListsToArray=function(arr,parentId){arr=arr||[];var order=0;this.children('li').each(function(){var li=$(this),listItem={},id=li.attr('id');if(!id){console.log(li);throw 'Previous item in console.log has no id. It is necessary to create the array.'}
listItem.id=id;listItem.parentId=parentId;listItem.value=li.data('value');listItem.order=order;arr.push(listItem);li.children('ul,ol').sortableListsToArray(arr,id);order++});return arr};$.fn.sortableListsToHierarchy=function(){var arr=[],order=0;$(this).children('li').each(function(){var li=$(this),listItem={},id=li.attr('id');if(!id){console.log(li);throw 'Previous item in console.log has no id. It is necessary to create the array.'}
listItem.id=id;listItem.value=li.data('value');listItem.order=order;arr.push(listItem);listItem.children=li.children('ul,ol').sortableListsToHierarchy();order++});return arr};$.fn.sortableListsToString=function(arr,parentId){arr=arr||[];parentId=parentId||'no-parent';$(this).children('li').each(function(){var li=$(this),id=li.attr('id'),matches=id?id.match(/(.+)[-=_](.+)/):null;if(!matches){console.log(li);throw 'Previous item in console.log has no id or id is not in required format xx_yy, xx-yy or xx=yy. It is necessary to create valid string.'}
arr.push(matches[1]+'['+matches[2]+']='+parentId);$(this).children('ul,ol').sortableListsToString(arr,matches[2])});return arr.join('&')}}(jQuery))