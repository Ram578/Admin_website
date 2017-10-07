$(document).ready(function(){
	
	$('#scores').DataTable({
		"searching": false,
		"paging":   false,
        "ordering": false
	});

$('#btnPrint').click(function(e) {
	e.preventDefault();
	$('.background').attr('style', "display: block;content: url('./resources/img/aims_logo.jpg'); width:200px; height:150px;margin:0em 0em 3em 2em;-webkit-print-color-adjust: exact;");
	setTimeout(function() {
		window.print();
	}, 800);
	// $('#scores').reload();
});

	$('html').hover(function() {
		console.log('test');
		$('.background').attr('style', "");
	});
	 // var afterPrint = function() {
        // console.log('Functionality to run after printing');
		// $('.background').attr('style', "");
    // };
});