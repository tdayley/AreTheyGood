var td = td || {};

$(function() {
	td.ajaxCall = function (dataUrl, method, formData) {
		$.ajax({
			type: method,
			url: dataUrl,
			data: formData,
			dataType: "json",
			success: function (data) {
				var placeholderId = data.target;
				var summonerName = data.summonerName;
				var region = data.region;
				
	            if (!placeholderId)
	                return;
	            if(placeholderId !== "alert-notification") {
	            	$("[data-ajax-id='alert-notification']").empty();
	            }
	            
				var destination = $("[data-ajax-id='" + placeholderId + "']");
				destination.html($(data.html));
				
				$(".js-refresh-mastery").on("click", function() {
					var data = [{name: "region", value: region}, {name: "summonerName", value: summonerName}];
					td.ajaxCall("refresh_summoner_information.php", "POST", data);
				});
			},
			error: function(info, textStatus, errorThrown) {
				$("[data-ajax-id='alert-notification']").replaceWith("<div data-ajax-id='alert-notification'>" + errorThrown + "<br><br>" + info.responseText + "</div>");
			} 
		});
	}
	
	$(".js-summoner-name-form").submit(function () {
    	$("[data-ajax-id='alert-notification']").empty();
    	
		if($("#summoner-name").val() === "") {
			$("#summoner-name").addClass("input-error");
			$("#summoner-name").focus();
			return false;
		}
		
		$("#summoner-name").removeClass("input-error");
		
		td.getSummonerInformation($(this));
		document.activeElement.blur()
		return false;
	});

	td.getSummonerInformation = function (form) {
		var action = form.attr("action");
		var method = form.attr("method");
		method = method ? method.toUpperCase() : "GET";
		var formData = form.serializeArray();
		
		td.ajaxCall(action, method, formData);
	}
});