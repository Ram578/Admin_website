$(document).ready(function(){
	
	$('#btnPrint').click(function(e) {
		e.preventDefault();
		$('.background').attr('style', "display: block;content: url('./resources/img/aims_logo.jpg'); width:200px; height:150px;margin:0em 0em 3em 2em;-webkit-print-color-adjust: exact;");
		setTimeout(function() {
			window.print();
		}, 800);
	});
	
});