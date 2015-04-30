$(document).ready(function () {
    $("#submit").click(function (e) {		
        var url = $("#url").val();    
        if(!checkUrl(url)){
			$("#url").val("");    
			$("#url").attr("placeholder", "Enter a valid URL");
			$("#url").addClass("errbox");
		}
		else{
			$("#url").removeClass("errbox");
			if (url.length > 0) {
				$("#results").html('&nbsp;&nbsp;<img src="images/loader.gif">');
				$.ajax({
					type: "POST",
					url: "crawl.php",
					data: "url=" + url,
					success: function (response){
						$("#results").html(response);
					}
				});
			}
	    }	    
    });
});

function checkUrl(url){ 
	var pattern = /^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/; 
    if(pattern.test(url)){
        return true;
    } else {
        return false;
    }
}
