<html>
<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/swipe.js"></script>
	<script>
		$(function(){
			var start;
			var end;
			$(document.body).bind('touchstart', function(e) {
				start = e;
			});
			$(document.body).bind('touchmove', function(e) {
				end = e;
			});
			$(document.body).bind('touchend', function(e) {
				var swipeleft = (start.originalEvent.touches[0].pageX-end.originalEvent.touches[0].pageX > 0);
				console.log(swipeleft);
			});

		});
	</script>
</head>
<body style="background: green;">
	<div style="height: 400px; width: 400px; background: red; margin: 50px;" id="swipeme"></div>
</body>
</html>