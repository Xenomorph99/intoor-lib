!function($){var t={init:function(){this.setMetaBoxToggles(),this.setMetaBoxCheckboxContainers(),this.setMetaBoxButtons(),this.exportCSV(),this.setPopularMetaBox(),this.setTrackingAdminMenu()},setMetaBoxToggles:function(){$('input[type="checkbox"]').on("change",function(){var t=$(this).attr("id"),e=$(this).parent().find("#hidden-"+t),o=$(this).is(":checked")?"1":"0";e.val(o)})},setMetaBoxCheckboxContainers:function(){$(".contained-checkbox").on("change",function(){var t=$(this).val(),e=$(this).parent().parent().parent().parent().parent().find(".checkbox-container-controller"),o=e.val(),n="";$(this).is(":checked")?""===o?e.val(t):(n=o+","+t,n.replace(",,",","),e.val(n)):(n=o.replace(t,"").replace(",,",",").replace(/,$/,"").replace(/^,/,""),e.val(n))})},setMetaBoxButtons:function(){$(".meta-box-restore-defaults").on("click",function(e){e.preventDefault();var o=confirm("Are you sure you want to reset this meta box with the default values?");if(o){var n=$(this).parent().parent(),a=n.find(".meta-box-form-section .meta-box-section-id").val(),i=n.find(".meta-box-form-defaults").html();n.find(".meta-box-form-section").remove(),n.find(".meta-box-buttons").before('<div class="meta-box-form-section">'+i+"</div>"),n.find(".meta-box-form-section .meta-box-section-id").val(a),t.setMetaBoxToggles()}}),$(".meta-box-add-form-section").on("click",function(e){e.preventDefault();var o=$(this).parent().parent(),n=o.find(".meta-box-form-defaults").html();o.find(".meta-box-buttons").before('<div class="meta-box-form-section">'+n+"</div>"),t.setMetaBoxToggles()}),$(".meta-box-remove-form-section").on("click",function(t){t.preventDefault();var e=$(this).parent().parent(),o=e.find(".meta-box-form-section").last();o.css("display","none").removeClass("meta-box-form-section").addClass("meta-box-form-section-disabled"),o.find(".meta-box-section-id").val("-"+o.find(".meta-box-section-id").val())})},exportCSV:function(){$("#mailing-list-export-btn").on("click",function(t){t.preventDefault();var e=$(this).data("api");window.open(e,"csv")})},setPopularMetaBox:function(){var t=0,e;$("#popular-posts-popular").on("change",function(){e=parseInt($("#total-popular-count").text()),t=$(this).is(":checked")?e+1:e-1,$("#total-popular-count").text(t)})},setTrackingAdminMenu:function(){var t,e;$("#add-param").on("click",function(){t=$(this).parent().parent(),e=t.find("table#template tr").html(),t.find("table.form-table tbody").append("<tr>"+e+"</tr>")})}};$(function(){t.init()})}(jQuery);