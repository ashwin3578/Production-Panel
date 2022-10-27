<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>jQuery UI Signature Basics</title>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<link href="css/jquery.signature.css" rel="stylesheet">
<style>
.kbw-signature { width: 400px; height: 200px; }
</style>
<!--[if IE]>
<script src="excanvas.js"></script>
<![endif]-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="js/jquery.signature.js"></script>
<script>
$(function() {
	var sig = $('#sig').signature();
	
	$('#clear').click(function() {
		sig.signature('clear');
	});
	
});
</script>
</head>
<body>
<h1>jQuery UI Signature</h1>
<p>This page demonstrates the very basics of the
	<a href="http://keith-wood.name/signature.html">jQuery UI Signature plugin</a>.
	It contains the minimum requirements for using the plugin and
	can be used as the basis for your own experimentation.</p>
<p>For more detail see the <a href="http://keith-wood.name/signatureRef.html">documentation reference</a> page.</p>
<p>Default signature:</p>
<div id="sig"></div>
<p style="clear: both;">
	<button id="disable">Disable</button> 
	<button id="clear">Clear</button> 
	<button id="json">To JSON</button>
	<button id="svg">To SVG</button>
</p>
<dl>
	<dt>Github</dt><dd><a href="https://github.com/kbwood/signature">https://github.com/kbwood/signature</a></dd>
	<dt>Bower</dt><dd>kbw-signature</dd>
</dl>
</body>
</html>
