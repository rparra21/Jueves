    document.addEventListener("DOMContentLoaded", (event) => {
		console.log('DOM is ready.');	

				var categories = document.getElementById("RdgWidgetArticles").getAttribute("data-categories");
				console.log(categories);
				var amount = document.getElementById("RdgWidgetArticles").getAttribute("data-amount");
				console.log(amount);

				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open( "GET", "http://10.10.2.61/widget/yes/"+amount+"/"+categories, false ); // false for synchronous request
				xmlHttp.send(null);
											
				document.getElementById('divRdgWidgetNewsArticles').innerHTML = xmlHttp.responseText;
	});