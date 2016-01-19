<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

?>

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
  var lat = "<?php echo $this->data['latitude'];?>";
  var lng = "<?php echo $this->data['longitude'];?>";
  var latLng = new google.maps.LatLng(lat, lng);
  var map = new google.maps.Map(document.getElementById('mapCanvas'), {
    zoom: 17,
    center: latLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  var marker = new google.maps.Marker({
    position: latLng,
    title: 'Point A',
    map: map,
    draggable: true
  });
  
  var input = /** @type {HTMLInputElement} */ (
    document.getElementById('jform_address'));
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
		var cvr = jQuery("#jform_cvr_number").val();
		jQuery.getJSON("http://cvrapi.dk/api?search="+cvr+"&country=dk", function(data){
			var name = data.owners[0].name;
			var lastName = name.split(" ").pop();
			jQuery("#jform_second_name").val(lastName);
			var lastIndex = name.lastIndexOf(" ");
			var firstName = name.substring(0, lastIndex);
			jQuery("#jform_first_name").val(firstName);
			jQuery("#jform_telephone_number").val(data.phone);
			jQuery("#jform_company_name").val(data.name);
			jQuery("#jform_email").val(data.email);
//			jQuery("#jform_address").val(data.address+""+data.city);
			
		});
    });
});
</script>

<script language="javascript">
    $(document).ready(function(e) {
        $("#jform_icon").msDropDown().data("dd");
    });
</script>
<style>
#mapCanvas {
  width: 100%;
  height: 500px;
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

<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-home"></i> Business setting</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="business-setting mt50">
                            <h2 class="text-center">Edit your business details</h2>
                                <form class="frm-business-setting" id="contact-form" action="<?php echo JRoute::_('index.php?option=com_business&task=business.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["cvrNumber"]?></label>
                                            <!--<input id="jform_cvr_number" name="<?php echo "jform[cvrNumber]";?>" value="<?php echo $this->data['cvrNumber'] ;?>" required="required" class="required form-control" type="text">-->
                                            <input id="jform_cvr_number" name="<?php echo "jform[cvrNumber]";?>" value="<?php echo $this->data['cvrNumber'] ;?>" class="form-control" type="text">
                                            </div>
                                            
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["businessName"]?></label>
                                            <input id="jform_company_name" name="<?php echo "jform[businessName]";?>" value="<?php echo $this->data['businessName'] ;?>" class="form-control" type="text">
                                            </div>
                                            
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["businessEmail"]?></label>
                                            <input id="jform_email" name="<?php echo "jform[businessEmail]";?>" value="<?php echo $this->data['businessEmail'] ;?>" class="form-control" type="text">
                                            </div>
                                            
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["shortName"]?></label>
                                            <input name="<?php echo "jform[shortName]";?>" value="<?php echo $this->data['shortName'] ;?>" class="form-control" type="text">
                                            </div>
                                            
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["phone"]?></label>
                                            <input id="jform_telephone_number" name="<?php echo "jform[phone]";?>" value="<?php echo $this->data['phone'] ;?>" class="form-control" type="text">
                                            </div>
                                            
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["website"]?></label>
                                            <input name="<?php echo "jform[website]";?>" value="<?php echo $this->data['website'] ;?>" class="form-control" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label for="">What icon best represents your business?</label>
                                                <select name="jform[icon]" id="jform_icon" class="form-control">
                                                    <?php foreach ($this->listIcon as $key=>$icon){
                                                        if($this->data['icon'] == $key)
                                                            { 
                                                    ?>
                                                    <option value="<?php echo $key;?>" selected="selected" data-image="<?php echo JPATH_BASE.$this->listIcon["$key"];?>"><?php echo $key;?></option>
                                                    <?php
                                                            }
                                                            else
                                                            {
                                                    ?>
                                                    <option value="<?php echo $key;?>" data-image="<?php echo JPATH_BASE.$this->listIcon["$key"];?>"><?php echo $key;?></option>        
                                                    <?php
                                                            }
                                                    }
                                                    ?>  
                                                </select>
                                            </div>
                                            <?php require_once 'workingtime.php';?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["firstName"]?></label>
                                            <input id="jform_first_name" name="<?php echo "jform[first_name]";?>" value="<?php echo $this->userinfo['firstName'] ;?>" class="form-control" type="text">
                                            </div>
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["lastName"]?></label>
                                            <input id="jform_second_name" name="<?php echo "jform[second_name]";?>" value="<?php echo $this->userinfo['lastName'] ;?>" class="form-control" type="text">
                                            </div>
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["address"]?></label>
                                            <input id="jform_address" name="<?php echo "jform[address]";?>" value="<?php echo $this->data['address'] ;?>" class="form-control" type="text">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label for=""><?php echo $this->listName["postnr"]?></label>
                                                    <input name="<?php echo "jform[postnr]";?>" value="<?php echo $this->data['postnr'] ;?>" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label for=""><?php echo $this->listName["postnrBy"]?></label>
                                                    <input name="<?php echo "jform[postnrBy]";?>" value="<?php echo $this->data['postnrBy'] ;?>" class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["country"]?></label>
                                            <select name="<?php echo "jform[country]";?>" class="form-control">
                                                <?php foreach($this->countries as $country){
                                                    if($this->data['country'] == $country)
                                                    {
                                                ?>
                                                <option value="<?php echo $country?>" selected="selected"><?php echo $country?></option>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                <option value="<?php echo $country?>"><?php echo $country?></option>
                                                <?php        
                                                    }
                                                }
                                                ?>
                                            </select>
                                            </div>
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["map"]?></label>
                                            <div class="map">
                                                <div id="mapCanvas"></div>
                                                <div id="infoPanel" style="display:none;">
                                                  <b>Marker status:</b>
                                                  <div id="markerStatus"><i>Click and drag the marker.</i></div>
                                                  <input type="hidden" id="jform_latitude" value="<?php echo $this->data['latitude'] ?>" name="jform[latitude]"/>
                                                  <input type="hidden" id="jform_longitude" value="<?php echo $this->data['longitude'] ?>" name="jform[longitude]"/>
                                                  <div id="info"></div>
                                                  <b>Closest matching address:</b>
                                                  <div id="address"></div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                        <button class="btn btnSubmit" type="submit">Submit</button>
                                        <input type="hidden" name="option" value="com_business" />
                                        <input type="hidden" name="jform[businessid]" value="<?php echo $this->data['id']?>" />
                                        <input type="hidden" name="jform[userid]" value="<?php echo $this->userinfo['id']?>" />
                                        <input type="hidden" name="task" value="business.save" />
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
