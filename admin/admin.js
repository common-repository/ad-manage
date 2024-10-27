function open_media_uploader_image(e){media_uploader=wp.media({title:"Select or upload your advert's creative",library:{type:"image"},button:{text:"Use this media"},frame:"post",state:"insert",multiple:!1}),media_uploader.on("insert",function(){var t=media_uploader.state().get("selection").first().toJSON();"image"===t.type&&e&&e(t)}),media_uploader.open()}jQuery(document).ready(function(e){function t(e,t,n){var a="",o="";t.forEach(function(e){n&&n===e&&(a=" selected"),o+="<option val='"+e+"'"+a+">"+e+"</option>",a=""}),$(e).html(o)}function n(e){var t=[];for(var n in e)t.push(n);return t}function a(e){var a={func:$(e).find(".rule-func"),operator:$(e).find(".rule-operator")},o={func:n(g),operator:n(g[a.func.val()])||n(g[0])};t(a.func,o.func,a.func.val()),t(a.operator,o.operator,a.operator.val())}function o(e){var t={func:$(e).find(".rule-func").val(),operator:$(e).find(".rule-operator").val()};return g[t.func]&&g[t.func][t.operator]?g[t.func][t.operator]:"text"}function r(e){function t(e){return 0>c&&(c=0),e.className=e.className+" advert-field rule-result",e.name="rule["+c+"][result]",$(n).replaceWith(e),"undefined"!==a&&$(e).val(a),$(e)}var n=$(e).find(".rule-result"),a=$(n).val(),r=o(e),c=i(e),u={categories:function(){$.post(admg.ajax_url,{_ajax_nonce:admg.advert_nonce,action:"admg_categories"},function(e){e=JSON.parse(e),t(u._dropdown(e,"term_id","name"))})},posts:function(){$.post(admg.ajax_url,{_ajax_nonce:admg.advert_nonce,action:"admg_posts"},function(e){e=JSON.parse(e),t(u._dropdown(e,"ID","post_title"))})},pages:function(){$.post(admg.ajax_url,{_ajax_nonce:admg.advert_nonce,action:"admg_pages"},function(e){e=JSON.parse(e),t(u._dropdown(e,"ID","post_title"))})},tags:function(){$.post(admg.ajax_url,{_ajax_nonce:admg.advert_nonce,action:"admg_tags"},function(e){e=JSON.parse(e),t(u._dropdown(e,"term_id","name"))})},post_types:function(){$.post(admg.ajax_url,{_ajax_nonce:admg.advert_nonce,action:"admg_post_types"},function(e){e=JSON.parse(e),console.log(e),t(u._dropdown(e,"name","name"))})},date:function(){t(u._input("text")).datetimepicker({dayOfWeekStart:1,lang:"en"})},text:function(){t(u._input("text"))},_input:function(e){var t=document.createElement("input");return t.type=e,t},_dropdown:function(e,t,n){if(0==e.length)return u._input("text");var a=document.createElement("select");for(var o in e){var r=document.createElement("option");r.value=e[o][t],r.textContent=e[o][n],a.appendChild(r)}return a}};void 0===typeof u[r]&&(r="text"),$(n).prop("disabled",!0),u.hasOwnProperty(r)?u[r]():u.text()}function i(e){var t=c(),n=-1;return $(t).each(function(t){$(this).is(e)&&(n=t)}),n}function c(e){return $(e)||(e=v),$(e).find(".rule")}function u(e){var t=$(e).closest(".rule-group"),n=c(t);$(e).remove(),1===n.length&&$(t).remove()}function d(e,t){s(),$.ajax({method:"POST",url:admg.ajax_url,data:{_ajax_nonce:admg.advert_nonce,action:"admg_advert_rule_markup",index:c().length}}).success(function(n){if($(e).append(n),t){var a=c(e);t(a[a.length-1])}l()})}function s(){$(v).closest(".main").addClass("loading")}function l(){$(v).closest(".main").removeClass("loading")}function p(){return $(v).find(".rule-group")}function f(){var e=0,t=p();$(t).each(function(){$(this).data("group")>=e&&(e=$(this).data("group")+1)});var n='		<table class="form-table rule-group" data-group="'+e+'">		    <tbody>		    			   	</tbody>		</table>';$(v).append(n);var a=$(v).children(".rule-group").last(),o=$(a).find("tbody");d(o,function(e){m(e)})}function m(e){var t=$(e).closest(".rule-group").data("group");a(e),a(e),r(e),$(e).find(".rule-parent").val(t),$(e).find("select").change(function(){a($(this).closest(".rule")),r(e)}),$(e).find(".rule-remove").click(function(){u($(this).closest(".rule"))}),$(e).find(".rule-add").click(function(){var e=$(this).closest("tbody");d(e,function(e){m(e)})})}$=jQuery;var g={title:{contains:"text",exactly:"text","does not contain":"text"},content:{contains:"text",exactly:"text","does not contain":"text"},category:{is:"categories","is not":"categories","is child of":"categories"},post:{is:"posts","is not":"posts",type:"post_types","has tag":"tags","has category":"categories","is child of":"categories"},date:{"is after":"date","is before":"date"}},v=$(".advert-rules");$(v).find(".rule").each(function(){m(this)}),$(".group-add").click(function(){f()})});var media_uploader=null;!function($){function e(){var e=$(n).find("[name='advert-url']").val(),t=$(a).find(".graphic").find("a");""==e?$(t).attr("href","#").attr("target","").css("cursor","default"):$(t).attr("href",e).attr("target","_blank").css("cursor","pointer")}function t(e){var t=document.createElement("A"),n=document.createElement("IMG"),o=document.createElement("UL");t.href="#",t.target="_blank",n.src=e.url,n.alt=e.alt,t.appendChild(n),$(a).find(".graphic").html("").append(t);for(var r=["width","height","subtype","filesizeHumanReadable"],i=["Width: %s px","Height: %s px","File type: %s","File size: %s"],c=0;c<r.length;c++){var u=document.createElement("LI");u.innerHTML=i[c].replace("%s",e[r[c]]),o.appendChild(u)}$(a).find(".details").html("").append(o)}var n=$(".advert-form"),a=$(n).find(".artwork-box").find(".aside"),o=$(n).find("[name='advert-type']");e(),$(n).find(".media-upload").click(function(){open_media_uploader_image(function(e){$(n).find("[name='advert-graphic']").val(e.id),t(e)})}),$(n).find("[name='advert-url']").keyup(function(t){e()})}(jQuery),function($){$.fn.admg_slug=function(e){function t(e){return encodeURIComponent(e.replace(/ /g,"-").toLowerCase())}e=$.extend({source:!1},e);var n=this;$(e.source).on("keydown paste",function(e){$(n).val(t($(this).val()))})},$("[name='advert-slug']").admg_slug({source:$("[name='advert-name']")}),$("[name='location-slug']").admg_slug({source:$("[name='location-name']")})}(jQuery),function($){var e=$(".check-column").find("input[type='checkbox']"),t=$(".bulk-actions-form").find("input[name='ids']");$(e).change(function(n){var a=[];$(e).each(function(){$(this).prop("checked")&&!$(this).parent().hasClass("manage-column")&&a.push($(this).val())}),$(t).val(a.join())})}(jQuery);