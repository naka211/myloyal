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
                jQuery.getJSON("http://cvrapi.dk/api?search="+cvr+"&country=dk", function(data){
			var name = data.owners[0].name;
                        if(name){
                            var lastName = name.split(" ").pop();
                            jQuery("#jform_second_name").val(lastName);
                            var lastIndex = name.lastIndexOf(" ");
                            var firstName = name.substring(0, lastIndex);
                            jQuery("#jform_first_name").val(firstName);
                        }
			jQuery("#jform_telephone_number").val(data.phone);
			jQuery("#jform_company_name").val(data.name);
			jQuery("#jform_email").val(data.email);
			jQuery("#jform_address").val(data.address+", "+data.city);
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
.dd-selected label{
    line-height: 40px !important;
}
.dd-option label{
    line-height: 40px !important;
}
</style>
<script>
    (function (a) { function g(a, b) { var c = a.data("ddslick"); var d = a.find(".dd-selected"), e = d.siblings(".dd-selected-value"), f = a.find(".dd-options"), g = d.siblings(".dd-pointer"), h = a.find(".dd-option").eq(b), k = h.closest("li"), l = c.settings, m = c.settings.data[b]; a.find(".dd-option").removeClass("dd-option-selected"); h.addClass("dd-option-selected"); c.selectedIndex = b; c.selectedItem = k; c.selectedData = m; if (l.showSelectedHTML) { d.html((m.imageSrc ? '<img class="dd-selected-image' + (l.imagePosition == "right" ? " dd-image-right" : "") + '" src="' + m.imageSrc + '" />' : "") + (m.text ? '<label class="dd-selected-text">' + m.text + "</label>" : "") + (m.description ? '<small class="dd-selected-description dd-desc' + (l.truncateDescription ? " dd-selected-description-truncated" : "") + '" >' + m.description + "</small>" : "")) } else d.html(m.text); e.val(m.value); c.original.val(m.value); a.data("ddslick", c); i(a); j(a); if (typeof l.onSelected == "function") { l.onSelected.call(this, c) } } function h(b) { var c = b.find(".dd-select"), d = c.siblings(".dd-options"), e = c.find(".dd-pointer"), f = d.is(":visible"); a(".dd-click-off-close").not(d).slideUp(50); a(".dd-pointer").removeClass("dd-pointer-up"); if (f) { d.slideUp("fast"); e.removeClass("dd-pointer-up") } else { d.slideDown("fast"); e.addClass("dd-pointer-up") } k(b) } function i(a) { a.find(".dd-options").slideUp(50); a.find(".dd-pointer").removeClass("dd-pointer-up").removeClass("dd-pointer-up") } function j(a) { var b = a.find(".dd-select").css("height"); var c = a.find(".dd-selected-description"); var d = a.find(".dd-selected-image"); if (c.length <= 0 && d.length > 0) { a.find(".dd-selected-text").css("lineHeight", b) } } function k(b) { b.find(".dd-option").each(function () { var c = a(this); var d = c.css("height"); var e = c.find(".dd-option-description"); var f = b.find(".dd-option-image"); if (e.length <= 0 && f.length > 0) { c.find(".dd-option-text").css("lineHeight", d) } }) } a.fn.ddslick = function (c) { if (b[c]) { return b[c].apply(this, Array.prototype.slice.call(arguments, 1)) } else if (typeof c === "object" || !c) { return b.init.apply(this, arguments) } else { a.error("Method " + c + " does not exists.") } }; var b = {}, c = { data: [], keepJSONItemsOnTop: false, width: 260, height: null, background: "#eee", selectText: "", defaultSelectedIndex: null, truncateDescription: true, imagePosition: "left", showSelectedHTML: true, clickOffToClose: true, onSelected: function () { } }, d = '<div class="dd-select"><input name="jform_icon" class="dd-selected-value" type="hidden" /><a class="dd-selected"></a><span class="dd-pointer dd-pointer-down"></span></div>', e = '<ul class="dd-options"></ul>', f = '<style id="css-ddslick" type="text/css">' + ".dd-select{ border-radius:2px; border:solid 1px #ccc; position:relative; cursor:pointer;}" + ".dd-desc { color:#aaa; display:block; overflow: hidden; font-weight:normal; line-height: 1.4em; }" + ".dd-selected{ overflow:hidden; display:block; padding:10px; font-weight:bold;}" + ".dd-pointer{ width:0; height:0; position:absolute; right:10px; top:50%; margin-top:-3px;}" + ".dd-pointer-down{ border:solid 5px transparent; border-top:solid 5px #000; }" + ".dd-pointer-up{border:solid 5px transparent !important; border-bottom:solid 5px #000 !important; margin-top:-8px;}" + ".dd-options{ border:solid 1px #ccc; border-top:none; list-style:none; box-shadow:0px 1px 5px #ddd; display:none; position:absolute; z-index:2000; margin:0; padding:0;background:#fff; overflow:auto;}" + ".dd-option{ padding:10px; display:block; border-bottom:solid 1px #ddd; overflow:hidden; text-decoration:none; color:#333; cursor:pointer;-webkit-transition: all 0.25s ease-in-out; -moz-transition: all 0.25s ease-in-out;-o-transition: all 0.25s ease-in-out;-ms-transition: all 0.25s ease-in-out; }" + ".dd-options > li:last-child > .dd-option{ border-bottom:none;}" + ".dd-option:hover{ background:#f3f3f3; color:#000;}" + ".dd-selected-description-truncated { text-overflow: ellipsis; white-space:nowrap; }" + ".dd-option-selected { background:#f6f6f6; }" + ".dd-option-image, .dd-selected-image { vertical-align:middle; float:left; margin-right:5px; max-width:40px;}" + ".dd-image-right { float:right; margin-right:15px; margin-left:5px;}" + ".dd-container{ position:relative;}â€‹ .dd-selected-text { font-weight:bold}â€‹</style>"; if (a("#css-ddslick").length <= 0) { a(f).appendTo("head") } b.init = function (b) { var b = a.extend({}, c, b); return this.each(function () { var c = a(this), f = c.data("ddslick"); if (!f) { var i = [], j = b.data; c.find("option").each(function () { var b = a(this), c = b.data(); i.push({ text: a.trim(b.text()), value: b.val(), selected: b.is(":selected"), description: c.description, imageSrc: c.imagesrc }) }); if (b.keepJSONItemsOnTop) a.merge(b.data, i); else b.data = a.merge(i, b.data); var k = c, l = a('<div id="' + c.attr("id") + '"></div>'); c.replaceWith(l); c = l; c.addClass("dd-container").append(d).append(e); var i = c.find(".dd-select"), m = c.find(".dd-options"); m.css({ width: b.width }); i.css({ width: b.width, background: b.background }); c.css({ width: b.width }); if (b.height != null) m.css({ height: b.height, overflow: "auto" }); a.each(b.data, function (a, c) { if (c.selected) b.defaultSelectedIndex = a; m.append("<li>" + '<a class="dd-option">' + (c.value ? ' <input class="dd-option-value" type="hidden" value="' + c.value + '" />' : "") + (c.imageSrc ? ' <img class="dd-option-image' + (b.imagePosition == "right" ? " dd-image-right" : "") + '" src="' + c.imageSrc + '" />' : "") + (c.text ? ' <label class="dd-option-text">' + c.text + "</label>" : "") + (c.description ? ' <small class="dd-option-description dd-desc">' + c.description + "</small>" : "") + "</a>" + "</li>") }); var n = { settings: b, original: k, selectedIndex: -1, selectedItem: null, selectedData: null }; c.data("ddslick", n); if (b.selectText.length > 0 && b.defaultSelectedIndex == null) { c.find(".dd-selected").html(b.selectText) } else { var o = b.defaultSelectedIndex != null && b.defaultSelectedIndex >= 0 && b.defaultSelectedIndex < b.data.length ? b.defaultSelectedIndex : 0; g(c, o) } c.find(".dd-select").on("click.ddslick", function () { h(c) }); c.find(".dd-option").on("click.ddslick", function () { g(c, a(this).closest("li").index()) }); if (b.clickOffToClose) { m.addClass("dd-click-off-close"); c.on("click.ddslick", function (a) { a.stopPropagation() }); a("body").on("click", function () { a(".dd-click-off-close").slideUp(50).siblings(".dd-select").find(".dd-pointer").removeClass("dd-pointer-up") }) } } }) }; b.select = function (b) { return this.each(function () { if (b.index) g(a(this), b.index) }) }; b.open = function () { return this.each(function () { var b = a(this), c = b.data("ddslick"); if (c) h(b) }) }; b.close = function () { return this.each(function () { var b = a(this), c = b.data("ddslick"); if (c) i(b) }) }; b.destroy = function () { return this.each(function () { var b = a(this), c = b.data("ddslick"); if (c) { var d = c.original; b.removeData("ddslick").unbind(".ddslick").replaceWith(d) } }) } })(jQuery)
</script>


<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-cog"></i> Indstillinger</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="business-setting mt50">
                            <h2 class="text-center">Rediger forretningens indstillinger</h2>
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
                                            <?php if($this->data['type'] == "1"){?>
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["pointDescription"]?></label>
                                            <input name="<?php echo "jform[pointDescription]";?>" value="<?php echo $this->data['pointDescription'] ;?>" class="form-control" type="text">
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="form-group">
                                            <label for=""><?php echo $this->listName["password"]?></label>
                                            <input name="<?php echo "jform[password]";?>" class="form-control" type="password">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Hvilket ikon reprÃ¦sentere din forretning ? </label>
                                                <li id="flag">
                                                    <select id="flagselection" class="form-control">
                                                        <?php foreach ($this->listIcon as $key=>$icon){
                                                            if(strstr($icon,$this->data['icon']) != "")
                                                                { 
                                                        ?>
                                                        <option value="<?php echo $key;?>" selected="selected" data-imagesrc="<?php echo JUri::root().$this->listIcon["$key"];?>"><?php echo $key;?></option>
                                                        <?php
                                                                }
                                                                else
                                                                {
                                                        ?>
                                                        <option value="<?php echo $key;?>" data-imagesrc="<?php echo JUri::root().$this->listIcon["$key"];?>"><?php echo $key;?></option>        
                                                        <?php
                                                                }
                                                        }
                                                        ?>  
                                                    </select>
                                                </li>
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
                                            <div style="display: none;">
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
                                        <button class="btn btnSubmit" type="submit">Gem</button>
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
<script>
    function oc(a){
        var o = {};
        for(var i=0;i<a.length;i++){
            o[a[i]]='';
        }
        return o;
    }
    $('#flag').ddslick({
        imagePosition:"left",
        background:"none"
    });
</script>
