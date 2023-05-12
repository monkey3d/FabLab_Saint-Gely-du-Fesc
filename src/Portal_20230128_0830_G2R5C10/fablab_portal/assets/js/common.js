
//--------------------------------------------------------------------------------------------
window.addEventListener('DOMContentLoaded', (event) => {
	let trace = false;
	if (trace) console.log('common.js - DOMContentLoaded - entry');
	let elem = document.getElementById('back-to-top');
	let offset = 200; // c'est à partir de ce décalage qu'apparaît le bouton
	//let duration = 500;
	window.addEventListener('scroll', function(e) {
	//console.log(window.pageYOffset);
		if (window.pageYOffset > offset) {
			elem.style.visibility = "visible";
			elem.style.opacity = 1;
		}
		else {
			elem.style.opacity = 0;
		}
	});
	elem.addEventListener('click', event => {
		window.scrollTo(0, 0);
	});
});
//--------------------------------------------------------------------------------------------
search_results = function() {
	let trace = true;
	if (trace) console.log("function search_results - entry");
	//var urlSearch = $('#js-url-search').data('urlSearch');
	let urlSearch = document.getElementById('js-url-search').getAttribute('data-url-search');
	if (trace) console.log("function search_results - urlSearch : " + urlSearch);
	//$("#pop").popover({placement:'bottom', trigger:'hover'});
	let popoverEl = document.getElementById('popover');
	let popover = new bootstrap.Popover(popoverEl, {
		placement: 'bottom',
		trigger: 'hover'
	});
	const form = document.getElementById('form_search');
	form.addEventListener('submit', (event) => {
		event.preventDefault();
		if (trace) console.log("function search_results - submit");
		const keyword = document.getElementById('search_form_keyword').value;
		if (trace) console.log("function search_results - keyword = " + keyword);
		fetch(urlSearch, {
            method: "POST",
            body: "keyword="+keyword,
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' // //pour un corps de type chaine
            	//'Content-Type': 'application/json',
            }),
            cache: "no-cache",
		})
		//.then(response => response.text()) // +08/07/2022 : https://gomakethings.com/getting-html-with-fetch-in-vanilla-js/
		.then(function (response) {
			console.log('response.text');
			return response.text();
		})
		.then(function (html) {
			console.log('html');
			document.getElementById('search_result').innerHTML = html;
			//var parser = new DOMParser();
			//var doc = parser.parseFromString(html, 'text/html');
		})
		/*
	    .then(response => {
	        // Do something with response.
	    	alert(response);
	    })
	    */
		.catch(error => alert("Erreur : " + error));
	});
}