$(document).ready(function(){
	
	$('#btnPrint').click(function(e) {
		e.preventDefault();
		setTimeout(function() {
			window.print();
		}, 800);
	});
	
});