 <?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

$db = JFactory::getDBO();
$db->setQuery("SELECT newId FROM #__users WHERE block = 0 AND newId != ''");
$listId1 = $db->loadColumn();

$db->setQuery("SELECT newId FROM #__sale");
$listId2 = $db->loadColumn();

$listId = array_merge($listId1, $listId2);

foreach($listId as $id){
	$idArr[] = '"'.$id.'"';
}
$idStr = implode(",", $idArr);
?>
<style>
    .frm-register-box .form-group {
    position: relative;
   }
   .frm-register-box .form-control {
    height: 45px;
    width: 95%;
   }
   .red {
    display: block;
    position: absolute;
    top: 10px;
    right: 0;
    color: red;
    font-size:15px;
   }

</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();
var markers = [];
function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}

function updateMarkerStatus(str) {
  document.getElementById('markerStatus').innerHTML = str;
}

function updateMarkerPosition(latLng) {
  document.getElementById('jform_latitude').setAttribute('value' , latLng.lat());
  document.getElementById('jform_longitude').setAttribute('value' , latLng.lng());
}

function updateMarkerAddress(str) {
  document.getElementById('address').innerHTML = str;
}

function initialize() {
  var latLng = new google.maps.LatLng(55.676097, 12.568337);
  var map = new google.maps.Map(document.getElementById('mapCanvas'), {
    zoom: 12,
    center: latLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  var marker = new google.maps.Marker({
    position: latLng,
    title: 'Drag this point to your business',
    map: map,
    draggable: true
  });
  
  var input = /** @type {HTMLInputElement} */ (
    document.getElementById('jform_businessaddress'));
  //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */
    (input));
  
  // Update current position info.
  updateMarkerPosition(latLng);
  geocodePosition(latLng);
  
  // Add dragging event listeners.
  google.maps.event.addListener(marker, 'dragstart', function() {
    updateMarkerAddress('Dragging...');
  });
  
  google.maps.event.addListener(marker, 'drag', function() {
    updateMarkerStatus('Dragging...');
    updateMarkerPosition(marker.getPosition());
  });
  
  google.maps.event.addListener(marker, 'dragend', function() {
    updateMarkerStatus('Drag ended');
    geocodePosition(marker.getPosition());
  });
  
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    var place = null;
    var viewport = null;
    for (var i = 0; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };
//      if(place.geometry.location)
//      {
//          initialize2(place.geometry.location, place.name)
//      }
      // Create a marker for each place.
//      var marker = new google.maps.Marker({
//        map: map,
//        //icon: image,
//        title: place.name,
//        position: place.geometry.location
//      });
//      viewport = place.geometry.viewport;
//      markers.push(marker);
//        marker.position = place.geometry.location;
      
      updateMarkerPosition(place.geometry.location);
      geocodePosition(place.geometry.location);
      initialize2(place.geometry.location,place.name);
      bounds.extend(place.geometry.location);
    }
    
    map.setCenter(bounds.getCenter());
//    marker.setMap(map);
  });

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });
}
function initialize2(latLng, _title) {
    var map = new google.maps.Map(document.getElementById('mapCanvas'), {
      zoom: 17,
      center: latLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var marker = new google.maps.Marker({
      position: latLng,
      title: _title,
      map: map,
      draggable: true
    });

    // Update current position info.
    updateMarkerPosition(latLng);
    geocodePosition(latLng);

    // Add dragging event listeners.
    google.maps.event.addListener(marker, 'dragstart', function() {
      //updateMarkerAddress('Dragging...');
    });

    google.maps.event.addListener(marker, 'drag', function() {
      updateMarkerStatus('Dragging...');
      updateMarkerPosition(marker.getPosition());
    });

    google.maps.event.addListener(marker, 'dragend', function() {
      updateMarkerStatus('Drag ended');
    });
}
// Onload handler to fire off the app.
google.maps.event.addDomListener(window, 'load', initialize);
jQuery( document ).ready(function() {
	jQuery("#jform_cvr_number").blur(function(e) {
		var geocoder = new google.maps.Geocoder();
		var cvr = jQuery("#jform_cvr_number").val();
                var address = "";
		jQuery.getJSON("http://cvrapi.dk/api?search="+cvr+"&country=dk", function(data){
                        if(data.owners != null){
                            var name = data.owners[0].name;
                            if(name){
                                var lastName = name.split(" ").pop();
                                jQuery("#jform_second_name").val(lastName);
                                var lastIndex = name.lastIndexOf(" ");
                                var firstName = name.substring(0, lastIndex);
                                jQuery("#jform_first_name").val(firstName);
                            }
                        }
			jQuery("#jform_telephone_number").val(data.phone);
			jQuery("#jform_company_name").val(data.name);
			jQuery("#jform_email").val(data.email);
			if(data.owners == null){
				jQuery("#jform_businessaddress").val("");
			} else {
				jQuery("#jform_businessaddress").val(data.address+", "+data.city);
			}
			
                        address = data.address+", "+data.city;
                        geocoder.geocode({ 'address': address }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                var latitude = results[0].geometry.location.lat();
                                var longitude = results[0].geometry.location.lng();
                                
                                var myLatlng = new google.maps.LatLng(latitude, longitude);
                                updateMarkerPosition(myLatlng);
                                
                                initialize2(myLatlng,"");
                                
                            } else {
                                
                            }
                        });
			
		});
    });
	
	jQuery("#jform_businessaddress, #jform_cvr_number").on("keydown", function(e) {
		if(e.which === 13) {
			return false;
		}
	});
	
	jQuery('#checkboxRegister').on('change invalid', function() {
		var textfield = jQuery(this).get(0);
		textfield.setCustomValidity('');
		
		if (!textfield.validity.valid) {
		  textfield.setCustomValidity('Du bedes acceptere vores handelsbetingelser');  
		}
	});
	
	var is_safari = navigator.userAgent.indexOf("Safari") > -1;
	if (is_safari){
		jQuery("#register").click(function(e) {
			if ( jQuery('#jform_businessaddress').val() == '' ) {
				alert('Bedes input firmaadresse');
				return false;
			}
			
        	if ( !jQuery('input[name="checkboxRegister"]').is(':checked') ) {
				alert('Du bedes acceptere vores handelsbetingelser');
				return false;	
			}
			jQuery("#registerSubmit").click();
    	});
	} else {
		jQuery("#register").click(function(e) {
        	jQuery("#registerSubmit").click();
    	});
	}
	
	var referencerArr = [
      <?php echo $idStr;?>
    ];
    jQuery( "#jform_referencer" ).autocomplete({
	  source: function(request, response) {
			var results = jQuery.ui.autocomplete.filter(referencerArr, request.term);
	
			response(results.slice(0, 10));
		}
    });
	
});


</script>

  <style>
  .invalid {
        border-color: red !important;
    }
  #mapCanvas {
    width: 300px;
    height: 300px;
    float: left;
  }
   #infoPanel {
    float: left;
    margin-left: 10px;
  }
  #infoPanel div {
    margin-bottom: 5px;
  }

  </style>

  <style>
      @font-face {
  font-family: 'HelveticaNeue';
  src: url('../fonts/HelveticaNeue.woff') format('woff'),
       url('../fonts/HelveticaNeue.ttf') format('truetype'),
       url('../fonts/HelveticaNeue.svg#HelveticaNeue') format('svg');
  font-weight: normal;
  font-style: normal;
}

@font-face {
  font-family: 'HelveticaNeue_0';
  src: url('../fonts/HelveticaNeue_0.eot');
  src: url('../fonts/HelveticaNeue_0.eot?#iefix') format('embedded-opentype'),
       url('../fonts/HelveticaNeue_0.woff2') format('woff2');
  font-weight: normal;
  font-style: normal;
}
@font-face {
  font-family: 'HelveticaNeue-Thin';
  src: url('../fonts/HelveticaNeue-Thin.eot');
  src: url('../fonts/HelveticaNeue-Thin.eot?#iefix') format('embedded-opentype'),
       url('../fonts/HelveticaNeue-Thin.woff2') format('woff2'),
       url('../fonts/HelveticaNeue-Thin.woff') format('woff'),
       url('../fonts/HelveticaNeue-Thin.ttf') format('truetype'),
       url('../fonts/HelveticaNeue-Thin.svg#HelveticaNeue-Thin') format('svg');
  font-weight: normal;
  font-style: normal;
}


html,body{padding:0;margin:0}
body{background-color:#f5f5f5; font-family: 'HelveticaNeue_0', Helvetica, Arial, sans-serif; font-size:14px;line-height:22px;color:#666;position:relative;-webkit-text-size-adjust:none; border-top: 5px solid #ab8a80;}
body *{text-shadow:none}
h1,h2,h3,h4,h5,h6{line-height:1; margin:20px 0 10px; color: #000; font-family: 'HelveticaNeue-Thin';}
h1,h2,h3{font-size:18px}
h4,h5,h6{font-size:16px}
h2 {font-size: 40px;}
p{margin:0 0 10px}
a,a:link,a:active,a:visited,a:hover{color:inherit;}
a:focus, a:hover {text-decoration: none;}
.header,.content,.footer{text-align:center}
.header,.footer{background:#777;font-size:16px;font-weight:700;color:#fff;line-height:60px;-moz-box-sizing:border-box;box-sizing:border-box;width:100%;height:60px;padding:0 50px}
.clear {clear: both;}
.mb200 {margin-bottom: 200px;} .mt50 {margin-top: 50px;} .mb30 {margin-bottom: 30px;} .mt30 {margin-top: 30px;} .mb500 {margin-bottom: 500px;}
.select-info h2 {
	color: #f7901e;
	font-size: 25px;
	font-weight: 600;
}
.top {
	padding: 30px 0;
	margin-top: 50px;
}
footer {
	background: #000;
	color: #fff;
	padding: 20px 0;
}
footer p {
	margin-bottom: 0;
	margin-top: 10px;
}
ul.list-social {
	list-style: none;
	margin-top: 10px;
}
ul.list-social li {
	display: inline-block;
	margin-right: 5px;
}
.frm-register-box {
	padding: 40px 65px !important;
	background: #fff;
	border: 1px solid #e2e2e2;
	min-height: 809px   !important;
	margin-right: 10px ;
}
.map img {
	width: 100%;
	border: 1px solid #e2e2e2;
}
.frm-register-box .form-control {
	height: 45px;
}
.btnPayment {
	margin-top: 15px;
	display: block;
	width: 100%;
	background: #f7901e;
	color: #fff;
	text-transform: uppercase;
	padding: 10px;
}
.btnGetgoing {
	margin-top: 15px;
	background: #f7901e;
	color: #fff !important;
	text-transform: uppercase;
	padding: 10px 60px;
}
.underline {
	text-decoration: underline;
}
.header2 {
	background: #fff;
	padding: 20px 0;
}
.header2 .list-social {
	margin-top: 25px;
}
.welcome-content {
	width: 600px;
	text-align: center;
	margin: 0 auto;
}
.welcome-content h2 {
	font-size: 40px;
}
.text-right {
	text-align: right;
}
ul.list-info {
	padding-left: 15px;
}
.title {
	font-size: 20px;
	font-weight: 600;
}





@media (max-width: 667px){
	
}

@media (max-width: 375px){
	
}

.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
  position: relative;
  min-height: 1px;
  padding-right: 15px;
  padding-left: 15px;
}
.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
  float: left;
}
.col-xs-12 {
  width: 100%;
}
.col-xs-11 {
  width: 91.66666667%;
}
.col-xs-10 {
  width: 83.33333333%;
}
.col-xs-9 {
  width: 75%;
}
.col-xs-8 {
  width: 66.66666667%;
}
.col-xs-7 {
  width: 58.33333333%;
}
.col-xs-6 {
  width: 50%;
}
.col-xs-5 {
  width: 41.66666667%;
}
.col-xs-4 {
  width: 33.33333333%;
}
.col-xs-3 {
  width: 25%;
}
.col-xs-2 {
  width: 16.66666667%;
}
.col-xs-1 {
  width: 8.33333333%;
}
.col-xs-pull-12 {
  right: 100%;
}
.col-xs-pull-11 {
  right: 91.66666667%;
}
.col-xs-pull-10 {
  right: 83.33333333%;
}
.col-xs-pull-9 {
  right: 75%;
}
.col-xs-pull-8 {
  right: 66.66666667%;
}
.col-xs-pull-7 {
  right: 58.33333333%;
}
.col-xs-pull-6 {
  right: 50%;
}
.col-xs-pull-5 {
  right: 41.66666667%;
}
.col-xs-pull-4 {
  right: 33.33333333%;
}
.col-xs-pull-3 {
  right: 25%;
}
.col-xs-pull-2 {
  right: 16.66666667%;
}
.col-xs-pull-1 {
  right: 8.33333333%;
}
.col-xs-pull-0 {
  right: auto;
}
.col-xs-push-12 {
  left: 100%;
}
.col-xs-push-11 {
  left: 91.66666667%;
}
.col-xs-push-10 {
  left: 83.33333333%;
}
.col-xs-push-9 {
  left: 75%;
}
.col-xs-push-8 {
  left: 66.66666667%;
}
.col-xs-push-7 {
  left: 58.33333333%;
}
.col-xs-push-6 {
  left: 50%;
}
.col-xs-push-5 {
  left: 41.66666667%;
}
.col-xs-push-4 {
  left: 33.33333333%;
}
.col-xs-push-3 {
  left: 25%;
}
.col-xs-push-2 {
  left: 16.66666667%;
}
.col-xs-push-1 {
  left: 8.33333333%;
}
.col-xs-push-0 {
  left: auto;
}
.col-xs-offset-12 {
  margin-left: 100%;
}
.col-xs-offset-11 {
  margin-left: 91.66666667%;
}
.col-xs-offset-10 {
  margin-left: 83.33333333%;
}
.col-xs-offset-9 {
  margin-left: 75%;
}
.col-xs-offset-8 {
  margin-left: 66.66666667%;
}
.col-xs-offset-7 {
  margin-left: 58.33333333%;
}
.col-xs-offset-6 {
  margin-left: 50%;
}
.col-xs-offset-5 {
  margin-left: 41.66666667%;
}
.col-xs-offset-4 {
  margin-left: 33.33333333%;
}
.col-xs-offset-3 {
  margin-left: 25%;
}
.col-xs-offset-2 {
  margin-left: 16.66666667%;
}
.col-xs-offset-1 {
  margin-left: 8.33333333%;
}
.col-xs-offset-0 {
  margin-left: 0;
}
@media (min-width: 768px) {
  .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
    float: left;
  }
  .col-sm-12 {
    width: 100%;
  }
  .col-sm-11 {
    width: 91.66666667%;
  }
  .col-sm-10 {
    width: 83.33333333%;
  }
  .col-sm-9 {
    width: 75%;
  }
  .col-sm-8 {
    width: 66.66666667%;
  }
  .col-sm-7 {
    width: 58.33333333%;
  }
  .col-sm-6 {
    width: 50%;
  }
  .col-sm-5 {
    width: 41.66666667%;
  }
  .col-sm-4 {
    width: 33.33333333%;
  }
  .col-sm-3 {
    width: 25%;
  }
  .col-sm-2 {
    width: 16.66666667%;
  }
  .col-sm-1 {
    width: 8.33333333%;
  }
  .col-sm-pull-12 {
    right: 100%;
  }
  .col-sm-pull-11 {
    right: 91.66666667%;
  }
  .col-sm-pull-10 {
    right: 83.33333333%;
  }
  .col-sm-pull-9 {
    right: 75%;
  }
  .col-sm-pull-8 {
    right: 66.66666667%;
  }
  .col-sm-pull-7 {
    right: 58.33333333%;
  }
  .col-sm-pull-6 {
    right: 50%;
  }
  .col-sm-pull-5 {
    right: 41.66666667%;
  }
  .col-sm-pull-4 {
    right: 33.33333333%;
  }
  .col-sm-pull-3 {
    right: 25%;
  }
  .col-sm-pull-2 {
    right: 16.66666667%;
  }
  .col-sm-pull-1 {
    right: 8.33333333%;
  }
  .col-sm-pull-0 {
    right: auto;
  }
  .col-sm-push-12 {
    left: 100%;
  }
  .col-sm-push-11 {
    left: 91.66666667%;
  }
  .col-sm-push-10 {
    left: 83.33333333%;
  }
  .col-sm-push-9 {
    left: 75%;
  }
  .col-sm-push-8 {
    left: 66.66666667%;
  }
  .col-sm-push-7 {
    left: 58.33333333%;
  }
  .col-sm-push-6 {
    left: 50%;
  }
  .col-sm-push-5 {
    left: 41.66666667%;
  }
  .col-sm-push-4 {
    left: 33.33333333%;
  }
  .col-sm-push-3 {
    left: 25%;
  }
  .col-sm-push-2 {
    left: 16.66666667%;
  }
  .col-sm-push-1 {
    left: 8.33333333%;
  }
  .col-sm-push-0 {
    left: auto;
  }
  .col-sm-offset-12 {
    margin-left: 100%;
  }
  .col-sm-offset-11 {
    margin-left: 91.66666667%;
  }
  .col-sm-offset-10 {
    margin-left: 83.33333333%;
  }
  .col-sm-offset-9 {
    margin-left: 75%;
  }
  .col-sm-offset-8 {
    margin-left: 66.66666667%;
  }
  .col-sm-offset-7 {
    margin-left: 58.33333333%;
  }
  .col-sm-offset-6 {
    margin-left: 50%;
  }
  .col-sm-offset-5 {
    margin-left: 41.66666667%;
  }
  .col-sm-offset-4 {
    margin-left: 33.33333333%;
  }
  .col-sm-offset-3 {
    margin-left: 25%;
  }
  .col-sm-offset-2 {
    margin-left: 16.66666667%;
  }
  .col-sm-offset-1 {
    margin-left: 8.33333333%;
  }
  .col-sm-offset-0 {
    margin-left: 0;
  }
}
@media (min-width: 992px) {
  .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
    float: left;
  }
  .col-md-12 {
    width: 100%;
  }
  .col-md-11 {
    width: 91.66666667%;
  }
  .col-md-10 {
    width: 83.33333333%;
  }
  .col-md-9 {
    width: 75%;
  }
  .col-md-8 {
    width: 66.66666667%;
  }
  .col-md-7 {
    width: 58.33333333%;
  }
  .col-md-6 {
    width: 50%;
  }
  .col-md-5 {
    width: 41.66666667%;
  }
  .col-md-4 {
    width: 33.33333333%;
  }
  .col-md-3 {
    width: 25%;
  }
  .col-md-2 {
    width: 16.66666667%;
  }
  .col-md-1 {
    width: 8.33333333%;
  }
  .col-md-pull-12 {
    right: 100%;
  }
  .col-md-pull-11 {
    right: 91.66666667%;
  }
  .col-md-pull-10 {
    right: 83.33333333%;
  }
  .col-md-pull-9 {
    right: 75%;
  }
  .col-md-pull-8 {
    right: 66.66666667%;
  }
  .col-md-pull-7 {
    right: 58.33333333%;
  }
  .col-md-pull-6 {
    right: 50%;
  }
  .col-md-pull-5 {
    right: 41.66666667%;
  }
  .col-md-pull-4 {
    right: 33.33333333%;
  }
  .col-md-pull-3 {
    right: 25%;
  }
  .col-md-pull-2 {
    right: 16.66666667%;
  }
  .col-md-pull-1 {
    right: 8.33333333%;
  }
  .col-md-pull-0 {
    right: auto;
  }
  .col-md-push-12 {
    left: 100%;
  }
  .col-md-push-11 {
    left: 91.66666667%;
  }
  .col-md-push-10 {
    left: 83.33333333%;
  }
  .col-md-push-9 {
    left: 75%;
  }
  .col-md-push-8 {
    left: 66.66666667%;
  }
  .col-md-push-7 {
    left: 58.33333333%;
  }
  .col-md-push-6 {
    left: 50%;
  }
  .col-md-push-5 {
    left: 41.66666667%;
  }
  .col-md-push-4 {
    left: 33.33333333%;
  }
  .col-md-push-3 {
    left: 25%;
  }
  .col-md-push-2 {
    left: 16.66666667%;
  }
  .col-md-push-1 {
    left: 8.33333333%;
  }
  .col-md-push-0 {
    left: auto;
  }
  .col-md-offset-12 {
    margin-left: 100%;
  }
  .col-md-offset-11 {
    margin-left: 91.66666667%;
  }
  .col-md-offset-10 {
    margin-left: 83.33333333%;
  }
  .col-md-offset-9 {
    margin-left: 75%;
  }
  .col-md-offset-8 {
    margin-left: 66.66666667%;
  }
  .col-md-offset-7 {
    margin-left: 58.33333333%;
  }
  .col-md-offset-6 {
    margin-left: 50%;
  }
  .col-md-offset-5 {
    margin-left: 41.66666667%;
  }
  .col-md-offset-4 {
    margin-left: 33.33333333%;
  }
  .col-md-offset-3 {
    margin-left: 25%;
  }
  .col-md-offset-2 {
    margin-left: 16.66666667%;
  }
  .col-md-offset-1 {
    margin-left: 8.33333333%;
  }
  .col-md-offset-0 {
    margin-left: 0;
  }
}
@media (min-width: 1200px) {
  .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 {
    float: left;
  }
  .col-lg-12 {
    width: 100%;
  }
  .col-lg-11 {
    width: 91.66666667%;
  }
  .col-lg-10 {
    width: 83.33333333%;
  }
  .col-lg-9 {
    width: 75%;
  }
  .col-lg-8 {
    width: 66.66666667%;
  }
  .col-lg-7 {
    width: 58.33333333%;
  }
  .col-lg-6 {
    width: 50%;
  }
  .col-lg-5 {
    width: 41.66666667%;
  }
  .col-lg-4 {
    width: 33.33333333%;
  }
  .col-lg-3 {
    width: 25%;
  }
  .col-lg-2 {
    width: 16.66666667%;
  }
  .col-lg-1 {
    width: 8.33333333%;
  }
  .col-lg-pull-12 {
    right: 100%;
  }
  .col-lg-pull-11 {
    right: 91.66666667%;
  }
  .col-lg-pull-10 {
    right: 83.33333333%;
  }
  .col-lg-pull-9 {
    right: 75%;
  }
  .col-lg-pull-8 {
    right: 66.66666667%;
  }
  .col-lg-pull-7 {
    right: 58.33333333%;
  }
  .col-lg-pull-6 {
    right: 50%;
  }
  .col-lg-pull-5 {
    right: 41.66666667%;
  }
  .col-lg-pull-4 {
    right: 33.33333333%;
  }
  .col-lg-pull-3 {
    right: 25%;
  }
  .col-lg-pull-2 {
    right: 16.66666667%;
  }
  .col-lg-pull-1 {
    right: 8.33333333%;
  }
  .col-lg-pull-0 {
    right: auto;
  }
  .col-lg-push-12 {
    left: 100%;
  }
  .col-lg-push-11 {
    left: 91.66666667%;
  }
  .col-lg-push-10 {
    left: 83.33333333%;
  }
  .col-lg-push-9 {
    left: 75%;
  }
  .col-lg-push-8 {
    left: 66.66666667%;
  }
  .col-lg-push-7 {
    left: 58.33333333%;
  }
  .col-lg-push-6 {
    left: 50%;
  }
  .col-lg-push-5 {
    left: 41.66666667%;
  }
  .col-lg-push-4 {
    left: 33.33333333%;
  }
  .col-lg-push-3 {
    left: 25%;
  }
  .col-lg-push-2 {
    left: 16.66666667%;
  }
  .col-lg-push-1 {
    left: 8.33333333%;
  }
  .col-lg-push-0 {
    left: auto;
  }
  .col-lg-offset-12 {
    margin-left: 100%;
  }
  .col-lg-offset-11 {
    margin-left: 91.66666667%;
  }
  .col-lg-offset-10 {
    margin-left: 83.33333333%;
  }
  .col-lg-offset-9 {
    margin-left: 75%;
  }
  .col-lg-offset-8 {
    margin-left: 66.66666667%;
  }
  .col-lg-offset-7 {
    margin-left: 58.33333333%;
  }
  .col-lg-offset-6 {
    margin-left: 50%;
  }
  .col-lg-offset-5 {
    margin-left: 41.66666667%;
  }
  .col-lg-offset-4 {
    margin-left: 33.33333333%;
  }
  .col-lg-offset-3 {
    margin-left: 25%;
  }
  .col-lg-offset-2 {
    margin-left: 16.66666667%;
  }
  .col-lg-offset-1 {
    margin-left: 8.33333333%;
  }
  .col-lg-offset-0 {
    margin-left: 0;
  }
}

.form-group {
  margin-bottom: 10px;
}
.frm-register-box input {
    height: 45px;
    background-color: #fff !important;
    background-image: none;
    border: 1px solid #ccc !important;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555;
    display: block;
    font-size: 14px;
    height: 34px;
    line-height: 1.42857;
    padding: 6px 12px !important;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s !important;
    width: 100%;
}
.frm-register-box2 input {
    height: 45px;
    background-color: #fff !important;
    background-image: none;
    border: 1px solid #ccc !important;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555;
    display: block;
    font-size: 14px;
    height: 34px;
    line-height: 1.42857;
    padding: 6px 12px !important;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s !important;
    width: 100%;
}

//vnatuan
.pack ul {
 margin-top: 30px;
 padding-left: 0;
 list-style: none;
}
.pack ul li {
 width: 33%;
 display: inline-block;
 border: 1px solid #e2e2e2;
 text-align: center;
 padding: 30px 0 20px;
 background: #fff;
 float: left;
}
.pack ul li:hover {
 border-color: #f7901e;
}
.pack ul li p {
 font-size: 14px;
 color: black;
}
.f55 {
 font-size: 55px;
 color: #f7901e;
}
.f35 {
 font-size: 35px;
}
.cf7901e {
 color: #f7901e;
}

small {font-size: 14px; color: black; text-transform: uppercase;}
.box-highline {
 margin-bottom: 15px;
}
.box-highline p {
 margin-bottom: 0;
 padding: 8px 0;
 font-size: 12px !important;
 color: #888 !important;
}

.box-highline p:nth-child(odd) {
 background-color: #f2eeec;
}
.box-highline p:nth-child(even)  {
 background-color: #fff;
}
.btnGetstart {
 background: #f7901e;
 color: #fff !important;
 text-transform: uppercase;
 width: 80%;
 padding: 10px 12px;
 margin:0 auto;
}

.btnGetstart:hover {
 background: #ec8411;
}
//vnatuan
  </style>

<div class="registration<?php echo $this->pageclass_sfx?>">

	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div id="register1" class="col-md-5 col-xs-offset-1 frm-register-box control-group">
			<!-- group 1-->
                        <h2 class="title" style="font-family:sans-serif"><?php echo JText::_('COM_USERS_REGISTER_TITLE_REGISTER1');?></h2>
                        
                        <div class="form-group">
                            <input id="jform_cvr_number" class="form-control" type="text" aria-required="true"  size="30" value="" name="jform[cvr_number]" placeholder="CVR-nr.">
                        </div>
						<p class="text-center">Du kan finde dit CVR-nr. på www.cvr.dk eller www.proff.dk</p>
                        <div class="form-group">
                            <input id="jform_first_name" class="required form-control" type="text" aria-required="true" required="required" size="30" value="" name="jform[first_name]" placeholder="Fornavn">
                            <span class="red">*</span>
                        </div>
                        <div class="form-group">
                            <input id="jform_second_name" class="required form-control" type="text" aria-required="true" required="required" size="30" value="" name="jform[second_name]" placeholder="Efternavn">
                            <span class="red">*</span>
                        </div>
                        <div class="form-group">
                            <input id="jform_telephone_number" class="required form-control" type="text" aria-required="true" required="required" size="30" value="" name="jform[telephone_number]" placeholder="Telefon nr.">
                            <span class="red">*</span>
                        </div>
                        <div class="form-group">
                            <input id="jform_company_name" class="required form-control" type="text" aria-required="true" required="required" size="30" value="" name="jform[company_name]" placeholder="Firmanavn">
                            <span class="red">*</span>
                        </div>
<!--                        <div class="form-group">
                            <select id="jform_company_name" class="form-control" name="jform[business_type]">
                                <option  value="1" selected="selected">Point</option>
                                <option  value="2">Stamp</option>
                            </select>
                        </div>                -->
                        <!-- group 2--> 
                        <p class="text-center"><?php echo JText::_('COM_USERS_REGISTER_TITLE_REGISTER2')?></p>
                        
                        <div class="form-group">
                            <input id="jform_email" class="validate-email required form-control" type="text" aria-required="true" required="required" size="30" value="" name="jform[email]" placeholder="E-mail">
                            <span class="red">*</span>
                        </div>
                        <div class="form-group">
                            <input id="jform_password1" class="validate-password required form-control" type="password" aria-required="true" required="required" size="30" value="" name="jform[password1]" placeholder="Kodeord">
                            <span class="red">*</span>
                        </div>
                        <div class="form-group">
                            <input id="jform_password2" class="validate-password required form-control" type="password" aria-required="true" required="required" size="30" value="" name="jform[password2]" placeholder="Gentag Kodeord">
                            <span class="red">*</span>
                        </div>
                        <!--<div class="form-group">
                            <textarea style="height:100px;resize: none;" id="jform_noter" class="form-control" rows="3" name="jform[noter]" placeholder="Eventuelle noter. Hvem har inviteret dig, hvor har du fundet os?"></textarea>
                        </div>-->
                        <div class="form-group">
                            <input id="jform_referencer" class="form-control" type="text" aria-required="true" size="30" value="" name="jform[referencer]" placeholder="Indtast/find bruger-ID">
                        </div>
						<p class="text-center">Eksempelvis en sælger, en anden forretning eller en af dine kunder der har anbefalet MyLoyal</p>
                        <div class="form-group">
                            <b style="">Felter markeret med * skal udfyldes.</b>
                        </div>
		<?php echo JHtml::_('form.token');?>
            </div>
           
            <div id="register3" style="display: block;" class="col-md-5 frm-register-box control-group">
		<h2 class="title" style="font-family:sans-serif"><?php echo JText::_('COM_USERS_REGISTER_TITLE_REGISTER3')?></h2>
                <div class="form-group">
                    <input id="jform_businessaddress" class="required form-control" type="text" aria-required="true" required="required" size="30" value="" name="jform[businessaddress]" placeholder="Firma adresse" autocomplete="off">	
                    <span class="red">*</span>
                </div>
                <!--<div class="form-group">
                    <p class="text-center"><?php echo JText::_('COM_USERS_REGISTER_TITLE_REGISTER4')?></p>
                </div>-->
                
                <div class="form-group">
                    <div class="">
                        <div id="mapCanvas"></div>
                        <div id="infoPanel" style="display:none;">
                          <b>Marker status:</b>
                          <div id="markerStatus"><i>Click and drag the marker.</i></div>
                          <input type="hidden" id="jform_latitude" name="jform[latitude]"/>
                          <input type="hidden" id="jform_longitude" name="jform[longitude]"/>
                          <div id="info"></div>
                          <b>Closest matching address:</b>
                          <div id="address"></div>
                        </div>
                    </div>
                </div>
                <div class="checkbox">
                    <label>
                        <input onclick='registercheck(this);' style="width:5% !important; height:14px;" required="required" type="checkbox" value="" id="checkboxRegister" name="checkboxRegister">
                        <a style="line-height: 22px;" class="underline" target="_blank" href="<?php echo JUri::root().'index.php/om-myloyal/handelsbetingelser'?>">
                            Jeg har læst og accepterer betingelser</a>
                    </label>
                </div>
                <div class="control-group">
                    <div class="">
                        <button type="button" onclick="" id="register" class="btn validate btnPayment"><?php echo JText::_('JREGISTER');?></button>
                        <button type="submit" id="registerSubmit" style="display:none;"></button>
                        <!--<button type="" onclick="" id="cancelregister" class="btn validate btnPayment"><?php echo JText::_('JREGISTER');?></button>-->
<!--                        <a class="btn" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>-->
                        <input type="hidden" name="option" value="com_users" />
                        <input type="hidden" name="task" value="registration.register" />
                        <input type="hidden" name="jform[city]" id="jform_city" value="" />
                    </div>
                </div>
            </div>
			<input type="hidden" name="package" value="<?php echo JRequest::getVar("package", 1);?>" />
	</form>
</div>
  <script>
//    function registercheck(cb)
//    {
//        if(cb.checked == true)
//        {
//            document.getElementById('register').style.display='block';
//            document.getElementById('cancelregister').style.display='none';
//        }
//        else
//        {
//            document.getElementById('cancelregister').style.display='block';
//            document.getElementById('register').style.display='none';
//        }
//    }
  </script>
