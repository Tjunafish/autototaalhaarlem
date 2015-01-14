<div class="detail_center">

	<div class="head corner_left"></div>
	<div class="head corner_right"></div>

	<div class="contact_title">
	
		Contact
	
	</div>

	<div id="map"></div>
	
	<div class="details">
	
	<strong>auto service haarlem</strong><br />
	ZIJLWEG 294 <strong>|</strong> 2015 CN HAARLEM<br />
	<strong>T.</strong> <a href="tel:0235392024">023-5392024</a> <strong>|</strong> F. 023-5269898<br />
	<a href="mailto:info@autoservicehaarlem.nl">INFO@AUTOSERVICEHAARLEM.NL</a><br />
	<br />
	<br />
	<strong>OPENINGSTIJDEN</strong><br />
	MA T/M VRIJ VAN <strong>9.00</strong> TOT <strong>18.00</strong> UUR<br />
	ZATERDAGS VAN <strong>9.00</strong> TOT <strong>17.00</strong> UUR<br />
	ZONDAG VAN <strong>12.00</strong> TOT <strong>16.00</strong> UUR<br />
	
	</div>

</div>

<script type="text/javascript">
$(function(){

	var latLng = new google.maps.LatLng(52.387067,4.611873);

	var myOptions = {
	  zoom: 			13,
	  center: 			new google.maps.LatLng(52.386431,4.61724),
	  mapTypeId: 		google.maps.MapTypeId.ROADMAP,
	  disableDefaultUI: true
	};	

	var map = new google.maps.Map(document.getElementById('map'),myOptions);
	
	marker 	= new google.maps.Marker({
		position: 	latLng,
		map:		map	
	});

});
</script>