!function($){var t={init:function(){this.setPopularLikeButton(),this.setSocialMediaShareCount()},setPopularLikeButton:function(){},setSocialMediaShareCount:function(){$("a.share-counter").on("click",function(t){t.preventDefault();var n=$(this),e={action:"share",post_id:n.data("id"),network:n.data("network"),api_key:n.data("key")};ga("send","event","Social","Share",e.key),$.ajax({url:n.data("api"),type:"POST",async:!1,data:e,dataType:"json",error:function(){ga("send","event","Social","API Status","Error")},success:function(t){ga("send","event","Social","API Status","Success"),counter=n.find(".social-media-share-button-count"),newCount=parseInt(counter.html(),10)+1,counter.empty().append(newCount)},complete:function(){window.open(n.attr("href"))}})})}};$(function(){t.init()})}(jQuery);